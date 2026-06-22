const sql = require("mssql");
require("dotenv").config();

const config = {
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  server: process.env.DB_SERVER,
  database: process.env.DB_DATABASE,
  options: {
    encrypt: false,
    trustServerCertificate: true
  }
};

if (process.env.DB_INSTANCE) {
  config.options.instanceName = process.env.DB_INSTANCE;
}

if (process.env.DB_PORT) {
  config.port = Number(process.env.DB_PORT);
}

const poolPromise = new sql.ConnectionPool(config)
  .connect()
  .then((pool) => {
    console.log("✅ Conectado a SQL Server");
    return pool;
  })
  .catch((err) => {
    console.error("❌ Error conectando a SQL Server:", err);
    throw err;
  });

module.exports = {
  sql,
  poolPromise
};