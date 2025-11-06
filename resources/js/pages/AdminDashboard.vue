<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
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
    countPending:number;
    countProspect: number;
    countonBoard: number;
    countArchive: number;
}>();

console.log(props.countAll)
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

                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <div
                    class="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border bg-white shadow-sm dark:bg-gray-900"
                >
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-5 dark:border-gray-800">
                        <h3 class="text-center text-lg font-semibold text-gray-800 dark:text-white">Request Overview</h3>
                    </div>

                    <!-- Lead Summary Grid -->

                    <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- All Lead -->
                        <Link :href="route('student.index')" method="get">
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-500 to-slate-800 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Archive</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countAll }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.lead')" method="get">
                            <!-- Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-300 to-slate-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Quotations</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countLead }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.pending')" method="get">
                            <!-- Pending Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-slate-800 to-slate-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Invoice</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countPending }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.prospect')" method="get">
                            <!-- Prospect Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-rose-400 to-red-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">M.R</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countProspect }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.onBoard')" method="get">
                            <!-- Onboard Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-amber-200 to-yellow-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Leave</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countonBoard }}</p>
                            </div>
                        </Link>
                        <Link :href="route('student.archive')" method="get">
                            <!-- Archive Lead -->
                            <div
                                class="rounded-lg bg-gradient-to-r from-lime-400 to-lime-500 p-3 text-center text-white shadow-sm transition hover:scale-[1.02] hover:shadow-md dark:border-gray-700"
                            >
                                <h4 class="text-sm font-medium opacity-90">Other's</h4>
                                <p class="mt-2 text-3xl font-bold">{{ props.countArchive }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
