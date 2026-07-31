const crypto = require("crypto");
const FormData = require("form-data");
const fileServerConfig = require("../config/fileServer.config");

function buildFullPath(numeroSolicitud) {
  return `${fileServerConfig.fullPathPrefix}/${numeroSolicitud}`;
}

function assertConfigured(operation) {
  const missingVariables = [];

  if (!fileServerConfig.baseUrl) {
    missingVariables.push("FILE_SERVER_BASE_URL");
  }

  if (operation === "upload" && !fileServerConfig.uploadPath) {
    missingVariables.push("FILE_SERVER_UPLOAD_PATH");
  }

  if (operation === "delete" && !fileServerConfig.deletePath) {
    missingVariables.push("FILE_SERVER_DELETE_PATH");
  }

  if (missingVariables.length) {
    throw new Error(`${missingVariables.join(", ")} no configurado(s)`);
  }
}

function normalizeBaseUrl(baseUrl) {
  return baseUrl.replace(/\/$/, "");
}

function createMockUploadResponse(fullPath, files) {
  return {
    code: 0,
    message: "Files uploaded",
    dirName: crypto.randomBytes(12).toString("hex"),
    fullPath,
    files: files.map((file) => {
      const extension = file.originalname.includes(".")
        ? file.originalname.slice(file.originalname.lastIndexOf("."))
        : "";
      const filename = `${crypto.randomBytes(12).toString("hex")}${extension}`;

      return {
        fieldname: file.fieldname,
        originalname: file.originalname,
        encoding: file.encoding,
        mimetype: file.mimetype,
        destination: fullPath,
        filename,
        path: `${fullPath}/${filename}`,
        size: file.size
      };
    })
  };
}

async function uploadFiles(fullPath, files) {
  if (fileServerConfig.mode === "mock") {
    return createMockUploadResponse(fullPath, files);
  }

  assertConfigured("upload");

  const form = new FormData();
  form.append("fullPath", fullPath);

  files.forEach((file) => {
    form.append("files", file.buffer, {
      filename: file.originalname,
      contentType: file.mimetype
    });
  });

  const response = await fetch(
    `${normalizeBaseUrl(fileServerConfig.baseUrl)}${fileServerConfig.uploadPath}`,
    {
      method: "POST",
      body: form,
      headers: form.getHeaders()
    }
  );

  const data = await response.json();

  if (!response.ok || Number(data.code ?? data.Code ?? 1) !== 0) {
    throw new Error(data.message || data.Message || "Error subiendo archivos");
  }

  return {
    ...data,
    fullPath
  };
}

async function deleteFiles(fullPath, files) {
  if (fileServerConfig.mode === "mock") {
    return {
      code: 0,
      message: "Files deleted",
      fullPath,
      files
    };
  }

  assertConfigured("delete");

  const response = await fetch(
    `${normalizeBaseUrl(fileServerConfig.baseUrl)}${fileServerConfig.deletePath}`,
    {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ fullPath, files })
    }
  );

  const data = await response.json();

  if (!response.ok || Number(data.code ?? data.Code ?? 1) !== 0) {
    throw new Error(data.message || data.Message || "Error eliminando archivos");
  }

  return data;
}

module.exports = {
  buildFullPath,
  uploadFiles,
  deleteFiles
};
