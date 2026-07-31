import { apiUrl } from "../config/apiConfig.js";

export async function uploadFiles(numeroSolicitud, files) {
  const formData = new FormData();
  formData.append("numeroSolicitud", numeroSolicitud);

  files.forEach((file) => {
    formData.append("files", file);
  });

  const response = await fetch(apiUrl("/api/files/upload"), {
    method: "POST",
    body: formData
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.descripcion || data.message || "Error subiendo archivo");
  }

  return data;
}

export async function deleteFiles(fullPath, files) {
  const response = await fetch(apiUrl("/api/files/delete"), {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ fullPath, files })
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.descripcion || data.message || "Error eliminando archivo");
  }

  return data;
}
