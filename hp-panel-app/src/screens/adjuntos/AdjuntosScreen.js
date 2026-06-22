import { appState } from "../../state/store.js";
import { getAdjuntosExistentes } from "../../services/adjuntosService.js";

export async function renderAdjuntosScreen() {
  const adjuntos = await getAdjuntosExistentes(
    appState.solicitud.numero,
    appState.usuario.usuarioRed
  );

  appState.adjuntos = adjuntos;

  const rows = adjuntos.map((item) => `
    <tr>
      <td>${item.nombreTipo}</td>
      <td>${item.yaCargado ? "Sí" : "No"}</td>
      <td>${item.pesoMb} MB</td>
      <td>${item.permiteCargar ? "Sí" : "No"}</td>
      <td>
        <button ${item.permiteCargar ? "" : "disabled"}>
          ${item.yaCargado ? "Reemplazar" : "Cargar"}
        </button>
      </td>
    </tr>
  `).join("");

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
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>

      <div style="margin-top:20px;">
        <button id="btnVolver">Atrás</button>
      </div>
    </div>
  `;

  document.getElementById("btnVolver").addEventListener("click", () => {
    window.navigateToSolicitud();
  });
}