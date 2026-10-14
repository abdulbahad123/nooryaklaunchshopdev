import Common from "./waziper/common.js";

async function test() {
    const instance_id = "6944B3FE0348";
    const bots = await Common.db_fetch("whatsapp_chatbot", [
        { instance_id },
        { status: 1 },
        { run: 1 },
    ]);
    console.log("Bots found:", bots);

    const waSession = await Common.db_get("whatsapp_sessions", [{ instance_id }]);
    console.log("WA Session found:", waSession);
    process.exit(0);
}
test();
