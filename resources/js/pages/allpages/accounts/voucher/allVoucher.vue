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
import { router, useForm, usePage } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { BookCheck, FileText, RefreshCcw, Search, ShieldCheck } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'All Voucher List', href: '/voucherheader/allVoucher' }];

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
    accountcode: Array<{ accountcode: string; description: string }>;
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

const selectedDebit = ref(null);
const queryDebit = ref('');
const filteredDebit = computed(() => {
    if (queryDebit.value === '') return props.accountcode;

    return props.accountcode.filter((n) => n.description && n.description.toLowerCase().includes(queryDebit.value.toLowerCase()));
});

const selectedCredit = ref(null);
const queryCredit = ref('');
const filteredCredit = computed(() => {
    if (queryCredit.value === '') return props.accountcode;

    return props.accountcode.filter((n) => n.description && n.description.toLowerCase().includes(queryCredit.value.toLowerCase()));
});

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

const submit = () => {
    const action = isEditMode.value && form.id ? route('voucherheader.allvoucherUpdate', form.id) : route('voucherheader.allvoucherStore');
    const method = isEditMode.value ? 'put' : 'post';

    if (!form.referance) {
        toast('Validation Error', {
            description: 'Please write referance',
        });
        return;
    }

    if (!selectedBranch.value?.id) {
        toast('Validation Error', {
            description: 'Please select a branch',
        });
        return;
    }
    if (!selectedDebit.value?.accountcode) {
        toast('Validation Error', {
            description: 'Please select a debit account',
        });
        return;
    }

    if (!form.debitAmt) {
        toast('Validation Error', {
            description: 'Please input debit amount',
        });
        return;
    }

    if (!selectedCredit.value?.accountcode) {
        toast('Validation Error', {
            description: 'Please select a credit account',
        });
        return;
    }

    if (!form.creditAmt) {
        toast('Validation Error', {
            description: 'Please input credit amount',
        });
        return;
    }

    if (!form.notes) {
        toast('Validation Error', {
            description: 'Please write notes',
        });
        return;
    }

    form.branch_id = selectedBranch.value.id;
    form.debitAcc = selectedDebit.value.accountcode;
    form.creditAcc = selectedCredit.value.accountcode;
    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('voucherheader.allvoucher'), {
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

const deleteForm = useForm({});

const onConfirm = async (allvoucher: number) => {
    if (!confirm('Are you sure you want to confirm this this voucher')) return;

    if (deleteForm.processing) return;

    deleteForm.put(`/voucherheader/${allvoucher}/status`, {
        onSuccess: () => {},
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const onBalance = async (allvoucher: number) => {
    if (!confirm('Are you sure you want to balance this this voucher')) return;

    if (deleteForm.processing) return;

    deleteForm.put(`/voucherheader/${allvoucher}/balance`, {
        onSuccess: () => {},
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
    if (queryDate.value === '') return props.allvoucher;

    return props.allvoucher.filter((n) => n.voucherdate && n.voucherdate.toLowerCase().includes(queryDate.value.toLowerCase()));
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

    router.get(route('voucherheader.allvoucher'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('voucherheader.allvoucher'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('voucherheader.allvoucher'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <Head title="All Voucher List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
                <Combobox v-model="selectedVoucher">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Voucher No"
                            @input="queryVoucher = $event.target.value"
                            :display-value="(c) => c?.vouchernumber ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredVoucher.length === 0 && queryVoucher !== ''" class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="one in filteredVoucher"
                                :key="one.id"
                                :value="one"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ one.vouchernumber }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedDate">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Voucher Date"
                            @input="queryDate = $event.target.value"
                            :display-value="(v) => v ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredDate.length === 0 && queryDate !== ''" class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption
                                v-for="one in filteredDate"
                                :key="one"
                                :value="one"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ one }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
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

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
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
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedRef">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Referance"
                            @input="queryRef = $event.target.value"
                            :display-value="(v) => v ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredRef.length === 0 && queryRef !== ''" class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption
                                v-for="ref in filteredRef"
                                :key="ref"
                                :value="ref"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ ref }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedYear">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Year"
                            @input="queryYear = $event.target.value"
                            :display-value="(v) => v ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredYear.length === 0 && queryYear !== ''" class="px-4 py-2 text-gray-500 select-none">Nothing found.</div>

                            <ComboboxOption
                                v-for="year in filteredYear"
                                :key="year"
                                :value="year"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ year }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedMonth">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Month"
                            @input="queryMonth = $event.target.value"
                            :display-value="(v) => v ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredMonth.length === 0 && queryMonth !== ''" class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="month in filteredMonth"
                                :key="month"
                                :value="month"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ month }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedStatus">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Status"
                            @input="queryStatus = $event.target.value"
                            :display-value="(v) => v ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
                            <div v-if="filteredStatus.length === 0 && queryStatus !== ''" class="px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="status in filteredStatus"
                                :key="status"
                                :value="status"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ status }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">All Voucher List</h2>
                    <p class="text-sm text-gray-500">Manage all Voucher from here.</p>
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
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onConfirm(vhd.id)"
                                        ><ShieldCheck class="text-green-500"></ShieldCheck
                                    ></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Confirm
                                    </span>
                                </div>

                                <div v-if="vhd.status == 'Posted'" class="group relative inline-block">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onBalance(vhd.id)"
                                        ><BookCheck class="text-blue-500"></BookCheck
                                    ></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Balance
                                    </span>
                                </div>
                            </TableCell>

                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onReport(vhd.id)"
                                        ><FileText class="text-red-500"></FileText
                                    ></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Report
                                    </span>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="flex flex-col gap-4 border-t bg-gray-50 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    <!-- Left -->
                    <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <label>Show</label>

                            <select
                                v-model="perPage"
                                @change="changePerPage"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                    {{ size }}
                                </option>
                            </select>
                        </div>

                        <span> Showing {{ voucherheader.from }} to {{ voucherheader.to }} of {{ voucherheader.total }} results </span>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap justify-center gap-2 md:justify-end">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            size="sm"
                            variant="outline"
                            @click="goToPage(link.url)"
                            :class="[
                                link.active ? 'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <DialogHeader class="space-y-1 border-b pb-4">
                    <DialogTitle class="text-xl font-semibold tracking-wide">
                        {{ isEditMode ? 'Edit All Voucher List' : 'Create All Voucher List' }}
                    </DialogTitle>
                    <DialogDescription class="text-sm text-gray-500">
                        {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new All Voucher List.' }}
                    </DialogDescription>
                </DialogHeader>
                <!-- Body -->
                <!-- Referance -->
                <div>
                    <Label for="referance" class="text-sm font-medium">Referance <span class="text-red-500">*</span></Label>
                    <Textarea v-model="form.referance" class="mt-1 w-full" placeholder="write your voucher referance"></Textarea>
                    <p v-if="form.errors.referance" class="mt-1 text-sm text-red-600">{{ form.errors.referance }}</p>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Branch -->
                    <div>
                        <Label for="branch_id" class="text-sm font-medium">Select Branch<span class="text-red-500">*</span></Label>
                        <Combobox v-model="selectedBranch">
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

                        <p v-if="form.errors.branch_id" class="mt-1 text-sm text-red-600">{{ form.errors.branch_id }}</p>
                    </div>

                    <!-- Voucher Date -->
                    <div>
                        <Label for="voucherdate" class="text-sm font-medium">Select Date<span class="text-red-500">*</span></Label>
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
                    <!-- Debit Account -->
                    <div>
                        <Label for="debitAcc" class="text-sm font-medium">Debit Account<span class="text-red-500">*</span></Label>
                        <Combobox v-model="selectedDebit">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select debit account..."
                                    :display-value="(debit) => debit?.description ?? ''"
                                    @input="queryDebit = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <!-- Options -->
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredDebit.length === 0 && queryDebit !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredDebit"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.description }}</span>
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

                        <p v-if="form.errors.debitAcc" class="mt-1 text-sm text-red-600">{{ form.errors.debitAcc }}</p>
                    </div>
                    <!-- Debit Amount -->
                    <div>
                        <Label for="debitAmt" class="text-sm font-medium">Debit Amount<span class="text-red-500">*</span></Label>
                        <Input type="text" id="debitAmt" v-model="form.debitAmt" placeholder="Enter Debit Amount" class="mt-1 w-full" />
                        <p v-if="form.errors.debitAmt" class="mt-1 text-sm text-red-600">{{ form.errors.debitAmt }}</p>
                    </div>
                    <!-- Credit Account -->
                    <div>
                        <Label for="creditAcc" class="text-sm font-medium">Credit Account<span class="text-red-500">*</span></Label>
                        <Combobox v-model="selectedCredit">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select credit account..."
                                    :display-value="(credit) => credit?.description ?? ''"
                                    @input="queryCredit = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <!-- Options -->
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredCredit.length === 0 && queryCredit !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredCredit"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.description }}</span>
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

                        <p v-if="form.errors.creditAcc" class="mt-1 text-sm text-red-600">{{ form.errors.creditAcc }}</p>
                    </div>

                    <!-- Credit Amount -->
                    <div>
                        <Label for="creditAmt" class="text-sm font-medium">Credit Amount<span class="text-red-500">*</span></Label>
                        <Input type="text" id="creditAmt" v-model="form.creditAmt" placeholder="Ex: -1000" class="mt-1 w-full" />
                        <p class="text-xs text-red-500">add minus sign(-) before numeric digit(s)</p>
                        <p v-if="form.errors.creditAmt" class="mt-1 text-sm text-red-600">{{ form.errors.creditAmt }}</p>
                    </div>
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
