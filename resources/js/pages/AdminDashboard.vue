<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Archive,
    ArrowRightLeft,
    Briefcase,
    CheckCircle,
    Clock,
    Coins,
    FileText,
    HandCoins,
    Landmark,
    PiggyBank,
    Plane,
    UserCheck,
    UserPlus,
    Wallet,
    Users,
    XCircle,
    PiggyBankIcon,
} from 'lucide-vue-next';

import {
    ClipboardDocumentListIcon,
    ClockIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    FireIcon,
    UsersIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/outline';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface FollowUpStats {
    total: number;
    pending: number;
    due_today: number;
    completed: number;
    overdue: number;
    urgent: number;
}
interface CounselorPerformance {
    user_id: number;
    user_name: string;
    total: number;
    pending: number;
    completed: number;
    overdue: number;
    urgent: number;
}

interface AccountItem {
    groupone_name: string;
    balance: number | string;
}


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
    countInactive1Month: number;
    countInactive3Month: number;
    countInactive6Month: number;
    followUpStats: FollowUpStats;
    counselorPerformance: CounselorPerformance[];
    accounts: AccountItem[];
    totalReceived: number;
    totalRefund: number;
}>();



const page = usePage();

const userId = computed(
    () => (page.props.auth as any)?.user?.id
);

const counselorPerformanceList = computed(() =>
    (props.counselorPerformance ?? []).slice(0, 10)
);

const formatBalance = (value: number | string) =>
    `${new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Math.abs(Number(value) || 0))}`;

const normalizeAccountGroup = (value?: string) =>
    (value ?? '').toLowerCase().replace(/[^a-z]/g, '');

const accountPrimaryCards = computed(() => {
    const groups = [
        {
            key: 'assets',
            label: 'Assets',
            icon: Wallet,
            barClass: 'bg-emerald-500',
            iconClass: 'text-emerald-500',
            iconBgClass: 'bg-emerald-50 dark:bg-emerald-950/40',
        },
        {
            key: 'liabilities',
            label: 'Liabilities',
            icon: HandCoins,
            barClass: 'bg-rose-500',
            iconClass: 'text-rose-500',
            iconBgClass: 'bg-rose-50 dark:bg-rose-950/40',
        },
        {
            key: 'revenue',
            label: 'Revenue',
            icon: Coins,
            barClass: 'bg-blue-500',
            iconClass: 'text-blue-500',
            iconBgClass: 'bg-blue-50 dark:bg-blue-950/40',
        },
        {
            key: 'expense',
            label: 'Expense',
            icon: PiggyBank,
            barClass: 'bg-amber-500',
            iconClass: 'text-amber-500',
            iconBgClass: 'bg-amber-50 dark:bg-amber-950/40',
        },
    ];

    return groups.map((group) => {
        const match = (props.accounts ?? []).find((account) =>
            normalizeAccountGroup(account.groupone_name).includes(group.key),
        );

        return {
            ...group,
            balance: match ? formatBalance(match.balance) : '৳0.00',
        };
    });
});

const followUpCards = computed(() => [
    {
        key: 'total',
        label: 'Total',
        value: props.followUpStats.total,
        icon: ClipboardDocumentListIcon,
        iconClass: 'text-blue-500',
        barClass: 'bg-blue-500',
        iconBgClass: 'bg-blue-50 dark:bg-blue-950/40',
    },
    {
        key: 'pending',
        label: 'Pending',
        value: props.followUpStats.pending,
        icon: ClockIcon,
        iconClass: 'text-amber-500',
        barClass: 'bg-amber-500',
        iconBgClass: 'bg-amber-50 dark:bg-amber-950/40',
    },
    {
        key: 'due_today',
        label: 'Due Today',
        value: props.followUpStats.due_today,
        icon: CheckCircleIcon,
        iconClass: 'text-emerald-500',
        barClass: 'bg-emerald-500',
        iconBgClass: 'bg-emerald-50 dark:bg-emerald-950/40',
    },
    {
        key: 'overdue',
        label: 'Overdue',
        value: props.followUpStats.overdue,
        icon: ExclamationTriangleIcon,
        iconClass: 'text-rose-500',
        barClass: 'bg-rose-500',
        iconBgClass: 'bg-rose-50 dark:bg-rose-950/40',
    },
    {
        key: 'urgent',
        label: 'Urgent',
        value: props.followUpStats.urgent,
        icon: FireIcon,
        iconClass: 'text-orange-500',
        barClass: 'bg-orange-500',
        iconBgClass: 'bg-orange-50 dark:bg-orange-950/40',
    },
    {
        key: 'completed',
        label: 'Completed',
        value: props.followUpStats.completed,
        icon: CheckCircleIcon,
        iconClass: 'text-teal-500',
        barClass: 'bg-teal-500',
        iconBgClass: 'bg-teal-50 dark:bg-teal-950/40',
    },
]);

const counselorCardThemes = [
    {
        barClass: 'bg-indigo-500',
        rankClass: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400',
    },
    {
        barClass: 'bg-blue-500',
        rankClass: 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
    },
    {
        barClass: 'bg-emerald-500',
        rankClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
    },
    {
        barClass: 'bg-amber-500',
        rankClass: 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400',
    },
    {
        barClass: 'bg-rose-500',
        rankClass: 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400',
    },
    {
        barClass: 'bg-purple-500',
        rankClass: 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
    },
    {
        barClass: 'bg-teal-500',
        rankClass: 'bg-teal-50 text-teal-600 dark:bg-teal-950/40 dark:text-teal-400',
    },
    {
        barClass: 'bg-orange-500',
        rankClass: 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400',
    },
];

const counselorCardTheme = (index: number) =>
    counselorCardThemes[index % counselorCardThemes.length];

const accountCardThemes = [
    {
        barClass: 'bg-emerald-500',
        iconClass: 'text-emerald-500',
        iconBgClass: 'bg-emerald-50 dark:bg-emerald-950/40',
        icon: Wallet,
    },
    {
        barClass: 'bg-blue-500',
        iconClass: 'text-blue-500',
        iconBgClass: 'bg-blue-50 dark:bg-blue-950/40',
        icon: Landmark,
    },
    {
        barClass: 'bg-amber-500',
        iconClass: 'text-amber-500',
        iconBgClass: 'bg-amber-50 dark:bg-amber-950/40',
        icon: Coins,
    },
    {
        barClass: 'bg-rose-500',
        iconClass: 'text-rose-500',
        iconBgClass: 'bg-rose-50 dark:bg-rose-950/40',
        icon: PiggyBank,
    },
];

const accountCardTheme = (index: number) =>
    accountCardThemes[index % accountCardThemes.length];
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="app-page">
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

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

                <!-- Dormant Leads Section -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <!-- Section Title -->
                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Dormant Leads
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Leads without any recent activity
                        </p>
                    </div>

                    <!-- Dormant Cards Grid -->
                    <div
                        class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-3 sm:gap-4 lg:grid-cols-1 xl:grid-cols-3 sm:p-5">

                        <!-- 1 Month+ -->
                        <Link :href="route('student.inactive1month')"
                            class="group relative flex min-h-[82px] items-center justify-between overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-orange-500"></span>

                            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                <div
                                    class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-lg bg-orange-50 dark:bg-orange-950/40">
                                    <Clock
                                        class="h-6 w-6 text-orange-500 transition-transform duration-200 group-hover:scale-110"
                                        stroke-width="1.5" />
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="truncate text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-100">
                                        1 Month+ Inactive
                                    </p>
                                    <p class="mt-0.5 truncate text-[11px] text-gray-400 dark:text-gray-500">
                                        No activity for > 1 month
                                    </p>
                                </div>
                            </div>

                            <span
                                class="ml-2 shrink-0 rounded-full bg-orange-500 px-2.5 py-1 text-center text-xs font-bold text-white">
                                {{ props.countInactive1Month }}
                            </span>
                        </Link>

                        <!-- 3 Month+ -->
                        <Link :href="route('student.inactive3month')"
                            class="group relative flex min-h-[82px] items-center justify-between overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-red-500"></span>

                            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                <div
                                    class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-lg bg-red-50 dark:bg-red-950/40">
                                    <Clock
                                        class="h-6 w-6 text-red-500 transition-transform duration-200 group-hover:scale-110"
                                        stroke-width="1.5" />
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="truncate text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-100">
                                        3 Months+ Inactive
                                    </p>
                                    <p class="mt-0.5 truncate text-[11px] text-gray-400 dark:text-gray-500">
                                        No activity for > 3 months
                                    </p>
                                </div>
                            </div>

                            <span
                                class="ml-2 shrink-0 rounded-full bg-red-500 px-2.5 py-1 text-center text-xs font-bold text-white">
                                {{ props.countInactive3Month }}
                            </span>
                        </Link>

                        <!-- 6 Month+ -->
                        <Link :href="route('student.inactive6month')"
                            class="group relative flex min-h-[82px] items-center justify-between overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-rose-600"></span>

                            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                <div
                                    class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/40">
                                    <XCircle
                                        class="h-6 w-6 text-rose-600 transition-transform duration-200 group-hover:scale-110"
                                        stroke-width="1.5" />
                                </div>
                                <div class="min-w-0">
                                    <p
                                        class="truncate text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-100">
                                        6 Months+ Inactive
                                    </p>
                                    <p class="mt-0.5 truncate text-[11px] text-gray-400 dark:text-gray-500">
                                        No activity for > 6 months
                                    </p>
                                </div>
                            </div>

                            <span
                                class="ml-2 shrink-0 rounded-full bg-rose-600 px-2.5 py-1 text-center text-xs font-bold text-white">
                                {{ props.countInactive6Month }}
                            </span>
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



                <!-- Follow-up Overview -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <!-- Header -->
                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Follow-up Overview
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Monitor follow-up activities
                        </p>
                    </div>
                    <!-- Follow-up Cards Grid -->
                    <div
                        class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 sm:gap-4 lg:grid-cols-2 xl:grid-cols-3 sm:p-5">
                        <div v-for="card in followUpCards" :key="card.key"
                            class="group relative flex min-h-[82px] items-center gap-3 sm:gap-4 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 sm:p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px]" :class="card.barClass"></span>

                            <div class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-lg"
                                :class="card.iconBgClass">
                                <component :is="card.icon"
                                    class="h-6 w-6 sm:h-7 sm:w-7 transition-transform duration-200 group-hover:scale-110"
                                    :class="card.iconClass" stroke-width="1.5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-lg sm:text-xl font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ card.value }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ card.label }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>




                <!-- Counselor Performance -->
                <div
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <!-- Header -->
                    <div
                        class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                                Counselor Performance
                            </h2>

                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                Follow-up performance by counselor
                            </p>
                        </div>


                        <Link :href="route('follow-up-notifications.all', {
                            userId: userId,
                        })"
                            class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/30">
                            View All

                            <ChevronRightIcon class="h-4 w-4" />
                        </Link>


                    </div>


                    <!-- Empty -->
                    <div v-if="!props.counselorPerformance?.length" class="px-5 py-10 text-center">

                        <UsersIcon class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" />

                        <p class="mt-2 text-sm text-gray-400">
                            No counselor performance data available
                        </p>
                    </div>


                    <!-- Performance List -->
                    <div v-else class="max-h-[65vh] overflow-y-auto p-4 sm:p-5">
                        <div class="grid grid-cols-1 gap-3">
                            <div v-for="(counselor, index) in counselorPerformanceList" :key="counselor.user_id"
                                class="group relative overflow-hidden rounded-lg border border-gray-100 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md sm:p-4 dark:border-gray-800 dark:bg-gray-900">
                                <span class="absolute inset-x-0 top-0 h-[3px]"
                                    :class="counselorCardTheme(index).barClass"></span>

                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-sm font-bold"
                                            :class="counselorCardTheme(index).rankClass">
                                            {{ index + 1 }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ counselor.user_name }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ counselor.total }} total follow-ups
                                            </p>
                                        </div>
                                    </div>

                                    <div v-if="counselor.urgent > 0"
                                        class="flex shrink-0 items-center gap-1 rounded-full bg-orange-50 px-2 py-1 text-[10px] font-bold text-orange-600 dark:bg-orange-950/30 dark:text-orange-400">
                                        <FireIcon class="h-3 w-3" />
                                        {{ counselor.urgent }} urgent
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                    <div class="rounded-lg bg-amber-50 px-3 py-2 dark:bg-amber-950/20">
                                        <p
                                            class="text-[10px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                            Pending
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                            {{ counselor.pending }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-emerald-50 px-3 py-2 dark:bg-emerald-950/20">
                                        <p
                                            class="text-[10px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                            Completed
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                            {{ counselor.completed }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-rose-50 px-3 py-2 dark:bg-rose-950/20">
                                        <p
                                            class="text-[10px] font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400">
                                            Overdue
                                        </p>
                                        <p class="mt-1 text-lg font-bold" :class="counselor.overdue > 0
                                            ? 'text-rose-600 dark:text-rose-400'
                                            : 'text-gray-900 dark:text-white'
                                            ">
                                            {{ counselor.overdue }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-blue-50 px-3 py-2 dark:bg-blue-950/20">
                                        <p
                                            class="text-[10px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                            Total
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                            {{ counselor.total }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="mb-1.5 flex items-center justify-between">
                                        <span class="text-[10px] font-medium text-gray-400">
                                            Completion Rate
                                        </span>

                                        <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                            {{
                                                counselor.total > 0
                                                    ? Math.round(
                                                        (counselor.completed /
                                                            counselor.total) * 100
                                                    )
                                                    : 0
                                            }}%
                                        </span>
                                    </div>

                                    <div class="h-1.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-500"
                                            :style="{
                                                width: `${counselor.total > 0
                                                    ? Math.round(
                                                        (counselor.completed /
                                                            counselor.total) * 100
                                                    )
                                                    : 0
                                                    }%`
                                            }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--Accounts Overview Section -->
                <div
                    class="mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <div
                        class="flex flex-col gap-1 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-slate-800">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Accounts Overview
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Today's accounts summary
                        </p>
                    </div>
                    <p class="px-5 py-4 text-xs text-slate-800 dark:text-slate-400">Accounts Summary Overview</p>
                    <div class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-4 sm:gap-4 sm:p-5">
                        <div v-for="(accountsitem, index) in accounts" :key="`${accountsitem.groupone_name}-${index}`"
                            class="group relative flex min-h-[82px] items-center gap-3 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md sm:gap-4 sm:p-4 dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px]"
                                :class="accountCardTheme(index).barClass"></span>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg sm:h-12 sm:w-12"
                                :class="accountCardTheme(index).iconBgClass">
                                <component :is="accountCardTheme(index).icon"
                                    class="h-6 w-6 transition-transform duration-200 group-hover:scale-110 sm:h-7 sm:w-7"
                                    :class="accountCardTheme(index).iconClass" stroke-width="1.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ formatBalance(accountsitem.balance) }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ accountsitem.groupone_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="px-5 py-4 text-xs text-slate-800 dark:text-slate-400">CRM Revenue Summary Overview</p>
                    <div class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-4 sm:gap-4 sm:p-5">
                        <div
                            class="group relative flex min-h-[82px] items-center gap-3 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md sm:gap-4 sm:p-4 dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-rose-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg sm:h-12 sm:w-12 text-rose-500 bg-rose-50 dark:bg-rose-950/40">
                                <PiggyBankIcon
                                    class="h-6 w-6 transition-transform duration-200 group-hover:scale-110 sm:h-7 sm:w-7"
                                    stroke-width="1.5"></PiggyBankIcon>

                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ formatBalance(totalReceived) }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    CRM REVENUE
                                </p>
                            </div>
                        </div>
                        <div
                            class="group relative flex min-h-[82px] items-center gap-3 overflow-hidden rounded-lg border border-gray-100 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md sm:gap-4 sm:p-4 dark:border-gray-800 dark:bg-gray-900">
                            <span class="absolute inset-x-0 top-0 h-[3px] bg-amber-500"></span>
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg sm:h-12 sm:w-12 text-amber-500 bg-amber-50 dark:bg-amber-950/40">
                                <Coins
                                    class="h-6 w-6 transition-transform duration-200 group-hover:scale-110 sm:h-7 sm:w-7"
                                    stroke-width="1.5"></Coins>

                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold leading-none text-gray-800 dark:text-gray-100">
                                    {{ formatBalance(totalRefund) }}
                                </p>
                                <p class="mt-1 truncate text-xs font-medium text-gray-500 dark:text-gray-400">
                                    CRM REFUND
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
