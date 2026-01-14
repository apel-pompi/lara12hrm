<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Badge } from '@/components/ui/badge';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, RefreshCcw, Search, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { computed, ref } from 'vue';

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


export interface Partner {
    id: number;
    name: string;
    brn: string;
    email: string;
    fax: string;
    website: string;
    photo: string;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner', href: '/partner' }];

const props = defineProps<{
    pertners: Paginated<Partner>;
    allpartner:Array<{id:number;name:string}>;
    workflow:Array<{id:number;name:string}>;
    partnertype:Array<{id:number;partnertypename:string}>;
    country:Array<{id:number;name:string}>;
}>();

const data = props.pertners;

const toggleStatus = (partner: Partner) => {
    const newStatus = !Boolean(partner.active); // boolean
    router.put(
        route('partner.updateStatus', partner.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                partner.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Partner  status update');
            },
        },
    );
};

const goToPartnerCreate = () => {
    router.visit('/partner/create');
};

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

const selectedName = ref(null);
const queryName = ref('');
const filteredName = computed(() => {
    if (queryName.value === '') return props.allpartner;

    return props.allpartner.filter((n) => n.name && n.name.toLowerCase().includes(queryName.value.toLowerCase()));
});

const selectedWorkflow = ref(null);
const queryWorkflow = ref('');
const filteredWorkflow = computed(() => {
    if (queryWorkflow.value === '') return props.workflow;

    return props.workflow.filter((n) => n.name && n.name.toLowerCase().includes(queryWorkflow.value.toLowerCase()));
});

const selectedPartnerType = ref(null);
const queryPartnerType = ref('');
const filteredPartnerType = computed(() => {
    if (queryPartnerType.value === '') return props.partnertype;

    return props.partnertype.filter((n) => n.partnertypename && n.partnertypename.toLowerCase().includes(queryPartnerType.value.toLowerCase()));
});

const selectedCountry = ref(null);
const queryCountry = ref('');
const filteredCountry = computed(() => {
    if (queryCountry.value === '') return props.country;

    return props.country.filter((n) => n.name && n.name.toLowerCase().includes(queryCountry.value.toLowerCase()));
});


const search = () => {
    const params: Record<string, any> = {};

    if (selectedName.value) params.name = selectedName.value.name;
    if (selectedWorkflow.value) params.workflow = selectedWorkflow.value.id;
    if (selectedPartnerType.value) params.partnerType = selectedPartnerType.value.id;
    if (selectedCountry.value) params.country = selectedCountry.value.id;

    router.get(route('partner.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('partner.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('partner.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

</script>

<template>
    <Head title="Partner" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-start space-x-2 py-4">
                <div class="flex items-center gap-2 py-4">
                    <Button variant="outline" size="sm" @click="goToPartnerCreate"><Plus></Plus> Create Partner </Button>
                </div>
                <div class="flex flex-wrap items-center gap-4 py-4">
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedName">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select partner name"
                                    @input="queryName = $event.target.value"
                                    :display-value="(c) => c?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredName.length === 0 && queryName !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredName"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.name }}
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
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedWorkflow">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select workflow"
                                    @input="queryWorkflow = $event.target.value"
                                    :display-value="(c) => c?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredWorkflow.length === 0 && queryWorkflow !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredWorkflow"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.name }}
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
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedPartnerType">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select partner type"
                                    @input="queryPartnerType = $event.target.value"
                                    :display-value="(c) => c?.partnertypename ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredPartnerType.length === 0 && queryPartnerType !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredPartnerType"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.partnertypename }}
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
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedCountry">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select country"
                                    @input="queryCountry = $event.target.value"
                                    :display-value="(c) => c?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredCountry.length === 0 && queryCountry !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredCountry"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.name }}
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
                    <div class="w-full sm:w-auto">
                        <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                    </div>
                    <div class="w-full sm:w-auto">
                        <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                    </div>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Workflow</TableHead>
                            <TableHead>Partner Type</TableHead>
                            <TableHead>Country</TableHead>
                            <TableHead>State</TableHead>
                            <TableHead>City</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(partner, index) in data.data" :key="index">
                            <TableCell>
                                <Link :href="route('PartnerActivities.application', partner.id)" method="get" class="flex items-center space-x-2">
                                    <template v-if="partner.photo">
                                        <img
                                            :src="`/storage/partner/${partner.photo}`"
                                            alt="Profile"
                                            class="h-10 w-10 rounded-full object-cover shadow-md"
                                        />
                                    </template>
                                    <template v-else>
                                        <span
                                            :class="[
                                                'flex h-10 w-10 items-center justify-center rounded-full text-lg font-semibold text-white shadow-md',
                                                getAvatarColor(partner.name),
                                            ]"
                                        >
                                            {{ (partner.name?.charAt(0) ?? '').toUpperCase() }}
                                        </span>
                                    </template>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ partner.name }}</span>
                                </Link>
                            </TableCell>
                            <TableCell>
                                <Badge class="m-0.5 p-1" variant="outline" v-for="(wf, idx) in partner.workflow_names" :key="idx">
                                    {{ wf }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ partner.partnertype.partnertypename }}</TableCell>
                            <TableCell>{{ partner.state.country?.name }}</TableCell>
                            <TableCell>{{ partner.state.name }}</TableCell>
                            <TableCell>{{ partner.city?.name }}</TableCell>
                            <TableCell>
                                <Switch v-model="partner.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(partner)"> </Switch>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                    <label for="per-page" class="text-gray-600">Show:</label>
                    <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                        <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                    </select>
                    <span>Showing {{ pertners.from }} to {{ pertners.to }} of {{ pertners.total }} results</span>
                </div>
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
        <!-- Dialog -->
    </AppLayout>
</template>
