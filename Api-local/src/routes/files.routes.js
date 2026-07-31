const express = require("express");
const multer = require("multer");
const fileServerConfig = require("../config/fileServer.config");
const {
  buildFullPath,
  deleteFiles,
  uploadFiles
} = require("../services/fileServerClient");

const router = express.Router();
const upload = multer({
  storage: multer.memoryStorage(),
  limits: {
    fileSize: fileServerConfig.maxFileSizeBytes
  }
});

function isPdf(file) {
  return (
    file.originalname?.toLowerCase().endsWith(".pdf") &&
    file.mimetype === "application/pdf"
  );
}

router.post("/upload", upload.array("files"), async (req, res) => {
  try {
    const { numeroSolicitud } = req.body;
    const files = req.files || [];

    if (!numeroSolicitud) {
      return res.status(400).json({
        codigo: 1,
        descripcion: "Numero de solicitud no informado"
      });
    }

    if (!files.length) {
      return res.status(400).json({
        codigo: 1,
        descripcion: "No se recibieron archivos"
      });
    }

    if (files.some((file) => !isPdf(file))) {
      return res.status(400).json({
        codigo: 1,
        descripcion: "Solo se permiten archivos PDF"
      });
    }

    const fullPath = buildFullPath(numeroSolicitud);
    const result = await uploadFiles(fullPath, files);

    return res.json(result);
  } catch (error) {
    console.error("Error en /api/files/upload:", error);
    return res.status(500).json({
      codigo: 1,
      descripcion: error.message || "Error subiendo archivos"
    });
  }
});

router.delete("/delete", async (req, res) => {
  try {
    const { numeroSolicitud, fullPath, files } = req.body;
    const pathToDelete = fullPath || buildFullPath(numeroSolicitud);

    if (!pathToDelete || !Array.isArray(files) || !files.length) {
      return res.status(400).json({
        codigo: 1,
        descripcion: "Parametros insuficientes para eliminar archivos"
      });
    }

    const result = await deleteFiles(pathToDelete, files);

    return res.json(result);
  } catch (error) {
    console.error("Error en /api/files/delete:", error);
    return res.status(500).json({
      codigo: 1,
      descripcion: error.message || "Error eliminando archivos"
    });
  }
});

module.exports = router;
