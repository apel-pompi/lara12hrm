<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Archive, Coffee, Flame, Mail, MessageCircleMore, Move3D, Rotate3D, Snowflake, SquarePen, TriangleAlert, Undo2, UserCheck } from 'lucide-vue-next';

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
</script>

<template>
    <aside class="flex w-full flex-col gap-6 bg-white p-4 shadow lg:w-1/5 dark:bg-gray-900">
        <!-- Profile -->
        <div class="flex flex-col items-center border-b pb-5 text-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 text-xl font-bold dark:bg-gray-700">
                {{ (props.student.fname?.charAt(0) ?? '').toUpperCase() }}{{ (props.student.lname?.charAt(0) ?? '').toUpperCase() }}
            </div>

            <div class="mt-2 flex items-center space-x-2 px-3 py-1">
                <Badge size="sm" :class="getStatusText(props.student.status).color">
                    {{ getStatusText(props.student.status).text }}
                </Badge>
            </div>

            <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.student.fname }} {{ props.student.lname }}</h2>

            <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                <button @click="updateRate(1)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><Snowflake />Lost</button>
                <button @click="updateRate(2)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><Coffee />Cold</button>
                <button @click="updateRate(3)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><TriangleAlert />Warm</button>
                <button @click="updateRate(4)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><Flame />Hot</button>
            </div>

            <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                <div class="group relative">
                    <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><MessageCircleMore /></button>
                    <span
                        class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                    >
                        Compose SMS
                    </span>
                </div>

                <div class="group relative">
                    <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><Mail /></button>
                    <span
                        class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                    >
                        Compose email
                    </span>
                </div>

                <div class="group relative">
                    <button @click="editStudent" class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><SquarePen /></button>
                    <span
                        class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                    >
                        Edit
                    </span>
                </div>

                <div class="group relative">
                    <div>
                        <div v-if="props.student.status !== 4">
                            <!-- Archive Button -->
                            <button @click="updateArchive(props.student.id, 4)" class="cursor-pointer text-[10px] uppercase hover:text-gray-700">
                                <Archive />
                            </button>
                            <span
                                class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                            >
                                Archive
                            </span>
                        </div>

                        <div v-else>
                            <!-- Restore Button -->
                            <button @click="updateArchive(props.student.id, 1)" class="cursor-pointer text-[10px] uppercase hover:text-gray-700">
                                <Undo2 />
                            </button>
                            <span
                                class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                            >
                                Restore
                            </span>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <div v-if="props.student.status <= 1">
                        <button @click="showDailogCreate" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                            <UserCheck />
                        </button>
                        <span
                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                        >
                            Assignee
                        </span>
                    </div>
                    <div v-else>
                        <button @click="updateTransfer(props.student.id)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                            <Move3D />
                        </button>
                        <span
                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                        >
                            Transfer
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <!-- Info Section -->
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 dark:text-gray-400">Added From:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-200">
                        {{ props.student.assainuser.name }}
                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-gray-600 dark:text-gray-400">Student ID:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-200">
                        {{ props.student.student_id }}
                    </p>
                </div>
            </div>

            <!-- Message -->
            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400" v-if="props.student.status==2">
                If there is no service charge or file opening charge, then you can transfer to onboard request.
            </div>

            <!-- Action Button -->
            <div class="mt-3 flex justify-end" v-if="props.student.student_id">
                <div class="group relative" v-if="props.student.status==2">
                    <button @click="updateonBoard(props.student.id)" class="rounded-full cursor-pointer p-2 transition hover:bg-green-100 dark:hover:bg-gray-800">
                        <Rotate3D class="h-6 w-6 text-green-700 dark:text-gray-300" />
                    </button>

                    <!-- Tooltip -->
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                    >
                        OnBoard
                    </span>
                </div>
            </div>
        </div>
    </aside>
</template>
