<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import NavUser from '@/components/NavUser.vue';
import FollowUpActivityModal from '@/pages/allpages/Agency/MetaChat/FollowUpComponents/FollowUpActivityModal.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import {
    BellIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    UserPlusIcon,
    SunIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import { computed, onMounted, onBeforeUnmount, ref } from 'vue';
import echo from '@/echo';


interface NotificationItem {
    id: number;
    user_id: number;
    follow_up_activity_id: number | null;
    follow_up_reminder_id: number | null;
    type: string;
    title: string;
    message: string;
    data: {
        priority?: string;
        student_id?: number;
        activity_id?: number;
        follow_up_date?: string;
        follow_up_time?: string;
    } | null;
    read_at: string | null;
    created_at: string;
    updated_at: string;
}

const time = ref<string>('');
const timeStyle = ref<{ color: string; textShadow: string; fontSize: string; fontWeight: string }>({
    color: '#2c3e50',
    textShadow: '0 2px 4px rgba(0,0,0,0.1)',
    fontSize: 'clamp(0.85rem, 2vw, 1.875rem)',
    fontWeight: '700',
});

const page = usePage();

const userId = computed<number | null>(
    () => (page.props as any).auth?.user?.id ?? null
);


const notifications = ref<NotificationItem[]>([]);
const unreadCount = ref(0);

const loading = ref(false);
const open = ref(false);
const markingAll = ref(false);

const hasNotifications = computed(
    () => notifications.value.length > 0
);


/*
|--------------------------------------------------------------------------
| Load Notifications
|--------------------------------------------------------------------------
*/
const currentPage = ref(1);
const lastPage = ref(1);
const total = ref(0);
const perPage = ref<number | null>(null);

const loadNotifications = async (pageNumber = 1) => {
    if (!userId.value) return;

    loading.value = true;

    try {
        const params: Record<string, any> = {
            page: pageNumber,
        };

        if (perPage.value) {
            params.per_page = perPage.value;
        }

        const response = await axios.get(
            `/follow-up-notifications/user/${userId.value}`,
            { params }
        );

        const result = response.data.data;

        notifications.value = result.data ?? [];

        currentPage.value = result.current_page ?? 1;
        lastPage.value = result.last_page ?? 1;
        total.value = result.total ?? 0;
        perPage.value = result.per_page ?? 15;

    } catch (error: any) {
        toast.error('Failed to load follow-up notifications.', error?.response?.data?.message);

    } finally {
        loading.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Load Unread Count
|--------------------------------------------------------------------------
*/

const loadUnreadCount = async () => {
    try {
        const response = await axios.get(
            `/follow-up-notifications/user/${userId.value}/unread-count`
        );
        unreadCount.value =
            response.data?.count ?? 0;

    } catch (error: any) {
        toast.error(
            'Notification Count Error:',
            error.response?.status
        );

        toast.error(
            'Notification Count Response:',
            error.response?.data
        );

        toast.error(
            'Notification Count URL:',
            error.config?.url
        );
    }
};

/*
|--------------------------------------------------------------------------
| Refresh
|--------------------------------------------------------------------------
*/

const refreshNotifications = async () => {

    await Promise.all([
        loadNotifications(),
        loadUnreadCount(),
    ]);

};

/*
|--------------------------------------------------------------------------
| Toggle Dropdown
|--------------------------------------------------------------------------
*/

const toggleNotifications = async () => {

    open.value = !open.value;

    if (open.value) {
        await refreshNotifications();
    }

};

/*
|--------------------------------------------------------------------------
| Mark As Read
|--------------------------------------------------------------------------
*/

const markAsRead = async (
    notification: NotificationItem
) => {

    if (notification.read_at) {
        return;
    }

    try {

        await axios.post(
            `/follow-up-notifications/${notification.id}/read/${userId.value}`
        );

        notification.read_at =
            new Date().toISOString();

        if (unreadCount.value > 0) {
            unreadCount.value--;
        }

    } catch (error: any) {

        toast.error(
            'Failed to mark notification as read.',
            error?.response?.data?.message
        );
    }
};

const openNotification = async (
    notification: NotificationItem
) => {
    await markAsRead(notification);

    const activityId =
        notification.follow_up_activity_id ??
        notification.data?.activity_id;

    if (!activityId) {
        toast.error(
            'Follow-up Activity ID not found.',
            notification
        );

        return;
    }

    openFollowUpActivity(activityId);

    open.value = false;
};

/*
|--------------------------------------------------------------------------
| Mark All As Read
|--------------------------------------------------------------------------
*/

const markAllAsRead = async () => {

    if (unreadCount.value === 0) {
        return;
    }

    try {

        await axios.post(
            `/follow-up-notifications/user/${userId.value}/read-all`
        );

        notifications.value.forEach(
            notification => {
                if (!notification.read_at) {
                    notification.read_at =
                        new Date().toISOString();
                }
            }
        );

        unreadCount.value = 0;

    } catch (error: any) {

        toast.error(
            'Failed to mark all notifications as read.',
            error?.response?.data?.message
        );
    }
};

/*
|--------------------------------------------------------------------------
| Notification Icon
|--------------------------------------------------------------------------
*/

const notificationIcon = (
    type: string
) => {

    switch (type) {

        case 'follow_up_assigned':
            return UserPlusIcon;

        case 'follow_up_due':
            return ClockIcon;

        case 'follow_up_overdue':
            return ExclamationTriangleIcon;

        default:
            return BellIcon;
    }
};

const notificationIconClass = (
    notification: NotificationItem
) => {

    if (notification.read_at) {
        return 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400';
    }

    switch (notification.type) {

        case 'follow_up_overdue':
            return 'bg-red-100 text-red-600 dark:bg-red-950/80 dark:text-red-400';

        case 'follow_up_due':
            return 'bg-amber-100 text-amber-600 dark:bg-amber-950/80 dark:text-amber-400';

        case 'follow_up_assigned':
            return 'bg-blue-100 text-blue-600 dark:bg-blue-950/80 dark:text-blue-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }

};

/*
|--------------------------------------------------------------------------
| Priority
|--------------------------------------------------------------------------
*/

const priorityClass = (
    priority?: string
) => {

    switch (priority) {

        case 'Urgent':
            return 'bg-red-100 text-red-700 dark:bg-red-950/80 dark:text-red-300';

        case 'High':
            return 'bg-orange-100 text-orange-700 dark:bg-orange-950/80 dark:text-orange-300';

        case 'Medium':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300';

        case 'Low':
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
};

/*
|--------------------------------------------------------------------------
| Time
|--------------------------------------------------------------------------
*/

const notificationTime = (
    date: string
) => {

    const created = new Date(date);
    const now = new Date();

    const diff =
        Math.floor(
            (now.getTime() - created.getTime())
            / 1000
        );

    if (diff < 60) {
        return 'Just now';
    }

    if (diff < 3600) {
        return `${Math.floor(diff / 60)} min ago`;
    }

    if (diff < 86400) {
        return `${Math.floor(diff / 3600)} hr ago`;
    }

    if (diff < 172800) {
        return 'Yesterday';
    }

    return created.toLocaleDateString();
};

let notificationChannel: string | null = null;

const listenForFollowUpNotifications = () => {
    if (!userId.value) {
        return;
    }

    notificationChannel = `follow-up-notifications.${userId.value}`;

    echo.private(notificationChannel)
        .listen(
            '.follow-up.notification.created',
            (event: any) => {

                const notification =
                    event.notification;

                notifications.value.unshift(
                    notification
                );

                unreadCount.value++;


            }
        );
};

let clockTimer: any = null;

const updateClock = () => {
    const now = new Date();
    time.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
};

onMounted(() => {
    updateClock();
    clockTimer = setInterval(updateClock, 1000);

    if (!userId.value) {
        return;
    }

    refreshNotifications();
    listenForFollowUpNotifications();

});

const stopNotificationListener = () => {
    if (!notificationChannel) {
        return;
    }

    echo.leave(notificationChannel);
    notificationChannel = null;
};

onBeforeUnmount(() => {
    if (clockTimer) {
        clearInterval(clockTimer);
    }
    stopNotificationListener();
});



const showFollowUpActivityModal = ref(false);

const selectedFollowUpActivityId =
    ref<number | null>(null);

const openFollowUpActivity = (
    activityId: number
) => {
    selectedFollowUpActivityId.value =
        activityId;

    showFollowUpActivityModal.value =
        true;
};

const closeFollowUpActivity = () => {
    showFollowUpActivityModal.value =
        false;

    selectedFollowUpActivityId.value =
        null;
};

const goToPage = (pageNumber: number) => {
    if (
        pageNumber < 1 ||
        pageNumber > lastPage.value ||
        loading.value
    ) {
        return;
    }

    loadNotifications(pageNumber);

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

</script>
<style scoped>
.time-display-alt {
    font-family: 'Courier New', monospace;
    font-size: clamp(0.85rem, 2vw, 1.875rem);
    font-weight: 800;
    color: #2c3e50;
    text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    letter-spacing: clamp(0.5px, 0.18vw, 2px);
    transition: all 0.5s ease;
    white-space: nowrap;
    line-height: 1;
}

/* Modern dark theme */

.time-display-dark {
    font-family: 'Arial', sans-serif;
    font-size: clamp(0.85rem, 2vw, 1.875rem);
    font-weight: 700;
    color: #ecf0f1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    letter-spacing: clamp(0.5px, 0.14vw, 1px);
    white-space: nowrap;
    line-height: 1;
}

@media (max-width: 768px) {

    .time-display-alt,
    .time-display-dark {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}
</style>
<template>
    <header
        class="bg-background/95 sticky top-0 z-20 grid min-h-16 shrink-0 grid-cols-[minmax(0,1fr)_auto] items-center gap-3 border-b border-gray-800 px-4 py-2 backdrop-blur transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:grid-cols-3 md:px-6">
        <div class="flex min-w-0 items-center gap-2">
            <SidebarTrigger class="-ml-1" />
        </div>

        <div class="order-3 col-span-2 flex min-w-0 items-center justify-center md:order-none md:col-span-1">
            <div class="time-display-alt max-w-full text-center" :style="timeStyle">
                {{ time }}
            </div>
        </div>

        <div class="ml-auto flex items-center justify-end gap-2 md:gap-4">
            <!-- Appearance Menu -->
            <DropdownMenu>
                <DropdownMenuTrigger :as-child="true">
                    <button
                        class="relative rounded-lg p-2 text-gray-400 transition hover:bg-gray-800/60 hover:text-white">
                        <SunIcon class="h-5 w-5" />
                    </button>
                </DropdownMenuTrigger>

                <DropdownMenuContent align="end"
                    class="w-max max-w-[calc(100vw-1.5rem)] overflow-hidden rounded-xl border border-gray-200 bg-white p-2 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                    <AppearanceTabs />
                </DropdownMenuContent>
            </DropdownMenu>
            <!-- Notifications -->
            <div class="relative">

                <button type="button" @click="toggleNotifications"
                    class="relative rounded-xl bg-white p-2.5 text-slate-600 transition hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white">

                    <BellIcon class="h-5 w-5" />

                    <span v-if="unreadCount > 0"
                        class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-900">
                        {{
                            unreadCount > 99
                                ? '99+'
                                : unreadCount
                        }}
                    </span>

                </button>

                <!-- Backdrop overlay for outside click on mobile and desktop -->
                <div v-if="open" class="fixed inset-0 z-40 bg-black/20 sm:bg-transparent" @click="open = false" />

                <Transition enter-active-class="transition duration-150 ease-out"
                    enter-from-class="translate-y-1 opacity-0" enter-to-class="translate-y-0 opacity-100"
                    leave-active-class="transition duration-100 ease-in" leave-from-class="translate-y-0 opacity-100"
                    leave-to-class="translate-y-1 opacity-0">

                    <div v-if="open"
                        class="fixed inset-x-3 top-16 z-50 flex max-h-[calc(100vh-5rem)] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-900 sm:absolute sm:inset-auto sm:right-0 sm:top-full sm:mt-2 sm:w-[380px] sm:max-h-[600px] sm:max-w-none">

                        <!-- Header -->
                        <div
                            class="flex shrink-0 items-center justify-between border-b border-slate-100 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">

                            <div>

                                <div class="flex items-center gap-2">

                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                        Notifications
                                    </h3>

                                    <span v-if="unreadCount > 0"
                                        class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600 dark:bg-red-950/80 dark:text-red-400">
                                        {{ unreadCount }} new
                                    </span>

                                </div>

                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                    Follow-up activities
                                </p>

                            </div>


                            <div class="flex items-center gap-1">

                                <button v-if="unreadCount > 0" type="button" :disabled="markingAll"
                                    @click="markAllAsRead"
                                    class="rounded-lg px-2 py-1 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 disabled:opacity-50 dark:text-blue-400 dark:hover:bg-blue-950/50">
                                    <span v-if="!markingAll">
                                        Mark all read
                                    </span>

                                    <span v-else>
                                        Updating...
                                    </span>
                                </button>


                                <button type="button" @click="open = false"
                                    class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200">

                                    <XMarkIcon class="h-4 w-4" />

                                </button>

                            </div>

                        </div>


                        <!-- Loading state -->
                        <div v-if="loading" class="flex flex-1 items-center justify-center py-12">

                            <div
                                class="h-6 w-6 animate-spin rounded-full border-2 border-slate-200 border-t-blue-600 dark:border-slate-700 dark:border-t-blue-500" />

                        </div>


                        <!-- Empty state -->
                        <div v-else-if="!hasNotifications" class="flex-1 px-6 py-12 text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">

                                <BellIcon class="h-7 w-7 text-slate-400 dark:text-slate-500" />

                            </div>

                            <h4 class="mt-4 text-sm font-semibold text-slate-700 dark:text-slate-300">
                                You're all caught up
                            </h4>

                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                No follow-up notifications available.
                            </p>

                        </div>

                        <!-- Notification items list -->
                        <div v-else
                            class="flex-1 max-h-[420px] overflow-y-auto overflow-x-hidden divide-y divide-slate-100 dark:divide-slate-800">

                            <div v-for="notification in notifications" :key="notification.id"
                                @click="openNotification(notification)"
                                class="group relative w-full cursor-pointer px-4 py-3.5 transition-all duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/60"
                                :class="{
                                    'bg-blue-50/50 dark:bg-blue-950/20': !notification.read_at,
                                    'bg-white dark:bg-slate-900': notification.read_at,
                                }">

                                <!-- Unread indicator -->
                                <span v-if="!notification.read_at"
                                    class="absolute left-0 top-0 h-full w-1 bg-blue-600 dark:bg-blue-500" />

                                <div class="flex w-full min-w-0 items-start gap-3">

                                    <!-- Notification Icon -->

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                                        :class="notificationIconClass(notification)">
                                        <component :is="notificationIcon(notification.type)" class="h-4 w-4" />
                                    </div>


                                    <!-- Content -->

                                    <div class="min-w-0 flex-1 overflow-hidden">

                                        <!-- Title + unread dot -->

                                        <div class="flex min-w-0 items-start gap-2">

                                            <p class="min-w-0 flex-1 truncate text-xs sm:text-sm" :class="notification.read_at
                                                ? 'font-medium text-slate-700 dark:text-slate-300'
                                                : 'font-semibold text-slate-900 dark:text-white'
                                                ">
                                                {{ notification.title }}
                                            </p>

                                            <span v-if="!notification.read_at"
                                                class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-blue-600 dark:bg-blue-500" />
                                        </div>


                                        <!-- Message -->

                                        <p
                                            class="mt-1 line-clamp-2 break-words text-xs leading-4 text-slate-500 dark:text-slate-400">
                                            {{ notification.message }}
                                        </p>


                                        <!-- Meta -->

                                        <div class="mt-2 flex flex-wrap items-center gap-2">

                                            <!-- Time -->

                                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">
                                                {{ notificationTime(notification.created_at) }}
                                            </span>


                                            <!-- Separator -->

                                            <span v-if="notification.data?.priority"
                                                class="text-[10px] text-slate-300 dark:text-slate-600">
                                                •
                                            </span>


                                            <!-- Priority -->

                                            <span v-if="notification.data?.priority"
                                                class="rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="priorityClass(
                                                    notification.data.priority
                                                )
                                                    ">
                                                {{ notification.data.priority }}
                                            </span>

                                        </div>

                                    </div>


                                    <!-- Arrow -->

                                    <div
                                        class="mt-2 shrink-0 text-slate-300 opacity-0 transition-opacity group-hover:opacity-100 dark:text-slate-600 dark:group-hover:text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7" />
                                        </svg>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Pagination -->

                        <div v-if="lastPage > 1"
                            class="flex shrink-0 flex-wrap items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/80 px-4 py-2.5 dark:border-slate-800 dark:bg-slate-950">

                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                Page {{ currentPage }} of {{ lastPage }}
                            </p>


                            <div class="flex items-center gap-1">

                                <button type="button" :disabled="currentPage === 1 || loading"
                                    @click="goToPage(currentPage - 1)"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                                    Prev
                                </button>


                                <template v-if="lastPage <= 5">
                                    <button v-for="pageNumber in lastPage" :key="pageNumber" type="button"
                                        @click="goToPage(pageNumber)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold transition"
                                        :class="pageNumber === currentPage
                                            ? 'bg-blue-600 text-white'
                                            : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800'
                                            ">
                                        {{ pageNumber }}
                                    </button>
                                </template>


                                <button type="button" :disabled="currentPage === lastPage || loading"
                                    @click="goToPage(currentPage + 1)"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                                    Next
                                </button>

                            </div>

                        </div>

                        <div v-if="hasNotifications"
                            class="shrink-0 border-t border-slate-100 bg-slate-50 px-4 py-2.5 text-center dark:border-slate-800 dark:bg-slate-950">

                            <button type="button"
                                class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                View all notifications
                            </button>

                        </div>

                    </div>

                </Transition>

            </div>

            <NavUser />
        </div>
    </header>
    <FollowUpActivityModal :show="showFollowUpActivityModal" :activity-id="selectedFollowUpActivityId"
        @close="closeFollowUpActivity" />
</template>
