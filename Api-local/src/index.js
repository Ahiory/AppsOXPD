const express = require("express");
const cors = require("cors");
require("dotenv").config();

const authRoutes = require("./routes/auth.routes");
const solicitudRoutes = require("./routes/solicitud.routes");
const adjuntosRoutes = require("./routes/adjuntos.routes");

const app = express();

app.use(
  cors({
    origin: [
      "http://localhost:5500",
      "http://127.0.0.1:5500"
    ]
  })
);

app.use(express.json());

app.get("/health", (req, res) => {
  res.json({ ok: true, service: "local-api" });
});

app.use("/api/auth", authRoutes);
app.use("/api/solicitud", solicitudRoutes);
app.use("/api/adjuntos", adjuntosRoutes);

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`🚀 Mini API local corriendo en http://localhost:${PORT}`);
});