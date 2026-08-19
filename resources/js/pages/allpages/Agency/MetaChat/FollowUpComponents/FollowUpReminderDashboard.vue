<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

import {
    BellAlertIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    CheckCircleIcon,
    ChevronRightIcon,
    ArrowPathIcon,
    CheckIcon,
    CalendarDaysIcon,
} from '@heroicons/vue/24/outline';

interface Reminder {
    id: number;
    follow_up_activity_id: number;
    student_id: number;
    assigned_to: number;

    remind_at: string;

    channel: string;
    status: string;

    is_sent: boolean;
    is_read: boolean;

    sent_at: string | null;
    read_at: string | null;

    error_message: string | null;

    activity?: {
        id: number;
        follow_up_date: string | null;
        follow_up_time: string | null;
        description: string | null;
        priority?: string | null;
        status?: string | null;
    };

    student?: {
        id: number;
        fname?: string;
        lname?: string;
        student_id?: string;
    };

    assigned_user?: {
        id: number;
        name: string;
    };
}

interface Dashboard {
    pending: number;
    today: number;
    overdue: number;
    completed: number;
}

const dashboard = ref<Dashboard>({
    pending: 0,
    today: 0,
    overdue: 0,
    completed: 0,
});

const todayReminders = ref<Reminder[]>([]);
const upcomingReminders = ref<Reminder[]>([]);

const loading = ref(true);
const actionLoading = ref<number | null>(null);

const loadDashboard = async () => {
    loading.value = true;

    try {
        const [
            dashboardResponse,
            todayResponse,
            upcomingResponse,
        ] = await Promise.all([
            axios.get('/follow-up-reminders/dashboard'),

            axios.get('/follow-up-reminders/today'),

            axios.get('/follow-up-reminders/upcoming', {
                params: {
                    days: 7,
                },
            }),
        ]);

        dashboard.value =
            dashboardResponse.data?.data ??
            dashboard.value;

        todayReminders.value =
            todayResponse.data?.data ?? [];

        upcomingReminders.value =
            upcomingResponse.data?.data ?? [];

    } catch (error: any) {
        console.error(
            'Failed to load reminder dashboard',
            error
        );

        toast.error(
            error?.response?.data?.message ??
            'Failed to load reminders.'
        );
    } finally {
        loading.value = false;
    }
};

const studentName = (reminder: Reminder) => {
    const student = reminder.student;

    if (!student) {
        return 'Unknown Student';
    }

    return [
        student.fname,
        student.lname,
    ]
        .filter(Boolean)
        .join(' ') ||
        student.student_id ||
        'Unknown Student';
};

const reminderTime = (date: string) => {
    if (!date) {
        return '-';
    }

    return new Date(date).toLocaleTimeString(
        'en-US',
        {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        }
    );
};

const reminderDate = (date: string) => {
    if (!date) {
        return '-';
    }

    return new Date(date).toLocaleDateString(
        'en-US',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    );
};

const priorityClass = (priority?: string | null) => {
    switch (priority?.toLowerCase()) {
        case 'urgent':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

        case 'high':
            return 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400';

        case 'medium':
            return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
};

const openReminder = (reminder: Reminder) => {
    if (!reminder.follow_up_activity_id) {
        return;
    }

    router.visit(
        route(
            'follow-up-activities.show',
            reminder.follow_up_activity_id
        )
    );
};

const markAsRead = async (reminder: Reminder) => {
    if (reminder.is_read) {
        return;
    }

    actionLoading.value = reminder.id;

    try {
        await axios.post(
            `/follow-up-reminders/${reminder.id}/mark-as-read`
        );

        reminder.is_read = true;
        reminder.read_at = new Date().toISOString();

    } catch (error: any) {
        toast.error(
            error?.response?.data?.message ??
            'Failed to mark reminder as read.'
        );
    } finally {
        actionLoading.value = null;
    }
};

const snoozeReminder = async (
    reminder: Reminder,
    minutes: number
) => {
    actionLoading.value = reminder.id;

    try {
        const response = await axios.post(
            `/follow-up-reminders/${reminder.id}/snooze`,
            {
                minutes,
            }
        );

        const updatedReminder =
            response.data?.data;

        if (updatedReminder) {
            const index =
                todayReminders.value.findIndex(
                    item => item.id === reminder.id
                );

            if (index !== -1) {
                todayReminders.value[index] =
                    updatedReminder;
            }
        }

        toast.success(
            `Reminder snoozed for ${minutes} minutes.`
        );

        await loadDashboard();

    } catch (error: any) {
        toast.error(
            error?.response?.data?.message ??
            'Failed to snooze reminder.'
        );
    } finally {
        actionLoading.value = null;
    }
};

const goToAllReminders = () => {
    router.visit(
        route('follow-up-reminders.index')
    );
};

onMounted(loadDashboard);
</script>

<template>
    <div class="space-y-5">

        <!-- Header -->

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    Follow-up Reminders
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Manage your upcoming and pending reminders.
                </p>
            </div>

            <button type="button" @click="goToAllReminders"
                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                View All

                <ChevronRightIcon class="h-4 w-4" />
            </button>

        </div>


        <!-- Loading -->

        <div v-if="loading"
            class="rounded-2xl border border-slate-200 bg-white p-10 text-center dark:border-slate-800 dark:bg-slate-900">
            <ArrowPathIcon class="mx-auto h-6 w-6 animate-spin text-slate-400" />

            <p class="mt-2 text-xs text-slate-400">
                Loading reminders...
            </p>
        </div>


        <template v-else>

            <!-- Summary -->

            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">

                <!-- Pending -->

                <div
                    class="rounded-xl border border-blue-100 bg-blue-50/70 p-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                    <div class="flex items-center justify-between">

                        <div>
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                Pending
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.pending }}
                            </p>
                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">
                            <BellAlertIcon class="h-5 w-5" />
                        </div>

                    </div>
                </div>


                <!-- Today -->

                <div
                    class="rounded-xl border border-amber-100 bg-amber-50/70 p-4 dark:border-amber-900/40 dark:bg-amber-950/20">
                    <div class="flex items-center justify-between">

                        <div>
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                Today
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.today }}
                            </p>
                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">
                            <ClockIcon class="h-5 w-5" />
                        </div>

                    </div>
                </div>


                <!-- Overdue -->

                <div
                    class="rounded-xl border border-red-100 bg-red-50/70 p-4 dark:border-red-900/40 dark:bg-red-950/20">
                    <div class="flex items-center justify-between">

                        <div>
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wider text-red-600 dark:text-red-400">
                                Overdue
                            </p>

                            <p class="mt-1 text-2xl font-black" :class="dashboard.overdue > 0
                                    ? 'text-red-600 dark:text-red-400'
                                    : 'text-slate-900 dark:text-white'
                                ">
                                {{ dashboard.overdue }}
                            </p>
                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400">
                            <ExclamationTriangleIcon class="h-5 w-5" />
                        </div>

                    </div>
                </div>


                <!-- Completed -->

                <div
                    class="rounded-xl border border-emerald-100 bg-emerald-50/70 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                    <div class="flex items-center justify-between">

                        <div>
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                Completed
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                                {{ dashboard.completed }}
                            </p>
                        </div>

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">
                            <CheckCircleIcon class="h-5 w-5" />
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
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                            Today's Reminders
                        </h3>

                        <p class="mt-0.5 text-xs text-slate-400">
                            Follow-ups scheduled for today
                        </p>
                    </div>

                    <CalendarDaysIcon class="h-5 w-5 text-slate-400" />

                </div>


                <!-- Empty -->

                <div v-if="!todayReminders.length" class="px-5 py-10 text-center">
                    <BellAlertIcon class="mx-auto h-7 w-7 text-slate-300" />

                    <p class="mt-2 text-xs text-slate-400">
                        No reminders for today.
                    </p>
                </div>


                <!-- List -->

                <div v-else>

                    <div v-for="reminder in todayReminders" :key="reminder.id"
                        class="group border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/40">

                        <div class="flex gap-3">

                            <!-- Time -->

                            <div class="w-16 shrink-0 text-center">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ reminderTime(reminder.remind_at) }}
                                </p>

                                <p class="mt-0.5 text-[10px] text-slate-400">
                                    Reminder
                                </p>
                            </div>


                            <!-- Divider -->

                            <div class="w-px bg-slate-200 dark:bg-slate-700" />


                            <!-- Content -->

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <button type="button" @click="openReminder(reminder)"
                                        class="truncate text-sm font-semibold text-slate-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                        {{ studentName(reminder) }}
                                    </button>

                                    <span v-if="reminder.activity?.priority"
                                        class="rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="priorityClass(
                                            reminder.activity.priority
                                        )
                                            ">
                                        {{ reminder.activity.priority }}
                                    </span>

                                </div>


                                <p v-if="reminder.activity?.description"
                                    class="mt-1 line-clamp-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ reminder.activity.description }}
                                </p>


                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                    <span class="text-[10px] text-slate-400">
                                        {{ reminder.channel }}
                                    </span>

                                    <span class="text-slate-300">
                                        •
                                    </span>

                                    <span class="text-[10px] text-slate-400">
                                        {{ reminder.status }}
                                    </span>

                                </div>

                            </div>


                            <!-- Actions -->

                            <div class="flex shrink-0 items-center gap-1">

                                <!-- Read -->

                                <button type="button" :disabled="reminder.is_read ||
                                    actionLoading === reminder.id
                                    " @click.stop="markAsRead(reminder)"
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600 disabled:cursor-default disabled:opacity-40 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-400"
                                    title="Mark as read">
                                    <CheckIcon class="h-4 w-4" />
                                </button>


                                <!-- Snooze 15 -->

                                <button type="button" :disabled="actionLoading === reminder.id
                                    " @click.stop="
                                        snoozeReminder(
                                            reminder,
                                            15
                                        )
                                        "
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 disabled:opacity-40 dark:hover:bg-amber-950/30 dark:hover:text-amber-400"
                                    title="Snooze 15 minutes">
                                    <ClockIcon class="h-4 w-4" />
                                </button>


                                <!-- Open -->

                                <button type="button" @click.stop="
                                    openReminder(reminder)
                                    "
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-950/30 dark:hover:text-blue-400"
                                    title="Open follow-up">
                                    <ChevronRightIcon class="h-4 w-4" />
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Upcoming -->

            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <div
                    class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">

                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                            Upcoming Reminders
                        </h3>

                        <p class="mt-0.5 text-xs text-slate-400">
                            Next 7 days
                        </p>
                    </div>

                    <ArrowPathIcon class="h-5 w-5 text-slate-400" />

                </div>


                <div v-if="!upcomingReminders.length" class="px-5 py-10 text-center">
                    <p class="text-xs text-slate-400">
                        No upcoming reminders.
                    </p>
                </div>


                <div v-else class="divide-y divide-slate-100 dark:divide-slate-800">

                    <button v-for="reminder in upcomingReminders.slice(0, 7)" :key="reminder.id" type="button"
                        @click="openReminder(reminder)"
                        class="flex w-full items-center gap-4 px-5 py-3 text-left transition hover:bg-slate-50 dark:hover:bg-slate-800/40">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400">
                            <ClockIcon class="h-4 w-4" />
                        </div>


                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ studentName(reminder) }}
                            </p>

                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ reminderDate(reminder.remind_at) }}
                                ·
                                {{ reminderTime(reminder.remind_at) }}
                            </p>

                        </div>


                        <ChevronRightIcon class="h-4 w-4 shrink-0 text-slate-300" />

                    </button>

                </div>

            </div>

        </template>

    </div>
</template>