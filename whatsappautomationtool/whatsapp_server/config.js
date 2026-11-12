/**
 * config.js (ESM version)
 * Compatible with Node.js 18/20/22/24
 * Automatically reads database credentials from .env files with environment variable overrides
 */

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Candidate paths to search for .env in order of priority
const candidateEnvPaths = [
  path.join(__dirname, ".env"),
  path.join(__dirname, "../Install/.env"),
  path.join(__dirname, "../.env"),
  "/home/nooryak/public_html/saasreselling.com/Install/.env",
  "/home/nooryak/public_html/saasreselling.com/.env",
  "/home/nooryak/public_html/whatsappbulk.youverse.in/Install/.env",
  "/home/nooryak/public_html/whatsappbulk.youverse.in/.env",
  "/home/nooryak/whatsappbulk.youverse.in/Install/.env",
  "/home/nooryak/whatsappbulk.youverse.in/.env",
  "/home/nooryak/public_html/.env",
];

const envVars = {};

for (const envPath of candidateEnvPaths) {
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
          if (val && (!envVars[key] || envVars[key] === "")) {
            envVars[key] = val;
          }
        }
      }
    }
  } catch (e) {}
}

const config = {
  debug: process.env.NODE_DEBUG === "true",
  app_url: process.env.APP_URL || envVars.APP_URL || "http://localhost:8080/",
  email_cron_enabled: process.env.EMAIL_CRON_ENABLED !== "false" && envVars.EMAIL_CRON_ENABLED !== "false",
  email_cron_url: process.env.EMAIL_CRON_URL || envVars.EMAIL_CRON_URL || "",
  database: {
    connectionLimit: 100,
    host: process.env.DB_HOST || envVars.DB_HOST || "127.0.0.1",
    user: process.env.DB_USERNAME || envVars.DB_USERNAME || "nooryak_sassadmindbuser",
    password: process.env.DB_PASSWORD !== undefined
      ? process.env.DB_PASSWORD
      : (envVars.DB_PASSWORD !== undefined && envVars.DB_PASSWORD !== ""
          ? envVars.DB_PASSWORD
          : "MySecretPass123!"),
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
