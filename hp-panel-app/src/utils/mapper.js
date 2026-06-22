export function mapUserValidation(row) {
  return {
    codigo: Number(row.CODIGO),
    descripcion: row.DESCRIPCION || ""
  };
}

export function mapSolicitud(row) {
  return {
    codigo: Number(row.CODIGO),
    solicitud: row.SOLICITUD || null,
    descripcion: row.DESCRIPCION || "",
    estado: row.ESTADO_SOLICITUD || "",
    afiliado: row.NOMBRE_AFILIADO || "",
    mensaje: row.MENSAJE || ""
  };
}

export function mapAdjunto(row) {
  return {
    codigoTipo: row.CodigoTipo,
    nombreTipo: row.NombreTipo,
    yaCargado: Boolean(row.YaCargado),
    pesoMb: Number(row.PesoMb || 0),
    permiteCargar: Boolean(row.PermiteCargar)
  };
}