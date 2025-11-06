<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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
    created_at:string;
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
    allsearch:[],
    allcountry:[],
    assaignUser:[],
    student: Paginated<Student>;
    filters: { name?: string };
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

const getTimeText = (timestamp: string) => {
    const date = new Date(timestamp);
    return {
        text: date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }),
        color: 'blue',
    };
};

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

// Combobox states
const selectedName = ref(null);
const queryName = ref('');

const selectedPhone = ref(null);
const queryPhone = ref('');

const selectedCountry = ref(null);
const queryDesCoun = ref('');

const selectedAssain = ref(null);
const queryAssain = ref('');

const selectedTime = ref(null);
const queryTime = ref('');

const selectedUser = ref(null);
const queryUser = ref('');

const selectedStatus = ref(null);
const queryStatus = ref('');
// Filtered lists
const filteredName = computed(() => {
    if (queryName.value === '') return props.allsearch;

    return props.allsearch.filter((n) => `${n.fname} ${n.lname}`.toLowerCase().includes(queryName.value.toLowerCase()));
});

const filteredPhone = computed(() => {
    if (queryPhone.value === '') return props.allsearch;

    return props.allsearch.filter((n) => n.phone && n.phone.toLowerCase().includes(queryPhone.value.toLowerCase()));
});

const filteredCountries = computed(() => {
     if (queryDesCoun.value === '') return props.allcountry;
    
     return props.allcountry.filter((n) => n.name && n.name.toLowerCase().includes(queryDesCoun.value.toLowerCase()));
});

const filteredAssain = computed(() => {
   
    const filtered =
        queryAssain.value === ''
            ? props.assaignUser
            : props.assaignUser.filter((n) => n.assainuser && n.assainuser.name.toLowerCase().includes(queryAssain.value.toLowerCase()));

    // unique user name
    const uniqueMap = new Map();
    filtered.forEach((item) => {
        if (item.assainuser && !uniqueMap.has(item.assainuser.id)) {
            uniqueMap.set(item.assainuser.id, item.assainuser);
        }
    });

    return Array.from(uniqueMap.values());
});

const filteredTime = computed(() => {
    const filtered =
        queryTime.value === ''
            ? props.allsearch
            : props.allsearch.filter((item) => getTimeText(item.created_at).text.toLowerCase().includes(queryTime.value.toLowerCase()));

    // Remove duplicates by text
    const uniqueMap = new Map<string, { text: string; color: string }>();
    filtered.forEach((item) => {
        const statusObj = getTimeText(item.created_at);
        if (!uniqueMap.has(statusObj.text)) {
            uniqueMap.set(statusObj.text, statusObj);
        }
    });

    return Array.from(uniqueMap.values());
});

const filteredUser = computed(() => {
    const filtered =
        queryUser.value === '' ? props.allsearch : props.allsearch.filter((n) => n.user && n.user.name.toLowerCase().includes(queryUser.value.toLowerCase()));

    // unique user name
    const uniqueMap = new Map();
    filtered.forEach((item) => {
        if (item.user && !uniqueMap.has(item.user.id)) {
            uniqueMap.set(item.user.id, item.user);
        }
    });

    return Array.from(uniqueMap.values());
});

const filteredStatus = computed(() => {
    const filtered =
        queryStatus.value === ''
            ? props.allsearch
            : props.allsearch.filter((item) => getStatusText(item.status).text.toLowerCase().includes(queryStatus.value.toLowerCase()));

    // Unique status by name
    const uniqueMap = new Map<string, { text: string}>();
    filtered.forEach((item) => {
        const statusObj = getStatusText(item.status);
        if (!uniqueMap.has(statusObj.text)) {
            uniqueMap.set(statusObj.text, statusObj);
        }
    });

    return Array.from(uniqueMap.values());
});

const search = () => {
    const params: Record<string, any> = {};

    if (selectedName.value) params.name = selectedName.value.id;
    if (selectedPhone.value) params.phone = selectedPhone.value.phone;
    if (selectedCountry.value) params.country = selectedCountry.value.id;
    if (selectedAssain.value) params.user = selectedAssain.value.id;
    if (selectedTime.value) params.created_at = selectedTime.value;
    if (selectedUser.value) params.user_id = selectedUser.value.id;
    if (selectedStatus.value) params.status = selectedStatus.value.id;

    router.get(route('student.prospect'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};
const refresh = () => {
    router.get(route('student.prospect'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('student.prospect'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
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
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToStudentCreate"><Plus></Plus> Student Create </Button>
               
            </div>
            <div class="flex items-center gap-2 py-4">
                <!-- Search start -->
                <div class="grid gap-2">
                    <Combobox v-model="selectedName">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select name..."
                                    :display-value="(n) => (n ? `${n.fname} ${n.lname}` : '')"
                                    @input="queryName = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredName.length === 0 && queryName !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredName"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.fname }} {{ n.lname }} </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedPhone">
                        <div class="relative w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Phone"
                                @input="queryPhone = $event.target.value"
                                :display-value="(c) => c?.phone ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredPhone.length === 0 && queryPhone !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="country in filteredPhone"
                                    :key="country.id"
                                    :value="country"
                                    class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ country.phone }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedCountry">
                        <div class="relative w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Country"
                                @input="queryDesCoun = $event.target.value"
                                :display-value="(c) => c?.name ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredCountries.length === 0 && queryDesCoun !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="country in filteredCountries"
                                    :key="country.id"
                                    :value="country"
                                    class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ country.name }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedAssain">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select assain user"
                                    :display-value="(n) => n?.name"
                                    @input="queryAssain = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredAssain.length === 0 && queryAssain !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredAssain"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.name }}
                                    </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedTime">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select entry date"
                                    :display-value="(n) => n?.text ?? ''"
                                    @input="queryTime = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredTime.length === 0 && queryTime !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredTime"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected, active }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.text }}
                                    </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        :class="active ? 'text-white' : 'text-indigo-600'"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedUser">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select entry user"
                                    :display-value="(n) => n?.name"
                                    @input="queryUser = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredUser.length === 0 && queryUser !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredUser"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.name }}
                                    </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="grid gap-2">
                    <Combobox v-model="selectedStatus">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select status..."
                                    :display-value="(n) => n?.text"
                                    @input="queryStatus = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredStatus.length === 0 && queryStatus !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredStatus"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.text }}
                                    </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
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
                <Button class="bg-green-600 text-white cursor-pointer" size="sm" @click="goToAll">({{ props.countAll }})All</Button>
                <Button class="bg-red-800 text-white cursor-pointer" size="sm" @click="goToPending">({{ props.countPending }})Pending</Button>
                <Button class="bg-green-500 text-white cursor-pointer" size="sm" @click="goToLead">({{ props.countLead }})Lead</Button>
                <Button class="bg-yellow-500 text-white cursor-pointer" size="sm" @click="goToProspect">({{ props.countProspect }})Prospect</Button>
                <Button class="bg-blue-500 text-white cursor-pointer" size="sm" @click="goToOnBoard">({{ props.countonBoard }})OnBoard</Button>
                <Button class="bg-gray-500 text-white cursor-pointer" size="sm" @click="goToArchive">({{ props.countArchive }})Archive</Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Gender</TableHead>
                            <TableHead>Destination Country</TableHead>
                            <TableHead>Assignee</TableHead>
                            <TableHead>Entry Time</TableHead>
                            <TableHead>Entry User</TableHead>
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
                                    <span class="font-medium text-gray-900">{{ stud.fname }} {{ stud.lname }} </span>
                                </Link>
                            </TableCell>
                            <TableCell>{{ stud.phone }}</TableCell>
                            <TableCell>
                                <span v-if="stud.gender==1">Male</span>
                                <span v-if="stud.gender==2">Female</span>
                                <span v-if="stud.gender==3">Other's</span>
                            </TableCell>
                            <TableCell>{{ stud.country.name }}</TableCell>
                            <TableCell>{{ stud.assainuser.name }}</TableCell>
                            <TableCell>{{ formatDate(stud.created_at) }}</TableCell>
                            <TableCell>{{ stud.user.name }}</TableCell>
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

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                    <label for="per-page" class="text-gray-600">Show:</label>
                    <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                        <option v-for="size in [5, 10, 25, 50, 100,200]" :key="size" :value="size">{{ size }}</option>
                    </select>
                    <span>Showing {{ student.from }} to {{ student.to }} of {{ student.total }} results</span>
                </div>

                <div class="space-x-2">
                    <Button
                        v-for="(link, index) in student.links"
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
            </div>
        </div>
    </AppLayout>
</template>
