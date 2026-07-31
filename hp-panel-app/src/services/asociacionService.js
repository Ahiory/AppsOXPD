import { apiUrl } from "../config/apiConfig.js";

export async function asociarAdjunto(payload) {
  const response = await fetch(apiUrl("/api/adjuntos/asociar"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(payload)
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.descripcion || "Error asociando adjunto");
  }

  return data;
}
