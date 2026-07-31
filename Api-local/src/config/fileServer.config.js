const maxFileSizeMb = Number(process.env.FILE_SERVER_MAX_FILE_SIZE_MB || 20);

module.exports = {
  mode: process.env.FILE_SERVER_MODE || "mock",
  baseUrl: process.env.FILE_SERVER_BASE_URL || "",
  uploadPath: process.env.FILE_SERVER_UPLOAD_PATH || "",
  deletePath: process.env.FILE_SERVER_DELETE_PATH || "",
  fullPathPrefix:
    process.env.FILE_SERVER_FULLPATH_PREFIX ||
    "PruebasCoreInfomedical/SolicitudesAuto",
  maxFileSizeMb,
  maxFileSizeBytes: maxFileSizeMb * 1024 * 1024
};
