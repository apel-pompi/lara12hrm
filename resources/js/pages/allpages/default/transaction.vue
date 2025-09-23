<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Plus, RefreshCcw, Search, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface Transaction {
    id: number;
    trnname_id: string;
    trncode: string;
    branch_id: number;
    yearname: number;
    monthname: number;
    lastnumber: number;
    increment: number;
    active: number;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Transaction Number', href: '/transaction' }];

const props = defineProps<{
    tranaction: Paginated<Transaction>;
    filters: { name?: string };
    tranactionName: { id: number; name: string }[];
    branch: { id: number; branchname: string }[];
    months: { id: number; name: string }[];
    years: { id: number; name: string }[];
}>();

const data = props.tranaction;

interface FormErrors {
    trnname_id?: number;
    trncode?: string;
    branch_id?: number;
    yearname?: number;
    monthname?: number;
    lastnumber?: number;
    increment?: number;
}

const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Helper function
const getMonthName = (month: number | string) => {
    const m = Number(month);
    return monthNames[m] ?? month;
};

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    trnname_id: '',
    trncode: '',
    branch_id: '',
    yearname: '',
    monthname: '',
    lastnumber: '',
    increment: '',
    active: false,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    form.trnname_id = selecteName.value ? selecteName.value.id : '';
    form.branch_id = selecteBranch.value ? selecteBranch.value.id : '';

    form.post(route('transaction.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: `Transaction created successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('transaction.index'), {
                    only: ['transactions'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', {
                description: firstError,
            });
        },
    });
};

const toggleStatus = (transaction: Transaction) => {
    const newStatus = !Boolean(transaction.active); // boolean
    router.put(
        route('transaction.updateStatus', transaction.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                transaction.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Transaction Code status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this transaction?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/transaction/show/${id}`, {
        onSuccess: () => {
            toast.success('Transaction deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

// Combobox states
const selecteName = ref(null);
const queryName = ref('');
const selecteBranch = ref(null);
const selecteCode = ref(null);
const queryBranch = ref('');
const queryCode = ref('');
// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.tranactionName : props.tranactionName.filter((n) => n.name)));
const filteredBranch = computed(() => (queryBranch.value === '' ? props.branch : props.branch.filter((n) => n.name)));
const filteredCode = computed(() => (queryCode.value === '' ? data.data : data.data.filter((n) => n.name)));

//Auto-generate Transaction Code
const isCodeReady = computed(() => {
    return selecteName.value && selecteBranch.value && form.yearname && form.monthname;
});

watch([selecteName, selecteBranch, () => form.yearname, () => form.monthname], ([newName, newBranch, newYear, newMonth]) => {
    if (newName && newBranch && newYear && newMonth) {
        // Transaction Name
        const nameCode = newName.name
            .split(' ')
            .map((word) => word[0].toUpperCase())
            .join('');

        // Branch Name Frist 3 letter
        const branchCode = newBranch.branchname.slice(0, 3).toUpperCase();

        // Year last 2 digit (2025 → 25)
        const yearCode = String(newYear).slice(-2);

        // Month 2 digit (1 → 01, 9 → 09)
        const monthCode = String(newMonth).padStart(2, '0');

        // Code build
        form.trncode = `${nameCode}${branchCode}${yearCode}${monthCode}`;
    }
});

const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.trnname_id = selecteName.value.id;
    if (selecteCode.value) params.trncode = selecteCode.value.trncode;
    if (selecteBranch.value) params.branch_id = selecteBranch.value.id;

    router.get(route('transaction.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('transaction.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Source" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create </Button>

                    <!-- Search start -->
                    <div class="grid gap-2">
                        <Combobox v-model="selecteName">
                            <div class="relative w-48">
                                <!-- Input -->
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select name..."
                                        :display-value="(n) => n?.name"
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
                                    <div
                                        v-if="filteredName.length === 0 && queryName !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
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
                        <Combobox v-model="selecteCode">
                            <div class="relative w-48">
                                <!-- Input -->
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select code..."
                                        :display-value="(n) => n?.trncode"
                                        @input="queryCode = $event.target.value"
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
                                        v-if="filteredCode.length === 0 && queryCode !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredCode"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ n.trncode }}
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
                        <Combobox v-model="selecteBranch">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Branch"
                                    @input="queryBranch = $event.target.value"
                                    :display-value="(c) => (c ? c.branchname : '')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="branch in filteredBranch"
                                        :key="branch.id"
                                        :value="branch"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ branch.branchname }}
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
                                <TableHead>Transaction Type</TableHead>
                                <TableHead>Transaction Code</TableHead>
                                <TableHead>Branch Name</TableHead>
                                <TableHead>Year</TableHead>
                                <TableHead>Month</TableHead>
                                <TableHead>Lastnumber</TableHead>
                                <TableHead>Increment</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(trn, index) in data.data" :key="trn.id ?? index">
                                <TableCell>{{ trn.transactionname.name }}</TableCell>
                                <TableCell>{{ trn.trncode }}</TableCell>
                                <TableCell>{{ trn.branch.branchname }}</TableCell>
                                <TableCell>20{{ trn.yearname }}</TableCell>
                                <TableCell>{{ getMonthName(trn.monthname) }}</TableCell>
                                <TableCell>{{ trn.lastnumber }}</TableCell>
                                <TableCell>{{ trn.increment }}</TableCell>
                                <TableCell>{{ trn.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="trn.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(trn)"> </Switch>
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(trn.id)"><Trash></Trash></Button>
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
            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-3">
                        <DialogTitle class="text-lg font-semibold">
                            {{ isEditMode ? 'Edit transaction number' : 'Create transaction number' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{
                                isEditMode
                                    ? 'Update the transaction number details and click save.'
                                    : 'Fill in the details below to create a new transaction number.'
                            }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid grid-cols-1 gap-6 py-4 md:grid-cols-2">
                        <!-- Transaction Name -->
                        <div class="grid gap-2">
                            <Label for="trntype" class="font-medium">Transaction Name</Label>
                            <Combobox v-model="selecteName">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Name"
                                        @input="queryName = $event.target.value"
                                        :display-value="(c) => (c ? c.name : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredName.length === 0 && queryName !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="name in filteredName"
                                            :key="name.id"
                                            :value="name"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ name.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.trnname_id" class="text-sm text-red-600">
                                {{ form.errors.trnname_id }}
                            </p>
                        </div>

                        <!-- Branch Name -->
                        <div class="grid gap-2">
                            <Label for="trncode" class="font-medium">Branch Name</Label>
                            <Combobox v-model="selecteBranch">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Branch"
                                        @input="queryBranch = $event.target.value"
                                        :display-value="(c) => (c ? c.branchname : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="branch in filteredBranch"
                                            :key="branch.id"
                                            :value="branch"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ branch.branchname }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.branch_id" class="text-sm text-red-600">
                                {{ form.errors.branch_id }}
                            </p>
                        </div>

                        <!-- Transaction Year -->
                        <div class="grid gap-2">
                            <Label for="yearname" class="font-medium">Transaction Year</Label>
                            <Select v-model="form.yearname">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="year in props.years" :key="year.id" :value="year.id">
                                            {{ year.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.yearname" class="text-sm text-red-600">
                                {{ form.errors.yearname }}
                            </p>
                        </div>

                        <!-- Transaction Month -->
                        <div class="grid gap-2">
                            <Label for="monthname" class="font-medium">Transaction Month</Label>
                            <Select v-model="form.monthname">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Month" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="month in props.months" :key="month.id" :value="month.id">
                                            {{ month.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.monthname" class="text-sm text-red-600">
                                {{ form.errors.monthname }}
                            </p>
                        </div>
                        <!-- Transaction Code -->
                        <div class="grid gap-2">
                            <Label for="trncode" class="font-medium">Transaction Code</Label>
                            <Input id="trncode" v-model="form.trncode" class="w-full" :disabled="!isCodeReady" autofocus />
                            <p v-if="form.errors.trncode" class="text-sm text-red-600">
                                {{ form.errors.trncode }}
                            </p>
                        </div>
                        <!-- Last Number -->
                        <div class="grid gap-2">
                            <Label for="lastnumber" class="font-medium">Last Number</Label>
                            <Input id="lastnumber" v-model="form.lastnumber" class="w-full" placeholder="Input last number" autofocus />
                            <p v-if="form.errors.lastnumber" class="text-sm text-red-600">
                                {{ form.errors.lastnumber }}
                            </p>
                        </div>

                        <!-- Increment -->
                        <div class="grid gap-2">
                            <Label for="increment" class="font-medium">Increment</Label>
                            <Input id="increment" v-model="form.increment" class="w-full" placeholder="Input Increment" autofocus />
                            <p v-if="form.errors.increment" class="text-sm text-red-600">
                                {{ form.errors.increment }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="flex justify-end space-x-2 border-t pt-4">
                        <DialogClose as-child>
                            <Button type="button" variant="secondary">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
