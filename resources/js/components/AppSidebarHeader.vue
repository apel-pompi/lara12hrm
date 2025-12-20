<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import NavUser from '@/components/NavUser.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { AlertTriangle, Bell, BellOff, CheckCircle, CircleAlert, CircleCheck, Info, Sun, X } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const time = ref<string>('');
const timeStyle = ref<{ color: string; textShadow: string; fontSize: string; fontWeight: string }>({
    color: '#2c3e50',
    textShadow: '0 2px 4px rgba(0,0,0,0.1)',
    fontSize: '30px',
    fontWeight: '700',
});

const updateTime = () => {
    const now = new Date();

    // Format time: HH:MM:SS
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12; // convert 0 → 12

    time.value = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

    // Update color based on time of day
    if (hours >= 6 && hours < 12) {
        // Morning - Golden color
        timeStyle.value.color = '#e67e22';
        timeStyle.value.textShadow = '0 2px 8px rgba(230, 126, 34, 0.3)';
    } else if (hours >= 12 && hours < 18) {
        // Afternoon - Blue color
        timeStyle.value.color = '#3498db';
        timeStyle.value.textShadow = '0 2px 8px rgba(52, 152, 219, 0.3)';
    } else if (hours >= 18 && hours < 22) {
        // Evening - Purple color
        timeStyle.value.color = '#9b59b6';
        timeStyle.value.textShadow = '0 2px 8px rgba(155, 89, 182, 0.3)';
    } else {
        // Night - Dark blue color
        timeStyle.value.color = '#2c3e50';
        timeStyle.value.textShadow = '0 2px 8px rgba(44, 62, 80, 0.3)';
    }

    // Add pulse animation every second
    timeStyle.value.fontSize = '30px';
    setTimeout(() => {
        timeStyle.value.fontSize = '30px';
    }, 100);
};

let interval: number;

onMounted(() => {
    updateTime();
    interval = window.setInterval(updateTime, 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

// Reactive states
const appearanceRef = ref(null);
const notificationsRef = ref(null);

const showAppearance = ref(false);
const toggleAppearance = () => {
    showAppearance.value = !showAppearance.value;
};

// Toggle notifications dropdown
const showNotifications = ref(false);
const notifications = ref([]);

// Fetch notifications from API
const fetchNotifications = async () => {
    try {
        const response = await fetch('/notifications', {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'include', // For sanctum cookies
        });

        if (!response.ok) {
            throw new Error('Failed to fetch notifications');
        }

        const data = await response.json();

        if (data.success) {
            notifications.value = data.notifications;
        }
    } catch (error) {
        console.error('Failed to fetch notifications:', error);
        notifications.value = [];
    }
};

// Computed property for unread count
const unreadCount = computed(() => {
    return notifications.value.filter((n) => !n.read).length;
});

// Toggle notifications dropdown
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value) {
        fetchNotifications();
    }
};

// Handle notification click
const handleNotificationClick = async (notification) => {
    // Mark as read
    if (!notification.read) {
        await markAsRead(notification.id);
    }
    // Handle navigation based on notification type
    if (notification.action_url) {
        window.location.href = notification.action_url;
    }

    showNotifications.value = false;
};

// Mark single notification as read
const markAsRead = async (notificationId: number) => {
    try {
        await axios.post(`/notifications/${notificationId}/read`);

        // UI Update
        const index = notifications.value.findIndex((n) => n.id === notificationId);
        if (index !== -1) notifications.value[index].read = true;

        toast.success('Notification marked as read');
    } catch (error) {
        toast.error('Failed to mark notification as read');
        console.error(error);
    }
};

// Mark all as read
const markAllAsRead = async () => {
    try {
        await axios.post(route('notifications.markAllAsRead'));

        notifications.value.forEach((n) => (n.read = true));

        toast.success('All notifications marked as read');
    } catch (error) {
        toast.error('Failed to mark all as read');
        console.error(error);
    }
};

// Notification icon mapper
const getNotificationIcon = (type) => {
    const icons = {
        success: { icon: CircleCheck, bg: 'bg-green-500/20', color: 'text-green-400' },
        error: { icon: CircleAlert, bg: 'bg-red-500/20', color: 'text-red-400' },
        warning: { icon: AlertTriangle, bg: 'bg-yellow-500/20', color: 'text-yellow-400' },
        info: { icon: Info, bg: 'bg-blue-500/20', color: 'text-blue-400' },
    };
    return icons[type] || icons.info;
};

// Format time
const formatTime = (timestamp) => {
    return new Date(timestamp).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;

    // Appearance dropdown close logic
    if (showAppearance.value && appearanceRef.value && !appearanceRef.value.contains(target)) {
        showAppearance.value = false;
    }

    // Notifications dropdown close logic
    if (showNotifications.value && notificationsRef.value && !notificationsRef.value.contains(target)) {
        showNotifications.value = false;
    }
};

// const checkUnread = async () => {
//     const response = await fetch(route('notifications.unreadCount'), {
//         headers: {
//             Accept: 'application/json',
//             'X-Requested-With': 'XMLHttpRequest',
//         },
//         credentials: 'include',
//     });

//     const data = await response.json();

    
//     if (data.unread_count > unreadCount.value) {
//         await fetchNotifications();
//         showNotifications.value = true;
//     }
// };

// Event listeners
// onMounted(() => {
//     document.addEventListener('click', handleClickOutside);
//     setInterval(checkUnread, 15000);
// });

// onUnmounted(() => {
//     document.removeEventListener('click', handleClickOutside);
// });
</script>
<style scoped>

.time-display-alt {
    font-family: 'Courier New', monospace;
    font-size: 30px;
    font-weight: 800;
    color: #2c3e50;
    text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    letter-spacing: 2px;
    transition: all 0.5s ease;
}

/* Modern dark theme */

.time-display-dark {
    font-family: 'Arial', sans-serif;
    font-size: 30px;
    font-weight: 700;
    color: #ecf0f1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    letter-spacing: 1px;
}
</style>
<template>
    <header
        class="grid h-16 shrink-0 grid-cols-3 items-center border-b border-gray-800 px-4 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-6"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center justify-center">
                <div class="time-display-alt" :style="timeStyle">
                    {{ time }}
                </div>
            
        </div>

        <div class="ml-auto flex items-center justify-end gap-4">
            <!-- Appearance Menu -->
            <div class="relative" ref="appearanceRef">
                <button @click="toggleAppearance" class="relative rounded-lg p-2 text-gray-400 transition hover:bg-gray-800/60 hover:text-white">
                    <Sun class="h-5 w-5" />
                </button>

                <div
                    v-if="showAppearance"
                    class="animate-in fade-in-0 zoom-in-95 absolute top-12 right-0 z-50 mr-1 w-80 overflow-hidden rounded-xl border border-gray-700 pl-8 shadow-2xl backdrop-blur-md"
                >
                    <AppearanceTabs />
                </div>
            </div>

            <!-- Notifications -->
            <!-- <div class="relative" ref="notificationsRef"> -->
                <!-- <button @click="toggleNotifications" class="relative rounded-lg p-2 text-gray-400 transition hover:bg-gray-800/60 hover:text-white">
                    <Bell class="h-5 w-5" />

                    <span
                        v-if="unreadCount > 0"
                        class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-semibold text-white"
                    >
                        {{ unreadCount > 9 ? '9+' : unreadCount }}
                    </span>
                </button> -->

                <!-- <div
                    v-if="showNotifications"
                    class="animate-in fade-in-0 zoom-in-95 absolute top-11 right-0 z-50 w-80 overflow-hidden rounded-xl border border-gray-700 bg-gray-900/95 shadow-2xl backdrop-blur-md"
                > -->
                    <!-- Header -->
                    <!-- <div class="flex items-center justify-between border-b border-gray-700 px-4 py-3">
                        <h3 class="text-sm font-semibold text-white">Notifications</h3>

                        <div class="flex items-center gap-1.5">
                            <button
                                v-if="unreadCount > 0"
                                @click="markAllAsRead"
                                class="rounded p-1.5 text-blue-400 transition hover:bg-blue-600/20 hover:text-blue-300"
                            >
                                <CheckCircle class="h-4 w-4" />
                            </button>

                            <button
                                @click="showNotifications = false"
                                class="rounded p-1.5 text-gray-400 transition hover:bg-gray-700 hover:text-white"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                    </div> -->

                    <!-- Notifications List -->
                    <!-- <div class="max-h-96 overflow-y-auto">
                        <template v-if="notifications.length">
                            <div
                                v-for="notification in notifications"
                                :key="notification.id"
                                @click="handleNotificationClick(notification)"
                                class="flex cursor-pointer gap-3 px-4 py-3 transition hover:bg-gray-800/60"
                                :class="notification.read ? 'bg-transparent' : 'bg-blue-600/15'"
                            >
                                <div class="flex h-9 w-9 items-center justify-center rounded-full" :class="getNotificationIcon(notification.type).bg">
                                    <component
                                        :is="getNotificationIcon(notification.type).icon"
                                        class="h-4 w-4"
                                        :class="getNotificationIcon(notification.type).color"
                                    />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-white">{{ notification.title }}</p>
                                    <p class="line-clamp-2 text-xs text-gray-400">{{ notification.message }}</p>
                                    <p class="mt-1 text-[11px] text-gray-500">{{ formatTime(notification.created_at) }}</p>
                                </div>

                                <span v-if="!notification.read" class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                            </div>
                        </template>

                        <div v-else class="p-6 text-center text-gray-400">
                            <BellOff class="mx-auto h-7 w-7 opacity-50" />
                            <p class="mt-2 text-sm">No notifications</p>
                        </div>
                    </div> -->

                    <!-- Footer -->
                    <!-- <div class="border-t border-gray-700 bg-gray-900/70 p-3">
                        <Link
                            :href="route('notifications.index')"
                            class="block w-full rounded py-2 text-center text-sm text-blue-400 transition hover:bg-blue-600/15 hover:text-blue-300"
                        >
                            View all notifications
                        </Link>
                    </div> -->
                <!-- </div>
            </div> -->

            <!-- Click Outside -->
            <!-- <div v-if="showNotifications" class="fixed inset-0 z-40" @click="showNotifications = false"></div> -->

            <NavUser />
        </div>
    </header>
</template>
