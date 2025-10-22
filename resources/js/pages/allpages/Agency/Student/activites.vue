<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import {  router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

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
const colors = [
    'bg-blue-500',
    'bg-green-500',
    'bg-purple-500',
    'bg-purple-700',
    'bg-pink-500',
    'bg-indigo-500',
    'bg-teal-500',
    'bg-yellow-400',
    'bg-yellow-700',
];

function getAvatarColor(name: string) {
    if (!name) return colors[0];
    const index = name.charCodeAt(0) % colors.length;
    return colors[index];
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

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <!-- Single Activity -->
            <!-- <div v-for="active in data.data" :key="active" class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-500 font-semibold text-white">
                    <span
                        :class="[
                            'flex h-10 w-10 items-center justify-center rounded-full text-lg font-semibold text-white shadow-md',
                            getAvatarColor(active.user.name),
                        ]"
                    >
                        {{ (active.user.name?.charAt(0) ?? '').toUpperCase() }}
                    </span>
                </div>

                <div class="flex-1">
                    <p class="text-sm">
                        <span class="font-semibold">{{ active.user.name }}</span>
                        <span class="ml-1 text-gray-600">{{ active.title }}</span>
                    </p> -->
                    <!-- <p class="mt-2 font-medium text-gray-800">BA/BSc in Economics</p>
                    <p class="text-sm text-gray-500">Acadia University</p> -->
                <!-- </div>

                <div class="text-xs text-gray-500">{{ formatDate(active.created_at) }}</div>
            </div>
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</div>
                <div class="space-x-2">
                    <Button
                        v-for="(link, index) in data.links"
                        :key="index"
                        :disabled="!link.url"
                        variant="outline"
                        size="sm"
                        :class="[link.active ? 'hover:outline' : '', !link.url ? 'cursor-not-allowed opacity-50' : '']"
                        @click="goToPage(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </Button>
                </div>
            </div> -->
        </div>
    </StudentLayout>
</template>
