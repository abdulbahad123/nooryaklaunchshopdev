/**
 * config.js (ESM version)
 * Compatible with Node.js 18/20/22/24
 * Environment variable support with production fallbacks
 */
const config = {
  debug: process.env.NODE_DEBUG === "true",
  app_url: process.env.APP_URL || "http://localhost:8080/",
  database: {
    connectionLimit: 100,
    host: process.env.DB_HOST || "127.0.0.1",
    user: process.env.DB_USERNAME || "root",
    password: process.env.DB_PASSWORD !== undefined ? process.env.DB_PASSWORD : "",
    database: process.env.DB_DATABASE || "nooryak_whatautomation",
    charset: "utf8mb4",
    debug: false,
    waitForConnections: true,
    multipleStatements: true,
  },
  cors: {
    origin: "*",
    optionsSuccessStatus: 200,
  },
};

export default config;
