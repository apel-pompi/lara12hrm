<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import {
    Archive,
    Coffee,
    Flame,
    Mail,
    MessageCircleMore,
    Move3D,
    Rotate3D,
    Snowflake,
    SquarePen,
    TriangleAlert,
    Undo2,
    UserCheck,
    User,
    Phone,
    Hash,
} from 'lucide-vue-next';

const props = defineProps<{
    student: any;
    editStudent: () => void;
    showDailogCreate: () => void;
    updateTransfer: (studentId: number) => void;
    updateonBoard: (studentId: number) => void;
    updateRate: (value: number) => void;
    updateArchive: (studentId: number, status: number) => void;
    getStatusText: (status: number) => { text: string; color: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const ratingButtons = [
    { value: 1, label: 'Lost', icon: Snowflake, activeClass: 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' },
    { value: 2, label: 'Cold', icon: Coffee, activeClass: 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' },
    { value: 3, label: 'Warm', icon: TriangleAlert, activeClass: 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' },
    { value: 4, label: 'Hot', icon: Flame, activeClass: 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' },
];
</script>

<template>
    <aside class="w-full lg:w-72 xl:w-80 flex-shrink-0">
        <!-- Profile Card -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <!-- Avatar & Name Section -->
            <div class="flex flex-col items-center px-5 pt-6 pb-4 text-center">
                <!-- Avatar -->
                <div class="relative">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-2xl font-bold text-white shadow-lg ring-4 ring-indigo-100 dark:ring-indigo-900/50">
                        {{ (props.student.fname?.charAt(0) ?? '').toUpperCase() }}{{ (props.student.lname?.charAt(0) ?? '').toUpperCase() }}
                    </div>
                    <!-- Status Badge on avatar -->
                    <div class="absolute -bottom-1 -right-1">
                        <Badge size="sm" :class="[getStatusText(props.student.status).color, 'text-[10px] px-1.5 py-0.5 shadow-sm']">
                            {{ getStatusText(props.student.status).text }}
                        </Badge>
                    </div>
                </div>

                <!-- Name -->
                <h2 class="mt-4 text-lg font-bold text-gray-900 dark:text-gray-100">
                    {{ props.student.fname }} {{ props.student.lname }}
                </h2>

                <!-- Rating Buttons -->
                <div class="mt-3 flex items-center gap-1">
                    <button
                        v-for="btn in ratingButtons"
                        :key="btn.value"
                        @click="updateRate(btn.value)"
                        :title="btn.label"
                        class="group flex flex-col items-center gap-0.5 rounded-lg px-2.5 py-1.5 text-gray-400 transition-all hover:scale-105 dark:text-gray-500"
                        :class="btn.activeClass.replace('bg-', 'hover:bg-').replace('text-', 'hover:text-')"
                    >
                        <component :is="btn.icon" class="h-4 w-4" />
                        <span class="text-[9px] font-medium uppercase tracking-wide">{{ btn.label }}</span>
                    </button>
                </div>

                <!-- Action Icon Buttons -->
                <div class="mt-3 flex items-center justify-center gap-2 border-t border-gray-100 pt-3 dark:border-gray-700/50 w-full">
                    <!-- Compose SMS -->
                    <div class="group relative">
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                            title="Compose SMS"
                        >
                            <MessageCircleMore class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Compose Email -->
                    <div class="group relative">
                        <button
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                            title="Compose email"
                        >
                            <Mail class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Edit -->
                    <div class="group relative">
                        <button
                            @click="editStudent"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400"
                            title="Edit student"
                        >
                            <SquarePen class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Archive / Restore -->
                    <div class="group relative">
                        <button
                            v-if="props.student.status !== 4"
                            @click="updateArchive(props.student.id, 4)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-900/30 dark:hover:text-amber-400"
                            title="Archive student"
                        >
                            <Archive class="h-4 w-4" />
                        </button>
                        <button
                            v-else
                            @click="updateArchive(props.student.id, 1)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400"
                            title="Restore student"
                        >
                            <Undo2 class="h-4 w-4" />
                        </button>
                    </div>

                    <!-- Assignee / Transfer -->
                    <div class="group relative">
                        <button
                            v-if="props.student.status <= 1"
                            @click="showDailogCreate"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400"
                            title="Assign user"
                        >
                            <UserCheck class="h-4 w-4" />
                        </button>
                        <button
                            v-else
                            @click="updateTransfer(props.student.id)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-all hover:bg-violet-50 hover:text-violet-600 dark:hover:bg-violet-900/30 dark:hover:text-violet-400"
                            title="Transfer student"
                        >
                            <Move3D class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700/50">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <User class="h-3.5 w-3.5 flex-shrink-0" />
                            <span>Added From</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[140px]">
                            {{ props.student.assainuser?.name ?? '—' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <Hash class="h-3.5 w-3.5 flex-shrink-0" />
                            <span>Student ID</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 font-mono">
                            {{ props.student.student_id ?? '—' }}
                        </p>
                    </div>
                </div>

                <!-- OnBoard notice -->
                <div
                    v-if="props.student.status == 2"
                    class="mt-4 rounded-lg bg-amber-50 p-3 text-[11px] leading-relaxed text-amber-700 dark:bg-amber-900/20 dark:text-amber-400"
                >
                    💡 If there is no service charge or file opening charge, you can transfer to onboard request.
                </div>

                <!-- OnBoard Action Button -->
                <div v-if="props.student.student_id && props.student.status == 2" class="mt-3">
                    <button
                        @click="updateonBoard(props.student.id)"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 active:scale-95 dark:bg-emerald-700 dark:hover:bg-emerald-600"
                    >
                        <Rotate3D class="h-4 w-4" />
                        Request OnBoard
                    </button>
                </div>
            </div>
        </div>
    </aside>
</template>
