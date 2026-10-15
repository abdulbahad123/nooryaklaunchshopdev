process.removeAllListeners('warning');
process.on('uncaughtException', (err) => {
  console.error('[UNCAUGHT_EXCEPTION]', err);
});
process.on('unhandledRejection', (reason) => {
  console.error('[UNHANDLED_REJECTION]', reason);
});

import WAZIPER from "./waziper/waziper.js";
import Common from "./waziper/common.js";

// Run auto-discovery on startup to register all existing agency databases
Common.autoDiscoverDatabases().catch(() => {});

// Multi-Tenant Context Middleware
WAZIPER.app.use((req, res, next) => {
  const instance_id = req.query.instance_id || req.body?.instance_id;
  const db = req.query.db || req.body?.db || (instance_id ? Common.getDbForInstance(instance_id) : null);

  if (instance_id && (req.query.db || req.body?.db)) {
    Common.registerInstanceDb(instance_id, req.query.db || req.body?.db);
  }

  Common.withDatabase(db, () => {
    next();
  });
});

WAZIPER.app.get("/instance", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  const db = req.query.db || req.body?.db;

  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    await WAZIPER.get_info(instance_id, res);
  }, db);
});

WAZIPER.app.get("/get_qrcode", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  const db = req.query.db || req.body?.db;
  
  if (!access_token || !instance_id) {
    return res.json({
      status: "error",
      message: "Authentication failed. Access token and Instance ID are required."
    });
  }
  
  try {
    await WAZIPER.instance(access_token, instance_id, true, res, async () => {
      await WAZIPER.get_qrcode(instance_id, res);
    }, db);
  } catch (error) {
    console.error(`[GET_QRCODE ERROR] ${instance_id}:`, error);
    return res.json({
      status: "error",
      message: `Failed to generate QR code: ${error.message}`
    });
  }
});

WAZIPER.app.get("/get_groups", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  const db = req.query.db || req.body?.db;

  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    await WAZIPER.get_groups(instance_id, res);
  }, db);
});

WAZIPER.app.get("/logout", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  const db = req.query.db || req.body?.db;

  WAZIPER.logout(instance_id, res, db);
});

WAZIPER.app.post("/send_message", async (req, res) => {
  const access_token = req.query.access_token || req.body.access_token;
  const instance_id = req.query.instance_id || req.body.instance_id;
  const db = req.query.db || req.body.db;

  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    WAZIPER.send_message(instance_id, access_token, req, res);
  }, db);
});

WAZIPER.app.get("/", async (req, res) => {
  // Freshly discover any newly imported agency databases immediately on request
  await Common.autoDiscoverDatabases().catch(() => {});
  return res.json({ 
    status: "success", 
    message: "Welcome to WAZIPER Universal 1-Node Server",
    active_databases: Common.getAllDatabases()
  });
});

const PORT = process.env.PORT || 8000;
WAZIPER.server.listen(PORT, () => {
  console.log(`✅ WAZIPER UNIVERSAL SERVER IS LIVE ON PORT ${PORT}`);
});
