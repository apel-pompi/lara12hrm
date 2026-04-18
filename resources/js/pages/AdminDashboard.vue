<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DualBarChart from '@/Components/ui/chart/DualBarChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    countAll: number;
    countLead: number;
    countPending: number;
    countProspect: number;
    countonBoard: number;
    countArchive: number;
    countArchiveApproval: number;
    countLeave: number;
    countQuotationApproval: number;
    countTransferApproval: number;
    countOnBoardApproval: number;
    countOnRefund: number;
    sumQuoat: number;
    sumInvoice: number;
    sumMR: Array;
    calander: {
        id: number;
        name: string;
        datetime: string;
        discus: string;
        student: {
            fname: string;
            lname: string;
            phone: string;
        };
    };
    presentCount: number;
    lateCount: number;
    absentCount: number;
    leaveCount: number;
}>();

const labels = [''];
const alllead = [props.countAll];
const lead = [props.countLead];
const pending = [props.countPending];
const prospect = [props.countProspect];
const onboard = [props.countonBoard];
const archive = [props.countArchive];

const showDate = ref(new Date());
const events = ref(
    props.calander.map((e) => ({
        id: e.id,
        name: e.name,
        datetime: e.datetime,
        discus: e.discus,
        fname: e.student.fname,
        lname: e.student.lname,
        phone: e.student.phone,
    })),
);
// Month grid calculation
function getDaysInMonth(date: Date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const days = [];
    for (let i = 0; i < firstDay.getDay(); i++) {
        days.push(null);
    }

    for (let d = 1; d <= lastDay.getDate(); d++) {
        const dt = new Date(year, month, d);
        dt.setHours(12, 0, 0, 0);
        days.push(dt);
    }

    return days;
}
const daysInMonth = computed(() => getDaysInMonth(showDate.value));

function nextMonth() {
    showDate.value = new Date(showDate.value.getFullYear(), showDate.value.getMonth() + 1, 1);
}
function prevMonth() {
    showDate.value = new Date(showDate.value.getFullYear(), showDate.value.getMonth() - 1, 1);
}

function eventsForDate(date: Date | null) {
    if (!date) return [];

    const d = date.toISOString().split('T')[0];

    return events.value.filter((e) => {
        const eventDate = e.datetime.split(' ')[0]; // extract date only
        return eventDate === d;
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Lead Overview</h3>
                    </div>

                    <!-- Lead Summary Grid -->

                    <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- All Lead -->
                        <Link :href="route('student.index')" method="get">
                            <div
                                class="rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">All Lead</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countAll }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.lead')" method="get">
                            <!-- Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-blue-800 to-indigo-900 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Lead</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countLead }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.pending')" method="get">
                            <!-- Pending Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-amber-500 to-pink-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Pending</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countPending }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.prospect')" method="get">
                            <!-- Prospect Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-900 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Prospect</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countProspect }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.onBoard')" method="get">
                            <!-- Onboard Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Onboard</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countonBoard }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.archive')" method="get">
                            <!-- Archive Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-900 to-slate-700 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Archive</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countArchive }}</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Request Overview</h3>
                    </div>

                    <!-- Lead Summary Grid -->

                    <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-3 lg:grid-cols-4">
                        <!-- Archive -->
                        <Link :href="route('dashboard.ArchiveRequest')" method="get">
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-500 to-slate-800 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Archive</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countArchiveApproval }}</p>
                            </div>
                        </Link>
                        <Link :href="route('dashboard.QuotationRequest')" method="get">
                            <!-- Quotation -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-300 to-slate-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Quotations</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countQuotationApproval }}</p>
                            </div>
                        </Link>

                        <Link :href="route('dashboard.LeaveRequest')" method="get">
                            <!-- Leave -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-amber-200 to-yellow-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Leave</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countLeave }}</p>
                            </div>
                        </Link>
                        <Link :href="route('dashboard.TransferRequest')" method="get">
                            <!-- Transfer -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-fuchsia-600 to-purple-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Transfer</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countTransferApproval }}</p>
                            </div>
                        </Link>
                        <Link :href="route('dashboard.onBoardRequest')" method="get">
                            <!-- Transfer -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-teal-200 to-teal-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">onBoard</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countOnBoardApproval }}</p>
                            </div>
                        </Link>
                        <Link :href="route('dashboard.ReturnRequest')" method="get">
                            <!-- Transfer -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-red-400 to-pink-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Refund</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countOnRefund }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Attendance Overview</h3>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4 p-4 sm:grid-cols-3 lg:grid-cols-4">
                        <!-- Stat Item -->
                        <div
                            class="cursor-pointer rounded-lg bg-gradient-to-r from-emerald-400 to-green-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                        >
                            <div class="flex flex-col items-center">
                                <h4 class="text-sm font-medium opacity-90">Present</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.presentCount }}</p>
                            </div>
                        </div>

                        <div
                            class="cursor-pointer rounded-lg bg-gradient-to-r from-blue-400 to-indigo-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                        >
                            <div class="flex flex-col items-center">
                                <h4 class="text-sm font-medium opacity-90">Late</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.lateCount }}</p>
                            </div>
                        </div>

                        <div
                            class="cursor-pointer rounded-xl bg-gradient-to-r from-red-400 to-pink-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                        >
                            <div class="flex flex-col items-center">
                                <h4 class="text-sm font-medium opacity-90">Absent</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.absentCount }}</p>
                            </div>
                        </div>

                        <div
                            class="cursor-pointer rounded-lg bg-gradient-to-r from-purple-400 to-violet-600 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                        >
                            <div class="flex flex-col items-center">
                                <h4 class="text-sm font-medium opacity-90">Leave</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.leaveCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Lead Chart</h3>
                    </div>
                    <!-- Lead Summary Grid -->
                    <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
                        <DualBarChart
                            :labels="labels"
                            :alllead="alllead"
                            :pending="pending"
                            :lead="lead"
                            :prospect="prospect"
                            :onboard="onboard"
                            :archive="archive"
                        />
                    </div>
                </div>
                <!-- Calander -->
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-200 p-5 dark:border-gray-800">
                        <!-- Prev Button -->
                        <Button @click="prevMonth" variant="outline" size="sm"> Prev </Button>

                        <!-- Month-Year Title -->
                        <h2 class="text-md text-center font-bold text-gray-900 dark:text-white">
                            {{ showDate.toLocaleString('default', { month: 'long', year: 'numeric' }) }}
                        </h2>

                        <!-- Next Button -->
                        <Button @click="nextMonth" variant="outline" size="sm"> Next </Button>
                    </div>

                    <!-- Weekday Header -->
                    <div
                        class="grid grid-cols-7 border-b border-gray-200 p-3 text-center font-semibold text-gray-700 dark:border-gray-800 dark:text-gray-300"
                    >
                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="mt-2 grid grid-cols-7 gap-2 p-4">
                        <template v-for="(day, idx) in daysInMonth" :key="idx">
                            <Link
                                v-if="day && eventsForDate(day).length"
                                :href="
                                    route('dashboard.Calender', {
                                        datetime: day.toISOString().split('T')[0],
                                    })
                                "
                                method="get"
                            >
                                <div
                                    class="group relative flex min-h-20 cursor-pointer items-center justify-center rounded-lg border border-gray-200 p-2 dark:border-gray-800"
                                    :class="
                                        eventsForDate(day).length
                                            ? 'border-blue-500 bg-blue-300 dark:bg-blue-900/20'
                                            : 'border-gray-200 dark:border-gray-800'
                                    "
                                >
                                    <div v-if="day" class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ day.getDate() }}
                                    </div>
                                </div>
                            </Link>

                            <div
                                v-else
                                class="group relative flex min-h-20 items-center justify-center rounded-lg border border-gray-200 p-2 dark:border-gray-800"
                            >
                                <div v-if="day" class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ day.getDate() }}
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
