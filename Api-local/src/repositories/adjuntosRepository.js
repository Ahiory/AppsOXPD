const { sql, poolPromise } = require("../db");
const { buildAdjuntosXml } = require("../services/adjuntosXmlService");

async function consultarAdjuntos(numeroSolicitud) {
  const pool = await poolPromise;
  const result = await pool
    .request()
    .input(
      "prm_soli_auto",
      sql.BigInt,
      numeroSolicitud ? Number(numeroSolicitud) : null
    )
    .execute("dbo.prs_solicitudes_auto_adjuntos_externo");

  return result.recordset || [];
}

async function asociarAdjuntos({ numeroSolicitud, usuarioRed, files, tipo = "1" }) {
  const xml = buildAdjuntosXml(files, tipo);
  const pool = await poolPromise;
  const result = await pool
    .request()
    .input(
      "prm_soli_auto",
      sql.BigInt,
      numeroSolicitud ? Number(numeroSolicitud) : null
    )
    .input("prm_origen", sql.Char(1), "P")
    .input("prm_usuari", sql.Char(15), usuarioRed || "")
    .input("prm_prestad", sql.VarChar(20), null)
    .input("prm_adjuntos", sql.Xml, xml)
    .execute("dbo.pru_solicitudes_auto_adjuntos");

  const row = result.recordset?.[0] || {};

  return {
    codigo: Number(row.CODIGO ?? row.codigo ?? 1),
    descripcion: row.DESCRIPCION ?? row.descripcion ?? "",
    xml
  };
}

module.exports = {
  consultarAdjuntos,
  asociarAdjuntos
};
