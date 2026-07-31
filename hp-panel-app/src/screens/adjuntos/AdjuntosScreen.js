import { appState } from "../../state/store.js";
import { getAdjuntosExistentes } from "../../services/adjuntosService.js";
import { asociarAdjunto } from "../../services/asociacionService.js";
import { deleteFiles, uploadFiles } from "../../services/fileServerService.js";
import { apiConfig } from "../../config/apiConfig.js";
import { messages } from "../../utils/messages.js";

function validatePdf(file) {
  if (!file) {
    return messages.archivoNoSeleccionado;
  }

  if (!file.name.toLowerCase().endsWith(".pdf") || file.type !== "application/pdf") {
    return messages.archivoNoPdf;
  }

  if (file.size > apiConfig.maxFileSizeMb * 1024 * 1024) {
    return messages.archivoMuyGrande;
  }

  return "";
}

function tipoParaAsociacion(codigoTipo) {
  const map = {
    HC: "1",
    OM: "2",
    ANX: "3"
  };

  return map[codigoTipo] || "1";
}

function renderRows(adjuntos) {
  return adjuntos
    .map((item) => {
      const pesoMb = Number(item.pesoMb || 0).toFixed(2);

      return `
    <tr>
      <td>${item.nombreTipo}</td>
      <td>${item.yaCargado ? "Si" : "No"}</td>
      <td>${pesoMb} MB</td>
      <td>${item.permiteCargar ? "Si" : "No"}</td>
      <td>
        <button
          class="btnCargar"
          data-tipo="${item.codigoTipo}"
          ${item.permiteCargar ? "" : "disabled"}
        >
          ${item.yaCargado ? "Reemplazar" : "Cargar"}
        </button>
      </td>
    </tr>
  `;
    })
    .join("");
}

async function cargarAdjunto(file, codigoTipo, messageBox) {
  const validationMessage = validatePdf(file);

  if (validationMessage) {
    messageBox.style.color = "#b00020";
    messageBox.textContent = validationMessage;
    return;
  }

  let uploadResult = null;
  messageBox.style.color = "#333";
  messageBox.textContent = messages.cargando;

  try {
    uploadResult = await uploadFiles(appState.solicitud.numero, [file]);

    await asociarAdjunto({
      numeroSolicitud: appState.solicitud.numero,
      usuarioRed: appState.usuario.usuarioRed,
      tipo: tipoParaAsociacion(codigoTipo),
      files: uploadResult.files
    });

    messageBox.style.color = "#007a3d";
    messageBox.textContent = messages.uploadOk;
    await renderAdjuntosScreen();
  } catch (error) {
    console.error(error);
    messageBox.style.color = "#b00020";

    if (uploadResult?.files?.length) {
      try {
        await deleteFiles(uploadResult.fullPath, uploadResult.files);
        messageBox.textContent = messages.rollbackOk;
      } catch (rollbackError) {
        console.error(rollbackError);
        messageBox.textContent = messages.rollbackError;
      }
      return;
    }

    messageBox.textContent = error.message || messages.errorGeneral;
  }
}

export async function renderAdjuntosScreen() {
  const adjuntos = await getAdjuntosExistentes(
    appState.solicitud.numero,
    appState.usuario.usuarioRed
  );

  appState.adjuntos = adjuntos;

  document.body.innerHTML = `
    <div style="font-family:Arial; padding:20px; max-width:900px; margin:auto;">
      <h2>Adjuntos de la solicitud</h2>

      <div style="margin-bottom:20px;">
        <p><strong>Solicitud:</strong> ${appState.solicitud.numero}</p>
        <p><strong>Afiliado:</strong> ${appState.solicitud.afiliado}</p>
        <p><strong>Estado:</strong> ${appState.solicitud.estado}</p>
      </div>

      <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Ya cargado</th>
            <th>Peso</th>
            <th>Permite cargar</th>
            <th>Accion</th>
          </tr>
        </thead>
        <tbody>
          ${renderRows(adjuntos)}
        </tbody>
      </table>

      <input id="fileInput" type="file" accept="application/pdf,.pdf" style="display:none;" />
      <div id="messageBox" style="margin-top:20px; color:#b00020;"></div>

      <div style="margin-top:20px;">
        <button id="btnVolver">Atras</button>
      </div>
    </div>
  `;

  const fileInput = document.getElementById("fileInput");
  const messageBox = document.getElementById("messageBox");
  let selectedTipo = "";

  document.querySelectorAll(".btnCargar").forEach((button) => {
    button.addEventListener("click", () => {
      selectedTipo = button.dataset.tipo;
      fileInput.value = "";
      fileInput.click();
    });
  });

  fileInput.addEventListener("change", async () => {
    await cargarAdjunto(fileInput.files[0], selectedTipo, messageBox);
  });

  document.getElementById("btnVolver").addEventListener("click", () => {
    window.navigateToSolicitud();
  });
}
