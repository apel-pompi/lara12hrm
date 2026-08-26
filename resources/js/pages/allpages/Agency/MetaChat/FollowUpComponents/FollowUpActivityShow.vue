<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import FollowUpTimeline from '@/pages/allpages/Agency/MetaChat/FollowUpComponents/FollowUpTimeline.vue';
import { toast } from 'vue-sonner';
import {
    ArrowLeftIcon,
    CalendarDaysIcon,
    ClockIcon,
    UserIcon,
    UserCircleIcon,
    FlagIcon,
    CheckCircleIcon,
    InformationCircleIcon,
    BellIcon,
} from '@heroicons/vue/24/outline';
import Button from '@/components/ui/button/Button.vue';

interface Student {
    id: number;
    student_id?: string | null;
    fname?: string | null;
    lname?: string | null;
    email?: string | null;
    phone?: string | null;

}

interface User {
    id: number;
    name: string;
    username?: string | null;
}

interface Master {
    id: number;
    name?: string;
    title?: string;
}

interface Status {
    id: number;
    name?: string;
    title?: string;
}

interface Reminder {
    id: number;
    remind_at: string;
    channel: string;
    status: string;
    is_sent: boolean;
    is_read: boolean;
    sent_at?: string | null;
    read_at?: string | null;
}

interface Activity {
    id: number;
    student_id: number;
    conversation_id?: number | null;

    follow_up_master_id?: number | null;
    follow_up_status_id?: number | null;

    assigned_to?: number | null;
    created_by?: number | null;

    title: string;
    description?: string | null;

    follow_up_date?: string | null;
    follow_up_time?: string | null;

    priority?: string | null;
    status?: string | null;
    remarks?: string | null;

    completed_at?: string | null;
    created_at?: string | null;
    updated_at?: string | null;
    is_auto?: boolean;

    student?: Student | null;
    master?: Master | null;
    status_relation?: Status | null;
    creator?: User | null;
    assignedTo?: User | null;
    reminders?: Reminder[];

}

const props = defineProps<{
    activity: Activity;
}>();

const studentName = computed(() => {
    const student = props.activity.student;

    if (!student) {
        return '-';
    }

    return [student.fname, student.lname]
        .filter(Boolean)
        .join(' ') || '-';
});

const assignedUserName = computed(() => {
    return props.activity.assigned_to?.name ?? '-';
});

const creatorName = computed(() => {
    return props.activity.creator?.name ?? '-';
});

const priorityClass = computed(() => {
    switch (props.activity.priority) {
        case 'Urgent':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

        case 'High':
            return 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400';

        case 'Medium':
            return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';

        case 'Low':
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';

        default:
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
    }
});


const formatDate = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(date);
};

const formatDateTime = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const formatTime = (value?: string | null) => {
    if (!value) {
        return '-';
    }

    const parts = value.split(':');

    if (parts.length < 2) {
        return value;
    }

    const date = new Date();

    date.setHours(
        Number(parts[0]),
        Number(parts[1]),
        0,
        0
    );

    return new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    }).format(date);
};


const goBack = () => {
    const assignedUserId = props.activity.assigned_to?.id;
    if (assignedUserId) {
        router.visit(route('follow-up-notifications.all', assignedUserId));
    } else {
        router.visit(route('dashboard'));
    }
};

const showTimelineModal = ref(false);
const openTimeline = () => {
    const studentId = props.activity?.student?.id;

    if (!studentId) {
        toast.error('Student is Not Found');
        return;
    }

    showTimelineModal.value = true;
};

</script>

<template>
    <AppLayout>
        <div class="app-page">
            <!-- Header Bar -->
            <div
                class="mb-6 flex flex-col items-center justify-between gap-4 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center gap-3 sm:gap-4">
                    <Button @click="goBack"
                        class="group flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-blue-600 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-blue-50 hover:shadow hover:ring-blue-200 focus:outline-none dark:bg-slate-800 dark:text-blue-400 dark:ring-slate-700 dark:hover:bg-slate-700 dark:hover:ring-blue-500/50"
                        title="Go Back">
                        <ArrowLeftIcon class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                    </Button>
                    <div class="hidden h-6 w-px bg-gray-300 sm:block dark:bg-gray-700"></div>
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <BellIcon class="h-5 w-5" />
                    </div>

                    <div>
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">
                            Follow-up Activity
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ activity.title }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">

                    <div class="flex items-center gap-2">

                        <!-- Priority -->
                        <span v-if="activity.priority" class="rounded-full px-3 py-1 text-xs font-bold"
                            :class="priorityClass">
                            {{ activity.priority }}
                        </span>

                        <!-- Status -->
                        <span v-if="activity.status" class="rounded-full px-3 py-1 text-xs font-bold" :style="{
                            backgroundColor: `${activity.status.color}20`,
                            color: activity.status.color
                        }">
                            {{ activity.status.name }}
                        </span>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button v-if="activity?.student?.id" @click="openTimeline"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <CheckCircleIcon class="h-4 w-4" />
                        <span>View Timeline</span>
                    </button>
                </div>
            </div>
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-6 dark:border-slate-800">
                    <div class="grid gap-6 p-6 lg:grid-cols-3">
                        <!-- Activity -->
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <h2
                                    class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Activity Information
                                </h2>
                                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">

                                    <div class="grid gap-5 sm:grid-cols-2">

                                        <div>
                                            <p class="text-xs font-medium text-slate-400">
                                                Follow-up Date
                                            </p>

                                            <div class="mt-1 flex items-center gap-2">
                                                <CalendarDaysIcon class="h-4 w-4 text-blue-500" />

                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ formatDate(activity.follow_up_date) }}
                                                </p>
                                            </div>
                                        </div>


                                        <div>
                                            <p class="text-xs font-medium text-slate-400">
                                                Follow-up Time
                                            </p>

                                            <div class="mt-1 flex items-center gap-2">
                                                <ClockIcon class="h-4 w-4 text-amber-500" />

                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ formatTime(activity.follow_up_time) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-400">
                                                Assigned To
                                            </p>

                                            <div class="mt-1 flex items-center gap-2">
                                                <UserIcon class="h-4 w-4 text-indigo-500" />

                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ assignedUserName }}
                                                </p>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium text-slate-400">
                                                Created By
                                            </p>

                                            <div class="mt-1 flex items-center gap-2">
                                                <UserCircleIcon class="h-4 w-4 text-slate-500" />

                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ creatorName }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Description -->

                            <div class="mb-6">

                                <h2
                                    class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Description
                                </h2>

                                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">

                                    <p v-if="activity.description"
                                        class="whitespace-pre-line text-sm leading-6 text-slate-700 dark:text-slate-300">
                                        {{ activity.description }}
                                    </p>

                                    <p v-else class="text-sm text-slate-400">
                                        No description provided.
                                    </p>

                                </div>

                            </div>


                            <!-- Remarks -->

                            <div>

                                <h2
                                    class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Remarks
                                </h2>

                                <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">

                                    <p v-if="activity.remarks"
                                        class="whitespace-pre-line text-sm leading-6 text-slate-700 dark:text-slate-300">
                                        {{ activity.remarks }}
                                    </p>

                                    <p v-else class="text-sm text-slate-400">
                                        No remarks.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Student -->

                        <div>

                            <h2
                                class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Student
                            </h2>

                            <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-800">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                        <UserIcon class="h-5 w-5" />
                                    </div>

                                    <div class="min-w-0">

                                        <p class="truncate font-bold text-slate-900 dark:text-white">
                                            {{ studentName }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Student ID:
                                            {{ activity.student?.student_id ?? activity.student_id }}
                                        </p>

                                    </div>

                                </div>


                                <div class="mt-5 space-y-4">

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Email
                                        </p>

                                        <p
                                            class="mt-1 break-all text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ activity.student?.email ?? '-' }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Phone
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ activity.student?.phone ?? '-' }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- Reminders -->

                            <div class="mt-6">

                                <h2
                                    class="mb-3 flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">

                                    <BellIcon class="h-4 w-4" />

                                    Reminders

                                </h2>

                                <div v-if="activity.reminders?.length" class="space-y-3">

                                    <div v-for="reminder in activity.reminders" :key="reminder.id"
                                        class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">

                                        <div class="flex items-start justify-between gap-3">

                                            <div>

                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ formatDateTime(reminder.remind_at) }}
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ reminder.channel }}
                                                </p>

                                            </div>

                                            <span
                                                class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                {{ reminder.status }}
                                            </span>

                                        </div>

                                    </div>

                                </div>

                                <div v-else
                                    class="rounded-xl border border-dashed border-slate-300 p-5 text-center dark:border-slate-700">
                                    <BellIcon class="mx-auto h-6 w-6 text-slate-300" />

                                    <p class="mt-2 text-xs text-slate-400">
                                        No reminders
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400">

                            <span class="flex items-center gap-1">
                                <InformationCircleIcon class="h-4 w-4" />

                                Created:
                                {{ formatDateTime(activity.created_at) }}
                            </span>

                            <span v-if="activity.completed_at" class="flex items-center gap-1">
                                <CheckCircleIcon class="h-4 w-4 text-emerald-500" />

                                Completed:
                                {{ formatDateTime(activity.completed_at) }}
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <FollowUpTimeline v-if="props.activity?.student?.id" :show="showTimelineModal"
            :student-id="props.activity.student?.id" @close="showTimelineModal = false" />
    </AppLayout>
</template>