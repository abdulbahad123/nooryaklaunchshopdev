process.removeAllListeners('warning');
process.on('uncaughtException', (err) => {
  console.error('[UNCAUGHT_EXCEPTION]', err);
});
process.on('unhandledRejection', (reason) => {
  console.error('[UNHANDLED_REJECTION]', reason);
});

import WAZIPER from "./waziper/waziper.js";

WAZIPER.app.get("/instance", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    await WAZIPER.get_info(instance_id, res);
  });
});

WAZIPER.app.get("/get_qrcode", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  
  if (!access_token || !instance_id) {
    return res.json({
      status: "error",
      message: "Authentication failed. Access token and Instance ID are required."
    });
  }
  
  try {
    await WAZIPER.instance(access_token, instance_id, true, res, async () => {
      await WAZIPER.get_qrcode(instance_id, res);
    });
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
  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    await WAZIPER.get_groups(instance_id, res);
  });
});

WAZIPER.app.get("/logout", async (req, res) => {
  const access_token = req.query.access_token;
  const instance_id = req.query.instance_id;
  WAZIPER.logout(instance_id, res);
});

WAZIPER.app.post("/send_message", async (req, res) => {
  const access_token = req.query.access_token || req.body.access_token;
  const instance_id = req.query.instance_id || req.body.instance_id;
  await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
    WAZIPER.send_message(instance_id, access_token, req, res);
  });
});

WAZIPER.app.get("/", async (req, res) => {
  return res.json({ status: "success", message: "Welcome to WAZIPER" });
});

const PORT = process.env.PORT || 8000;
WAZIPER.server.listen(PORT, () => {
  console.log(`✅ WAZIPER IS LIVE ON PORT ${PORT}`);
});
