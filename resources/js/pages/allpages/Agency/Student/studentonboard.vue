<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { Plus, RefreshCcw, Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

export interface Student {
    id: number;
    photo: string;
    student_id: string;
    fname: string;
    lname: string;
    gender: number;
    email: string;
    phone: string;
    descountry_id: number;
    stage_id: number;
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
    allsearch: [];
    assaignUser: [];
    student: Paginated<Student>;
    filters: { name?: string };
    studentID: { id: number; student_id: string }[];
    countAll: { countAll: number };
    countLead: { countLead: number };
    countPending: { countPending: number };
    countProspect: { countProspect: number };
    countonBoard: { countonBoard: number };
    countArchive: { countArchive: number };
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

const goToStudentCreate = () => {
    router.visit('/student/create');
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

const goToAll = () => {
    router.get(route('student.index'), {}, { replace: true });
};

const goToLead = () => {
    router.get(route('student.lead'), {}, { replace: true });
};
const goToPending = () => {
    router.get(route('student.pending'), {}, { replace: true });
};
const goToProspect = () => {
    router.get(route('student.prospect'), {}, { replace: true });
};

const goToOnBoard = () => {
    router.get(route('student.onBoard'), {}, { replace: true });
};

const goToArchive = () => {
    router.get(route('student.archive'), {}, { replace: true });
};
</script>

<template>
    <Head title="Student" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToStudentCreate"><Plus></Plus> Student Create </Button>
            </div>
            <div class="flex items-center gap-2 py-4">
                <!-- Search start -->
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedID">
                        <div class="relative w-64">
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Search ID..."
                                    :display-value="(s: Student | null) => (s ? `${s.student_id}` : '')"
                                    @input="($event) => searchStudents('student_id', $event.target.value)"
                                    @focus="() => showAllStudents('student_id')"
                                />
                                <ComboboxButton
                                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    @click="() => showAllStudents('student_id')"
                                >
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>
                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white text-sm shadow">
                                <div v-if="idResults.length === 0">
                                    <span class="block px-4 py-2 text-gray-500">Searching ... </span>
                                </div>

                                <ComboboxOption
                                    v-for="s in idResults"
                                    :key="s.id"
                                    :value="s"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ s.student_id }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedStudent">
                        <div class="relative w-64">
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Search student..."
                                    :display-value="(s: Student | null) => (s ? `${s.fname} ${s.lname}` : '')"
                                    @input="($event) => searchStudents('name', $event.target.value)"
                                    @focus="() => showAllStudents('name')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" @click="() => showAllStudents('name')">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>
                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white text-sm shadow">
                                <div v-if="nameResults.length === 0">
                                    <span class="block px-4 py-2 text-gray-500">Searching ... </span>
                                </div>

                                <ComboboxOption
                                    v-for="s in nameResults"
                                    :key="s.id"
                                    :value="s"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ s.fname }} {{ s.lname }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedPhone">
                        <div class="relative w-64">
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Search phone..."
                                    :display-value="(s: Student | null) => (s ? s.phone : '')"
                                    @input="($event) => searchStudents('phone', $event.target.value)"
                                    @focus="() => showAllStudents('phone')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" @click="() => showAllStudents('phone')">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>
                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white text-sm shadow">
                                <div v-if="phoneResults.length === 0">
                                    <span class="block px-4 py-2 text-gray-500">Searching ...</span>
                                </div>

                                <ComboboxOption
                                    v-for="s in phoneResults"
                                    :key="s.id"
                                    :value="s"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ s.phone }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedAssain">
                        <div class="relative w-64">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Search assain user..."
                                :display-value="(s) => s?.name ?? ''"
                                @input="($event) => searchStudents('assain', $event.target.value)"
                                @focus="() => showAllStudents('assain')"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" @click="() => showAllStudents('assain')">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white text-sm shadow">
                                <div v-if="assainResults.length === 0">
                                    <span class="block px-4 py-2 text-gray-500">Searching ...</span>
                                </div>
                                <ComboboxOption
                                    v-for="s in assainResults"
                                    :key="s.id"
                                    :value="s"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ s.name }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedDate">
                        <div class="relative w-64">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select entry date..."
                                :display-value="(d) => d ?? ''"
                                @input="($event) => searchStudents('date', $event.target.value)"
                                @focus="() => showAllStudents('date')"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2" @click="() => showAllStudents('date')">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white text-sm shadow">
                                <div v-if="dateResults.length === 0">
                                    <span class="block px-4 py-2 text-gray-500">Searching ...</span>
                                </div>
                                <ComboboxOption
                                    v-for="d in dateResults"
                                    :key="d"
                                    :value="d"
                                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ d }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                </div>
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                </div>
            </div>
            <div class="flex items-center gap-2 py-4">
                <Button class="cursor-pointer bg-green-600 text-white" size="sm" @click="goToAll">({{ props.countAll }})All</Button>
                <Button class="cursor-pointer bg-red-800 text-white" size="sm" @click="goToPending">({{ props.countPending }})Pending</Button>
                <Button class="cursor-pointer bg-green-500 text-white" size="sm" @click="goToLead">({{ props.countLead }})Lead</Button>
                <Button class="cursor-pointer bg-yellow-500 text-white" size="sm" @click="goToProspect">({{ props.countProspect }})Prospect</Button>
                <Button class="cursor-pointer bg-blue-500 text-white" size="sm" @click="goToOnBoard">({{ props.countonBoard }})OnBoard</Button>
                <Button class="cursor-pointer bg-gray-500 text-white" size="sm" @click="goToArchive">({{ props.countArchive }})Archive</Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Gender</TableHead>
                            <TableHead>Source</TableHead>
                            <TableHead>Assignee</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(stud, index) in data.data" :key="stud.id ?? index">
                            <TableCell>
                                <Link :href="route('studentActivities.index', stud.id)" method="get" class="flex items-center space-x-2">
                                    <template v-if="stud.photo">
                                        <img
                                            :src="`/storage/student/${stud.photo}`"
                                            alt="Profile"
                                            class="h-10 w-10 rounded-full object-cover shadow-md"
                                        />
                                    </template>
                                    <template v-else>
                                        <span
                                            :class="[
                                                'flex h-10 w-10 items-center justify-center rounded-full text-lg font-semibold text-white shadow-md',
                                                getAvatarColor(stud.fname),
                                            ]"
                                        >
                                            {{ (stud.fname?.charAt(0) ?? '').toUpperCase() }}{{ (stud.lname?.charAt(0) ?? '').toUpperCase() }}
                                        </span>
                                    </template>
                                    <span class="font-medium text-gray-900">{{ stud.student_id }}</span>
                                </Link>
                            </TableCell>
                            <TableCell>
                                <Link :href="route('studentActivities.index', stud.id)" method="get" class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">{{ stud.fname }} {{ stud.lname }} </span>
                                </Link>
                            </TableCell>
                            <TableCell>{{ stud.phone }}</TableCell>
                            <TableCell>
                                <span v-if="stud.gender == 1">Male</span>
                                <span v-if="stud.gender == 2">Female</span>
                                <span v-if="stud.gender == 3">Other's</span>
                            </TableCell>
                            <TableCell>{{ stud.source.name }}</TableCell>
                            <TableCell>{{ stud.assainuser.name }}</TableCell>
                            <TableCell>
                                <div class="flex items-center space-x-2">
                                    <Badge size="sm" :class="getStatusText(stud.status).color">
                                        {{ getStatusText(stud.status).text }}
                                    </Badge>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                    <Label for="per-page" class="text-gray-600">Show:</Label>
                    <Select v-model="perPage" class="rounded border px-2 py-1 text-sm">
                        <SelectTrigger>
                            <SelectValue placeholder="Select Gender" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="size in [5, 10, 25, 50, 100, 200, 500]" :key="size" :value="size">{{ size }}</SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <span>Showing {{ student.from }} to {{ student.to }} of {{ student.total }} results</span>
                </div>

                <div class="space-x-2">
                    <Button
                        v-for="(Link, index) in student.links"
                        :key="index"
                        :disabled="!Link.url"
                        variant="outline"
                        size="sm"
                        :class="[Link.active ? 'hover:outline' : '', !Link.url ? 'cursor-not-allowed opacity-50' : '']"
                        @click="goToPage(Link.url)"
                    >
                        <span v-html="Link.label"></span>
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
