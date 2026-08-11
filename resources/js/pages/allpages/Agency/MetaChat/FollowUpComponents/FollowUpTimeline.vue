<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import axios from 'axios';

import {
    XMarkIcon,
    ClockIcon,
    UserIcon,
    PhoneIcon,
    CheckCircleIcon,
    XCircleIcon,
    ArrowPathIcon,
    PencilSquareIcon,
    PlusCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
} from '@heroicons/vue/24/outline';

interface TimelineUser {
    id: number;
    name: string;
}

interface FollowUpMaster {
    id: number;
    code: string;
    name: string;
    description?: string;
    icon?: string;
    color?: string;
}

interface FollowUpStatus {
    id: number;
    code: string;
    name: string;
    description?: string;
    color?: string;
    icon?: string;
    is_completed?: boolean;
    is_cancelled?: boolean;
}

interface FollowUpActivity {
    id: number;
    student_id: number;
    follow_up_master_id: number;
    follow_up_status_id: number;
    assigned_to?: number;
    created_by?: number;
    title: string;
    description?: string;
    follow_up_date?: string;
    follow_up_time?: string;
    priority?: string;
    status?: string;
    remarks?: string;
    completed_at?: string | null;
    is_auto?: boolean;
    meta?: any;
    master?: FollowUpMaster;
    status?: FollowUpStatus;
}

interface TimelineItem {
    id: number;
    follow_up_activity_id: number;
    student_id: number;
    user_id: number | null;
    event_type: string;
    title: string;
    description: string | null;
    old_values: Record<string, any> | null;
    new_values: Record<string, any> | null;
    meta: Record<string, any> | null;
    is_system: boolean;
    created_at: string;
    updated_at: string;

    activity?: FollowUpActivity;
    user?: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    show: boolean;
    studentId?: number | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const timeline = ref<TimelineItem[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

const fetchTimeline = async () => {
    if (!props.studentId) {
        timeline.value = [];
        return;
    }

    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(
            `/api/students/${props.studentId}/follow-up-timeline`,
        );

        timeline.value = response.data?.data ?? [];
    } catch (err: any) {
        console.error('Failed to load follow-up timeline:', err);

        error.value =
            err?.response?.data?.message ??
            'Failed to load follow-up timeline.';

        timeline.value = [];
    } finally {
        loading.value = false;
    }
};

watch(
    () => props.show,
    (show) => {
        if (show) {
            fetchTimeline();
        }
    },
);

watch(
    () => props.studentId,
    () => {
        if (props.show) {
            fetchTimeline();
        }
    },
);

const eventConfig = (eventType: string) => {
    const type = eventType?.toLowerCase();

    switch (type) {
        case 'created':
            return {
                label: 'Created',
                icon: PlusCircleIcon,
                iconClass: 'text-blue-600',
                bgClass: 'bg-blue-100',
                dotClass: 'bg-blue-600',
            };

        case 'updated':
            return {
                label: 'Updated',
                icon: PencilSquareIcon,
                iconClass: 'text-indigo-600',
                bgClass: 'bg-indigo-100',
                dotClass: 'bg-indigo-600',
            };

        case 'rescheduled':
            return {
                label: 'Rescheduled',
                icon: ArrowPathIcon,
                iconClass: 'text-amber-600',
                bgClass: 'bg-amber-100',
                dotClass: 'bg-amber-500',
            };

        case 'completed':
            return {
                label: 'Completed',
                icon: CheckCircleIcon,
                iconClass: 'text-emerald-600',
                bgClass: 'bg-emerald-100',
                dotClass: 'bg-emerald-600',
            };

        case 'cancelled':
        case 'canceled':
            return {
                label: 'Cancelled',
                icon: XCircleIcon,
                iconClass: 'text-red-600',
                bgClass: 'bg-red-100',
                dotClass: 'bg-red-600',
            };

        case 'missed':
            return {
                label: 'Missed',
                icon: ExclamationTriangleIcon,
                iconClass: 'text-orange-600',
                bgClass: 'bg-orange-100',
                dotClass: 'bg-orange-500',
            };

        default:
            return {
                label: eventType || 'Activity',
                icon: InformationCircleIcon,
                iconClass: 'text-slate-600',
                bgClass: 'bg-slate-100',
                dotClass: 'bg-slate-500',
            };
    }
};

const formatDateTime = (date?: string) => {
    if (!date) {
        return '';
    }

    const value = new Date(date);

    if (Number.isNaN(value.getTime())) {
        return date;
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    }).format(value);
};

const formatFollowUpDate = (
    date?: string,
    time?: string,
) => {
    if (!date) {
        return '';
    }

    let value = date;

    /*
     * API sometimes returns:
     * 2026-08-06T18:00:00.000000Z
     *
     * But follow_up_time separately:
     * 08:55:00
     */

    if (time) {
        value = `${date.substring(0, 10)}T${time}`;
    }

    const parsed = new Date(value);

    if (Number.isNaN(parsed.getTime())) {
        return `${date} ${time ?? ''}`.trim();
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    }).format(parsed);
};

const priorityClass = (priority?: string) => {
    switch (priority) {
        case 'Urgent':
            return 'bg-red-100 text-red-700';

        case 'High':
            return 'bg-orange-100 text-orange-700';

        case 'Medium':
            return 'bg-yellow-100 text-yellow-700';

        case 'Low':
            return 'bg-slate-100 text-slate-600';

        default:
            return 'bg-slate-100 text-slate-600';
    }
};

const statusClass = (status?: FollowUpStatus) => {
    if (status?.is_completed) {
        return 'bg-emerald-100 text-emerald-700';
    }

    if (status?.is_cancelled) {
        return 'bg-red-100 text-red-700';
    }

    return 'bg-blue-100 text-blue-700';
};

const activityTitle = (item: TimelineItem) => {
    return (
        item.activity?.master?.name ??
        item.activity?.title ??
        'Follow-up'
    );
};

const assignedUserName = (item: TimelineItem) => {
    const assignedId = item.activity?.assigned_to;

    if (
        assignedId &&
        item.user?.id === assignedId
    ) {
        return item.user.name;
    }

    return item.user?.name ?? 'System';
};

const hasChanges = (item: TimelineItem): boolean => {
    return changedFields(item).length > 0;
};

const changedFields = (item: TimelineItem): string[] => {
    const oldValues = item.old_values ?? {};
    const newValues = item.new_values ?? {};

    const fields = new Set([
        ...Object.keys(oldValues),
        ...Object.keys(newValues),
    ]);

    return Array.from(fields).filter(
        (field) => oldValues[field] !== newValues[field],
    );
};

const close = () => {
    emit('close');
};

onMounted(() => {
    if (props.show) {
        fetchTimeline();
    }
});

const fieldLabel = (field: string): string => {
    const labels: Record<string, string> = {
        status: 'Status',
        priority: 'Priority',
        assigned_to: 'Assigned To',
        follow_up_date: 'Follow Up Date',
        follow_up_time: 'Follow Up Time',
        title: 'Title',
        description: 'Description',
        remarks: 'Remarks',
    };

    return (
        labels[field] ??
        field
            .replace(/_/g, ' ')
            .replace(/\b\w/g, (char) => char.toUpperCase())
    );
};

const formatChangeValue = (
    field: string,
    value: any,
): string => {
    if (
        value === null ||
        value === undefined ||
        value === ''
    ) {
        return '—';
    }

    if (field === 'follow_up_date') {
        const date = new Date(value);

        if (!Number.isNaN(date.getTime())) {
            return new Intl.DateTimeFormat('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            }).format(date);
        }
    }

    if (field === 'follow_up_time') {
        return String(value).substring(0, 5);
    }

    if (field === 'assigned_to') {
        return `User #${value}`;
    }

    if (typeof value === 'boolean') {
        return value ? 'Yes' : 'No';
    }

    if (typeof value === 'object') {
        return JSON.stringify(value);
    }

    return String(value);
};
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
                @click.self="close">
                <div
                    class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <!-- Header -->
                    <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100">
                                    <ClockIcon class="h-5 w-5 text-blue-600" />
                                </div>

                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">
                                        Follow-up Timeline
                                    </h2>

                                    <p class="text-xs text-slate-500">
                                        Student #{{ studentId }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="button" @click="close"
                            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="min-h-0 flex-1 overflow-y-auto">
                        <!-- Loading -->
                        <div v-if="loading" class="flex min-h-[300px] items-center justify-center">
                            <div class="flex items-center gap-3 text-sm text-slate-500">
                                <div
                                    class="h-5 w-5 animate-spin rounded-full border-2 border-slate-300 border-t-blue-600">
                                </div>

                                Loading timeline...
                            </div>
                        </div>

                        <!-- Error -->
                        <div v-else-if="error" class="m-5 rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex gap-3">
                                <ExclamationTriangleIcon class="h-5 w-5 shrink-0 text-red-600" />

                                <div>
                                    <p class="text-sm font-semibold text-red-700">
                                        Unable to load timeline
                                    </p>

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ error }}
                                    </p>

                                    <button type="button" @click="fetchTimeline"
                                        class="mt-3 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                                        Try Again
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Empty -->
                        <div v-else-if="timeline.length === 0"
                            class="flex min-h-[350px] flex-col items-center justify-center px-6 text-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                                <ClockIcon class="h-7 w-7 text-slate-400" />
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-slate-700">
                                No timeline yet
                            </h3>

                            <p class="mt-1 max-w-sm text-sm text-slate-500">
                                Follow-up activities and their history
                                will appear here.
                            </p>
                        </div>

                        <!-- Timeline -->
                        <div v-else class="px-5 py-6">
                            <div class="relative">
                                <!-- Vertical line -->
                                <div class="absolute left-[18px] top-3 bottom-3 w-px bg-slate-200"></div>

                                <div v-for="(item, index) in timeline" :key="item.id"
                                    class="relative flex gap-4 pb-7 last:pb-0">
                                    <!-- Icon -->
                                    <div class="relative z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                                        :class="eventConfig(item.event_type)
                                            .bgClass
                                            ">
                                        <component :is="eventConfig(
                                            item.event_type,
                                        ).icon
                                            " class="h-5 w-5" :class="eventConfig(
                                                item.event_type,
                                            ).iconClass
                                                " />
                                    </div>

                                    <!-- Content -->
                                    <div class="min-w-0 flex-1">
                                        <!-- Top row -->
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h3 class="text-sm font-semibold text-slate-900">
                                                        {{ item.title }}
                                                    </h3>

                                                    <span class="rounded-full px-2 py-0.5 text-[11px] font-medium"
                                                        :class="eventConfig(
                                                            item.event_type,
                                                        ).bgClass
                                                            ">
                                                        {{
                                                            eventConfig(
                                                                item.event_type,
                                                            ).label
                                                        }}
                                                    </span>
                                                </div>

                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    {{
                                                        formatDateTime(
                                                            item.created_at,
                                                        )
                                                    }}
                                                </p>
                                            </div>

                                            <div v-if="item.user"
                                                class="flex items-center gap-1.5 text-xs text-slate-500">
                                                <UserIcon class="h-3.5 w-3.5" />

                                                {{ item.user.name }}
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <p v-if="item.description" class="mt-3 text-sm leading-6 text-slate-600">
                                            {{ item.description }}
                                        </p>

                                        <!-- Activity info -->
                                        <div v-if="item.activity"
                                            class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <!-- Follow Up Type -->
                                                <span v-if="
                                                    item.activity.master
                                                "
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                                                    <PhoneIcon class="h-3.5 w-3.5" />

                                                    {{
                                                        item.activity
                                                            .master.name
                                                    }}
                                                </span>

                                                <!-- Status -->
                                                <span v-if="
                                                    item.activity.status
                                                " class="rounded-full px-2.5 py-1 text-xs font-medium" :class="statusClass(
                                                    item.activity
                                                        .status,
                                                )
                                                    ">
                                                    {{
                                                        item.activity.status
                                                            .name
                                                    }}
                                                </span>

                                                <!-- Priority -->
                                                <span v-if="
                                                    item.activity.priority
                                                " class="rounded-full px-2.5 py-1 text-xs font-medium" :class="priorityClass(
                                                    item.activity
                                                        .priority,
                                                )
                                                    ">
                                                    {{
                                                        item.activity
                                                            .priority
                                                    }}
                                                </span>
                                            </div>

                                            <!-- Follow-up date -->
                                            <div v-if="
                                                item.activity
                                                    .follow_up_date
                                            "
                                                class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-500">
                                                <div class="flex items-center gap-1.5">
                                                    <ClockIcon class="h-4 w-4" />

                                                    {{
                                                        formatFollowUpDate(
                                                            item.activity
                                                                .follow_up_date,
                                                            item.activity
                                                                .follow_up_time,
                                                        )
                                                    }}
                                                </div>

                                                <div v-if="
                                                    item.activity
                                                        .assigned_to
                                                " class="flex items-center gap-1.5">
                                                    <UserIcon class="h-4 w-4" />

                                                    Assigned
                                                </div>
                                            </div>

                                            <!-- Remarks -->
                                            <div v-if="
                                                item.activity.remarks
                                            " class="mt-3 border-t border-slate-200 pt-3 text-sm text-slate-600">
                                                <span class="font-medium text-slate-700">
                                                    Remarks:
                                                </span>

                                                {{ item.activity.remarks }}
                                            </div>
                                        </div>

                                        <!-- Changed values -->
                                        <div v-if="hasChanges(item)"
                                            class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                            <div class="mb-2 flex items-center gap-2">
                                                <ArrowPathIcon class="h-4 w-4 text-slate-500" />

                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                                                    Changes
                                                </span>
                                            </div>

                                            <div class="grid gap-2 sm:grid-cols-2">
                                                <div v-for="field in changedFields(item)" :key="field"
                                                    class="rounded-lg border border-slate-200 bg-white p-2.5">
                                                    <p
                                                        class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                                        {{ fieldLabel(field) }}
                                                    </p>

                                                    <div class="mt-1 flex flex-wrap items-center gap-1 text-xs">
                                                        <span class="rounded-md bg-red-50 px-2 py-1 text-red-600">
                                                            {{
                                                                formatChangeValue(
                                                                    field,
                                                            item.old_values?.[field],
                                                            )
                                                            }}
                                                        </span>

                                                        <span class="text-slate-400">
                                                            →
                                                        </span>

                                                        <span
                                                            class="rounded-md bg-emerald-50 px-2 py-1 font-medium text-emerald-700">
                                                            {{
                                                                formatChangeValue(
                                                                    field,
                                                            item.new_values?.[field],
                                                            )
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- System badge -->
                                        <div v-if="item.is_system" class="mt-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                                                System generated
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t border-slate-200 bg-slate-50 px-5 py-3">
                        <p class="text-xs text-slate-500">
                            {{ timeline.length }}
                            {{ timeline.length === 1 ? 'event' : 'events' }}
                        </p>

                        <button type="button" @click="close"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition:
        opacity 0.2s ease,
        transform 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from>div,
.modal-leave-to>div {
    transform: scale(0.97);
}
</style>