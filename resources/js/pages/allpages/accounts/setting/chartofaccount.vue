<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsLayout from '@/layouts/settings/accountLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chart OF Accounts',
        href: '/accountssetting',
    },
];

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

export interface ChartOfAccount {
    accounttype: string;
    accountcode: string;
    description: string;
    accountusage: string;
    analyticalcode: string;
    groupone: { groupone: number; description: string };
    grouptwo: { grouptwo: number; description: string };
    groupthree: { groupthree: string; description: string };
    active: number;
    user: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    chartofaccount: Paginated<ChartOfAccount>;
    filters: { name?: string };
    groupone: { groupone: number; description: string };
    grouptwo: { grouptwo: number; description: string };
    groupthree: { groupthree: number; description: string };
    others: [];
}>();

const data = props.chartofaccount;

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    accounttype: '',
    accountcode: '',
    accountusage: '',
    analyticalcode: '',
    description: '',
    groupone: null as number | null,
    grouptwo: null as number | null,
    groupthree: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const grouptwoOptions = ref([]);

const fetchTwo = async () => {
    if (!form.groupone) return; // groupone code
    const res = await fetch(`/chartOfAccount/getGroupTwo/${form.groupone}`);
    const data = await res.json();
    grouptwoOptions.value = data.data;
};

watch(
    () => form.groupone,
    () => {
        fetchTwo();
    },
);

const groupthreeOptions = ref([]);

const fetchThree = async () => {
    if (!form.groupone || !form.grouptwo) return; // ensure both selected

    const res = await fetch(`/chartOfAccount/getGroupThree/${form.groupone}/${form.grouptwo}`);
    const data = await res.json();
    groupthreeOptions.value = data.data;
};

watch(
    () => form.grouptwo,
    () => {
        form.groupthree = '';
        fetchThree();
    },
);

const generateAccountCode = async () => {
    if (!form.groupthree) return;

    const res = await fetch(`/chartOfAccount/generateAccountCode/${form.groupthree}`);
    const data = await res.json();
    form.accountcode = data.accountcode;
};

watch(
    () => form.groupthree,
    () => {
        form.accountcode = ''; // reset
        generateAccountCode();
    },
);

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/chartOfAccount/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching chart of accounts details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.data);
        form.id = data.data.id;
        // Fetch group three options
        await fetchThree();

        form.groupthree = data.data.groupthree;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('chartOfAccount.update', form.id) : route('chartOfAccount.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('chartOfAccount.index'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            form.reset();
            showDialog.value = false;
        },

        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const flash = usePage().props.flash;
            if (flash?.error) {
                toast('error', {
                    description: flash.error + firstError,
                });
            }
        },
    });
};

const toggleStatus = (one: ChartOfAccount) => {
    const newStatus = !Boolean(one.active); // boolean
    router.put(
        route('chartOfAccount.updateStatus', one.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                one.active = newStatus ? 1 : 0; // local update (number)
                const flash = usePage().props.flash;
                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this chart of accounts?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/chartOfAccount/show/${id}`, {
        onSuccess: () => {
            const flash = usePage().props.flash;
            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
        },
        onError: () => {
            if (flash?.success) {
                toast('error', {
                    description: flash.success,
                });
            }
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const selectedGroupOne = ref(null);
const queryGroupOne = ref('');
const filteredGroupOne = computed(() => {
    if (queryGroupOne.value === '') return props.groupone;

    return props.groupone.filter((n) => n.description && n.description.toLowerCase().includes(queryGroupOne.value.toLowerCase()));
});

const selectedGroupTwo = ref(null);
const queryGroupTwo = ref('');
const filteredGroupTwo = computed(() => {
    if (queryGroupTwo.value === '') return props.grouptwo;

    return props.grouptwo.filter((n) => n.description && n.description.toLowerCase().includes(queryGroupTwo.value.toLowerCase()));
});

const selectedGroupThree = ref(null);
const queryGroupThree = ref('');
const filteredGroupThree= computed(() => {
    if (queryGroupThree.value === '') return props.groupthree;

    return props.groupthree.filter((n) => n.description && n.description.toLowerCase().includes(queryGroupThree.value.toLowerCase()));
});

const selectedDescription = ref(null);
const queryDescription = ref('');
const filteredDescription= computed(() => {
    if (queryDescription.value === '') return props.others;

    return props.others.filter((n) => n.description && n.description.toLowerCase().includes(queryDescription.value.toLowerCase()));
});

const selectedUsages = ref(null);
const queryUsages = ref('');

const filteredUsages = computed(() => {
    let list = props.others
    if (queryUsages.value.trim() !== '') {
        const q = queryUsages.value.toLowerCase()
        list = list.filter(item =>
            item.accountusage?.toLowerCase().includes(q)
        )
    }
    return [...new Set(list.map(item => item.accountusage).filter(Boolean))]
})

const selectedAnalytic = ref(null);
const queryAnalytic = ref('');

const filteredAnalytic = computed(() => {
    let list = props.others
    if (queryAnalytic.value.trim() !== '') {
        const q = queryAnalytic.value.toLowerCase()
        list = list.filter(item =>
            item.analyticalcode?.toLowerCase().includes(q)
        )
    }
    return [...new Set(list.map(item => item.analyticalcode).filter(Boolean))]
})

const search = () => {
    const params: Record<string, any> = {};

    if (selectedGroupOne.value) params.groupone = selectedGroupOne.value.groupone;
    if (selectedGroupTwo.value) params.grouptwo = selectedGroupTwo.value.grouptwo;
    if (selectedGroupThree.value) params.groupthree = selectedGroupThree.value.groupthree;
    if (selectedDescription.value) params.description = selectedDescription.value.description;
    if (selectedUsages.value) params.accountusage = selectedUsages.value;
    if (selectedAnalytic.value) params.analyticalcode = selectedAnalytic.value;

    router.get(route('chartOfAccount.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('chartOfAccount.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('chartOfAccount.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Accounts Setting" />

        <AccountsLayout :breadcrumbs="breadcrumbs">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Create
                    </Button>
                </div>
                <div class="flex flex-wrap items-center gap-4 py-4">
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedGroupOne">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select GroupOne"
                                    @input="queryGroupOne = $event.target.value"
                                    :display-value="(c) => c?.description ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredGroupOne.length === 0 && queryGroupOne !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredGroupOne"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.description }}
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
                        <Combobox v-model="selectedGroupTwo">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select GroupTwo"
                                    @input="queryGroupTwo = $event.target.value"
                                    :display-value="(c) => c?.description ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredGroupTwo.length === 0 && queryGroupTwo !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="two in filteredGroupTwo"
                                        :key="two.id"
                                        :value="two"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ two.description }}
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
                        <Combobox v-model="selectedGroupThree">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select GroupThree"
                                    @input="queryGroupThree = $event.target.value"
                                    :display-value="(c) => c?.description ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredGroupThree.length === 0 && queryGroupThree !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="three in filteredGroupThree"
                                        :key="three.id"
                                        :value="three"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ three.description }}
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
                        <Combobox v-model="selectedDescription">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select Description"
                                    @input="queryDescription = $event.target.value"
                                    :display-value="(c) => c?.description ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredDescription.length === 0 && queryDescription !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="three in filteredDescription"
                                        :key="three.id"
                                        :value="three"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ three.description }}
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
                        <Combobox v-model="selectedUsages">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Account Usage"
                                    @input="queryUsages = $event.target.value"
                                    :display-value="(value) => value ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredUsages.length === 0 && queryUsages !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="usage in filteredUsages"
                                        :key="usage.id"
                                        :value="usage"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ usage }}
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
                        <Combobox v-model="selectedAnalytic">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Analytic Type"
                                    @input="queryAnalytic = $event.target.value"
                                    :display-value="(value) => value ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredAnalytic.length === 0 && queryAnalytic !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="usage in filteredAnalytic"
                                        :key="usage.id"
                                        :value="usage"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ usage }}
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
                <div class="rounded-xl border overflow-hidden shadow-sm">
                    <Table class="w-full text-sm">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sl</TableHead>
                                <TableHead>Group One</TableHead>
                                <TableHead>Group Two</TableHead>
                                <TableHead>Group Three</TableHead>
                                <TableHead>Account Code</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Account Usage</TableHead>
                                <TableHead>Analytic Type</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow  v-for="(chart, index) in data.data ?? []" :key="index" class="hover:bg-muted/50">
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell>{{ chart.group_one.description }}</TableCell>
                                <TableCell>{{ chart.group_two.description }}</TableCell>
                                <TableCell>{{ chart.group_three.description }}</TableCell>
                                <TableCell>{{ chart.accountcode }}</TableCell>
                                <TableCell>{{ chart.description }}</TableCell>
                                <TableCell>{{ chart.accountusage }}</TableCell>
                                <TableCell>{{ chart.analyticalcode }}</TableCell>
                                <TableCell>
                                    <Switch v-model="chart.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(chart)"> </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(chart.id)"><SquarePen></SquarePen></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(chart.id)"><Trash></Trash></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 py-4">
                    <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                        <label for="per-page" class="text-gray-600">Show:</label>
                        <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span>Showing {{ chartofaccount.from }} to {{ chartofaccount.to }} of {{ chartofaccount.total }} results</span>
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
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                    <!-- Header -->
                    <DialogHeader class="mb-4 border-b pb-3">
                        <DialogTitle class="text-2xl font-semibold tracking-wide">
                            {{ isEditMode ? 'Edit Chart of Accounts' : 'Create Chart of Accounts' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new Chart of Accounts.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- First Group -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">First Group<span class="text-red-500">*</span></Label>
                            <Select v-model="form.groupone">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group One" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="one in props.groupone" :key="one.id" :value="one.groupone">
                                            {{ one.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.groupone" class="mt-1 text-sm text-red-600">{{ form.errors.groupone }}</p>
                        </div>

                        <!-- Second Group -->
                        <div>
                            <Label for="grouptwo" class="text-sm font-medium">Second Group<span class="text-red-500">*</span></Label>
                            <Select v-model="form.grouptwo">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group Two" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="two in grouptwoOptions" :key="two.id" :value="two.grouptwo">
                                            {{ two.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.grouptwo" class="mt-1 text-sm text-red-600">{{ form.errors.grouptwo }}</p>
                        </div>

                        <!-- Third Group -->
                        <div>
                            <Label for="groupthree" class="text-sm font-medium">Third Group<span class="text-red-500">*</span></Label>
                            <Select v-model="form.groupthree">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group Three" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="three in groupthreeOptions" :key="three.id" :value="three.groupthree">
                                            {{ three.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.groupthree" class="mt-1 text-sm text-red-600">{{ form.errors.groupthree }}</p>
                        </div>

                        <!-- Account Code -->
                        <div>
                            <Label for="accountcode" class="text-sm font-medium">Account Code<span class="text-red-500">*</span></Label>
                            <Input type="text" id="accountcode" v-model="form.accountcode" class="mt-1 w-full" readonly disabled />
                            <p v-if="form.errors.accountcode" class="mt-1 text-sm text-red-600">{{ form.errors.accountcode }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="description" class="text-sm font-medium">Description<span class="text-red-500">*</span></Label>
                            <Input type="text" id="description" v-model="form.description" placeholder="Enter Description" class="mt-1 w-full" />
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                        </div>

                        <!-- Account Type -->
                        <div>
                            <Label for="accounttype" class="text-sm font-medium">Account Type<span class="text-red-500">*</span></Label>
                            <Select v-model="form.accounttype">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Account Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Asset">Asset</SelectItem>
                                        <SelectItem value="Liability">Liability</SelectItem>
                                        <SelectItem value="Income">Income</SelectItem>
                                        <SelectItem value="Expenses">Expenses</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.accounttype" class="mt-1 text-sm text-red-600">{{ form.errors.accounttype }}</p>
                        </div>

                        <!-- Account Usage -->
                        <div>
                            <Label for="accountusage" class="text-sm font-medium">Account Usage<span class="text-red-500">*</span></Label>
                            <Select v-model="form.accountusage">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Account Usage" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Ledger">Ledger</SelectItem>
                                        <SelectItem value="AP">AP [Account Payable]</SelectItem>
                                        <SelectItem value="AR">AR [Account Receivable]</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.accountusage" class="mt-1 text-sm text-red-600">{{ form.errors.accountusage }}</p>
                        </div>

                        <!-- Analytical Code -->
                        <div>
                            <Label for="analyticalcode" class="text-sm font-medium">Analytical Type</Label>
                            <Select v-model="form.analyticalcode">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Analytical Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Cash">Cash</SelectItem>
                                        <SelectItem value="Non-Cash">Non-Cash</SelectItem>
                                        <SelectItem value="Cheque">Cheque</SelectItem>
                                        <SelectItem value="Bankers Draft">Bankers Draft</SelectItem>
                                        <SelectItem value="Pay Order">Pay Order</SelectItem>
                                        <SelectItem value="Letter of Credit">Letter of Credit</SelectItem>
                                        <SelectItem value="Wire Transfer">Wire Transfer</SelectItem>
                                        <SelectItem value="Others">Others</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.analyticalcode" class="mt-1 text-sm text-red-600">{{ form.errors.analyticalcode }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="mt-6 flex justify-end gap-3 border-t pt-4">
                        <DialogClose as-child>
                            <Button variant="secondary">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AccountsLayout>
    </AppLayout>
</template>
