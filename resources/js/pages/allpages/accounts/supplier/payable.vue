<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router, useForm } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { RefreshCcw, Save, Search, ShieldCheck, Trash2, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Supplier Payable', href: '/suppliersPayble' }];

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

export interface SupplierPayable {
    suppliername: string;
    branch: string;
    contact_person: string;
    payable: number;
    totalOutstanding: number;
}

const props = defineProps<{
    payables: Paginated<SupplierPayable>;
    branch: { id: number; branchname: string }[];
    accounts: Array<{ accountcode: string; description: string }>;
    account_code: { accdisc: string; cracc: string; dracc: string };
}>();

const data = props.payables;

const form = useForm({
    id: null as number | null,
    voucherdate: '',
    branch_id: '',
    account: '',
    amountPaid: null as number | null,
    notes: '',
    acc_code: '',
    craccount: '',
    selectedPay: [],
});

const vdate = ref<string | null>(null);

const maxDate = today(getLocalTimeZone());

watch(vdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.voucherdate = newDate.toISOString().split('T')[0];
    }
});

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});

const selectedSupplier = ref(null);
const querySupplier = ref('');
const filteredSupplier = computed(() => {
    if (querySupplier.value === '') return data.data;

    return data.data.filter((n) => n.suppliername && n.suppliername.toLowerCase().includes(querySupplier.value.toLowerCase()));
});

const selectedPerson = ref(null);
const queryPerson = ref('');
const filteredPerson = computed(() => {
    if (queryPerson.value === '') return data.data;

    return data.data.filter((n) => n.contact_person && n.contact_person.toLowerCase().includes(queryPerson.value.toLowerCase()));
});

const search = () => {
    const params: Record<string, any> = {};
    if (selectedBranch.value) params.branch = selectedBranch.value.id;
    if (selectedSupplier.value) params.suppliercode = selectedSupplier.value.suppliercode;
    if (selectedPerson.value) params.contact_person = selectedPerson.value.contact_person;
    console.log('Search Params:', params);
    router.get(route('suppliersPayble.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const refresh = () => {
    router.get(route('suppliersPayble.index'), {}, { replace: true });
};

const selectedAccount = ref(null);
const queryAccount = ref('');
const filteredAccount = computed(() => {
    if (queryAccount.value === '') return props.accounts;

    return props.accounts.filter((n) => n.description && n.description.toLowerCase().includes(queryAccount.value.toLowerCase()));
});

const showDialogAdd = ref(false);
const supplierName = ref<string | null>(null);
const AccountsName = ref<string | null>(null);

const fetchData = async (suppliercode: string, suppliername: string, groupThreeDescription: string, branchId: number) => {
    try {
        form.reset();
        form.clearErrors();
        const url = route('suppliersPayble.FetchPayment', {
            supplier_payment: suppliercode,
            supplier_name: suppliername,
            group_three_description: groupThreeDescription,
            branch_id: branchId,
        });
        const res = await fetch(url);
        const data = await res.json();
        supplierName.value = data.supplier_name;
        AccountsName.value = data.group_three_description;
        form.branch_id = data.branch_id;
        form.acc_code = data.account_code?.accdisc || '';
        form.craccount = data.account_code?.cracc || '';
        form.payinv =
            data.supplier_payment?.map((s: any) => ({
                invicenumber: s.invicenumber,
                primeamt: Math.abs(Number(s.primeamt)),
                date: s.date,
                currency: s.currency,
                exchagerate: s.exchagerate,
            })) ?? [];
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const showDailog = async (suppliercode: string, suppliername: string, groupThreeDescription: string, branchId: number) => {
    if (suppliercode == null || suppliername === '' || groupThreeDescription === '' || branchId == null) {
        toast('error', {
            description: 'Supplier not found',
        });
        showDialogAdd.value = false;
    } else {
        await fetchData(suppliercode, suppliername, groupThreeDescription, branchId);
        showDialogAdd.value = true;
    }
};

const amountPaid = ref<number>(0);
const totalOutstanding = computed<number>(() => {
    return form.payinv.reduce((total, f) => total + Number(f.primeamt || 0), 0);
});

// Editable pay state
const selectedPay = ref<
    Array<{
        invicenumber: string;
        primeamt: number;
        date: string;
        currency: string;
        exchagerate: string;
    }>
>([]);

function editPay(pay: any) {
    if (!pay.primeamt || Number(pay.primeamt) <= 0) {
        toast('error', {
            description: 'Invalid pay amount! Amount must be greater than 0.',
        });
        return;
    }
    const exists = selectedPay.value.find((f) => f.invicenumber === pay.invicenumber);
    if (!exists) {
        selectedPay.value.push({
            invicenumber: pay.invicenumber,
            primeamt: pay.primeamt,
            date: pay.date,
            currency: pay.currency,
            exchagerate: pay.exchagerate,
        });
    }
}

const totalAfterEdit = computed(() => {
    const total = selectedPay.value.reduce((sum, f) => sum + Number(f.primeamt || 0), 0);
    return total;
});

function deletePayRow(index: number) {
    selectedPay.value.splice(index, 1);
}

watch(amountPaid, (val) => {
    if (val > totalOutstanding.value) {
        toast('error', {
            description: 'Amount Paid cannot exceed Total Outstanding',
        });
        amountPaid.value = totalOutstanding.value;
    }
    if (val < 0) {
        amountPaid.value = 0;
    }
});

watch(totalAfterEdit, (val) => {
    if (val > amountPaid.value) {
        toast('error', {
            description: 'Allocated amount cannot exceed Amount Paid',
        });

        // Last edited row adjust
        const last = selectedPay.value[selectedPay.value.length - 1];
        if (last) {
            const excess = val - amountPaid.value;
            last.primeamt = Math.max(Number(last.primeamt) - excess, 0);
        }
    }
});

function updatePayAmount(pay: any) {
    if (pay.primeamt > pay.original_amount) {
        toast('error', {
            description: 'Allocated amount cannot exceed invoice amount',
        });
        pay.primeamt = pay.original_amount;
    }
}

const submitInvoice = () => {
    if (!form.voucherdate) {
        toast('Validation Error', {
            description: 'Please select a valid payment date.',
        });
        return;
    }
    if (!selectedAccount.value?.accountcode) {
        toast('Validation Error', {
            description: 'Please select a valid account.',
        });
        return;
    }

    const action = route('suppliersPayble.store');
    form.account = selectedAccount.value ? selectedAccount.value.accountcode : '';
    form.amountPaid = amountPaid.value;

    if (!form.amountPaid) {
        toast('Validation Error', {
            description: 'Please enter a valid amount paid.',
        });
        return;
    }

    form.selectedPay = selectedPay.value;

    form.post(action, {
        onSuccess: () => {
            setTimeout(() => {
                showDialogAdd.value = false;
                form.reset();
                router.visit(route('suppliersPayble.manage'), {
                    //only: ['voucher_apalcs'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            if (Object.keys(errors).length) {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', { description: firstError });
            }
        },
    });
};

const manage = () => {
    router.visit(route('suppliersPayble.manage'), {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <Head title="Supplier Payable" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border px-4 md:min-h-min">
            <div class="flex flex-wrap items-center gap-4 py-4">
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="manage"
                        ><Plus></Plus> Manage Payable
                    </Button>
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedBranch">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Branch"
                                @input="queryBranch = $event.target.value"
                                :display-value="(c) => c?.branchname ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredBranch.length === 0 && queryBranch !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredBranch"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.branchname }}
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
                    <Combobox v-model="selectedSupplier">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Supplier"
                                @input="querySupplier = $event.target.value"
                                :display-value="(c) => c?.suppliername ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredSupplier.length === 0 && querySupplier !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredSupplier"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.suppliername }}
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
                    <Combobox v-model="selectedPerson">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Person"
                                @input="queryPerson = $event.target.value"
                                :display-value="(c) => c?.contact_person ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredPerson.length === 0 && queryPerson !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredPerson"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.contact_person }}
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
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Branch</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Contact Person</TableHead>
                            <TableHead>Payable Amount</TableHead>
                            <TableHead class="text-center">Create Payment</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(supplier, index) in data.data" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ supplier?.branch.branchname }}</TableCell>
                            <TableCell>{{ supplier?.suppliername }}</TableCell>
                            <TableCell>{{ supplier?.contact_person }}</TableCell>
                            <TableCell>{{ supplier?.payableamt }}</TableCell>

                            <TableCell class="text-center">
                                <Button
                                    class="m-[2px] cursor-pointer"
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        showDailog(
                                            supplier.suppliercode,
                                            supplier.suppliername,
                                            supplier.chart_o_f_account.group_three.description,
                                            supplier.branch_id,
                                        )
                                    "
                                >
                                    <ShieldCheck class="mr-1 h-4 w-4 text-red-500" />
                                </Button>
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
        <Dialog v-model:open="showDialogAdd">
            <DialogContent
                class="flex max-h-[85vh] flex-col overflow-hidden rounded-xl border-0 bg-white shadow-2xl sm:max-w-3xl lg:max-w-5xl dark:bg-gray-900"
            >
                <!-- HEADER - Fixed at top -->
                <DialogHeader class="shrink-0 border-b border-gray-100 px-6 pt-6 pb-4 dark:border-gray-800">
                    <DialogTitle class="text-xl font-semibold text-gray-900 dark:text-white"> Create Supplier Payment </DialogTitle>
                    <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Record payment against supplier invoices
                    </DialogDescription>
                </DialogHeader>

                <!-- SCROLLABLE CONTENT - Flexible height -->
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <div class="space-y-6">
                        <!-- PAYMENT DETAILS CARD -->
                        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                            <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                                Payment Details
                                <span class="mt-1 block text-xs font-normal text-gray-500 dark:text-gray-400">
                                    Complete the required payment information
                                </span>
                            </h3>

                            <div class="space-y-5">
                                <!-- DATE AND ACCOUNT -->
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Payment Date <span class="ml-0.5 text-red-500">*</span>
                                        </Label>
                                        <VueDatePicker
                                            v-model="vdate"
                                            :max-date="maxDate"
                                            :format="'yyyy-MM-dd'"
                                            :enable-time-picker="false"
                                            placeholder="Select Date"
                                            auto-apply
                                        />
                                        <p v-if="form.errors.voucherdate" class="mt-1 text-sm text-red-600">{{ form.errors.voucherdate }}</p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            A/C Head Payable
                                            <span class="ml-0.5 text-red-500">*</span>
                                        </Label>

                                        <Combobox v-model="selectedAccount">
                                            <div class="relative">
                                                <ComboboxInput
                                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                                    placeholder="Select account..."
                                                    :display-value="(account) => account?.description ?? ''"
                                                    @input="queryAccount = $event.target.value"
                                                />
                                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                                </ComboboxButton>

                                                <!-- Options -->
                                                <ComboboxOptions
                                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                                >
                                                    <div
                                                        v-if="filteredAccount.length === 0 && queryAccount !== ''"
                                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                                    >
                                                        Nothing found.
                                                    </div>

                                                    <ComboboxOption
                                                        v-for="n in filteredAccount"
                                                        :key="n.id"
                                                        :value="n"
                                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                                        v-slot="{ selected }"
                                                    >
                                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                            {{ n.description }}</span
                                                        >
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

                                        <p v-if="form.errors.account" class="mt-1 text-sm text-red-600">{{ form.errors.account }}</p>
                                    </div>
                                </div>

                                <!-- AMOUNT -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Amount Paid
                                        <span class="ml-0.5 text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute top-1/2 left-3 -translate-y-1/2 transform text-gray-500 dark:text-gray-400">৳</span>
                                        <input
                                            type="number"
                                            v-model.number="amountPaid"
                                            :max="totalOutstanding"
                                            placeholder="0.00"
                                            class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pr-3 pl-8 text-right text-lg font-semibold text-gray-900 transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-blue-600"
                                        />
                                    </div>
                                </div>

                                <!-- SUPPLIER INFO CARD -->
                                <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                                    <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                                        <div>
                                            <span class="mb-1 block text-gray-600 dark:text-gray-400">Supplier</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ supplierName }}</span>
                                        </div>
                                        <div>
                                            <span class="mb-1 block text-gray-600 dark:text-gray-400">Expense Account</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ AccountsName }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- NOTES -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300"> Payment Notes </label>
                                    <Textarea
                                        v-model="form.notes"
                                        placeholder="Add any payment notes..."
                                        class="w-full resize-none rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-900 transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-blue-600"
                                    ></Textarea>
                                </div>
                            </div>
                        </div>

                        <!-- UNPAID INVOICES TABLE -->
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                            <div class="border-b border-gray-100 bg-gray-50 px-5 py-3.5 dark:border-gray-800 dark:bg-gray-800/50">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Unpaid Invoices</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead
                                        class="bg-gray-50 text-xs font-medium tracking-wider text-gray-500 uppercase dark:bg-gray-800/50 dark:text-gray-400"
                                    >
                                        <tr>
                                            <th class="px-5 py-3 text-left font-medium">Invoice #</th>
                                            <th class="px-5 py-3 text-left font-medium">Date</th>
                                            <th class="px-5 py-3 text-center font-medium">Currency</th>
                                            <th class="px-5 py-3 text-center font-medium">Rate</th>
                                            <th class="px-5 py-3 text-right font-medium">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        <tr
                                            v-for="(inv, index) in form.payinv"
                                            :key="index"
                                            @click="editPay(inv)"
                                            class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                        >
                                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ inv.invicenumber }}</td>
                                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ inv.date }}</td>
                                            <td class="px-5 py-3.5 text-center text-gray-600 dark:text-gray-400">{{ inv.currency }}</td>
                                            <td class="px-5 py-3.5 text-center text-gray-600 dark:text-gray-400">{{ inv.exchagerate }}</td>
                                            <td class="px-5 py-3.5 text-right font-semibold text-gray-900 dark:text-white">{{ inv.primeamt }}</td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-t border-gray-100 bg-gray-50 px-5 py-3.5 dark:border-gray-800 dark:bg-gray-800/50">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Outstanding</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{
                                        form.payinv.reduce((total, f) => total + Number(f.primeamt || 0), 0).toFixed(2)
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ALLOCATION SECTION - This was hidden before -->
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                            <div class="border-b border-gray-100 bg-gray-50 px-5 py-3.5 dark:border-gray-800 dark:bg-gray-800/50">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Allocated Invoices</h3>
                            </div>
                            <div v-if="selectedPay.length">
                                <table class="w-full">
                                    <thead
                                        class="bg-gray-50 text-xs font-medium tracking-wider text-gray-500 uppercase dark:bg-gray-800/50 dark:text-gray-400"
                                    >
                                        <tr>
                                            <th class="px-5 py-3 text-left font-medium">Invoice #</th>
                                            <th class="px-5 py-3 text-left font-medium">Date</th>
                                            <th class="px-5 py-3 text-center font-medium">Currency</th>
                                            <th class="px-5 py-3 text-center font-medium">Rate</th>
                                            <th class="px-5 py-3 text-right font-medium">Amount</th>
                                            <th class="px-5 py-3 text-right font-medium">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        <tr
                                            v-for="(pay, index) in selectedPay"
                                            :key="index"
                                            class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                        >
                                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ pay.invicenumber }}</td>
                                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ pay.date }}</td>
                                            <td class="px-5 py-3.5 text-center text-gray-600 dark:text-gray-400">{{ pay.currency }}</td>
                                            <td class="px-5 py-3.5 text-center text-gray-600 dark:text-gray-400">{{ pay.exchagerate }}</td>
                                            <td class="px-5 py-3.5 text-right font-semibold text-gray-900 dark:text-white">
                                                <input
                                                    type="number"
                                                    v-model.number="pay.primeamt"
                                                    @input="updatePayAmount(pay)"
                                                    class="mt-1 w-full rounded border border-gray-300 px-2 py-1 shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none md:w-32"
                                                />
                                            </td>
                                            <td class="px-5 py-3.5 text-right font-semibold text-gray-900 dark:text-white">
                                                <Button
                                                    variant="destructive"
                                                    @click="deletePayRow(index)"
                                                    class="flex cursor-pointer items-center gap-1"
                                                >
                                                    <Trash2 class="h-4 w-4" /> Delete
                                                </Button>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="p-8 text-center">
                                <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No invoices allocated yet</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Select invoices from above to allocate payment</p>
                            </div>

                            <div class="border-t border-gray-100 bg-gray-50 px-5 py-3.5 dark:border-gray-800 dark:bg-gray-800/50">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allocated Total</span>
                                    <div class="relative">
                                        <span class="absolute top-1/2 left-3 -translate-y-1/2 transform text-gray-500 dark:text-gray-400">৳</span>
                                        <input
                                            readonly
                                            :value="totalAfterEdit.toFixed(2)"
                                            class="w-32 rounded-lg border border-gray-300 bg-gray-50 py-2 pr-3 pl-8 text-right font-semibold text-gray-900 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER - Fixed at bottom -->
                <DialogFooter class="flex items-center justify-between">
                    <!-- LEFT : CLOSE -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="flex items-center gap-2">
                            <X class="h-4 w-4" />
                            Close
                        </Button>
                    </DialogClose>

                    <!-- RIGHT : SAVE -->
                    <Button
                        :disabled="form.processing"
                        @click="submitInvoice"
                        class="flex items-center gap-2 bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                    >
                        <template v-if="form.processing">Saving...</template>
                        <template v-else><Save class="h-4 w-4" />Save Payment</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
