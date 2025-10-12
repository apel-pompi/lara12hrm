<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import { Archive, Coffee, Flame, Mail, MessageCircleMore, Snowflake, SquarePen, TriangleAlert, Undo2, UserCheck } from 'lucide-vue-next';

const props = defineProps<{
    student: any;
    showDailogCreate: () => void;
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
                    <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700"><SquarePen /></button>
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
                        <button
                            @click="showDailogCreate"
                            class="cursor-pointer text-[8px] uppercase hover:text-gray-700"
                        >
                            <UserCheck />
                        </button>
                        <span
                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                        >
                            Assignee
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Forecast -->
        <div class="border-b pb-5" v-for="(service, index) in props.studentService" :key="service.id">
            <h3 class="pb-5 text-[12px] font-semibold text-gray-700 dark:text-gray-300">APPLICATION SALES FORECAST</h3>
            <p class="mt-1 flex items-center pb-5 text-sm"><span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span> {{ service.productfees?.netamount ?? 0 }} CAD</p>
            <p class="text-gray-500">Product Fees</p>
                                
            <h3 class="mt-2 pb-5 text-[12px] font-semibold text-gray-700 dark:text-gray-300">INTERESTED SERVICES SALES FORECAST</h3>
            <p class="mt-1 flex items-center pb-5 text-sm"><span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span> {{ service.productfees?.netamount ?? 0 }} CAD</p>
        </div>

        <!-- Personal Details -->
        <div class="border-b pb-5 text-sm">
            <h4 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">PERSONAL DETAILS:</h4>
            <p>Tag(s): <span class="text-gray-500">-</span></p>

            <div class="mt-2 flex items-center gap-2">
                <Button class="rounded-lg bg-blue-500 px-3 py-1 text-xs text-white shadow hover:bg-blue-600">Activate</Button>
                <Button class="rounded-lg bg-gray-300 px-3 py-1 text-xs text-gray-700 shadow hover:bg-gray-400">De-Activate</Button>
            </div>

            <p class="mt-2 text-gray-600">
                Added From: <span class="font-medium">{{ props.student.assainuser.name }}</span>
            </p>
            <p class="text-gray-600">
                Student Id: <span class="font-medium">{{ props.student.student_id }}</span>
            </p>
        </div>
    </aside>
</template>
