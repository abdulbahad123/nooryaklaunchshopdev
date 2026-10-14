/**
 * config.js (ESM version)
 * Compatible with Node.js 18/20/22
 */
const config = {
  debug: false,
  app_url: "http://localhost:8080/",
  database: {
    connectionLimit: 500,
    host: "127.0.0.1",
    user: "root",
    password: "",
    database: "replix_automation",
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