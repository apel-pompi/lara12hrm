<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { RefreshCcw, Search } from 'lucide-vue-next';
import { nextTick, onMounted, ref, watch,computed } from 'vue';

export interface Student {
    id: number;
    student_id: string;
    fname: string;
    lname: string;
    gender: number;
    phone: string;
    assain_user: number;
    source_id: number;
    user_id: number;
    created_at: string;
    status: number;
}

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
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student', href: '/student' }];

const props = defineProps<{
    student: Paginated<Student>;
    filters: { name?: string };
    studentID: { id: number; student_id: string }[];
    countAll: { countAll: number };
    countLead: { countLead: number };
    countPending: { countPending: number };
    countProspect: { countProspect: number };
    countonBoard: { countonBoard: number };
    countArchive: { countArchive: number };
    countInactive1Month: number;
    countInactive3Month: number;
    countInactive6Month: number;
    showInactiveTabs: boolean;
}>();

const data = props.student;

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

const getStatusText = (status: number) => {
    switch (status) {
        case 1:
            return { id: '1', text: 'Lead', color: 'bg-green-500 text-white' };
        case 2:
            return { id: '2', text: 'Prospect', color: 'bg-yellow-500 text-black' };
        case 3:
            return { id: '3', text: 'onBoard', color: 'bg-blue-500 text-white' };
        case 4:
            return { id: '4', text: 'Achieved', color: 'bg-gray-500 text-white' };
        default:
            return { id: null, text: 'Pending', color: 'bg-red-800 text-white' };
    }
};

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
// Selected values
const selectedID = ref<Student | null>(null);
const selectedStudent = ref<Student | null>(null);
const selectedPhone = ref<Student | null>(null);
const selectedAssain = ref<Student['assain_user'] | null>(null);
const selectedDate = ref<string | null>(null);

// Query & results
const idResults = ref<Student[]>([]);
const nameResults = ref<Student[]>([]);
const phoneResults = ref<Student[]>([]);
const assainResults = ref<Student['assain_user'][]>([]);
const dateResults = ref<string[]>([]);

let timer: ReturnType<typeof setTimeout> | null = null;

// Fetch students from server
const fetchStudents = async (type: 'student_id' | 'name' | 'phone' | 'assain' | 'date', query: string = '') => {
    try {
        const res = await axios.get<Student[]>('/student/SearchOnBoard', {
            params: { type, q: query },
        });

        switch (type) {
            case 'student_id':
                idResults.value = res.data;
                break;
            case 'name':
                nameResults.value = res.data;
                break;
            case 'phone':
                phoneResults.value = res.data;
                break;
            case 'assain':
                // unique assain users
                const map = new Map<number, Student['assain_user']>();
                res.data.forEach((s) => {
                    if (s.assainuser && !map.has(s.assainuser.id)) {
                        map.set(s.assainuser.id, s.assainuser);
                    }
                });
                assainResults.value = Array.from(map.values());
                break;
            case 'date':
                const dateMap = new Map<string, string>();
                res.data.forEach((s) => {
                    if (!s.date) return;

                    if (!dateMap.has(s.date)) {
                        dateMap.set(s.date, s.date);
                    }
                });
                dateResults.value = Array.from(dateMap.keys()).sort((a, b) => b.localeCompare(a));
                break;
        }
    } catch (e) {
        console.error(e);
    }
};

// Debounced search
const searchStudents = (type: 'student_id' | 'name' | 'phone' | 'assain' | 'date', query: string) => {
    if (timer) clearTimeout(timer);

    timer = setTimeout(() => {
        fetchStudents(type, query);
    }, 300);
};

// Show all data on focus / button click
const showAllStudents = (type: 'student_id' | 'name' | 'phone' | 'assain' | 'date') => {
    fetchStudents(type, '');
};

const search = () => {
    const params: Record<string, any> = {};
    if (selectedID.value) params.student_id = selectedID.value.student_id;
    if (selectedStudent.value) params.name = selectedStudent.value.id;
    if (selectedPhone.value) params.phone = selectedPhone.value.phone;
    if (selectedAssain.value) params.user = selectedAssain.value.id;
    if (selectedDate.value) params.created_at = selectedDate.value;

    router.get(route('student.onBoard'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('student.onBoard'), {}, { replace: true });
};

const perPage = ref(10);

watch(perPage, (value) => {
    router.get(
        route('student.onBoard'),
        { per_page: value },
        {
            preserveState: false,
            preserveScroll: true,
            replace: true,
        },
    );
});

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const page = usePage();
const active = ref('inactive1month');
const tabRefs = ref<HTMLElement[]>([]);
const tabs = computed(() => {
    const baseTabs = [
        { key: 'all',      label: 'All',     count: props.countAll },
        { key: 'pending',  label: 'Pending', count: props.countPending },
        { key: 'lead',     label: 'Lead',    count: props.countLead },
        { key: 'prospect', label: 'Prospect',count: props.countProspect },
        { key: 'onboard',  label: 'OnBoard', count: props.countonBoard },
        { key: 'archive',  label: 'Archive', count: props.countArchive },
    ];

    if (!props.showInactiveTabs) {
        return baseTabs;
    }

    return baseTabs.concat([
        { key: 'inactive1month', label: '1 Month+', count: props.countInactive1Month },
        { key: 'inactive3month', label: '3 Months+', count: props.countInactive3Month },
        { key: 'inactive6month', label: '6 Months+', count: props.countInactive6Month },
    ]);
});

const routes: Record<string, string> = {
    all:            'student.index',
    pending:        'student.pending',
    lead:           'student.lead',
    prospect:       'student.prospect',
    onboard:        'student.onBoard',
    archive:        'student.archive',
    inactive1month: 'student.inactive1month',
    inactive3month: 'student.inactive3month',
    inactive6month: 'student.inactive6month',
};

const indicatorStyle = ref({});

const updateIndicator = () => {
    const index = tabs.value.findIndex((t) => t.key === active.value);
    const el = tabRefs.value[index];
    if (!el) return;
    indicatorStyle.value = { width: el.offsetWidth + 'px', transform: `translateX(${el.offsetLeft}px)` };
};

const setActive = async (tab) => {
    active.value = tab;
    await nextTick();
    updateIndicator();

    router.get(route(routes[tab]), {}, { replace: true });
};

const setActiveFromUrl = () => {
    const url = page.url;

    if (url.includes('inactive/6month'))      active.value = 'inactive6month';
    else if (url.includes('inactive/3month')) active.value = 'inactive3month';
    else if (url.includes('inactive/1month')) active.value = 'inactive1month';
    else if (url.includes('pending')) active.value = 'pending';
    else if (url.includes('lead')) active.value = 'lead';
    else if (url.includes('prospect')) active.value = 'prospect';
    else if (url.includes('onBoard')) active.value = 'onboard';
    else if (url.includes('archive')) active.value = 'archive';
    else active.value = 'all';
};

onMounted(async () => {
    setActiveFromUrl();
    await nextTick();
    updateIndicator();

    window.addEventListener('resize', updateIndicator);
});

watch(active, async () => {
    await nextTick();
    updateIndicator();
});

const statusClass = (status) => {
    const map = {
        1: 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-600',
        2: 'px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600',
        3: 'px-2 py-1 text-xs rounded-full bg-red-100 text-red-600',
    };

    return map[status] ?? 'px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600';
};
</script>

<template>
    <Head title="Student Onboard List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div class="flex justify-center">
                <div class="no-scrollbar relative mb-4 flex overflow-x-auto rounded-full bg-gray-100/80 p-1 dark:bg-gray-800/80">
                    <!-- Sliding Indicator -->
                    <div
                        class="absolute top-1 bottom-1 rounded-full bg-white shadow transition-all duration-300 ease-out dark:bg-gray-900"
                        :style="indicatorStyle"
                    ></div>

                    <!-- Tabs -->
                    <button
                        v-for="(tab, index) in tabs"
                        :key="tab.key"
                        @click="setActive(tab.key)"
                        :ref="(el) => (tabRefs[index] = el)"
                        class="relative z-10 cursor-pointer px-5 py-1.5 text-sm font-medium whitespace-nowrap transition-all duration-200"
                        :class="active === tab.key ? 'text-indigo-600' : 'text-gray-600 hover:text-indigo-500 dark:text-gray-300'"
                    >
                        {{ tab.label }}
                        <span class="ml-1 text-xs opacity-70">({{ tab.count }})</span>
                    </button>
                </div>
            </div>
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6">
                    <!-- Student ID -->
                    <Combobox v-model="selectedID" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Search ID..."
                                :display-value="(s: Student | null) => (s ? `${s.student_id}` : '')"
                                @input="($e) => searchStudents('student_id', $e.target.value)"
                                @focus="() => showAllStudents('student_id')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('student_id')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="idResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in idResults" :key="s.id" :value="s" class="option">
                                    {{ s.student_id }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                    <!--  Name -->
                    <Combobox v-model="selectedStudent" as="div" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Search name..."
                                :display-value="(s) => (s ? `${s.fname} ${s.lname}` : '')"
                                @input="($e) => searchStudents('name', $e.target.value)"
                                @focus="() => showAllStudents('name')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('name')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="nameResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in nameResults" :key="s.id" :value="s" class="option">
                                    {{ s.fname }} {{ s.lname }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                    <!--  Phone -->
                    <Combobox v-model="selectedPhone" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Search phone..."
                                :display-value="(s) => s?.phone ?? ''"
                                @input="($e) => searchStudents('phone', $e.target.value)"
                                @focus="() => showAllStudents('phone')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('phone')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="phoneResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in phoneResults" :key="s.id" :value="s" class="option">
                                    {{ s.phone }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <!-- Assign -->
                    <Combobox v-model="selectedAssain" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Assign user..."
                                :display-value="(s) => s?.name ?? ''"
                                @input="($e) => searchStudents('assain', $e.target.value)"
                                @focus="() => showAllStudents('assain')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('assain')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="assainResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in assainResults" :key="s.id" :value="s" class="option">
                                    {{ s.name }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <!-- Date -->
                    <Combobox v-model="selectedDate" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Entry date..."
                                :display-value="(d) => d ?? ''"
                                @input="($e) => searchStudents('date', $e.target.value)"
                                @focus="() => showAllStudents('date')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('date')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="dateResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="d in dateResults" :key="d" :value="d" class="option">
                                    {{ d }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                    <div class="flex flex-col items-stretch justify-end gap-2 sm:flex-row">
                        <Button class="btn-primary w-full cursor-pointer sm:w-auto" @click="search"><Search class="mr-1 h-4 w-4" /> Search </Button>
                        <Button class="w-full cursor-pointer sm:w-auto" @click="refresh"><RefreshCcw class="mr-1 h-4 w-4" /> Refresh </Button>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">onBoard Student list</h2>
                    <p class="text-sm text-gray-500">Manage all onBoard Student from here.</p>
                </div>
                <Table class="w-full min-w-225 text-sm">
                    <TableHeader>
                        <TableRow class="bg-gray-100 hover:bg-gray-200">
                            <TableHead>Student Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Gender</TableHead>
                            <TableHead>Source</TableHead>
                            <TableHead>Assignee User</TableHead>
                            <TableHead>Entry Date Time</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(stud, index) in data.data" :key="stud.id ?? index">
                            <!-- Student -->
                            <TableCell>
                                <Link :href="route('studentActivities.index', stud.id)" class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <div class="relative">
                                        <img v-if="stud.photo" :src="`/storage/student/${stud.photo}`" class="h-10 w-10 rounded-full object-cover" />
                                        <div
                                            v-else
                                            :class="[
                                                'flex h-10 w-10 items-center justify-center rounded-full font-semibold text-white',
                                                getAvatarColor(stud.fname),
                                            ]"
                                        >
                                            {{ stud.fname?.charAt(0) }}{{ stud.lname?.charAt(0) }}
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ stud.fname }} {{ stud.lname }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ stud.student_id }}</div>
                                    </div>
                                </Link>
                            </TableCell>
                            <!-- Phone -->
                            <TableCell class="td">{{ stud.phone }}</TableCell>
                            <!-- Gender -->
                            <TableCell class="td">
                                <span class="badge-gray">
                                    {{ stud.gender == 1 ? 'Male' : stud.gender == 2 ? 'Female' : 'N/A' }}
                                </span>
                            </TableCell>
                            <!-- Source -->
                            <TableCell class="td">{{ stud.source?.name }}</TableCell>
                            <!-- Assignee -->
                            <TableCell class="td">{{ stud.assainuser?.name }}</TableCell>

                            <!-- Date -->
                            <TableCell class="td text-gray-500">
                                {{ formatDate(stud.created_at) }}
                            </TableCell>
                            <!-- Status -->
                            <TableCell class="td">
                                <span :class="statusClass(stud.status)">
                                    {{ getStatusText(stud.status).text }}
                                </span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="mt-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <!-- LEFT SIDE -->
                <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center dark:text-gray-300">
                    <!-- Per Page -->
                    <div class="flex items-center gap-2">
                        <span>Show</span>

                        <Select v-model="perPage">
                            <SelectTrigger class="h-8 w-20 text-sm">
                                <SelectValue placeholder="10" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="size in [5, 10, 25, 50, 100, 200, 500]" :key="size" :value="size">
                                    {{ size }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <span>entries</span>
                    </div>

                    <!-- Info -->
                    <div>
                        Showing
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ student.from }}
                        </span>
                        to
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ student.to }}
                        </span>
                        of
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ student.total }}
                        </span>
                        results
                    </div>
                </div>

                <!-- RIGHT SIDE PAGINATION -->
                <div class="flex flex-wrap justify-center gap-2 md:justify-end">
                    <button
                        v-for="(link, index) in student.links"
                        :key="index"
                        @click="goToPage(link.url)"
                        :disabled="!link.url"
                        v-html="link.label"
                        :class="[
                            'rounded-md border px-3 py-1.5 text-sm transition',
                            link.active
                                ? 'border-indigo-600 bg-indigo-600 text-white shadow'
                                : 'bg-white text-gray-600 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                            !link.url ? 'cursor-not-allowed opacity-50' : '',
                        ]"
                    ></button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
