<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
    name: string;
    trncode: string;
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
    transaction: Paginated<Transaction>;
    transactionFilter: { name: string };
    filters: { name?: string };
}>();

const data = props.transaction;

interface FormErrors {
    name?: string;
    trncode?: string;
    lastnumber?: number;
    increment?: number;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    trncode: '',
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

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

watch(
    () => form.name,
    (newVal) => {
        if (newVal === 'Student ID') {
            form.trncode = 'STU-';
        } else if (newVal === 'Money Received') {
            form.trncode = 'MR--';
        } else if (newVal === 'Quoatations No') {
            form.trncode = 'QTN-';
        } else if (newVal === 'Invoice No') {
            form.trncode = 'INV-';
        } else if (newVal === 'Amount Refund') {
            form.trncode = 'SR--';
        } else if (newVal === 'Opening Blance') {
            form.trncode = 'OB--';
        } else if (newVal === 'Journal Voucher') {
            form.trncode = 'JV--';
        } else if (newVal === 'Payment Voucher') {
            form.trncode = 'PAY-';
        } else if (newVal === 'Receipt Voucher') {
            form.trncode = 'RCV-';
        } else if (newVal === 'Reverse Voucher') {
            form.trncode = 'REV-';
        } else if (newVal === 'Supplier No') {
            form.trncode = 'SUP-';
        } else if (newVal === 'Supplier Invoice') {
            form.trncode = 'AP--';
        } else if (newVal === 'Supplier Payment') {
            form.trncode = 'APV-';
        } else {
            form.trncode = '';
        }
    },
);

// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

const selecteCode = ref(null); // code
const queryCode = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.transactionFilter : props.transactionFilter.filter((n) => n.name)));

const filteredCode = computed(() => (queryCode.value === '' ? props.transactionFilter : props.transactionFilter.filter((n) => n.trncode)));

const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.name = selecteName.value.name;

    router.get(route('transaction.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};
const refresh = () => {
    router.get(route('transaction.index'), {}, { replace: true });
};
const perPage = ref(10);

const changePerPage = () => {
    router.get(route('transaction.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Source" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header Actions -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Actions -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <!-- Create -->
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Create
                        </Button>

                        <!-- Search Combobox -->
                        <Combobox v-model="selecteName">
                            <div class="relative w-full sm:w-56">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Search name..."
                                        :display-value="(n) => n?.name"
                                        @input="queryName = $event.target.value"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                </div>

                                <ComboboxOptions
                                    class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-xl ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div v-if="filteredName.length === 0 && queryName !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="selected ? 'font-medium' : 'font-normal'" class="block truncate">
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

                        <Combobox v-model="selecteName">
                            <div class="relative w-full sm:w-56">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Search code..."
                                        :display-value="(n) => n?.trncode"
                                        @input="queryCode = $event.target.value"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                </div>

                                <ComboboxOptions
                                    class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-xl ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div v-if="filteredCode.length === 0 && queryCode !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredCode"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="selected ? 'font-medium' : 'font-normal'" class="block truncate">
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

                        <!-- Search -->
                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="search">
                            <Search class="mr-2 h-4 w-4" />
                            Search
                        </Button>

                        <!-- Refresh -->
                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="refresh">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Refresh
                        </Button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Transaction Management</h2>
                        <p class="text-sm text-gray-500">Manage all transaction records from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Transaction Name</TableHead>
                                <TableHead>Transaction Code</TableHead>
                                <TableHead>Lastnumber</TableHead>
                                <TableHead>Increment</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(trn, index) in data.data" :key="trn.id ?? index">
                                <TableCell>{{ trn.name }}</TableCell>
                                <TableCell>{{ trn.trncode }}</TableCell>
                                <TableCell>{{ trn.lastnumber }}</TableCell>
                                <TableCell>{{ trn.increment }}</TableCell>
                                <TableCell>{{ trn.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="trn.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(trn)"> </Switch>
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button size="sm" variant="outline" @click="onDelete(trn.id)"><Trash></Trash></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <!-- Pagination -->
                <div class="mt-5 flex flex-col items-center justify-between gap-4 md:flex-row">
                    <!-- Left -->
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                        <label>Show:</label>

                        <select v-model="perPage" @change="changePerPage" class="rounded-md border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                {{ size }}
                            </option>
                        </select>

                        <span> Showing {{ transaction.from }} to {{ transaction.to }} of {{ transaction.total }} results </span>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap gap-2">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            size="sm"
                            :class="[
                                link.active ? 'bg-indigo-600 text-white hover:bg-indigo-700' : '',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]"
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
                            <Label for="name" class="font-medium">Transaction Name<span class="text-red-500">*</span></Label>
                            <Select v-model="form.name">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Student ID">Student ID</SelectItem>
                                        <SelectItem value="Money Received">Money Received</SelectItem>
                                        <SelectItem value="Quoatations No">Quoatations No</SelectItem>
                                        <SelectItem value="Invoice No">Invoice No</SelectItem>
                                        <SelectItem value="Amount Refund">Amount Refund</SelectItem>
                                        <SelectItem value="Opening Blance">Opening Blance</SelectItem>
                                        <SelectItem value="Journal Voucher">Journal Voucher</SelectItem>
                                        <SelectItem value="Payment Voucher">Payment Voucher</SelectItem>
                                        <SelectItem value="Receipt Voucher">Receipt Voucher</SelectItem>
                                        <SelectItem value="Reverse Voucher">Reverse Voucher</SelectItem>
                                        <SelectItem value="Supplier No">Supplier No</SelectItem>
                                        <SelectItem value="Supplier Invoice">Supplier Invoice</SelectItem>
                                        <SelectItem value="Supplier Payment">Supplier Payment</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Transaction Code -->
                        <div class="grid gap-2">
                            <Label for="trncode" class="font-medium">Transaction Code<span class="text-red-500">*</span></Label>
                            <Input id="trncode" v-model="form.trncode" class="w-full" readonly autofocus />
                            <p v-if="form.errors.trncode" class="text-sm text-red-600">
                                {{ form.errors.trncode }}
                            </p>
                        </div>
                        <!-- Last Number -->
                        <div class="grid gap-2">
                            <Label for="lastnumber" class="font-medium">Last Number<span class="text-red-500">*</span></Label>
                            <Input id="lastnumber" v-model="form.lastnumber" class="w-full" placeholder="Input last number" autofocus />
                            <p v-if="form.errors.lastnumber" class="text-sm text-red-600">
                                {{ form.errors.lastnumber }}
                            </p>
                        </div>

                        <!-- Increment -->
                        <div class="grid gap-2">
                            <Label for="increment" class="font-medium">Increment<span class="text-red-500">*</span></Label>
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
