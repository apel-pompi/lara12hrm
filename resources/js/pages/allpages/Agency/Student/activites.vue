<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Activity, ChevronLeft, ChevronRight } from 'lucide-vue-next';

export interface Paginated<T> {
    data: T[];
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

export interface Activity {
    id: number;
    student_id: string;
    title: string;
    fristactivity: string;
    lastactivity: string;
    user_id: number;
    created_at: number;
}

const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
    activity: Paginated<Activity>;
    filters: { name?: string };
}>();
const data = props.activity;

const avatarColors = [
    { bg: 'bg-violet-500', ring: 'ring-violet-200' },
    { bg: 'bg-blue-500', ring: 'ring-blue-200' },
    { bg: 'bg-emerald-500', ring: 'ring-emerald-200' },
    { bg: 'bg-rose-500', ring: 'ring-rose-200' },
    { bg: 'bg-amber-500', ring: 'ring-amber-200' },
    { bg: 'bg-cyan-500', ring: 'ring-cyan-200' },
    { bg: 'bg-fuchsia-500', ring: 'ring-fuchsia-200' },
    { bg: 'bg-indigo-500', ring: 'ring-indigo-200' },
    { bg: 'bg-teal-500', ring: 'ring-teal-200' },
];

function getAvatarColor(name: string) {
    if (!name) return avatarColors[0];
    const index = name.charCodeAt(0) % avatarColors.length;
    return avatarColors[index];
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
};

const formatTimeAgo = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now.getTime() - date.getTime();
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    if (days < 7) return `${days}d ago`;
    return formatDate(dateString);
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

// Filter out prev/next links to show only numbered pages
const paginationLinks = data.links;
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-1">
            <!-- Header Row -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/30">
                        <Activity class="h-4 w-4 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Activity Log</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ data.total }} total events</p>
                    </div>
                </div>
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                    Page {{ data.current_page }} / {{ data.last_page }}
                </span>
            </div>

            <!-- Empty State -->
            <div v-if="data.data.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <Activity class="h-8 w-8 text-gray-400" />
                </div>
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300">No activities yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Activity will appear here as events occur.</p>
            </div>

            <!-- Timeline -->
            <div v-else class="relative">
                <!-- Vertical line -->
                <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-200 via-indigo-100 to-transparent dark:from-indigo-800 dark:via-indigo-900/50 dark:to-transparent"></div>

                <div class="space-y-1">
                    <div
                        v-for="(active, idx) in data.data"
                        :key="active.id"
                        class="group relative flex items-start gap-4 rounded-xl p-3 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800/50"
                    >
                        <!-- Avatar with connector dot -->
                        <div class="relative z-10 flex-shrink-0">
                            <div
                                :class="[
                                    'flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm ring-2 ring-white transition-transform duration-200 group-hover:scale-105 dark:ring-gray-900',
                                    getAvatarColor(active.user?.name).bg,
                                ]"
                            >
                                {{ (active.user?.name?.charAt(0) ?? '?').toUpperCase() }}
                            </div>
                        </div>

                        <!-- Content Card -->
                        <div class="min-w-0 flex-1 rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm transition-shadow duration-200 group-hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm leading-relaxed text-gray-800 dark:text-gray-200">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ active.user?.name ?? 'Unknown' }}</span>
                                        <span class="ml-1 text-gray-600 dark:text-gray-400">{{ active.title }}</span>
                                    </p>
                                </div>
                                <div class="flex flex-shrink-0 flex-col items-end gap-1">
                                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">
                                        {{ formatTimeAgo(active.created_at) }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                        {{ formatDate(active.created_at) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="data.last_page > 1" class="mt-6 flex flex-col items-center justify-between gap-3 border-t border-gray-100 pt-4 sm:flex-row dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-900 dark:text-gray-200">{{ data.from }}</span> –
                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ data.to }}</span>
                    of <span class="font-medium text-gray-900 dark:text-gray-200">{{ data.total }}</span> results
                </p>
                <div class="flex items-center gap-1">
                    <template v-for="(link, index) in paginationLinks" :key="index">
                        <!-- Prev -->
                        <button
                            v-if="link.label.includes('Previous')"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition-colors hover:bg-indigo-50 hover:text-indigo-600 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400"
                        >
                            <ChevronLeft class="h-4 w-4" />
                        </button>
                        <!-- Next -->
                        <button
                            v-else-if="link.label.includes('Next')"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition-colors hover:bg-indigo-50 hover:text-indigo-600 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400"
                        >
                            <ChevronRight class="h-4 w-4" />
                        </button>
                        <!-- Numbered pages -->
                        <button
                            v-else
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            :class="[
                                'flex h-8 min-w-[2rem] items-center justify-center rounded-lg border px-2 text-sm font-medium transition-colors',
                                link.active
                                    ? 'border-indigo-500 bg-indigo-600 text-white shadow-sm'
                                    : 'border-gray-200 bg-white text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400',
                                !link.url ? 'cursor-not-allowed opacity-40' : 'cursor-pointer',
                            ]"
                        >
                            <span v-html="link.label"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>
