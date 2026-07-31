function escapeXml(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&apos;");
}

function buildAdjuntosXml(files, tipo = "1") {
  const adjuntos = (files || [])
    .map((file) => {
      const pesoMb = file.size ? Number(file.size) / (1024 * 1024) : 0;

      return `
    <adjunto>
        <tipo>${escapeXml(file.tipo || tipo)}</tipo>
        <nombre>${escapeXml(file.originalname)}</nombre>
        <id>${escapeXml(file.filename)}</id>
        <pesoMb>${pesoMb.toFixed(2)}</pesoMb>
    </adjunto>`;
    })
    .join("");

  return `<adjuntos>${adjuntos}
</adjuntos>`;
}

module.exports = {
  buildAdjuntosXml
};
