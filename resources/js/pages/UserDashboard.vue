<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DualBarChart from '@/Components/ui/chart/DualBarChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Archive,
    Calendar,
    CalendarDays,
    CheckCircle,
    ChevronLeft,
    ChevronRight,
    Clock,
    FileText,
    Palmtree,
    Plane,
    TrendingUp,
    UserCheck,
    UserPlus,
    Users,
    XCircle,
} from 'lucide-vue-next';
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
        const eventDate = e.datetime.split(' ')[0];
        return eventDate === d;
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 border border-white/60 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 shadow-[0_24px_60px_-24px_rgba(79,70,229,0.35)] dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                    <div
                        class="border-b border-indigo-100 bg-gradient-to-r from-indigo-500 to-blue-500 p-4 dark:border-indigo-900/40 dark:from-indigo-900 dark:to-blue-900"
                    >
                        <div class="flex items-center gap-2">
                            <Users class="h-5 w-5 text-white" />
                            <h3 class="text-base font-semibold text-white">Lead Overview</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3">
                        <Link
                            :href="route('student.index')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-indigo-950/40 dark:to-blue-900/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300"
                                >
                                    <Users class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">All Lead</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countAll }}</p>
                            </div>
                        </Link>

                        <Link
                            :href="route('student.lead')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-blue-950/40 dark:to-cyan-900/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300"
                                >
                                    <UserPlus class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Lead</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countLead }}</p>
                            </div>
                        </Link>

                        <Link
                            :href="route('student.pending')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-amber-950/40 dark:to-orange-900/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-300"
                                >
                                    <Clock class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countPending }}</p>
                            </div>
                        </Link>

                        <Link
                            :href="route('student.prospect')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-emerald-950/40 dark:to-teal-900/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-300"
                                >
                                    <CheckCircle class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Prospect</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countProspect }}</p>
                            </div>
                        </Link>

                        <Link
                            :href="route('student.onBoard')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-cyan-50 to-sky-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-cyan-950/40 dark:to-sky-900/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-cyan-100 text-cyan-600 dark:bg-cyan-900 dark:text-cyan-300"
                                >
                                    <UserCheck class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Onboard</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countonBoard }}</p>
                            </div>
                        </Link>

                        <Link
                            :href="route('student.archive')"
                            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-gray-50 p-4 transition-all hover:scale-[1.02] hover:shadow-md dark:from-slate-800/40 dark:to-gray-800/20"
                        >
                            <div class="relative z-10 flex flex-col">
                                <div
                                    class="mb-3 flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300"
                                >
                                    <Archive class="h-4 w-4" />
                                </div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Archive</h4>
                                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ props.countArchive }}</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                    <div
                        class="border-b border-fuchsia-100 bg-gradient-to-r from-fuchsia-500 to-rose-500 p-4 dark:border-fuchsia-900/40 dark:from-fuchsia-900 dark:to-rose-900"
                    >
                        <div class="flex items-center gap-2">
                            <FileText class="h-5 w-5 text-white" />
                            <h3 class="text-base font-semibold text-white">Request Overview</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3">
                        <Link
                            :href="route('dashboard.ArchiveRequest')"
                            class="group rounded-xl border border-gray-100 bg-white p-4 transition-all hover:border-purple-200 hover:bg-purple-50/30 hover:shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:hover:border-purple-900/50 dark:hover:bg-purple-900/10"
                        >
                            <div class="flex flex-col gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400"
                                >
                                    <Archive class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Archive</p>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ props.countArchiveApproval }}</h4>
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="route('dashboard.QuotationRequest')"
                            class="group rounded-xl border border-gray-100 bg-white p-4 transition-all hover:border-blue-200 hover:bg-blue-50/30 hover:shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:hover:border-blue-900/50 dark:hover:bg-blue-900/10"
                        >
                            <div class="flex flex-col gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400"
                                >
                                    <FileText class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Quotations</p>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ props.countQuotationApproval }}</h4>
                                </div>
                            </div>
                        </Link>

                        <Link
                            :href="route('leave.index')"
                            class="group rounded-xl border border-gray-100 bg-white p-4 transition-all hover:border-amber-200 hover:bg-amber-50/30 hover:shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:hover:border-amber-900/50 dark:hover:bg-amber-900/10"
                        >
                            <div class="flex flex-col gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400"
                                >
                                    <Plane class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Leave</p>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ props.leaveCount }}</h4>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                    <div
                        class="border-b border-emerald-100 bg-gradient-to-r from-emerald-500 to-teal-500 p-4 dark:border-emerald-900/40 dark:from-emerald-900 dark:to-teal-900"
                    >
                        <div class="flex items-center gap-2">
                            <Clock class="h-5 w-5 text-white" />
                            <h3 class="text-base font-semibold text-white">Attendance Overview</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3">
                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 p-4 transition hover:shadow-md dark:border-emerald-900/30 dark:bg-emerald-950/20"
                        >
                            <CheckCircle class="mb-2 h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-emerald-600 uppercase dark:text-emerald-400">Present</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.presentCount }}</p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-amber-100 bg-amber-50 p-4 transition hover:shadow-md dark:border-amber-900/30 dark:bg-amber-950/20"
                        >
                            <Clock class="mb-2 h-6 w-6 text-amber-600 dark:text-amber-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-amber-600 uppercase dark:text-amber-400">Late</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.lateCount }}</p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-rose-100 bg-rose-50 p-4 transition hover:shadow-md dark:border-rose-900/30 dark:bg-rose-950/20"
                        >
                            <XCircle class="mb-2 h-6 w-6 text-rose-600 dark:text-rose-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-rose-600 uppercase dark:text-rose-400">Absent</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.absentCount }}</p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-purple-100 bg-purple-50 p-4 transition hover:shadow-md dark:border-purple-900/30 dark:bg-purple-950/20"
                        >
                            <Calendar class="mb-2 h-6 w-6 text-purple-600 dark:text-purple-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-purple-600 uppercase dark:text-purple-400">Leave</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.leaveCount }}</p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-fuchsia-100 bg-fuchsia-50 p-4 transition hover:shadow-md dark:border-fuchsia-900/30 dark:bg-fuchsia-950/20"
                        >
                            <Palmtree class="mb-2 h-6 w-6 text-fuchsia-600 dark:text-fuchsia-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-fuchsia-600 uppercase dark:text-fuchsia-400">Holiday</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.holidayCount }}</p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-sky-100 bg-sky-50 p-4 transition hover:shadow-md dark:border-sky-900/30 dark:bg-sky-950/20"
                        >
                            <TrendingUp class="mb-2 h-6 w-6 text-sky-600 dark:text-sky-400" />
                            <h4 class="text-xs font-semibold tracking-wider text-sky-600 uppercase dark:text-sky-400">Work Hours</h4>
                            <p class="mt-1 text-3xl font-black text-gray-900 dark:text-gray-100">{{ props.totalWork }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 p-4 dark:from-emerald-950/40 dark:to-green-900/20">
                                <p class="text-xs font-semibold tracking-wider text-emerald-600 uppercase dark:text-emerald-400">In Time</p>
                                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ props.intimes }}</p>
                            </div>
                            <div class="rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 p-4 dark:from-blue-950/40 dark:to-indigo-900/20">
                                <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase dark:text-blue-400">Out Time</p>
                                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ props.outtimes }}</p>
                            </div>
                            <div class="rounded-xl bg-gradient-to-br from-rose-50 to-pink-50 p-4 dark:from-rose-950/40 dark:to-pink-900/20">
                                <p class="text-xs font-semibold tracking-wider text-rose-600 uppercase dark:text-rose-400">Status</p>
                                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ props.statuses }}</p>
                            </div>
                            <div class="rounded-xl bg-gradient-to-br from-violet-50 to-purple-50 p-4 dark:from-violet-950/40 dark:to-purple-900/20">
                                <p class="text-xs font-semibold tracking-wider text-violet-600 uppercase dark:text-violet-400">Total Hours</p>
                                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ props.workhours }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm md:col-span-2 lg:col-span-2 dark:border-gray-800 dark:bg-gray-900/50"
                >
                    <div
                        class="border-b border-violet-100 bg-gradient-to-r from-violet-500 to-indigo-500 p-4 dark:border-violet-900/40 dark:from-violet-900 dark:to-indigo-900"
                    >
                        <div class="flex items-center gap-2">
                            <TrendingUp class="h-5 w-5 text-white" />
                            <h3 class="text-base font-semibold text-white">Lead Analytics</h3>
                        </div>
                    </div>
                    <div class="p-6">
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

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                    <div
                        class="flex items-center justify-between border-b border-sky-100 bg-gradient-to-r from-sky-500 to-cyan-500 p-4 dark:border-sky-900/40 dark:from-sky-900 dark:to-cyan-900"
                    >
                        <Button @click="prevMonth" variant="ghost" size="icon" class="h-8 w-8 rounded-full">
                            <ChevronLeft class="h-4 w-4 text-white" />
                        </Button>
                        <div class="flex items-center gap-2">
                            <CalendarDays class="h-4 w-4 text-white" />
                            <h2 class="text-sm font-bold text-white">
                                {{ showDate.toLocaleString('default', { month: 'long', year: 'numeric' }) }}
                            </h2>
                        </div>
                        <Button @click="nextMonth" variant="ghost" size="icon" class="h-8 w-8 rounded-full">
                            <ChevronRight class="h-4 w-4 text-white" />
                        </Button>
                    </div>

                    <div
                        class="grid grid-cols-7 border-b border-gray-100 bg-gray-50/50 p-2 text-center text-xs font-semibold text-gray-500 dark:border-gray-800 dark:bg-gray-900/50 dark:text-gray-400"
                    >
                        <div>Su</div>
                        <div>Mo</div>
                        <div>Tu</div>
                        <div>We</div>
                        <div>Th</div>
                        <div>Fr</div>
                        <div>Sa</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1 p-3">
                        <template v-for="(day, idx) in daysInMonth" :key="idx">
                            <Link
                                v-if="day && eventsForDate(day).length"
                                :href="route('dashboard.Calender', { datetime: day.toISOString().split('T')[0] })"
                                class="group relative flex aspect-square cursor-pointer flex-col items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 transition-all hover:bg-indigo-600 hover:text-white hover:shadow-md dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-600 dark:hover:text-white"
                            >
                                <span class="text-sm font-semibold">{{ day.getDate() }}</span>
                                <span
                                    class="absolute bottom-1.5 h-1.5 w-1.5 rounded-full bg-indigo-500 group-hover:bg-white dark:bg-indigo-400"
                                ></span>
                            </Link>

                            <div
                                v-else-if="day"
                                class="flex aspect-square items-center justify-center rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ day.getDate() }}
                            </div>

                            <div v-else class="aspect-square"></div>
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
