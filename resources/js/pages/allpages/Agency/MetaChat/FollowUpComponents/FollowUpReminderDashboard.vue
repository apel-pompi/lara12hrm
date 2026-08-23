<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import {
    BellAlertIcon,
    CheckCircleIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    UserIcon,
    CalendarDaysIcon,
    CheckIcon,
    PaperAirplaneIcon,
    MoonIcon,
    TrashIcon,
    EllipsisVerticalIcon,
} from '@heroicons/vue/24/outline';

interface Reminder {
    id: number;
    follow_up_activity_id: number;
    student_id: number;
    assigned_to: number;
    remind_at: string;
    channel: string;
    status: string;
    is_sent: boolean | number;
    is_read: boolean | number;
    sent_at: string | null;
    read_at: string | null;
    error_message: string | null;
    payload: any;
    activity?: {
        id: number;
        title: string;
        description: string | null;
        follow_up_date: string;
        follow_up_time: string | null;
        priority: string;
        status: string;
    };
    student?: {
        id: number;
        fname: string;
        lname: string;
        phone: string | null;
        email: string | null;
    };
    assigned_user?: {
        id: number;
        name: string;
        username: string;
    };
}

interface ReminderDashboard {
    pending: number;
    today: number;
    overdue: number;
    completed: number;
}

const loading = ref(true);
const todayLoading = ref(false);
const upcomingLoading = ref(false);
const actionLoading = ref<number | null>(null);

const dashboard = ref<ReminderDashboard>({
    pending: 0,
    today: 0,
    overdue: 0,
    completed: 0,
});

const todayReminders = ref<Reminder[]>([]);
const upcomingReminders = ref<Reminder[]>([]);

/**
 * Format UTC datetime to Bangladesh time
 */
const formatDateTime = (date: string | null) => {
    if (!date) {
        return '-';
    }

    return new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Dhaka',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    }).format(new Date(date));
};

/**
 * Student name
 */
const studentName = (reminder: Reminder) => {
    if (!reminder.student) {
        return 'Unknown Student';
    }

    return `${reminder.student.fname ?? ''} ${reminder.student.lname ?? ''}`.trim();
};

/**
 * Priority class
 */
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

/**
 * Status class
 */
const statusClass = (status?: string) => {
    switch (status) {
        case 'Pending':
            return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';

        case 'Completed':
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';

        case 'Sent':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';

        case 'Failed':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

        case 'Cancelled':
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
};

/**
 * Load dashboard summary
 */
const loadDashboard = async () => {
    const response = await axios.get(
        '/follow-up-reminders/dashboard'
    );

    dashboard.value =
        response.data?.data ?? dashboard.value;
};

/**
 * Load today's reminders
 */
const loadToday = async () => {
    todayLoading.value = true;

    try {
        const response = await axios.get(
            '/follow-up-reminders/today'
        );

        todayReminders.value =
            response.data?.data ?? [];
    } finally {
        todayLoading.value = false;
    }
};

/**
 * Load upcoming reminders
 */
const loadUpcoming = async () => {
    upcomingLoading.value = true;

    try {
        const response = await axios.get(
            '/follow-up-reminders/upcoming',
            {
                params: {
                    days: 7,
                },
            }
        );

        upcomingReminders.value =
            response.data?.data ?? [];
    } finally {
        upcomingLoading.value = false;
    }
};

/**
 * Load everything
 */
const loadData = async () => {
    loading.value = true;

    try {
        await Promise.all([
            loadDashboard(),
            loadToday(),
            loadUpcoming(),
        ]);
    } catch (error) {
        console.error(
            'Failed to load reminder dashboard',
            error
        );
    } finally {
        loading.value = false;
    }
};

onMounted(loadData);

const markAsRead = async (reminder: Reminder) => {
    if (actionLoading.value === reminder.id) {
        return;
    }

    actionLoading.value = reminder.id;

    try {
        await axios.post(
            `/follow-up-reminders/${reminder.id}/mark-as-read`
        );

        reminder.is_read = true;
        reminder.read_at = new Date().toISOString();

    } catch (error) {
        console.error(
            'Failed to mark reminder as read',
            error
        );
    } finally {
        actionLoading.value = null;
    }
};

const markAsSent = async (reminder: Reminder) => {
    if (actionLoading.value === reminder.id) {
        return;
    }

    actionLoading.value = reminder.id;

    try {
        await axios.post(
            `/follow-up-reminders/${reminder.id}/mark-as-sent`
        );

        reminder.is_sent = true;
        reminder.sent_at = new Date().toISOString();
        reminder.status = 'Sent';

    } catch (error) {
        console.error(
            'Failed to mark reminder as sent',
            error
        );
    } finally {
        actionLoading.value = null;
    }
};

const snoozeReminder = async (
    reminder: Reminder,
    minutes: number
) => {
    if (actionLoading.value === reminder.id) {
        return;
    }

    actionLoading.value = reminder.id;

    try {
        const response = await axios.post(
            `/follow-up-reminders/${reminder.id}/snooze`,
            {
                minutes,
            }
        );

        const updatedReminder =
            response.data?.data ?? response.data;

        if (updatedReminder?.remind_at) {
            reminder.remind_at =
                updatedReminder.remind_at;
        } else {
            const date = new Date(reminder.remind_at);

            date.setMinutes(
                date.getMinutes() + minutes
            );

            reminder.remind_at =
                date.toISOString();
        }

    } catch (error) {
        console.error(
            'Failed to snooze reminder',
            error
        );
    } finally {
        actionLoading.value = null;
    }
};
const deleteReminder = async (
    reminder: Reminder
) => {
    if (actionLoading.value === reminder.id) {
        return;
    }

    const confirmed = window.confirm(
        'Are you sure you want to delete this reminder?'
    );

    if (!confirmed) {
        return;
    }

    actionLoading.value = reminder.id;

    try {
        await axios.delete(
            `/follow-up-reminders/${reminder.id}`
        );

        todayReminders.value =
            todayReminders.value.filter(
                item => item.id !== reminder.id
            );

        upcomingReminders.value =
            upcomingReminders.value.filter(
                item => item.id !== reminder.id
            );

        dashboard.value.pending =
            Math.max(0, dashboard.value.pending - 1);

    } catch (error) {
        console.error(
            'Failed to delete reminder',
            error
        );
    } finally {
        actionLoading.value = null;
    }
};

const openFollowUpActivity = (
    reminder: Reminder
) => {
    const activityId =
        reminder.follow_up_activity_id ??
        reminder.activity?.id;

    if (!activityId) {
        console.warn(
            'Follow-up activity ID not found',
            reminder
        );

        return;
    }

    router.visit(
        route('follow-up-activities.show', {
            activity: activityId,
        })
    );
};
</script>

<template>
    <div class="space-y-5">

        <!-- Header -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                Follow-up Reminders
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Manage and monitor your follow-up reminders.
            </p>
        </div>

        <!-- Loading -->
        <div v-if="loading"
            class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="mx-auto h-7 w-7 animate-spin rounded-full border-2 border-slate-200 border-t-blue-600" />

            <p class="mt-3 text-sm text-slate-500">
                Loading reminders...
            </p>
        </div>

        <template v-else>

            <!-- Summary -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                <!-- Pending -->
                <div
                    class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm dark:border-blue-900/40 dark:bg-slate-900">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <BellAlertIcon class="h-5 w-5" />
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Pending
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.pending }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Today -->
                <div
                    class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-amber-900/40 dark:bg-slate-900">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                            <ClockIcon class="h-5 w-5" />
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Today
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.today }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Overdue -->
                <div
                    class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm dark:border-red-900/40 dark:bg-slate-900">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                            <ExclamationTriangleIcon class="h-5 w-5" />
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Overdue
                            </p>

                            <p class="mt-1 text-2xl font-black" :class="dashboard.overdue > 0
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-slate-900 dark:text-white'
                                ">
                                {{ dashboard.overdue }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Completed -->
                <div
                    class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900/40 dark:bg-slate-900">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                            <CheckCircleIcon class="h-5 w-5" />
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Completed
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.completed }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Today's Reminders -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div
                    class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            Today's Reminders
                        </h3>

                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            Follow-ups scheduled for today
                        </p>
                    </div>

                    <span
                        class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                        {{ todayReminders.length }}
                    </span>
                </div>

                <!-- Loading -->
                <div v-if="todayLoading" class="px-5 py-10 text-center text-sm text-slate-400">
                    Loading today's reminders...
                </div>

                <!-- Empty -->
                <div v-else-if="!todayReminders.length" class="px-5 py-12 text-center">
                    <CalendarDaysIcon class="mx-auto h-9 w-9 text-slate-300 dark:text-slate-700" />

                    <p class="mt-3 text-sm font-medium text-slate-500">
                        No reminders for today
                    </p>
                </div>

                <!-- List -->
                <div v-else>

                    <div v-for="reminder in todayReminders" :key="reminder.id" @click="openFollowUpActivity(reminder)"
                        class="border-b border-slate-100 px-5 py-4 last:border-b-0 dark:border-slate-800">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                            <!-- Reminder information -->
                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h4 class="font-semibold text-slate-900 dark:text-white">
                                        {{ reminder.activity?.title ?? 'Follow-up Reminder' }}
                                    </h4>

                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold" :class="priorityClass(
                                        reminder.activity?.priority
                                    )
                                        ">
                                        {{ reminder.activity?.priority ?? 'Normal' }}
                                    </span>

                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold"
                                        :class="statusClass(reminder.status)">
                                        {{ reminder.status }}
                                    </span>

                                </div>

                                <div
                                    class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-500 dark:text-slate-400">

                                    <span class="flex items-center gap-1">
                                        <UserIcon class="h-3.5 w-3.5" />

                                        {{ studentName(reminder) }}
                                    </span>

                                    <span>
                                        Assigned to:
                                        <strong class="font-semibold text-slate-700 dark:text-slate-300">
                                            {{ reminder.assigned_user?.name ?? '-' }}
                                        </strong>
                                    </span>

                                </div>

                                <p v-if="reminder.activity?.description"
                                    class="mt-2 line-clamp-1 text-xs text-slate-400">
                                    {{ reminder.activity.description }}
                                </p>

                            </div>


                            <!-- Reminder time -->
                            <div class="shrink-0 xl:text-right">

                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                    Reminder
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">
                                    {{ formatDateTime(reminder.remind_at) }}
                                </p>

                                <span
                                    class="mt-1 inline-block rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                    {{ reminder.channel }}
                                </span>

                            </div>


                            <!-- Actions -->
                            <div class="flex flex-wrap items-center gap-2 xl:w-[330px] xl:justify-end">

                                <!-- Mark as Read -->
                                <button v-if="!reminder.is_read" type="button" :disabled="actionLoading === reminder.id"
                                    @click.stop="markAsRead(reminder)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-400 dark:hover:bg-emerald-950/50">
                                    <CheckIcon class="h-4 w-4" />

                                    Mark Read
                                </button>

                                <span v-else
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400">
                                    <CheckCircleIcon class="h-4 w-4" />

                                    Read
                                </span>


                                <!-- Mark as Sent -->
                                <button v-if="!reminder.is_sent" type="button" :disabled="actionLoading === reminder.id"
                                    @click.stop="markAsSent(reminder)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-400 dark:hover:bg-blue-950/50">
                                    <PaperAirplaneIcon class="h-4 w-4" />

                                    Mark Sent
                                </button>

                                <span v-else
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 dark:bg-blue-950/30 dark:text-blue-400">
                                    <PaperAirplaneIcon class="h-4 w-4" />

                                    Sent
                                </span>


                                <!-- Snooze -->
                                <div class="relative">

                                    <details class="group">

                                        <summary
                                            class="flex cursor-pointer list-none items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-400 dark:hover:bg-amber-950/50">
                                            <MoonIcon class="h-4 w-4" />

                                            Snooze
                                        </summary>

                                        <div
                                            class="absolute right-0 z-20 mt-2 w-40 overflow-hidden rounded-xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-900">

                                            <button type="button" @click.stop="snoozeReminder(reminder, 15)"
                                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                                15 minutes
                                            </button>

                                            <button type="button" @click.stop="snoozeReminder(reminder, 30)"
                                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                                30 minutes
                                            </button>

                                            <button type="button" @click.stop="snoozeReminder(reminder, 60)"
                                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                                1 hour
                                            </button>

                                            <button type="button" @click.stop="snoozeReminder(reminder, 180)"
                                                class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                                3 hours
                                            </button>

                                        </div>

                                    </details>

                                </div>


                                <!-- Delete -->
                                <button type="button" :disabled="actionLoading === reminder.id"
                                    @click.stop="deleteReminder(reminder)"
                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400 dark:hover:bg-red-950/50"
                                    title="Delete reminder">
                                    <TrashIcon class="h-4 w-4" />
                                </button>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- Upcoming -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">
                        Upcoming Reminders
                    </h3>

                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                        Reminders scheduled within the next 7 days
                    </p>
                </div>

                <div v-if="upcomingLoading" class="px-5 py-10 text-center text-sm text-slate-400">
                    Loading upcoming reminders...
                </div>

                <div v-else-if="!upcomingReminders.length" class="px-5 py-12 text-center">
                    <CalendarDaysIcon class="mx-auto h-9 w-9 text-slate-300 dark:text-slate-700" />

                    <p class="mt-3 text-sm font-medium text-slate-500">
                        No upcoming reminders
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Nothing scheduled for the next 7 days.
                    </p>
                </div>

                <div v-else>

                    <div v-for="reminder in upcomingReminders" :key="reminder.id"
                        class="border-b border-slate-100 px-5 py-4 last:border-b-0 dark:border-slate-800">
                        <div class="flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <h4 class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                                    {{
                                        reminder.activity?.title ??
                                        'Follow-up Reminder'
                                    }}
                                </h4>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ studentName(reminder) }}
                                    ·
                                    {{
                                        reminder.assigned_user?.name ??
                                        '-'
                                    }}
                                </p>

                            </div>

                            <p class="shrink-0 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                {{
                                    formatDateTime(
                                        reminder.remind_at
                                    )
                                }}
                            </p>

                        </div>
                    </div>

                </div>

            </div>

        </template>

    </div>
</template>