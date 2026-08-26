<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import { FileText, Pencil, Plus, RefreshCcw, Search, ShieldCheck, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Payment Voucher', href: '/voucherheader/paymentVoucher' }];

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

export interface VoucherHeader {
    vouchernumber: string;
    voucherdate: string;
    referance: string;
    yearname: number;
    monthname: number;
    status: string;
    branch: {
        id: number;
        branchname: string;
    };
    user: {
        name: string;
    };
}

const props = defineProps<{
    voucherheader: Paginated<VoucherHeader>;
    filters: { name?: string };
    branch: Array<{ id: number; branchname: string }>;
    draccountcode: Array<{ accountcode: string; description: string }>;
    craccountcode: Array<{ accountcode: string; description: string }>;
    allvoucher: Array<{ vouchernumber: string; voucherdate: string; referance: string; yearname: number; monthname: number; status: string }>;
}>();

const data = props.voucherheader;

const vdate = ref<string | null>(null);

const maxDate = today(getLocalTimeZone());

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});

const selectedDetailDebit = ref<any>(null);
const queryDetailDebit = ref('');
const filteredDetailDebit = computed(() => {
    if (queryDetailDebit.value === '') return props.draccountcode;

    return props.draccountcode.filter((n) => n.description && n.description.toLowerCase().includes(queryDetailDebit.value.toLowerCase()));
});

const selectedDetailCredit = ref<any>(null);
const queryDetailCredit = ref('');
const filteredDetailCredit = computed(() => {
    if (queryDetailCredit.value === '') return props.craccountcode;
    return props.craccountcode.filter((n) => n.description && n.description.toLowerCase().includes(queryDetailCredit.value.toLowerCase()));
});
const newCreditParticular = ref('');
const newCreditAmount = ref<number | string>('');
const editingCreditIndex = ref(-1);
const creditDetails = ref<Array<{ accountcode: string; creditAccName: string; particular: string; amount: number }>>([]);
const creditDetailsTotal = computed(() => creditDetails.value.reduce((sum, d) => sum + d.amount, 0));
const creditAccountBalance = ref<number | null>(null);

const fetchCreditBalance = async (accountcode: string) => {
    try {
        const response = await axios.get(route('voucherheader.balance', accountcode));
        creditAccountBalance.value = response.data.balance;
    } catch (error) {
        console.error('Error fetching balance:', error);
        creditAccountBalance.value = null;
    }
};

watch(selectedDetailCredit, (newVal) => {
    if (newVal && newVal.accountcode) {
        fetchCreditBalance(newVal.accountcode);
    } else {
        creditAccountBalance.value = null;
    }
});

const addCreditDetail = () => {
    if (!selectedDetailCredit.value) {
        toast('Validation Error', { description: 'Please select a credit account' });
        return;
    }
    if (!newCreditParticular.value.trim()) {
        toast('Validation Error', { description: 'Please write notes' });
        return;
    }
    if (!newCreditAmount.value || Number(newCreditAmount.value) <= 0) {
        toast('Validation Error', { description: 'Please enter a valid amount' });
        return;
    }
    if (creditAccountBalance.value !== null && creditAccountBalance.value <= 0) {
        toast('Validation Error', { description: 'Insufficient balance in selected credit account' });
        return;
    }
    if (editingCreditIndex.value >= 0) {
        creditDetails.value[editingCreditIndex.value] = {
            accountcode: selectedDetailCredit.value.accountcode,
            creditAccName: selectedDetailCredit.value.description,
            particular: newCreditParticular.value.trim(),
            amount: Number(newCreditAmount.value),
        };
        editingCreditIndex.value = -1;
    } else {
        creditDetails.value.push({
            accountcode: selectedDetailCredit.value.accountcode,
            creditAccName: selectedDetailCredit.value.description,
            particular: newCreditParticular.value.trim(),
            amount: Number(newCreditAmount.value),
        });
    }
    newCreditParticular.value = '';
    newCreditAmount.value = '';
    selectedDetailCredit.value = null;
};

const editCreditDetail = (index: number) => {
    const detail = creditDetails.value[index];
    selectedDetailCredit.value = props.craccountcode.find((acc) => acc.accountcode === detail.accountcode) || null;
    newCreditParticular.value = detail.particular;
    newCreditAmount.value = detail.amount;
    editingCreditIndex.value = index;
};

const deleteCreditDetail = (index: number) => {
    creditDetails.value.splice(index, 1);
    if (editingCreditIndex.value === index) {
        editingCreditIndex.value = -1;
        newCreditParticular.value = '';
        newCreditAmount.value = '';
        selectedDetailCredit.value = null;
    }
};

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    vouchernumber: '',
    voucherdate: '',
    referance: '',
    branch_id: '',
    notes: '',
    debitAcc: '',
    debitAmt: '',
    creditAcc: '',
    creditAmt: '',
});

watch(vdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.voucherdate = newDate.toISOString().split('T')[0];
    }
});
const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    details.value = [];
    creditDetails.value = [];
    newParticular.value = '';
    newAmount.value = '';
    newCreditParticular.value = '';
    newCreditAmount.value = '';
    selectedDetailDebit.value = null;
    selectedDetailCredit.value = null;
    editingIndex.value = -1;
    editingCreditIndex.value = -1;
    showDialog.value = true;
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('voucherheader.paymentUpdate', form.id) : route('voucherheader.paymentStore');
    const method = isEditMode.value ? 'put' : 'post';

    if (!form.referance) {
        toast('Validation Error', { description: 'Please write referance' });
        return;
    }
    if (!selectedBranch.value?.id) {
        toast('Validation Error', { description: 'Please select a branch' });
        return;
    }
    if (details.value.length === 0) {
        toast('Validation Error', { description: 'Please add at least one debit account detail' });
        return;
    }
    if (creditDetails.value.length === 0) {
        toast('Validation Error', { description: 'Please add at least one credit account detail' });
        return;
    }
    if (Math.abs(detailsTotal.value - creditDetailsTotal.value) > 0.01) {
        toast('Validation Error', { description: 'Total Debit and Total Credit must be equal' });
        return;
    }
    if (!form.notes) {
        toast('Validation Error', { description: 'Please write notes' });
        return;
    }

    form.branch_id = selectedBranch.value.id;
    form.debitAmt = detailsTotal.value;
    form.creditAmt = -creditDetailsTotal.value;

    form.transform((data) => ({
        ...data,
        details: details.value,
        creditDetails: creditDetails.value,
    }))[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                details.value = [];
                creditDetails.value = [];
                showDialog.value = false;
                router.visit(route('voucherheader.payment'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            form.reset();
            details.value = [];
            creditDetails.value = [];
            showDialog.value = false;
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const flash = usePage().props.flash;
            if (flash?.error) {
                toast('error', { description: flash.error + firstError });
            }
        },
    });
};

const onEdit = async (payment: number) => {
    try {
        const response = await axios.get(route('voucherheader.paymentEdit', payment));
        const voucher = response.data;
        // Reset
        form.reset();
        form.clearErrors();
        // Header fields
        form.id = voucher.id;
        form.referance = voucher.referance;
        form.voucherdate = voucher.voucherdate;
        form.notes = voucher.notes;
        form.branch_id = voucher.branch_id;
        vdate.value = new Date(voucher.voucherdate);
        selectedBranch.value = voucher.branch;
        const debitRows = voucher.voucherdt.filter((d: any) => d.primeamt > 0);
        const creditRows = voucher.voucherdt.filter((d: any) => d.primeamt < 0);

        // Load all debit rows as details
        details.value = debitRows.map((d: any) => ({
            accountcode: d.accountcode,
            debitAccName: d.chart_o_f_account ? d.chart_o_f_account.description : '',
            particular: d.notes || '',
            amount: Number(d.primeamt),
        }));

        // Load all credit rows as creditDetails
        creditDetails.value = creditRows.map((d: any) => ({
            accountcode: d.accountcode,
            creditAccName: d.chart_o_f_account ? d.chart_o_f_account.description : '',
            particular: d.notes || '',
            amount: Math.abs(Number(d.primeamt)),
        }));

        form.debitAmt = detailsTotal.value;
        newParticular.value = '';
        newAmount.value = '';
        selectedDetailDebit.value = null;
        editingIndex.value = -1;

        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        toast('Error', {
            description: 'Unable to load voucher data',
        });
    }
};

const deleteForm = useForm({});

// Particulars detail list
const newParticular = ref('');
const newAmount = ref<number | string>('');
const editingIndex = ref(-1);
const details = ref<Array<{ accountcode: string; debitAccName: string; particular: string; amount: number }>>([]);

const addDetail = () => {
    if (!selectedDetailDebit.value) {
        toast('Validation Error', { description: 'Please select a debit account' });
        return;
    }
    if (!newParticular.value.trim()) {
        toast('Validation Error', { description: 'Please write notes' });
        return;
    }
    if (!newAmount.value || Number(newAmount.value) <= 0) {
        toast('Validation Error', { description: 'Please enter a valid amount' });
        return;
    }
    if (editingIndex.value >= 0) {
        details.value[editingIndex.value] = {
            accountcode: selectedDetailDebit.value.accountcode,
            debitAccName: selectedDetailDebit.value.description,
            particular: newParticular.value.trim(),
            amount: Number(newAmount.value),
        };
        editingIndex.value = -1;
    } else {
        details.value.push({
            accountcode: selectedDetailDebit.value.accountcode,
            debitAccName: selectedDetailDebit.value.description,
            particular: newParticular.value.trim(),
            amount: Number(newAmount.value),
        });
    }
    newParticular.value = '';
    newAmount.value = '';
    selectedDetailDebit.value = null;
};

const editDetail = (index: number) => {
    const detail = details.value[index];
    selectedDetailDebit.value = props.draccountcode.find((acc) => acc.accountcode === detail.accountcode) || null;
    newParticular.value = detail.particular;
    newAmount.value = detail.amount;
    editingIndex.value = index;
};

const deleteDetail = (index: number) => {
    details.value.splice(index, 1);
    if (editingIndex.value === index) {
        editingIndex.value = -1;
        newParticular.value = '';
        newAmount.value = '';
        selectedDetailDebit.value = null;
    }
};

const detailsTotal = computed(() => details.value.reduce((sum, d) => sum + d.amount, 0));

const onConfirm = async (payment: number) => {
    if (!confirm('Are you sure you want to confirm this this voucher')) return;

    if (deleteForm.processing) return;

    deleteForm.put(`/voucherheader/${payment}/paymentStatus`, {
        onSuccess: () => { },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const onReport = async (vhd: number) => {
    const url = route('voucherheader.singleReport', {
        voucherID: vhd,
    });

    window.open(url, '_blank');
};


const selectedVoucher = ref(null);

const queryVoucher = ref('');
const filteredVoucher = computed(() => {
    if (queryVoucher.value === '') return props.allvoucher;

    return props.allvoucher.filter((n) => n.vouchernumber && n.vouchernumber.toLowerCase().includes(queryVoucher.value.toLowerCase()));
});

const selectedDate = ref(null);
const queryDate = ref('');
const filteredDate = computed(() => {
    const filtered = queryDate.value
        ? props.allvoucher.filter((v) => v.voucherdate?.toLowerCase().includes(queryDate.value.toLowerCase()))
        : props.allvoucher;

    // unique date list
    const map = new Map();
    filtered.forEach((v) => {
        if (v.voucherdate && !map.has(v.voucherdate)) {
            map.set(v.voucherdate, v.voucherdate);
        }
    });

    return Array.from(map.values());
});

const selectedRef = ref(null);
const queryRef = ref('');
const filteredRef = computed(() => {
    const filtered = queryRef.value
        ? props.allvoucher.filter((v) => v.referance?.toLowerCase().includes(queryRef.value.toLowerCase()))
        : props.allvoucher;

    // unique referance list
    const map = new Map();
    filtered.forEach((v) => {
        if (v.referance && !map.has(v.referance)) {
            map.set(v.referance, v.referance);
        }
    });

    return Array.from(map.values());
});

const selectedYear = ref(null);
const queryYear = ref('');

const filteredYear = computed(() => {
    // filter vouchers based on queryYear
    const filtered = queryYear.value ? props.allvoucher.filter((v) => v.yearname?.includes(queryYear.value)) : props.allvoucher;

    // return unique year names
    return [...new Set(filtered.map((v) => v.yearname).filter(Boolean))];
});

const selectedMonth = ref(null);
const queryMonth = ref('');

const filteredMonth = computed(() => {
    // filter vouchers based on queryMonth
    const filtered = queryMonth.value ? props.allvoucher.filter((v) => v.monthname?.includes(queryMonth.value)) : props.allvoucher;

    // return unique month names
    return [...new Set(filtered.map((v) => v.monthname).filter(Boolean))];
});

const selectedStatus = ref(null);
const queryStatus = ref('');
const filteredStatus = computed(() => {
    const filtered = queryStatus.value
        ? props.allvoucher.filter((v) => v.status?.toLowerCase().includes(queryStatus.value.toLowerCase()))
        : props.allvoucher;

    // unique status list
    const map = new Map();
    filtered.forEach((v) => {
        if (v.status && !map.has(v.status)) {
            map.set(v.status, v.status);
        }
    });

    return Array.from(map.values());
});

const search = () => {
    const params: Record<string, any> = {};

    if (selectedVoucher.value) params.vouchernumber = selectedVoucher.value.vouchernumber;
    if (selectedDate.value) params.voucherdate = selectedDate.value.voucherdate;
    if (selectedBranch.value) params.branch_id = selectedBranch.value.id;
    if (selectedRef.value) params.referance = selectedRef.value;
    if (selectedYear.value) params.yearname = selectedYear.value;
    if (selectedMonth.value) params.monthname = selectedMonth.value;
    if (selectedStatus.value) params.status = selectedStatus.value;

    router.get(route('voucherheader.payment'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('voucherheader.payment'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('voucherheader.payment'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Payment Voucher" />
        <div class="app-page">
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900">
                <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm"
                    @click="showDailogCreate">
                    <Plus></Plus> Create
                </Button>
                <Combobox v-model="selectedVoucher">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Voucher No" @input="queryVoucher = $event.target.value"
                            :display-value="(c) => c?.vouchernumber ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredVoucher.length === 0 && queryVoucher !== ''"
                                class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption v-for="one in filteredVoucher" :key="one.id" :value="one"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ one.vouchernumber }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedDate">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Voucher Date" @input="queryDate = $event.target.value"
                            :display-value="(v) => v ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredDate.length === 0 && queryDate !== ''"
                                class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption v-for="one in filteredDate" :key="one" :value="one"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ one }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedBranch">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm" placeholder="Select Branch"
                            @input="queryBranch = $event.target.value" :display-value="(c) => c?.branchname ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption v-for="one in filteredBranch" :key="one.id" :value="one"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ one.branchname }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedRef">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm" placeholder="Select Referance"
                            @input="queryRef = $event.target.value" :display-value="(v) => v ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredRef.length === 0 && queryRef !== ''"
                                class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption v-for="ref in filteredRef" :key="ref" :value="ref"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ ref }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedYear">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm" placeholder="Select Year"
                            @input="queryYear = $event.target.value" :display-value="(v) => v ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredYear.length === 0 && queryYear !== ''"
                                class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption v-for="year in filteredYear" :key="year" :value="year"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ year }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedMonth">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm" placeholder="Select Month"
                            @input="queryMonth = $event.target.value" :display-value="(v) => v ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredMonth.length === 0 && queryMonth !== ''"
                                class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption v-for="month in filteredMonth" :key="month" :value="month"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ month }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedStatus">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput class="w-full rounded-md border px-3 py-2 text-sm" placeholder="Select Status"
                            @input="queryStatus = $event.target.value" :display-value="(v) => v ?? ''" />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredStatus.length === 0 && queryStatus !== ''"
                                class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption v-for="status in filteredStatus" :key="status" :value="status"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }">
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ status }}
                                </span>
                                <span v-if="selected"
                                    class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Button variant="outline" size="sm" @click="search">
                    <Search></Search> Search
                </Button>
                <Button variant="outline" size="sm" @click="refresh">
                    <RefreshCcw></RefreshCcw> Refresh
                </Button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Payment Voucher List</h2>
                    <p class="text-sm text-gray-500">Manage all Payment Voucher from here.</p>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Voucher No</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Branch</TableHead>
                            <TableHead>Reference</TableHead>
                            <TableHead>Year</TableHead>
                            <TableHead>Period</TableHead>
                            <TableHead>Created By</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                            <TableHead>Reports</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(vhd, index) in data.data" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ vhd.vouchernumber }}</TableCell>
                            <TableCell>{{ vhd.voucherdate }}</TableCell>
                            <TableCell>{{ vhd.branch.branchname }}</TableCell>
                            <TableCell>{{ vhd.referance }}</TableCell>
                            <TableCell>{{ vhd.yearname }}</TableCell>
                            <TableCell>{{ vhd.monthname }}</TableCell>
                            <TableCell>{{ vhd.user.name }}</TableCell>
                            <TableCell>{{ vhd.status }}</TableCell>

                            <TableCell class="text-center">
                                <div v-if="vhd.status == 'Balanced'" class="group relative inline-block">
                                    <Button class="cursor-pointer" size="sm" variant="outline"
                                        @click="onConfirm(vhd.id)">
                                        <ShieldCheck class="text-green-500"></ShieldCheck>
                                    </Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block">
                                        Confirm
                                    </span>
                                </div>
                                <div v-if="vhd.status !== 'Posted'" class="group relative inline-block">
                                    <Button class="cursor-pointer" size="sm" variant="outline" @click="onEdit(vhd.id)">
                                        <SquarePen class="text-indigo-500"></SquarePen>
                                    </Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block">
                                        Edit
                                    </span>
                                </div>
                            </TableCell>

                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button class="cursor-pointer" size="sm" variant="outline"
                                        @click="onReport(vhd.id)">
                                        <FileText class="text-red-500"></FileText>
                                    </Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block">
                                        Report
                                    </span>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div
                    class="flex flex-col gap-4 border-t bg-gray-50 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    <!-- Left -->
                    <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <label>Show</label>

                            <select v-model="perPage" @change="changePerPage"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500">
                                <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                    {{ size }}
                                </option>
                            </select>
                        </div>

                        <span> Showing {{ voucherheader.from }} to {{ voucherheader.to }} of {{ voucherheader.total }}
                            results </span>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap justify-center gap-2 md:justify-end">
                        <Button v-for="(link, index) in data.links" :key="index" :disabled="!link.url" size="sm"
                            variant="outline" @click="goToPage(link.url)" :class="[
                                link.active ? 'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]">
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent
                class="max-h-[90vh] w-[95vw] max-w-2xl overflow-y-auto rounded-2xl border border-gray-200 bg-white p-4 shadow-xl sm:max-w-2xl sm:p-6 dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <DialogHeader class="space-y-1 border-b pb-4">
                    <DialogTitle class="text-xl font-semibold tracking-wide">
                        {{ isEditMode ? 'Edit Payment Voucher' : 'Create Payment Voucher' }}
                    </DialogTitle>
                    <DialogDescription class="text-sm text-gray-500">
                        {{ isEditMode ?
                            'Modify the information and click Update.' : 'Fill out the form to add a new Payment Voucher.'
                        }}
                    </DialogDescription>
                </DialogHeader>
                <!-- Body -->
                <!-- Referance -->
                <div>
                    <Label for="referance" class="text-sm font-medium">Referance <span
                            class="text-red-500">*</span></Label>
                    <Textarea v-model="form.referance" class="mt-1 w-full"
                        placeholder="write your voucher referance"></Textarea>
                    <p v-if="form.errors.referance" class="mt-1 text-sm text-red-600">{{ form.errors.referance }}</p>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Branch -->
                    <div>
                        <Label for="branch_id" class="text-sm font-medium">Select Branch<span
                                class="text-red-500">*</span></Label>
                        <Combobox v-model="selectedBranch">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Branch" @input="queryBranch = $event.target.value"
                                    :display-value="(c) => (c ? c.branchname : '')" />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900">
                                    <div v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>
                                    <ComboboxOption v-for="branch in filteredBranch" :key="branch.id" :value="branch"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white">
                                        {{ branch.branchname }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>

                        <p v-if="form.errors.branch_id" class="mt-1 text-sm text-red-600">{{ form.errors.branch_id }}
                        </p>
                    </div>

                    <!-- Voucher Date -->
                    <div>
                        <Label for="voucherdate" class="text-sm font-medium">Select Date<span
                                class="text-red-500">*</span></Label>
                        <VueDatePicker v-model="vdate" :max-date="maxDate" :format="'yyyy-MM-dd'"
                            :enable-time-picker="false" placeholder="Select Date" auto-apply />
                        <p v-if="form.errors.voucherdate" class="mt-1 text-sm text-red-600">{{ form.errors.voucherdate
                        }}</p>
                    </div>
                </div>
                <!-- Detail Input -->
                <div
                    class="rounded-lg border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700/50 dark:bg-gray-800/50">
                    <h3 class="mb-4 text-sm font-semibold text-indigo-900 dark:text-indigo-300">Add Debit Details</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                        <div class="md:col-span-12">
                            <Label class="text-sm font-medium">Debit Account<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selectedDetailDebit">
                                <div class="relative mt-1">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select account..."
                                        :display-value="(debit) => debit?.description ?? ''"
                                        @input="queryDetailDebit = $event.target.value" />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900">
                                        <div v-if="filteredDetailDebit.length === 0 && queryDetailDebit !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none">
                                            Nothing found.
                                        </div>
                                        <ComboboxOption v-for="n in filteredDetailDebit" :key="n.id" :value="n"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }">
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ n.description }}</span>
                                            <span v-if="selected"
                                                class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                <CheckIcon class="h-5 w-5" />
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div class="md:col-span-6 lg:col-span-7">
                            <Label class="text-sm font-medium">Notes<span class="text-red-500">*</span></Label>
                            <Textarea v-model="newParticular" class="mt-1 w-full" placeholder="Write notes"
                                rows="2"></Textarea>
                        </div>
                        <div class="mt-3 md:col-span-4 lg:col-span-3">
                            <Label class="text-sm font-medium">Amount<span class="text-red-500">*</span></Label>
                            <Input type="number" v-model="newAmount" class="mt-1 w-full" placeholder="Enter Amount" />
                        </div>
                        <div class="mt-3 flex items-center md:col-span-2 lg:col-span-2">
                            <Button @click="addDetail" size="sm" class="w-full">
                                <Plus class="mr-1 h-4 w-4" />{{ editingIndex >= 0 ? 'Update' : 'Add' }}
                            </Button>
                        </div>
                    </div>
                </div>
                <!-- Particulars Table -->
                <div v-if="details.length"
                    class="mt-3 max-h-48 overflow-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12">SL</TableHead>
                                <TableHead>Debit Account</TableHead>
                                <TableHead>Notes</TableHead>
                                <TableHead class="text-right">Amount</TableHead>
                                <TableHead class="w-24 text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(row, idx) in details" :key="idx">
                                <TableCell class="text-xs">{{ idx + 1 }}</TableCell>
                                <TableCell class="text-sm">{{ row.debitAccName }}</TableCell>
                                <TableCell class="text-sm">{{ row.particular }}</TableCell>
                                <TableCell class="text-right text-sm font-medium">{{ row.amount.toLocaleString() }}
                                </TableCell>
                                <TableCell class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <Button variant="ghost" size="sm" class="h-7 w-7 p-0" @click="editDetail(idx)">
                                            <Pencil class="h-3.5 w-3.5 text-indigo-500" />
                                        </Button>
                                        <Button variant="ghost" size="sm" class="h-7 w-7 p-0"
                                            @click="deleteDetail(idx)">
                                            <Trash2 class="h-3.5 w-3.5 text-red-500" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div
                        class="flex items-center justify-between border-t border-indigo-200 bg-indigo-50 px-4 py-3 dark:border-indigo-800 dark:bg-indigo-900/30">
                        <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">Total Debit
                            Amount</span>
                        <span class="text-base font-bold text-indigo-800 dark:text-indigo-200">৳ {{
                            detailsTotal.toLocaleString() }}</span>
                    </div>
                </div>
                <div v-else
                    class="mt-3 rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-400 dark:border-gray-600">
                    No particulars added yet. Use the fields above to add.
                </div>

                <!-- Detail Credit Input -->
                <div
                    class="rounded-lg border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700/50 dark:bg-gray-800/50">
                    <h3 class="mb-4 text-sm font-semibold text-emerald-900 dark:text-emerald-300">Add Credit Details
                    </h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                        <div class="md:col-span-12">
                            <div class="flex items-center justify-between">
                                <Label class="text-sm font-medium">Credit Account<span
                                        class="text-red-500">*</span></Label>
                                <span v-if="creditAccountBalance !== null" class="text-xs font-semibold"
                                    :class="creditAccountBalance >= 0 ? 'text-green-600' : 'text-red-600'">
                                    Balance: ৳ {{ creditAccountBalance.toLocaleString() }}
                                </span>
                            </div>
                            <Combobox v-model="selectedDetailCredit">
                                <div class="relative mt-1">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select account..."
                                        :display-value="(credit) => credit?.description ?? ''"
                                        @input="queryDetailCredit = $event.target.value" />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900">
                                        <div v-if="filteredDetailCredit.length === 0 && queryDetailCredit !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none">
                                            Nothing found.
                                        </div>
                                        <ComboboxOption v-for="n in filteredDetailCredit" :key="n.id" :value="n"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }">
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ n.description }}</span>
                                            <span v-if="selected"
                                                class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                <CheckIcon class="h-5 w-5" />
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div class="md:col-span-6 lg:col-span-7">
                            <Label class="text-sm font-medium">Notes<span class="text-red-500">*</span></Label>
                            <Textarea v-model="newCreditParticular" class="mt-1 w-full" placeholder="Write notes"
                                rows="2"></Textarea>
                        </div>
                        <div class="mt-3 md:col-span-4 lg:col-span-3">
                            <Label class="text-sm font-medium">Amount<span class="text-red-500">*</span></Label>
                            <Input type="number" v-model="newCreditAmount" class="mt-1 w-full"
                                placeholder="Enter Amount" />
                        </div>
                        <div class="mt-3 flex items-center md:col-span-2 lg:col-span-2">
                            <Button @click="addCreditDetail" size="sm" class="w-full">
                                <Plus class="mr-1 h-4 w-4" />{{ editingCreditIndex >= 0 ? 'Update' : 'Add' }}
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Credit Particulars Table -->
                <div v-if="creditDetails.length"
                    class="mt-3 max-h-48 overflow-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12">SL</TableHead>
                                <TableHead>Credit Account</TableHead>
                                <TableHead>Notes</TableHead>
                                <TableHead class="text-right">Amount</TableHead>
                                <TableHead class="w-24 text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(row, idx) in creditDetails" :key="idx">
                                <TableCell class="text-xs">{{ idx + 1 }}</TableCell>
                                <TableCell class="text-sm">{{ row.creditAccName }}</TableCell>
                                <TableCell class="text-sm">{{ row.particular }}</TableCell>
                                <TableCell class="text-right text-sm font-medium">{{ row.amount.toLocaleString() }}
                                </TableCell>
                                <TableCell class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <Button variant="ghost" size="sm" class="h-7 w-7 p-0"
                                            @click="editCreditDetail(idx)">
                                            <Pencil class="h-3.5 w-3.5 text-indigo-500" />
                                        </Button>
                                        <Button variant="ghost" size="sm" class="h-7 w-7 p-0"
                                            @click="deleteCreditDetail(idx)">
                                            <Trash2 class="h-3.5 w-3.5 text-red-500" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div
                        class="flex items-center justify-between border-t border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-800 dark:bg-emerald-900/30">
                        <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Total Credit
                            Amount</span>
                        <span class="text-base font-bold text-emerald-800 dark:text-emerald-200">৳ {{
                            creditDetailsTotal.toLocaleString() }}</span>
                    </div>
                </div>
                <div v-else
                    class="mt-3 rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-400 dark:border-gray-600">
                    No credit particulars added yet.
                </div>

                <!-- Notes -->
                <div>
                    <Label for="notes" class="text-sm font-medium">Notes <span class="text-red-500">*</span></Label>
                    <Textarea v-model="form.notes" class="mt-1 w-full" placeholder="write voucher notes"></Textarea>
                    <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-8 flex justify-end gap-3 border-t pt-4">
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
    </AppLayout>
</template>
