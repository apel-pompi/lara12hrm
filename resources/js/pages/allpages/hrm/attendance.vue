<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router } from '@inertiajs/vue3';
import { CalendarDays, Clock, Clock3, Eye, Fingerprint, RefreshCcw, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';
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

const selectedAttendanceDetails = ref<any[]>([]);

const onShow = async (id: string, date: string) => {
    try {
        const res = await fetch(`/attendance/show/${id}/${date}`);
        if (!res.ok) {
            toast.error('Server error while fetching Personnel info details.');
            return;
        }
        const data = await res.json();

        selectedAttendanceDetails.value = data.attendance || [];
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

const selectedPersonID = ref(null); // empid
const selectedPerson = ref(null); // empname
const selectedDate = ref(null); // Date
const queryID = ref('');
const queryName = ref('');
const queryDate = ref('');
const filteredPeopleID = computed(() => {
    let items = props.attendance?.data || [];
    if (queryID.value !== '') {
        items = items.filter((person) => person?.user_id?.toLowerCase().includes(queryID.value.toLowerCase()));
    }
    const seen = new Set();
    return items.filter((item) => {
        if (!item.user_id || seen.has(item.user_id)) return false;
        seen.add(item.user_id);
        return true;
    });
});

const filteredPeople = computed(() => {
    let items = props.attendance?.data || [];
    if (queryName.value !== '') {
        items = items.filter((person) => person?.employee?.empname?.toLowerCase().includes(queryName.value.toLowerCase()));
    }
    const seen = new Set();
    return items
        .map((item) => item.employee)
        .filter((emp) => {
            if (!emp || !emp.empname || seen.has(emp.empname)) return false;
            seen.add(emp.empname);
            return true;
        });
});

const filteredDate = computed(() => {
    let items = props.attendance?.data || [];
    if (queryDate.value !== '') {
        items = items.filter((item) => item?.attend_date?.toLowerCase().includes(queryDate.value.toLowerCase()));
    }
    const seen = new Set();
    return items
        .map((item) => item.attend_date)
        .filter((date) => {
            if (!date || seen.has(date)) return false;
            seen.add(date);
            return true;
        });
});

const refresh = () => {
    router.get(route('attendance.index'), {}, { replace: true });
};

const search = () => {
    const params: Record<string, any> = {};
    if (selectedPersonID.value) {
        params.empid = selectedPersonID.value.user_id;
    }
    if (selectedPerson.value) params.empname = selectedPerson.value.empname;
    if (selectedDate.value) params.date = selectedDate.value;

    router.get(route('attendance.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Attendance Manager" />
        <div class="app-page">
            <div
                class="flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900">

                <!-- Employee ID -->
                <Combobox v-model="selectedPersonID">
                    <div class="relative">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Employee ID..." :display-value="(person) => person?.user_id"
                                @input="queryID = $event.target.value" />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-xl border bg-white shadow-lg">
                                <ComboboxOption v-for="person in filteredPeopleID" :key="person.id" :value="person"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white">
                                    {{ person.user_id }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </div>
                </Combobox>

                <!-- Employee Name -->
                <Combobox v-model="selectedPerson">
                    <div class="relative">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="Employee Name..." :display-value="(person) => person?.empname"
                                @input="queryName = $event.target.value" />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-xl border bg-white shadow-lg">
                                <ComboboxOption v-for="person in filteredPeople" :key="person.empid" :value="person"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white">
                                    {{ person.empname }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </div>
                </Combobox>
                <!-- Date -->
                <Combobox v-model="selectedDate">
                    <div class="relative">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="Date..." :display-value="(date) => date"
                                @input="queryDate = $event.target.value" />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-xl border bg-white shadow-lg">
                                <ComboboxOption v-for="date in filteredDate" :key="date" :value="date"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white">
                                    {{ date }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </div>
                </Combobox>
                <Button size="sm" @click="search" class="rounded-xl bg-indigo-600 px-5 text-white hover:bg-indigo-700">
                    <Search class="h-4 w-4" />
                    Search
                </Button>
                <Button variant="outline" size="sm" @click="refresh" class="rounded-xl px-5">
                    <RefreshCcw class="h-4 w-4" />
                    Refresh
                </Button>
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Table Card -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <!-- Toolbar -->
                    <div
                        class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Attendance Manager</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Biometric attendance records</p>
                    </div>

                    <!-- Responsive Table -->
                    <div class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow
                                    class="border-b border-gray-100 bg-gray-50/80 dark:border-gray-700 dark:bg-gray-800/50">
                                    <TableHead
                                        class="w-12 px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        #</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Employee</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Department</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Designation</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Date</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        In Time</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Out Time</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Status</TableHead>
                                    <TableHead
                                        class="px-4 py-3 text-center text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">
                                        Action</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <!-- Empty State -->
                                <TableRow v-if="props.attendance.data.length === 0">
                                    <TableCell colspan="8" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                <Fingerprint class="h-7 w-7 text-gray-400" />
                                            </div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No
                                                attendance records found</p>
                                        </div>
                                    </TableCell>
                                </TableRow>

                                <!-- Data Rows -->
                                <TableRow v-for="(record, index) in attendance.data" :key="record.id"
                                    class="border-b border-gray-50 transition-colors hover:bg-gray-50/70 dark:border-gray-700/50 dark:hover:bg-gray-800/40">
                                    <!-- Serial -->
                                    <TableCell class="px-4 py-3.5 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ index + 1 }}
                                    </TableCell>

                                    <!-- Employee Info -->
                                    <TableCell class="px-4 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-sm font-bold"
                                                :class="getAvatarClass(record.employee?.empname ?? '')">
                                                {{ (record.employee?.empname?.charAt(0) ?? '?').toUpperCase() }}
                                            </div>
                                            <div class="min-w-0">
                                                <p
                                                    class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ record.employee?.empname ?? '—' }}
                                                </p>
                                                <span
                                                    class="mt-0.5 inline-block rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
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
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{
                                                formatDate(record.attend_date) }}</span>
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
                                            <span class="text-sm font-medium text-rose-700 dark:text-rose-400">{{
                                                formatTime(record.outtime) }}</span>
                                        </div>
                                        <span v-else class="text-sm text-gray-400">—</span>
                                    </TableCell>

                                    <!-- Status -->
                                    <TableCell class="px-4 py-3.5 text-center">
                                        <!-- Present -->
                                        <span v-if="record.status === 'Present'"
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:ring-emerald-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Present
                                        </span>

                                        <!-- Late -->
                                        <span v-else-if="record.status === 'Late'"
                                            class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:ring-yellow-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                            Late
                                        </span>

                                        <!-- Absent -->
                                        <span v-else
                                            class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Absent
                                        </span>
                                    </TableCell>
                                    <TableCell class="flex justify-center">
                                        <Button size="icon" variant="ghost"
                                            class="h-8 w-8 text-blue-600 hover:bg-blue-100"
                                            @click="onShow(record.user_id, record.attend_date)">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex flex-col gap-4 border-t bg-white px-5 py-4 md:flex-row md:items-center md:justify-between">
                        <!-- Left -->
                        <div class="flex flex-col gap-3 md:flex-row md:items-center">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span>Show</span>

                                <select v-model="perPage" @change="changePerPage"
                                    class="rounded-lg border px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                        {{ size }}
                                    </option>
                                </select>

                                <span>entries</span>
                            </div>

                            <p class="text-sm text-gray-500">
                                Showing {{ attendance.from }} to {{ attendance.to }} of {{ attendance.total }} results
                            </p>
                        </div>

                        <!-- Right -->
                        <div class="flex flex-wrap items-center gap-2">
                            <Button v-for="(link, index) in attendance.links" :key="index" :disabled="!link.url"
                                variant="outline" size="sm" :class="[
                                    'min-w-9.5 rounded-lg',
                                    link.active ? 'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700' : '',
                                    !link.url ? 'cursor-not-allowed opacity-50' : '',
                                ]" @click="goToPage(link.url)">
                                <span v-html="link.label"></span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent
                class="max-h-[90vh] w-[95vw] max-w-lg overflow-y-auto rounded-2xl p-4 sm:p-6 md:max-w-xl lg:max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Attendance Details</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm">
                        Punch history for {{ selectedAttendanceDetails[0]?.employee?.empname || 'Employee' }} on
                        {{ formatDate(selectedAttendanceDetails[0]?.record_time || '') }}.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="selectedAttendanceDetails.length > 0" class="mt-4 flex flex-col gap-6">
                    <!-- Employee Summary Card -->
                    <div
                        class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-900/50">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                            {{ (selectedAttendanceDetails[0]?.employee?.empname?.charAt(0) ?? '?').toUpperCase() }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ selectedAttendanceDetails[0]?.employee?.empname }}
                            </h3>
                            <div
                                class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <Fingerprint class="h-4 w-4" />
                                    <span>{{ selectedAttendanceDetails[0]?.employee?.empid }}</span>
                                </div>
                                <div class="flex items-center gap-1.5"
                                    v-if="selectedAttendanceDetails[0]?.employee?.department?.deptname">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    <span>{{ selectedAttendanceDetails[0]?.employee?.department?.deptname }}</span>
                                </div>
                                <div class="flex items-center gap-1.5"
                                    v-if="selectedAttendanceDetails[0]?.employee?.designation?.desname">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    <span>{{ selectedAttendanceDetails[0]?.employee?.designation?.desname }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Punch Timeline -->
                    <div>
                        <h4
                            class="mb-4 text-sm font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">
                            Punch Records ({{ selectedAttendanceDetails.length }} punches)
                        </h4>

                        <div
                            class="relative space-y-4 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:border-l-2 before:border-dashed before:border-gray-200 dark:before:border-gray-700">
                            <div v-for="(punch, index) in selectedAttendanceDetails" :key="punch.id"
                                class="relative flex items-center gap-6">
                                <div
                                    class="z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-4 border-white bg-indigo-100 text-indigo-600 shadow dark:border-gray-950 dark:bg-indigo-900 dark:text-indigo-400">
                                    <Clock class="h-5 w-5" />
                                </div>

                                <div
                                    class="flex-1 rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-xs font-semibold tracking-wider text-indigo-600 uppercase dark:text-indigo-400">
                                            Punch {{ index + 1 }}
                                        </span>
                                        <time class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            {{ formatTime(punch.record_time) }}
                                        </time>
                                        <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                            <span class="inline-block h-2 w-2 rounded-full"
                                                :class="punch.device_ip ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600'"></span>
                                            Device IP: {{ punch.device_ip || 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="py-12 text-center">
                    <div
                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                        <Clock3 class="h-6 w-6 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No punch data found</h3>
                    <p class="mt-1 text-sm text-gray-500">Could not find any punch records for this employee on the
                        selected date.</p>
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
