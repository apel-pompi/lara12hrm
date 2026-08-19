<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FollowUpDashboard from '@/pages/allpages/Agency/MetaChat/FollowUpComponents/FollowUpDashboard.vue'

import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Archive,
    Calendar,
    CheckCircle,
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


</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="app-page">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Lead Overview Section -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <!-- Section Title -->
                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Lead Overview
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Track and monitor your leads performance
                        </p>
                    </div>

                    <!-- Lead Cards Grid -->
                    <div
                        class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 sm:gap-4 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 sm:p-5">

                        <!-- All Lead -->
                        <Link :href="route('student.index')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-cyan-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-cyan-50 dark:bg-cyan-950/40">
                                <Users
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-cyan-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countAll }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    All Leads
                                </p>
                            </div>
                        </Link>

                        <!-- New Lead -->
                        <Link :href="route('student.lead')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-red-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-red-50 dark:bg-red-950/40">
                                <UserPlus
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-red-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countLead }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    New Leads
                                </p>
                            </div>
                        </Link>

                        <!-- Pending -->
                        <Link :href="route('student.pending')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-amber-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/40">
                                <Clock
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-amber-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countPending }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Pending Leads
                                </p>
                            </div>
                        </Link>

                        <!-- Prospect -->
                        <Link :href="route('student.prospect')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-emerald-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/40">
                                <CheckCircle
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-emerald-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countProspect }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Prospect
                                </p>
                            </div>
                        </Link>

                        <!-- Onboard -->
                        <Link :href="route('student.onBoard')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-blue-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40">
                                <UserCheck
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-blue-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countonBoard }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Onboard
                                </p>
                            </div>
                        </Link>

                        <!-- Archive -->
                        <Link :href="route('student.archive')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-slate-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                <Archive
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-slate-500 transition-transform duration-200 group-hover:scale-110 dark:text-slate-400"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countArchive }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Archive
                                </p>
                            </div>
                        </Link>

                    </div>
                </div>
                <!-- Request Overview Section -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <!-- Section Title -->
                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Request Overview
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Track and manage pending requests
                        </p>
                    </div>
                    <!-- Request Cards Grid -->
                    <div
                        class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 sm:gap-4 lg:grid-cols-2 xl:grid-cols-3 sm:p-5">
                        <!-- Archive -->
                        <Link :href="route('dashboard.ArchiveRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-purple-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-950/40">
                                <Archive
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-purple-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countArchiveApproval }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Archive
                                </p>
                            </div>
                        </Link>
                        <!-- Quotation -->
                        <Link :href="route('dashboard.QuotationRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-blue-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40">
                                <FileText
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-blue-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countQuotationApproval }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Quotations
                                </p>
                            </div>
                        </Link>
                        <!-- Leave -->
                        <Link :href="route('dashboard.LeaveRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-amber-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/40">
                                <Plane
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-amber-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countLeave }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Leave
                                </p>
                            </div>
                        </Link>
                        <!-- Transfer -->
                        <Link :href="route('dashboard.TransferRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-pink-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-pink-50 dark:bg-pink-950/40">
                                <ArrowRightLeft
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-pink-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countTransferApproval }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Transfer
                                </p>
                            </div>
                        </Link>
                        <!-- OnBoard -->
                        <Link :href="route('dashboard.onBoardRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-teal-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-teal-50 dark:bg-teal-950/40">
                                <Briefcase
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-teal-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countOnBoardApproval }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    OnBoard
                                </p>
                            </div>
                        </Link>
                        <!-- Refund -->
                        <Link :href="route('dashboard.ReturnRequest')"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-rose-500"></span>
                            <div
                                class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/40">
                                <HandCoins
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-rose-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ props.countOnRefund }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Refund
                                </p>
                            </div>
                        </Link>

                    </div>
                </div>
                <!-- Attendance Overview Section -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <!-- Section Title -->
                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Attendance Overview
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Today's attendance summary
                        </p>
                    </div>
                    <!-- Attendance Cards Grid -->
                    <div
                        class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 sm:gap-4 lg:grid-cols-2 xl:grid-cols-3 sm:p-5">

                        <!-- Present -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-emerald-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10">
                                <CheckCircle
                                    class="h-5 w-5 text-emerald-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.presentCount }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Present</p>
                            </div>
                        </div>

                        <!-- Late -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-amber-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-500/10">
                                <Clock
                                    class="h-5 w-5 text-amber-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.lateCount }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Late</p>
                            </div>
                        </div>

                        <!-- Absent -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-rose-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-500/10">
                                <XCircle
                                    class="h-5 w-5 text-rose-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.absentCount }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Absent</p>
                            </div>
                        </div>

                        <!-- Leave -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-purple-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-500/10">
                                <Calendar
                                    class="h-5 w-5 text-purple-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.leaveCount }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Leave</p>
                            </div>
                        </div>

                        <!-- Holiday -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-fuchsia-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10">
                                <Palmtree
                                    class="h-5 w-5 text-fuchsia-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.holidayCount }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Holiday</p>
                            </div>
                        </div>

                        <!-- Work Hours -->
                        <div
                            class="group relative flex h-20 items-center gap-4 overflow-hidden rounded-xl border border-slate-200/80 bg-white px-4 shadow-sm transition-all duration-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/90">
                            <span class="absolute inset-x-0 top-0 h-1 bg-sky-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-50 dark:bg-sky-500/10">
                                <TrendingUp
                                    class="h-5 w-5 text-sky-500 transition-transform duration-200 group-hover:scale-110"
                                    stroke-width="2" />
                            </div>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">{{
                                    props.totalWork }}</p>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Work Hours</p>
                            </div>
                        </div>

                    </div>
                    <!-- Today's Details Grid -->
                    <div class="pt-2">
                        <p
                            class="mb-3 px-4 sm:px-5 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Today's Shift Details
                        </p>

                        <div
                            class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-4 sm:gap-4 lg:grid-cols-2 xl:grid-cols-3 sm:p-5">
                            <div
                                class="rounded-xl border border-emerald-500/20 bg-emerald-50/40 p-3.5 dark:bg-emerald-950/20">
                                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">In Time</p>
                                <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">{{
                                    props.intimes }}</p>
                            </div>

                            <div class="rounded-xl border border-blue-500/20 bg-blue-50/40 p-3.5 dark:bg-blue-950/20">
                                <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Out Time</p>
                                <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">{{
                                    props.outtimes }}</p>
                            </div>

                            <div
                                class="rounded-xl border border-purple-500/20 bg-purple-50/40 p-3.5 dark:bg-purple-950/20">
                                <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Status</p>
                                <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">{{
                                    props.statuses }}</p>
                            </div>

                            <div
                                class="rounded-xl border border-indigo-500/20 bg-indigo-50/40 p-3.5 dark:bg-indigo-950/20">
                                <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400">Total Hours</p>
                                <p class="mt-1 text-base font-semibold text-slate-800 dark:text-slate-100">{{
                                    props.workhours }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Follow Up Section -->
                <FollowUpDashboard />
            </div>
        </div>

    </AppLayout>
</template>
