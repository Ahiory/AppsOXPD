const express = require("express");
const router = express.Router();
const { sql, poolPromise } = require("../db");

router.post("/consultar", async (req, res) => {
  try {
    const { numeroSolicitud } = req.body;

    const pool = await poolPromise;
    const result = await pool
      .request()
      .input(
        "prm_SOLI_AUTO",
        sql.BigInt,
        numeroSolicitud ? Number(numeroSolicitud) : null
      )
      .execute("dbo.prs_valida_solicitudes_auto_externo");

    const row = result.recordset?.[0];

    return res.json({
      codigo: Number(row?.CODIGO ?? 1),
      solicitud: row?.SOLICITUD ?? null,
      descripcion: row?.DESCRIPCION ?? "",
      estado: row?.ESTADO_SOLICITUD ?? "",
      afiliado: row?.NOMBRE_AFILIADO ?? "",
      mensaje: row?.MENSAJE ?? ""
    });
  } catch (error) {
    console.error("Error en /api/solicitud/consultar:", error);
    return res.status(500).json({
      codigo: 1,
      descripcion: "ERROR_TECNICO",
      mensaje: "Error consultando la solicitud"
    });
  }
});

module.exports = router;