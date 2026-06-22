const express = require("express");
const router = express.Router();
const { sql, poolPromise } = require("../db");

router.post("/consultar", async (req, res) => {
  try {
    const { numeroSolicitud, usuarioRed } = req.body;

    const pool = await poolPromise;
    const result = await pool
      .request()
      .input(
        "prm_SOLI_AUTO",
        sql.BigInt,
        numeroSolicitud ? Number(numeroSolicitud) : null
      )
      .input("prm_USUARIO", sql.Char(15), usuarioRed || "")
      .execute("dbo.prs_consulta_adjuntos_soli_externo");

    const rows = result.recordset || [];

    return res.json(
      rows.map((row) => ({
        codigo: Number(row.CODIGO ?? 0),
        descripcion: row.DESCRIPCION ?? "",
        codigoTipo: row.CodigoTipo,
        nombreTipo: row.NombreTipo,
        yaCargado: Boolean(row.YaCargado),
        pesoMb: Number(row.PesoMb || 0),
        permiteCargar: Boolean(row.PermiteCargar)
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

module.exports = router;