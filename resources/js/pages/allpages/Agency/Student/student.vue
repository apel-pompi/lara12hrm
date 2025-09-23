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
        default:
            return { id: '4', text: 'Achieved', color: 'bg-gray-500 text-white' };
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

const selectedEmail = ref(null);
const queryEmail = ref('');

const selectedCountry = ref(null);
const queryDesCoun = ref('');

const selectedAssain = ref(null);
const queryAssain = ref('');

const selectedStatus = ref(null);
const queryStatus = ref('');
// Filtered lists
const filteredName = computed(() => {
    if (queryName.value === '') return data.data;

    return data.data.filter((n) => `${n.fname} ${n.lname}`.toLowerCase().includes(queryName.value.toLowerCase()));
});

const filteredPhone = computed(() => {
    return data.data
        .filter((n) => n.phone && n.phone.trim() !== '')
        .filter((n) => (queryPhone.value === '' ? true : n.phone.toLowerCase().includes(queryPhone.value.toLowerCase())));
});
const filteredEmail = computed(() => {
    return data.data
        .filter((n) => n.email && n.email.trim() !== '')
        .filter((n) => (queryEmail.value === '' ? true : n.email.toLowerCase().includes(queryEmail.value.toLowerCase())));
});

const filteredCountries = computed(() => {
    const filtered =
        queryDesCoun.value === ''
            ? data.data
            : data.data.filter((n) => n.country && n.country.name.toLowerCase().includes(queryDesCoun.value.toLowerCase()));

    // unique country name
    const uniqueMap = new Map();
    filtered.forEach((item) => {
        if (item.country && !uniqueMap.has(item.country.id)) {
            uniqueMap.set(item.country.id, item.country);
        }
    });

    return Array.from(uniqueMap.values());
});

const filteredAssain = computed(() => {
    const filtered =
        queryAssain.value === ''
            ? data.data
            : data.data.filter((n) => n.assainuser && n.assainuser.name.toLowerCase().includes(queryAssain.value.toLowerCase()));

    // unique user name
    const uniqueMap = new Map();
    filtered.forEach((item) => {
        if (item.assainuser && !uniqueMap.has(item.assainuser.id)) {
            uniqueMap.set(item.assainuser.id, item.assainuser);
        }
    });

    return Array.from(uniqueMap.values());
});

const filteredStatus = computed(() => {
    const filtered =
        queryStatus.value === ''
            ? data.data
            : data.data.filter((item) => getStatusText(item.status).text.toLowerCase().includes(queryStatus.value.toLowerCase()));

    // Unique status by name
    const uniqueMap = new Map<string, { text: string; color: string }>();
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
    if (selectedEmail.value) params.email = selectedEmail.value.email;
    if (selectedCountry.value) params.country = selectedCountry.value.id;
    if (selectedAssain.value) params.user = selectedAssain.value.id;
    if (selectedStatus.value) params.status = selectedStatus.value.id;

    router.get(route('student.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('student.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <Head title="Student" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToStudentCreate"><Plus></Plus> Student Create </Button>
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
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select phone..."
                                    :display-value="(n) => n?.phone"
                                    @input="queryPhone = $event.target.value"
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
                                    v-if="filteredPhone.length === 0 && queryPhone !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredPhone"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.phone }}
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
                    <Combobox v-model="selectedEmail">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select email..."
                                    :display-value="(n) => n?.email"
                                    @input="queryEmail = $event.target.value"
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
                                    v-if="filteredEmail.length === 0 && queryEmail !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredEmail"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ n.email }}
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
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Destination Country</TableHead>
                            <TableHead>Assignee</TableHead>
                            <TableHead>Contact Source</TableHead>
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
                            <TableCell>{{ stud.email }}</TableCell>
                            <TableCell>{{ stud.country.name }}</TableCell>
                            <TableCell>{{ stud.assainuser.name }}</TableCell>
                            <TableCell>{{ stud.source.name }}</TableCell>
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
            </div>
        </div>
    </AppLayout>
</template>
