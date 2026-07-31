import { apiUrl } from "../config/apiConfig.js";

export async function validateUser(usuarioRed) {
  const response = await fetch(apiUrl("/api/auth/validate"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ usuarioRed })
  });

  if (!response.ok) {
    throw new Error("Error validando usuario");
  }

  return await response.json();
}
