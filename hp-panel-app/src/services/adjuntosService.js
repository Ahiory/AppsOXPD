import { appState } from "../state/store.js";

export async function getAdjuntosExistentes(numeroSolicitud, usuarioRed = appState.usuario.usuarioRed) {
  const response = await fetch("http://localhost:3000/api/adjuntos/consultar", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ numeroSolicitud, usuarioRed })
  });

  if (!response.ok) {
    throw new Error("Error consultando adjuntos");
  }

  return await response.json();
}