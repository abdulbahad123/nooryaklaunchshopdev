/**
 * config.js (ESM version)
 * Compatible with Node.js 18/20/22/24
 * Automatically reads database credentials from ../Install/.env with environment variable overrides
 */

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Automatically load database config from ../Install/.env if available
const envPath = path.join(__dirname, "../Install/.env");
const envVars = {};

try {
  if (fs.existsSync(envPath)) {
    const lines = fs.readFileSync(envPath, "utf8").split(/\r?\n/);
    for (const line of lines) {
      const trimmed = line.trim();
      if (trimmed && !trimmed.startsWith("#") && trimmed.includes("=")) {
        const idx = trimmed.indexOf("=");
        const key = trimmed.slice(0, idx).trim();
        let val = trimmed.slice(idx + 1).trim();
        if ((val.startsWith('"') && val.endsWith('"')) || (val.startsWith("'") && val.endsWith("'"))) {
          val = val.slice(1, -1);
        }
        envVars[key] = val;
      }
    }
  }
} catch (e) {}

const config = {
  debug: process.env.NODE_DEBUG === "true",
  app_url: process.env.APP_URL || envVars.APP_URL || "http://localhost:8080/",
  database: {
    connectionLimit: 100,
    host: process.env.DB_HOST || envVars.DB_HOST || "127.0.0.1",
    user: process.env.DB_USERNAME || envVars.DB_USERNAME || "nooryak_sassadmindbuser",
    password: process.env.DB_PASSWORD !== undefined ? process.env.DB_PASSWORD : (envVars.DB_PASSWORD !== undefined ? envVars.DB_PASSWORD : ""),
    database: process.env.DB_DATABASE || envVars.DB_DATABASE || "nooryak_whatautomation",
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
