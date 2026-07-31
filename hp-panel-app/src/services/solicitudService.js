import { apiUrl } from "../config/apiConfig.js";

export async function getSolicitud(numeroSolicitud) {
  const response = await fetch(apiUrl("/api/solicitud/consultar"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ numeroSolicitud })
  });

  if (!response.ok) {
    throw new Error("Error consultando solicitud");
  }

  return await response.json();
}
