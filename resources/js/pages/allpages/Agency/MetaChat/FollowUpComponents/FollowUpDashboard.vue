<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { router, usePage, Link } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import {
    BellIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    UserPlusIcon,
    ArrowPathIcon,
    ChevronRightIcon,
} from '@heroicons/vue/24/outline';

const page = usePage();

const userId = computed(
    () => (page.props.auth as any)?.user?.id
);

const dashboard = ref({
    unread: 0,
    today: 0,
    due: 0,
    overdue: 0,
});

const notifications = ref<any[]>([]);

const loading = ref(true);

const fetchDashboard = async () => {
    if (!userId.value) return;

    loading.value = true;

    try {
        const [dashboardResponse, notificationResponse] =
            await Promise.all([
                axios.get(
                    `/follow-up-notifications/user/${userId.value}/dashboard`
                ),

                axios.get(
                    `/follow-up-notifications/user/${userId.value}`
                ),
            ]);

        dashboard.value =
            dashboardResponse.data.data ?? dashboard.value;

        notifications.value =
            notificationResponse.data.data?.data ?? [];

    } catch (error: any) {
        console.error(
            'Failed to load follow-up dashboard',
            error
        );

        toast.error(
            error?.response?.data?.message ??
            'Failed to load follow-up dashboard'
        );
    } finally {
        loading.value = false;
    }
};

const notificationIcon = (type: string) => {
    switch (type) {
        case 'follow_up_assigned':
            return UserPlusIcon;

        case 'follow_up_due':
            return ClockIcon;

        case 'follow_up_overdue':
            return ExclamationTriangleIcon;

        case 'follow_up_rescheduled':
            return ArrowPathIcon;

        default:
            return BellIcon;
    }
};

const notificationIconClass = (type: string) => {
    switch (type) {
        case 'follow_up_assigned':
            return 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400';

        case 'follow_up_due':
            return 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400';

        case 'follow_up_overdue':
            return 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400';

        case 'follow_up_rescheduled':
            return 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
};

const openNotification = async (
    notification: any
) => {
    try {
        if (!notification.read_at) {
            await axios.post(
                `/follow-up-notifications/${notification.id}/read/${userId.value}`
            );

            notification.read_at =
                new Date().toISOString();
        }

        const activityId =
            notification.data?.activity_id ??
            notification.follow_up_activity_id;

        if (activityId) {
            router.visit(
                route(
                    'follow-up-activities.show',
                    activityId
                )
            );
        }

    } catch (error: any) {
        toast.error('Failed to open notification', error?.response?.data?.message);
    }
};

onMounted(fetchDashboard);

</script>

<template>
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <!-- Header -->

        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">

            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    Follow-up Overview
                </h3>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Your follow-up activity at a glance
                </p>
            </div>

            <Link :href="route('follow-up-notifications.all', {
                userId: userId,
            })
                "
                class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/30">
                View All

                <ChevronRightIcon class="h-4 w-4" />
            </Link>

        </div>


        <!-- Stats -->

        <div
            class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:grid-cols-4 sm:divide-y-0 dark:divide-slate-800">

            <!-- Unread -->

            <div class="p-4">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <BellIcon class="h-4.5 w-4.5" />
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            Unread
                        </p>

                        <p class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ dashboard.unread }}
                        </p>
                    </div>

                </div>
            </div>


            <!-- Today -->

            <div class="p-4">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <ClockIcon class="h-4.5 w-4.5" />
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            Today
                        </p>

                        <p class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ dashboard.today }}
                        </p>
                    </div>

                </div>
            </div>


            <!-- Due -->

            <div class="p-4">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                        <ClockIcon class="h-4.5 w-4.5" />
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            Due
                        </p>

                        <p class="mt-0.5 text-xl font-bold text-slate-900 dark:text-white">
                            {{ dashboard.due }}
                        </p>
                    </div>

                </div>
            </div>


            <!-- Overdue -->

            <div class="p-4">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                        <ExclamationTriangleIcon class="h-4.5 w-4.5" />
                    </div>

                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            Overdue
                        </p>

                        <p class="mt-0.5 text-xl font-bold" :class="dashboard.overdue > 0
                            ? 'text-red-600 dark:text-red-400'
                            : 'text-slate-900 dark:text-white'
                            ">
                            {{ dashboard.overdue }}
                        </p>
                    </div>

                </div>
            </div>

        </div>


        <!-- Recent Notifications -->

        <div class="border-t border-slate-100 dark:border-slate-800">

            <div class="flex items-center justify-between px-5 py-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Recent Notifications
                </h4>
            </div>


            <!-- Loading -->

            <div v-if="loading" class="px-5 py-8 text-center text-xs text-slate-400">
                Loading...
            </div>


            <!-- Empty -->

            <div v-else-if="!notifications.length" class="px-5 py-8 text-center">
                <BellIcon class="mx-auto h-7 w-7 text-slate-300" />

                <p class="mt-2 text-xs text-slate-400">
                    No recent notifications
                </p>
            </div>


            <!-- List -->

            <div v-else>

                <div v-for="notification in notifications" :key="notification.id"
                    @click="openNotification(notification)"
                    class="group flex cursor-pointer gap-3 border-t border-slate-100 px-5 py-3.5 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
                    :class="!notification.read_at
                        ? 'bg-blue-50/30 dark:bg-blue-950/10'
                        : ''
                        ">

                    <!-- Icon -->

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full" :class="notificationIconClass(
                        notification.type
                    )
                        ">
                        <component :is="notificationIcon(
                            notification.type
                        )
                            " class="h-4.5 w-4.5" />
                    </div>


                    <!-- Content -->

                    <div class="min-w-0 flex-1">

                        <div class="flex items-center gap-2">

                            <p class="truncate text-sm" :class="notification.read_at
                                ? 'font-medium text-slate-700 dark:text-slate-300'
                                : 'font-bold text-slate-900 dark:text-white'
                                ">
                                {{ notification.title }}
                            </p>

                            <span v-if="!notification.read_at" class="h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600" />

                        </div>

                        <p class="mt-0.5 line-clamp-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ notification.message }}
                        </p>

                    </div>


                    <!-- Arrow -->

                    <ChevronRightIcon
                        class="mt-2 h-4 w-4 shrink-0 text-slate-300 opacity-0 transition group-hover:opacity-100" />

                </div>

            </div>

        </div>

    </div>
</template>