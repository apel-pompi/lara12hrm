const path = require("path");
const fs = require("fs");
const express = require('express');
const axios = require("axios");
const Zkteco = require("zkteco-js");

const app = express();
const port = 3000;

// --- CONFIGURATION ---
const CONFIG = {
    SYNC_INTERVAL: 10 * 60 * 1000, // ডিফল্ট ১০ মিনিট (অটো সিঙ্কের জন্য)
    DEVICE_PORT: 4370,
    DEFAULT_IP: "118.67.221.58", // আপনার ডিফল্ট আইপি
    DEFAULT_API: "http://crm.glendonedu.com/api"
};

// --- HELPERS ---
const formatRecordTime = (record_time) => {
    try {
        const date = new Date(record_time);
        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const dd = String(date.getDate()).padStart(2, "0");
        const hh = String(date.getHours()).padStart(2, "0");
        const mi = String(date.getMinutes()).padStart(2, "0");
        const ss = String(date.getSeconds()).padStart(2, "0");
        return `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`;
    } catch (error) {
        return null;
    }
};

const getCurrentDate = () => new Date().toISOString().slice(0, 10);

const safeDeviceOperation = async (device, operation) => {
    try { return await operation(device); } catch (error) {
        console.error("Device operation failed:", error.message);
        return null;
    }
};

// --- CORE SYNC FUNCTION ---
const performSync = async (date, targetIp, targetApi) => {
    console.log(`\n[${new Date().toLocaleTimeString()}] Sync Start: IP ${targetIp}`);
    let device;
    try {
        device = new Zkteco(targetIp, CONFIG.DEVICE_PORT, 5200, 5000);
        await device.createSocket();
        
        const result = await device.getAttendances();
        const logs = result.data || result || [];
        
        const filteredLogs = logs.filter(log => {
            if (!log.record_time) return false;
            return new Date(log.record_time).toISOString().slice(0, 10) === date;
        });

        console.log(`Total logs: ${logs.length}, Today's logs: ${filteredLogs.length}`);

        let successCount = 0;
        for (const log of filteredLogs) {
            const attendanceData = {
                user_id: (log.user_id || '').toString(),
                record_time: formatRecordTime(log.record_time),
                device_ip: targetIp,
                state: log.state || 0,
            };

            try {
                await axios.post(targetApi, attendanceData, { timeout: 10000 });
                successCount++;
            } catch (err) {
                console.error(`Failed to send User ${log.user_id}: ${err.message}`);
            }
        }
        return { success: true, successful: successCount, total: filteredLogs.length };
    } catch (error) {
        return { success: false, error: error.message };
    } finally {
        if (device) await device.disconnect().catch(() => {});
    }
};

// --- EXPRESS ROUTES (For Laravel Control) ---
app.get('/sync', async (req, res) => {
    const { ip, api } = req.query;
    const targetIp = ip || CONFIG.DEFAULT_IP;
    const targetApi = api || CONFIG.DEFAULT_API;

    const result = await performSync(getCurrentDate(), targetIp, targetApi);
    if (result.success) {
        res.json({ status: 'success', data: result });
    } else {
        res.status(500).json({ status: 'error', message: result.error });
    }
});

// --- AUTO SYNC SERVICE ---
const startAutoSync = () => {
    setInterval(async () => {
        console.log("Running Scheduled Auto-Sync...");
        await performSync(getCurrentDate(), CONFIG.DEFAULT_IP, CONFIG.DEFAULT_API);
    }, CONFIG.SYNC_INTERVAL);
};

// --- START SERVER ---
app.listen(port, () => {
    console.log(`
    =============================================
    🚀 ZKTeco Bridge Server running at http://localhost:${port}
    - Manual Sync: http://localhost:${port}/sync?ip=YOUR_IP&api=YOUR_API
    =============================================
    `);
    startAutoSync(); 
});