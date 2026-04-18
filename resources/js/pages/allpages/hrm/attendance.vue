<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { CalendarDays, Clock, Clock3, Eye, Fingerprint } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Attendance Manager', href: '/attendance' }];

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

const props = defineProps<{
    attendance: Paginated<{
        id: number;
        user_id: string;
        attend_date: string;
        intime?: string;
        outtime?: string;
        status?: string;
        device_ip?: string;
        state?: string;
        employee?: {
            empid: string;
            empname: string;
            email?: string;
            phoneoffice?: string;
            blood?: string;
            department?: { deptname: string };
            designation?: { desname: string };
        };
    }>;
}>();

const showDialog = ref(false);

const showDailogCreate = () => {
    showDialog.value = true;
};

const onShow = async (id: string, date: string) => {
    try {
        const res = await fetch(`/attendance/show/${id}/${date}`);
        if (!res.ok) {
            toast.error('Server error while fetching Personnel info details.');
            return;
        }
        const data = await res.json();
        console.log(data);

        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
        toast.error('Network error occurred. Please try again.');
    }
};

const formatDate = (dt: string) => {
    if (!dt) return '—';
    const d = new Date(dt);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatTime = (dt: string) => {
    if (!dt) return '—';
    const d = new Date(dt);
    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
};

const avatarColors = [
    'bg-indigo-100 text-indigo-600',
    'bg-violet-100 text-violet-600',
    'bg-cyan-100 text-cyan-600',
    'bg-emerald-100 text-emerald-600',
    'bg-rose-100 text-rose-600',
    'bg-amber-100 text-amber-600',
];

const getAvatarClass = (name: string) => {
    if (!name) return avatarColors[0];
    return avatarColors[name.charCodeAt(0) % avatarColors.length];
};

const perPage = ref(props.attendance.per_page);

const changePerPage = () => {
    router.get(route('attendance.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Attendance Manager" />

        <div class="min-h-screen bg-gray-50 p-4 md:p-6 dark:bg-gray-950">
            <!-- Table Card -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <!-- Toolbar -->
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Attendance Manager</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Biometric attendance records</p>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="border-b border-gray-100 bg-gray-50/80 dark:border-gray-700 dark:bg-gray-800/50">
                                <TableHead
                                    class="w-12 px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >#</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Employee</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Department</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Designation</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Date</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >In Time</TableHead
                                >
                                <TableHead class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Out Time</TableHead
                                >
                                <TableHead
                                    class="px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Status</TableHead
                                >
                                <TableHead
                                    class="px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400"
                                    >Action</TableHead
                                >
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <!-- Empty State -->
                            <TableRow v-if="props.attendance.data.length === 0">
                                <TableCell colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                            <Fingerprint class="h-7 w-7 text-gray-400" />
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No attendance records found</p>
                                    </div>
                                </TableCell>
                            </TableRow>

                            <!-- Data Rows -->
                            <TableRow
                                v-for="(record, index) in attendance.data"
                                :key="record.id"
                                class="border-b border-gray-50 transition-colors hover:bg-gray-50/70 dark:border-gray-700/50 dark:hover:bg-gray-800/40"
                            >
                                <!-- Serial -->
                                <TableCell class="px-4 py-3.5 text-center text-sm text-gray-500 dark:text-gray-400">
                                    {{ index + 1 }}
                                </TableCell>

                                <!-- Employee Info -->
                                <TableCell class="px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-sm font-bold"
                                            :class="getAvatarClass(record.employee?.empname ?? '')"
                                        >
                                            {{ (record.employee?.empname?.charAt(0) ?? '?').toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                {{ record.employee?.empname ?? '—' }}
                                            </p>
                                            <span
                                                class="mt-0.5 inline-block rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                                            >
                                                {{ record.employee?.empid ?? '—' }}
                                            </span>
                                        </div>
                                    </div>
                                </TableCell>

                                <!-- Department -->
                                <TableCell class="px-4 py-3.5">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ record.employee?.department?.deptname ?? '—' }}
                                    </span>
                                </TableCell>

                                <!-- Designation -->
                                <TableCell class="px-4 py-3.5">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ record.employee?.designation?.desname ?? '—' }}
                                    </span>
                                </TableCell>

                                <!-- Date -->
                                <TableCell class="px-4 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <CalendarDays class="h-3.5 w-3.5 flex-shrink-0 text-gray-400" />
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(record.attend_date) }}</span>
                                    </div>
                                </TableCell>

                                <!-- In Time -->
                                <TableCell class="px-4 py-3.5">
                                    <div v-if="record.intime" class="flex items-center gap-1.5">
                                        <Clock class="h-3.5 w-3.5 flex-shrink-0 text-emerald-500" />
                                        <span class="text-sm font-medium text-emerald-700 dark:text-emerald-400">{{
                                            formatTime(record.intime)
                                        }}</span>
                                    </div>
                                    <span v-else class="text-sm text-gray-400">—</span>
                                </TableCell>

                                <!-- Out Time -->
                                <TableCell class="px-4 py-3.5">
                                    <div v-if="record.outtime" class="flex items-center gap-1.5">
                                        <Clock3 class="h-3.5 w-3.5 flex-shrink-0 text-rose-500" />
                                        <span class="text-sm font-medium text-rose-700 dark:text-rose-400">{{ formatTime(record.outtime) }}</span>
                                    </div>
                                    <span v-else class="text-sm text-gray-400">—</span>
                                </TableCell>

                                <!-- Status -->
                                <TableCell class="px-4 py-3.5 text-center">
                                    <!-- Present -->
                                    <span
                                        v-if="record.status === 'Present'"
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-800"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Present
                                    </span>

                                    <!-- Late -->
                                    <span
                                        v-else-if="record.status === 'Late'"
                                        class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:ring-yellow-800"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                        Late
                                    </span>

                                    <!-- Absent -->
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-800"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Absent
                                    </span>
                                </TableCell>
                                <TableCell class="flex justify-center">
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="h-8 w-8 text-blue-600 hover:bg-blue-100"
                                        @click="onShow(record.user_id, record.attend_date)"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Footer -->
                <div class="flex flex-col gap-4 border-t bg-white px-5 py-4 md:flex-row md:items-center md:justify-between">
                    <!-- Left -->
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>Show</span>

                            <select
                                v-model="perPage"
                                @change="changePerPage"
                                class="rounded-lg border px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            >
                                <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                    {{ size }}
                                </option>
                            </select>

                            <span>entries</span>
                        </div>

                        <p class="text-sm text-gray-500">Showing {{ attendance.from }} to {{ attendance.to }} of {{ attendance.total }} results</p>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap items-center gap-2">
                        <Button
                            v-for="(link, index) in attendance.links"
                            :key="index"
                            :disabled="!link.url"
                            variant="outline"
                            size="sm"
                            :class="[
                                'min-w-9.5 rounded-lg',
                                link.active ? 'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700' : '',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]"
                            @click="goToPage(link.url)"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent class="h-[auto] w-full max-w-[95vw] overflow-y-auto sm:max-w-[900px]">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show employee details</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this employee. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2"></div>
                    </div>
                    <!-- Column 1 -->
                    <div class="space-y-4"></div>
                    <!-- Column 3 -->
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="showDialog = false"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
