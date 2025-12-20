<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DualBarChart from '@/Components/ui/chart/DualBarChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, CheckCircle, Clock, Palmtree, TrendingUp, XCircle } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
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
    countQuotationApproval: number;
    sumQuoat: number;
    sumInvoice: number;
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
    intimes: string;
    outtimes: string;
    statuses: string;
    workhours: string;
    presentCount: number;
    lateCount: number;
    absentCount: number;
    leaveCount: number;
    holidayCount: number;
    totalWork: number;
}>();

const currentDate = ref('');
onMounted(() => {
    // Set current date
    const now = new Date();
    currentDate.value = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});

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
                <!-- Lead Overview -->
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Lead Overview</h3>
                    </div>

                    <!-- Lead Summary Grid -->

                    <div class="grid grid-cols-2 gap-5 p-4 sm:grid-cols-2 lg:grid-cols-3">
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
                <!-- Request Overview -->
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Request Overview</h3>
                    </div>

                    <!-- Lead Summary Grid -->

                    <div class="grid grid-cols-2 gap-5 p-4 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- All Lead -->
                        <Link :href="route('dashboard.ArchiveRequest')" method="get">
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-500 to-slate-800 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Archive</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countArchiveApproval }}</p>
                            </div>
                        </Link>
                        <Link :href="route('dashboard.QuotationRequest')" method="get">
                            <!-- Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-300 to-slate-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Quotations</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countQuotationApproval }}</p>
                            </div>
                        </Link>

                        <Link :href="route('leave.index')">
                            <!-- Onboard Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-amber-200 to-yellow-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Leave</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.leaveCount }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
                <!-- Attendance Overview -->
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Attendance Overview</h3>
                    </div>

                    <!-- Stats Cards -->
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                            <!-- Stat Item -->
                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <CheckCircle class="h-5 w-5 text-green-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.presentCount }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Present</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <Clock class="h-5 w-5 text-amber-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.lateCount }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Late</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <XCircle class="h-5 w-5 text-red-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.absentCount }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Absent</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <Calendar class="h-5 w-5 text-blue-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.leaveCount }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Leave</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <Palmtree class="h-5 w-5 text-purple-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.holidayCount }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Holiday</span>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <TrendingUp class="h-5 w-5 text-indigo-500" />
                                    <span class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ props.totalWork }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Work Hours</span>
                                </div>
                            </div>
                        </div>

                        <!-- Today Status -->
                        <div class="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <!-- In Time -->
                            <div class="rounded-xl bg-gradient-to-r from-emerald-400 to-green-600 p-2 text-center text-white shadow-md">
                                <span class="mt-1 block text-lg font-bold">{{ props.intimes }}</span>
                                <span class="text-xs opacity-90">In Time</span>
                            </div>

                            <!-- Out Time -->
                            <div class="rounded-xl bg-gradient-to-r from-blue-400 to-indigo-600 p-2 text-center text-white shadow-md">
                                <span class="mt-1 block text-lg font-bold">{{ props.outtimes }}</span>
                                <span class="text-xs opacity-90">Out Time</span>
                            </div>

                            <!-- Status -->
                            <div class="rounded-xl bg-gradient-to-r from-red-400 to-pink-600 p-2 text-center text-white shadow-md">
                                <span class="mt-1 block text-lg font-bold">{{ props.statuses }}</span>
                                <span class="text-xs opacity-90">Status</span>
                            </div>

                            <!-- Total Hours -->
                            <div class="rounded-xl bg-gradient-to-r from-purple-400 to-violet-600 p-2 text-center text-white shadow-md">
                                <span class="mt-1 block text-lg font-bold">{{ props.workhours }}</span>
                                <span class="text-xs opacity-90">Total Hours</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Lead Chart -->
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
                            <div class="group relative min-h-[80px] cursor-pointer rounded-lg border border-gray-200 p-2 dark:border-gray-800" :class="eventsForDate(day).length ? 'border-blue-500 bg-blue-300 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-800'">
                                <div v-if="day" class="font-medium text-gray-900 dark:text-gray-100">{{ day.getDate() }}</div>
                                <div v-for="event in eventsForDate(day)" :key="event.id" class="mt-1 rounded px-2 py-1 text-xs text-white">
                                    
                                    <div class="text-[10px] text-black">{{ event.datetime.split(' ')[1] }}</div>
                                   
                                </div>
                                <div
                                    v-if="eventsForDate(day).length"
                                    class="absolute top-full left-1/2 z-50 mt-2 w-max -translate-x-1/2 rounded-md bg-gray-900 px-3 py-2 text-xs text-white opacity-0 shadow-lg transition-opacity duration-200 group-hover:opacity-100"
                                >
                                    <!-- Tooltip Content -->
                                    <div v-for="event in eventsForDate(day)" :key="event.id" class="mb-1 last:mb-0">
                                        <div class="font-semibold">{{ event.name }}</div>
                                        <div class="text-gray-300">{{ event.datetime }}</div>
                                        <div class="text-gray-300">{{ event.discus }}</div>
                                        <div class="text-[10px] text-gray-400">{{ event.fname }} {{ event.lname }}</div>
                                        <div class="text-[10px] text-gray-400">{{ event.phone }}</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
