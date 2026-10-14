import Common from "./waziper/common.js";

async function test() {
    const team = await Common.db_get('teams', [{ id: 1 }]);
    console.log("team.permissions type:", typeof team.permissions);
    console.log("team.permissions value:", team.permissions);
    try {
        const parsed = JSON.parse(team.permissions || '{}');
        console.log("parsed:", parsed);
        console.log("parsed.whatsapp_message_per_month:", parsed.whatsapp_message_per_month);
    } catch (e) {
        console.error("JSON.parse error:", e.message);
    }
    process.exit(0);
}
test();
