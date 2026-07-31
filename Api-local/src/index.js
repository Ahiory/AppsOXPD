const express = require("express");
const cors = require("cors");
require("dotenv").config();

const authRoutes = require("./routes/auth.routes");
const solicitudRoutes = require("./routes/solicitud.routes");
const adjuntosRoutes = require("./routes/adjuntos.routes");
const filesRoutes = require("./routes/files.routes");

const app = express();
const configuredCorsOrigins = new Set([
  "http://localhost:5500",
  "http://127.0.0.1:5500",
  ...(process.env.CORS_ORIGINS || "")
    .split(",")
    .map((origin) => origin.trim())
    .filter(Boolean)
]);

const isProduction = (process.env.NODE_ENV || "development") === "production";
const allowAllOrigins =
  process.env.CORS_ALLOW_ALL === "true" ||
  (!isProduction && !process.env.CORS_ORIGINS);

function validateCorsOrigin(origin, callback) {
  if (!origin || allowAllOrigins || configuredCorsOrigins.has(origin)) {
    callback(null, true);
    return;
  }

  callback(new Error("Origen no permitido por CORS"));
}

app.use(
  cors({
    origin: validateCorsOrigin
  })
);

app.use(express.json());

app.get("/health", (req, res) => {
  res.json({ ok: true, service: "local-api" });
});

app.use("/api/auth", authRoutes);
app.use("/api/solicitud", solicitudRoutes);
app.use("/api/adjuntos", adjuntosRoutes);
app.use("/api/files", filesRoutes);

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`🚀 Mini API local corriendo en http://localhost:${PORT}`);
});
