const express = require("express");
const router = express.Router();
const {
  asociarAdjuntos,
  consultarAdjuntos
} = require("../repositories/adjuntosRepository");

router.post("/consultar", async (req, res) => {
  try {
    const { numeroSolicitud } = req.body;
    const rows = await consultarAdjuntos(numeroSolicitud);

    return res.json(
      rows.map((row) => ({
        codigo: Number(row.CODIGO ?? 0),
        descripcion: row.DESCRIPCION ?? "",
        codigoTipo: row.CodigoTipo ?? row.Tipo_adjunto,
        nombreTipo: row.NombreTipo ?? row.Descripcion_tipo_adjunto,
        yaCargado: Boolean(row.YaCargado ?? (row.Tiene_adjunto === "S")),
        pesoMb: Number(row.PesoMb ?? row.Tamanio_adjunto ?? 0),
        permiteCargar: Boolean(row.PermiteCargar ?? 1)
      }))
    );
  } catch (error) {
    console.error("Error en /api/adjuntos/consultar:", error);
    return res.status(500).json([
      {
        codigo: 1,
        descripcion: "ERROR_TECNICO"
      }
    ]);
  }
});

router.post("/asociar", async (req, res) => {
  try {
    const { numeroSolicitud, usuarioRed, files, tipo } = req.body;

    if (!numeroSolicitud || !usuarioRed || !Array.isArray(files) || !files.length) {
      return res.status(400).json({
        codigo: 1,
        descripcion: "Parametros insuficientes para asociar adjuntos"
      });
    }

    const result = await asociarAdjuntos({
      numeroSolicitud,
      usuarioRed,
      files,
      tipo
    });

    if (result.codigo !== 0) {
      return res.status(400).json(result);
    }

    return res.json(result);
  } catch (error) {
    console.error("Error en /api/adjuntos/asociar:", error);
    return res.status(500).json({
      codigo: 1,
      descripcion: "ERROR_TECNICO"
    });
  }
});

module.exports = router;
