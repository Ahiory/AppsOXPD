const express = require("express");
const router = express.Router();
const { sql, poolPromise } = require("../db");

router.post("/validate", async (req, res) => {
  try {
    const { usuarioRed } = req.body;

    const pool = await poolPromise;
    const result = await pool
      .request()
      .input("prm_USUARIO", sql.Char(15), usuarioRed || "")
      .execute("dbo.prs_valida_usuario_soli_externo");

    const row = result.recordset?.[0];

    return res.json({
      codigo: Number(row?.CODIGO ?? 1),
      descripcion: row?.DESCRIPCION ?? "NO HABILITADO"
    });
  } catch (error) {
    console.error("Error en /api/auth/validate:", error);
    return res.status(500).json({
      codigo: 1,
      descripcion: "ERROR_TECNICO"
    });
  }
});

module.exports = router;