<script setup lang="ts">
import { computed, ref } from 'vue';
import axios from 'axios';
import { Link, router, usePage, Head } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

import {
    BellIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    UserPlusIcon,
    ArrowPathIcon,
    ChevronRightIcon,
    CheckIcon,
    CheckCircleIcon,
} from '@heroicons/vue/24/outline';


const breadcrumbs: BreadcrumbItem[] = [{ title: 'Notification Center', href: '/metachat' }];
const page = usePage();


const userId = computed<number | null>(
    () => (page.props.auth as any)?.user?.id ?? null
);

export interface Paginated<T> {
    data: T[];

    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;

    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}


export interface FollowUpNotification {
    id: number;
    user_id: number;

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

    follow_up_activity_id: number;
    follow_up_reminder_id: number | null;

    read_at: string | null;

    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    notifications: Paginated<FollowUpNotification>;
}>();

const filter = ref<'all' | 'unread'>('all');

const markingAll = ref(false);


const notificationList = ref<FollowUpNotification[]>([
    ...props.notifications.data,
]);

const filteredNotifications = computed(() => {
    if (filter.value === 'unread') {
        return notificationList.value.filter(
            notification => !notification.read_at
        );
    }

    return notificationList.value;
});


const unreadCount = computed(() => {
    return notificationList.value.filter(
        notification => !notification.read_at
    ).length;
});


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


const priorityClass = (priority?: string) => {
    switch (priority) {
        case 'Urgent':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

        case 'High':
            return 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400';

        case 'Medium':
            return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';

        case 'Low':
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
};


const notificationTime = (date?: string) => {
    if (!date) {
        return '-';
    }

    const parsed = new Date(date);

    if (Number.isNaN(parsed.getTime())) {
        return date;
    }

    return parsed.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
};


const openNotification = async (
    notification: FollowUpNotification
) => {
    try {
        if (
            !notification.read_at &&
            userId.value
        ) {
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

        console.error(
            'Failed to open notification',
            error
        );

        toast.error(
            error?.response?.data?.message ??
            'Failed to open notification'
        );
    }
};


const markAllAsRead = async () => {

    if (
        !userId.value ||
        markingAll.value ||
        unreadCount.value === 0
    ) {
        return;
    }

    markingAll.value = true;

    try {

        await axios.post(
            `/follow-up-notifications/user/${userId.value}/read-all`
        );

        notificationList.value.forEach(
            notification => {

                if (!notification.read_at) {
                    notification.read_at =
                        new Date().toISOString();
                }

            }
        );


        toast.success(
            'All notifications marked as read.'
        );

    } catch (error: any) {

        console.error(
            'Failed to mark all notifications as read',
            error
        );

        toast.error(
            error?.response?.data?.message ??
            'Failed to mark all notifications as read'
        );

    } finally {

        markingAll.value = false;
    }
};
</script>


<template>

    <Head title="Notification Center" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-slate-50 px-4 py-6 dark:bg-slate-950 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-5xl">


                <!-- ================================================= -->
                <!-- Header -->
                <!-- ================================================= -->

                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <BellIcon class="h-6 w-6" />
                            </div>


                            <div>

                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">
                                    Notification Center
                                </h1>

                                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                    Manage your follow-up notifications
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Mark All -->

                    <button v-if="unreadCount > 0" type="button" :disabled="markingAll" @click="markAllAsRead"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">

                        <CheckCircleIcon class="h-4 w-4" />

                        {{
                            markingAll
                                ? 'Marking...'
                                : 'Mark all as read'
                        }}

                    </button>

                </div>


                <!-- ================================================= -->
                <!-- Summary -->
                <!-- ================================================= -->

                <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-3">

                    <!-- Total -->

                    <div
                        class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            Total
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">
                            {{ props.notifications.total }}
                        </p>

                    </div>


                    <!-- Unread -->

                    <div
                        class="rounded-xl border border-blue-100 bg-blue-50/70 p-4 shadow-sm dark:border-blue-900/30 dark:bg-blue-950/20">

                        <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-500">
                            Unread
                        </p>

                        <p class="mt-1 text-2xl font-bold text-blue-700 dark:text-blue-400">
                            {{ unreadCount }}
                        </p>

                    </div>


                    <!-- Status -->

                    <div
                        class="col-span-2 rounded-xl border border-emerald-100 bg-emerald-50/70 p-4 shadow-sm dark:border-emerald-900/30 dark:bg-emerald-950/20 sm:col-span-1">

                        <p
                            class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                            Status
                        </p>

                        <p v-if="unreadCount === 0"
                            class="mt-1 text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                            You're all caught up
                        </p>

                        <p v-else class="mt-1 text-sm font-semibold text-blue-700 dark:text-blue-400">
                            {{ unreadCount }} unread notification{{
                                unreadCount > 1 ? 's' : ''
                            }}
                        </p>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- Filter -->
                <!-- ================================================= -->

                <div class="mb-4 flex items-center justify-between">

                    <div
                        class="inline-flex rounded-lg border border-slate-200 bg-white p-1 dark:border-slate-800 dark:bg-slate-900">

                        <button type="button" @click="filter = 'all'"
                            class="rounded-md px-3 py-1.5 text-xs font-semibold transition" :class="filter === 'all'
                                ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900'
                                : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'
                                ">
                            All
                        </button>


                        <button type="button" @click="filter = 'unread'"
                            class="rounded-md px-3 py-1.5 text-xs font-semibold transition" :class="filter === 'unread'
                                ? 'bg-blue-600 text-white'
                                : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'
                                ">
                            Unread
                        </button>

                    </div>


                    <span class="text-xs text-slate-400">
                        {{ props.notifications.total }} notifications
                    </span>

                </div>


                <!-- ================================================= -->
                <!-- Notification Card -->
                <!-- ================================================= -->

                <div
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">


                    <!-- ================================================= -->
                    <!-- Empty -->
                    <!-- ================================================= -->

                    <div v-if="!filteredNotifications.length" class="px-6 py-16 text-center">

                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <BellIcon class="h-7 w-7 text-slate-400" />
                        </div>


                        <h3 class="mt-4 text-sm font-semibold text-slate-700 dark:text-slate-300">
                            {{
                                filter === 'unread'
                                    ? 'No unread notifications'
                                    : 'No notifications'
                            }}
                        </h3>


                        <p class="mt-1 text-xs text-slate-400">
                            {{
                                filter === 'unread'
                                    ? "You're all caught up."
                                    : 'You do not have any notifications yet.'
                            }}
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- Notification List -->
                    <!-- ================================================= -->

                    <div v-else>

                        <div v-for="notification in filteredNotifications" :key="notification.id"
                            @click="openNotification(notification)"
                            class="group flex cursor-pointer gap-4 border-b border-slate-100 px-5 py-4 transition last:border-b-0 dark:border-slate-800"
                            :class="!notification.read_at
                                ? 'bg-blue-50/40 hover:bg-blue-50 dark:bg-blue-950/10 dark:hover:bg-blue-950/20'
                                : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'
                                ">


                            <!-- Icon -->

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full" :class="notificationIconClass(
                                notification.type
                            )
                                ">

                                <component :is="notificationIcon(
                                    notification.type
                                )
                                    " class="h-5 w-5" />

                            </div>


                            <!-- Content -->

                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-2">

                                            <h3 class="truncate text-sm" :class="!notification.read_at
                                                ? 'font-bold text-slate-900 dark:text-white'
                                                : 'font-semibold text-slate-700 dark:text-slate-300'
                                                ">
                                                {{ notification.title }}
                                            </h3>


                                            <span v-if="!notification.read_at"
                                                class="h-2 w-2 shrink-0 rounded-full bg-blue-600" />

                                        </div>


                                        <p class="mt-1 text-sm leading-5 text-slate-500 dark:text-slate-400">
                                            {{ notification.message }}
                                        </p>

                                    </div>


                                    <!-- Arrow -->

                                    <ChevronRightIcon
                                        class="mt-1 h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500" />

                                </div>


                                <!-- Meta -->

                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                    <span class="text-[11px] text-slate-400">
                                        {{
                                            notificationTime(
                                                notification.created_at
                                            )
                                        }}
                                    </span>


                                    <!-- Priority -->

                                    <span v-if="
                                        notification.data?.priority
                                    " class="rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="priorityClass(
                                        notification.data.priority
                                    )
                                        ">
                                        {{
                                            notification.data.priority
                                        }}
                                    </span>


                                    <!-- Read -->

                                    <span v-if="notification.read_at"
                                        class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-600 dark:text-emerald-400">
                                        <CheckIcon class="h-3 w-3" />
                                        Read
                                    </span>


                                    <!-- Unread -->

                                    <span v-else class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">
                                        Unread
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- Laravel / Inertia Pagination -->
                <!-- ================================================= -->

                <div v-if="props.notifications.last_page > 1"
                    class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <!-- Page Info -->

                    <p class="text-xs text-slate-400">
                        Showing
                        {{ props.notifications.from ?? 0 }}
                        to
                        {{ props.notifications.to ?? 0 }}
                        of
                        {{ props.notifications.total }}
                        notifications
                    </p>


                    <!-- Pagination -->

                    <div class="flex flex-wrap items-center gap-1">

                        <Link v-for="(link, index) in props.notifications.links" :key="`${index}-${link.label}`"
                            :href="link.url ?? '#'" preserve-scroll preserve-state
                            class="rounded-lg px-3 py-2 text-xs font-semibold transition" :class="{
                                'bg-blue-600 text-white':
                                    link.active,

                                'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800':
                                    !link.active && link.url,

                                'cursor-not-allowed opacity-40':
                                    !link.url,
                            }" v-html="link.label" />

                    </div>

                </div>


            </div>

        </div>
    </AppLayout>
</template>