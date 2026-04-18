<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import HrmLayout from '@/layouts/settings/hrmLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'ZKTeco Device Manager ', href: '/zkteco' }];

// Reactive data
const deviceConfig = ref({
    ip: '',
    port: '',
});

const selectedDate = ref(new Date().toISOString().split('T')[0]); // Today's date
const dateRange = ref({
    start: '',
    end: '',
});

const connecting = ref(false);
const syncing = ref(false);
const loading = ref(false);
const deviceStatus = ref({
    connected: false,
});
const syncResult = ref(null);
const stats = ref({
    total_records: 0,
    today_records: 0,
    unique_users: 0,
    last_7_days: 0,
    last_30_days: 0,
});

// Methods
const setTodayDate = () => {
    selectedDate.value = new Date().toISOString().split('T')[0];
};

const flash = usePage().props.flash;

const formatDateForDisplay = (date) => {
    if (!date) return;
    toast('error', {
        description: flash.error,
    });

    const d = new Date(date);
    return d.toLocaleDateString();
};

const connectDevice = async () => {
    if (!deviceConfig.value.ip || !deviceConfig.value.port) {
        toast('error', {
            description: flash.error,
        });
        return;
    }

    connecting.value = true;
    syncResult.value = null;
    loading.value = true;

    try {
        const response = await axios.post('zkteco/connect', deviceConfig.value);

        if (response.data.success) {
            toast('success', {
                description: response.data.message,
            });
            await checkDeviceStatus();
            await loadStats();
        } else {
            toast('error', {
                description: response.data.message,
            });
        }
    } catch (error) {
        toast('error', {
            description: flash.error,
        });
    } finally {
        connecting.value = false;
        loading.value = false;
    }
};

const syncData = async () => {
    if (!deviceStatus.value.connected) {
        toast('error', {
            description: flash.error,
        });
        return;
    }

    if (!selectedDate.value) {
        toast('error', {
            description: flash.error,
        });
        return;
    }

    syncing.value = true;
    syncResult.value = null;
    loading.value = true;

    try {
        const response = await axios.post('zkteco/sync', {
            date: selectedDate.value,
        });
        syncResult.value = response.data;

        if (response.data.success) {
            await loadStats();
            await checkDeviceStatus();
            toast('success', {
                description: `✅ Sync complete! ${response.data.data.new_records} records synced for ${formatDateForDisplay(selectedDate.value)}`,
            });
        }
    } catch (error) {
        syncResult.value = {
            success: false,
            message: error.response?.data?.message || error.message,
        };
    } finally {
        syncing.value = false;
        loading.value = false;

        // Hide result after 5 seconds
        setTimeout(() => {
            syncResult.value = null;
        }, 5000);
    }
};

const syncDateRange = async () => {
    if (!deviceStatus.value.connected) {
        toast('error', {
            description: flash.error,
        });
        return;
    }

    if (!dateRange.value.start || !dateRange.value.end) {
        toast('error', {
            description: flash.error,
        });
        return;
    }

    syncing.value = true;
    syncResult.value = null;
    loading.value = true;

    try {
        const response = await axios.post('zkteco/sync-range', {
            start_date: dateRange.value.start,
            end_date: dateRange.value.end,
        });
        syncResult.value = response.data;

        if (response.data.success) {
            await loadStats();
            await checkDeviceStatus();
            toast('success', {
                description: `✅ Sync complete! ${response.data.data.new_records} records synced from ${formatDateForDisplay(dateRange.value.start)} to ${formatDateForDisplay(dateRange.value.end)}`,
            });
        }
    } catch (error) {
        syncResult.value = {
            success: false,
            message: error.response?.data?.message || error.message,
        };
    } finally {
        syncing.value = false;
        loading.value = false;

        setTimeout(() => {
            syncResult.value = null;
        }, 5000);
    }
};

const checkDeviceStatus = async () => {
    try {
        const response = await axios.get('/zkteco/status');
        deviceStatus.value = response.data;
    } catch (error) {
        deviceStatus.value = {
            connected: false,
            message: 'Failed to check status',
        };
    }
};

const loadStats = async () => {
    try {
        const response = await axios.get('zkteco/stats');
        stats.value = response.data;
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    const d = new Date(date);
    return d.toLocaleString();
};

// Component mount
onMounted(() => {
    checkDeviceStatus();
    loadStats();

    // Auto refresh every 30 seconds
    const interval = setInterval(() => {
        if (!syncing.value && !connecting.value) {
            checkDeviceStatus();
            loadStats();
        }
    }, 30000);

    // Cleanup
    return () => clearInterval(interval);
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="ZKTeco Device Manager" />
        <HrmLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
                <div class="min-h-screen bg-gray-100 p-4 md:p-6">
                    <div class="mx-auto max-w-7xl space-y-6">
                        <!-- 🔌 CONNECT + STATUS -->
                        <div class="grid gap-6 lg:grid-cols-2">
                            <!-- Connect Card -->
                            <div class="rounded-2xl bg-white p-6 shadow-lg">
                                <h2 class="mb-4 text-lg font-semibold text-gray-800">🔌 Connect Device</h2>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm text-gray-600">Device IP</label>
                                        <input
                                            type="text"
                                            v-model="deviceConfig.ip"
                                            placeholder="192.168.1.100"
                                            class="mt-1 w-full rounded-xl border px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                                        />
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-600">Port</label>
                                        <input
                                            type="number"
                                            v-model="deviceConfig.port"
                                            placeholder="4370"
                                            class="mt-1 w-full rounded-xl border px-4 py-2 focus:ring-2 focus:ring-cyan-500"
                                        />
                                    </div>

                                    <button
                                        @click="connectDevice"
                                        :disabled="connecting"
                                        class="w-full rounded-xl bg-cyan-600 py-2 font-semibold text-white transition hover:bg-cyan-700 disabled:opacity-50"
                                    >
                                        {{ connecting ? 'Connecting...' : 'Connect Device' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Status Card -->
                            <div class="rounded-2xl bg-white p-6 shadow-lg">
                                <h2 class="mb-4 text-lg font-semibold text-gray-800">📊 Device Status</h2>

                                <div v-if="deviceStatus.connected" class="space-y-3">
                                    <div class="rounded-lg bg-green-100 p-3 font-medium text-green-700">✅ Connected</div>

                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>IP:</div>
                                        <div class="font-medium">{{ deviceStatus.ip }}</div>
                                        <div>Port:</div>
                                        <div>{{ deviceStatus.port }}</div>
                                        <div>Name:</div>
                                        <div>{{ deviceStatus.device_info?.name }}</div>
                                        <div>Serial:</div>
                                        <div>{{ deviceStatus.device_info?.serial }}</div>
                                        <div>Last:</div>
                                        <div>{{ formatDate(deviceStatus.last_connected) }}</div>
                                        <div>Total:</div>
                                        <div class="font-bold text-blue-600">{{ deviceStatus.total_records }}</div>
                                    </div>
                                </div>

                                <div v-else class="rounded-lg bg-yellow-100 p-4 text-yellow-700">⚠️ Not Connected</div>
                            </div>
                        </div>

                        <!-- 📅 DATE SECTION -->
                        <div class="rounded-2xl bg-white p-6 shadow-lg">
                            <h2 class="mb-4 text-lg font-semibold text-gray-800">📅 Select Date</h2>

                            <div class="grid gap-4 md:grid-cols-3">
                                <input type="date" v-model="selectedDate" class="rounded-xl border px-4 py-2 focus:ring-2 focus:ring-purple-500" />

                                <button @click="setTodayDate" class="rounded-xl bg-gray-700 py-2 text-white">Today</button>

                                <button
                                    @click="syncData"
                                    :disabled="syncing || !deviceStatus.connected"
                                    class="rounded-xl bg-green-600 py-2 font-semibold text-white"
                                >
                                    {{ syncing ? 'Syncing...' : 'Sync Data' }}
                                </button>
                            </div>

                            <!-- Range -->
                            <div class="mt-5 grid gap-3 md:grid-cols-3">
                                <input type="date" v-model="dateRange.start" class="rounded-xl border px-3 py-2" />
                                <input type="date" v-model="dateRange.end" class="rounded-xl border px-3 py-2" />
                                <button @click="syncDateRange" class="rounded-xl bg-blue-600 py-2 text-white">Sync Range</button>
                            </div>
                        </div>

                        <!-- 📊 STATS -->
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-2xl bg-blue-600 p-4 text-white shadow">
                                <p>Total</p>
                                <h3 class="text-2xl font-bold">{{ stats.total_records }}</h3>
                            </div>

                            <div class="rounded-2xl bg-green-600 p-4 text-white shadow">
                                <p>Today</p>
                                <h3 class="text-2xl font-bold">{{ stats.today_records }}</h3>
                            </div>

                            <div class="rounded-2xl bg-purple-600 p-4 text-white shadow">
                                <p>Users</p>
                                <h3 class="text-2xl font-bold">{{ stats.unique_users }}</h3>
                            </div>

                            <div class="rounded-2xl bg-orange-500 p-4 text-white shadow">
                                <p>7 Days</p>
                                <h3 class="text-2xl font-bold">{{ stats.last_7_days }}</h3>
                            </div>
                        </div>
                        <!-- Loading Indicator -->
                        <div
                            v-if="loading"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-all duration-300"
                        >
                            <div class="min-w-55 rounded-2xl border border-white/20 bg-white/90 p-6 shadow-2xl">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="relative">
                                        <div class="h-14 w-14 animate-spin rounded-full border-4 border-gray-200 border-t-blue-600"></div>
                                    </div>

                                    <div class="text-center">
                                        <h3 class="font-semibold text-gray-800">Please Wait</h3>
                                        <p class="text-sm text-gray-500">Processing request...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </HrmLayout>
    </AppLayout>
</template>
