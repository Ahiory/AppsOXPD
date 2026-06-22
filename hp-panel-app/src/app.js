import { renderSolicitudScreen } from "./screens/solicitud/SolicitudScreen.js";
import { renderAdjuntosScreen } from "./screens/adjuntos/AdjuntosScreen.js";
import { appState } from "./state/store.js";
import { validateUser } from "./services/authService.js";

async function bootstrap() {
  try {
    // temporal mientras conectas login real de la impresora
    appState.usuario.cedula = "12345678";
    appState.usuario.usuarioRed = "JMARQUEZ";

    const userResult = await validateUser(appState.usuario.usuarioRed);

    if (userResult.codigo !== 0) {
      document.body.innerHTML = `
        <div style="padding:20px;font-family:Arial">
          <h2>Acceso restringido</h2>
          <p>${userResult.descripcion}</p>
        </div>
      `;
      return;
    }

    appState.usuario.validado = true;
    renderSolicitudScreen();
  } catch (error) {
    console.error(error);
    document.body.innerHTML = `
      <div style="padding:20px;font-family:Arial">
        <h2>Error técnico</h2>
        <p>No fue posible iniciar la aplicación.</p>
      </div>
    `;
  }
}

window.navigateToAdjuntos = renderAdjuntosScreen;
window.navigateToSolicitud = renderSolicitudScreen;

bootstrap();