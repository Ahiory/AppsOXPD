import { appState } from "../../state/store.js";
import { getSolicitud } from "../../services/solicitudService.js";
import { messages } from "../../utils/messages.js";

export function renderSolicitudScreen() {
  document.body.innerHTML = `
    <div style="font-family:Arial; padding:20px; max-width:700px; margin:auto;">
      <h2>Gestor Integral de Solicitudes</h2>
      <div style="margin-top:20px;">
        <label for="numeroSolicitud"><strong>No. Solicitud</strong></label>
        <input id="numeroSolicitud" type="text" style="display:block; width:100%; padding:10px; margin-top:8px;" />
      </div>

      <div style="margin-top:20px; display:flex; gap:10px;">
        <button id="btnLimpiar">Limpiar</button>
        <button id="btnAceptar">Aceptar</button>
      </div>

      <div id="messageBox" style="margin-top:20px; color:#b00020;"></div>
    </div>
  `;

  const numeroInput = document.getElementById("numeroSolicitud");
  const btnLimpiar = document.getElementById("btnLimpiar");
  const btnAceptar = document.getElementById("btnAceptar");
  const messageBox = document.getElementById("messageBox");

  btnLimpiar.addEventListener("click", () => {
    numeroInput.value = "";
    messageBox.textContent = "";
  });

  btnAceptar.addEventListener("click", async () => {
    const numero = numeroInput.value.trim();

    if (!numero) {
      messageBox.textContent = messages.solicitudNoInformada;
      return;
    }

    messageBox.textContent = messages.cargando;

    try {
      const result = await getSolicitud(numero);

      if (result.codigo !== 0) {
        messageBox.textContent = result.mensaje || messages.solicitudNoExiste;
        return;
      }

      appState.solicitud.numero = result.solicitud;
      appState.solicitud.estado = result.estado;
      appState.solicitud.afiliado = result.afiliado;
      appState.solicitud.puedeAdjuntar = true;

      window.navigateToAdjuntos();
    } catch (error) {
      console.error(error);
      messageBox.textContent = messages.errorGeneral;
    }
  });
}