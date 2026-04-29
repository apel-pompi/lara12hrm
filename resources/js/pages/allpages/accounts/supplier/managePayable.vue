<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router } from '@inertiajs/vue3';
import '@vuepic/vue-datepicker/dist/main.css';
import { FileText, RefreshCcw, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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

export interface SupplierPayment {
    vouchernumber: string;
    accountcode: string;
    voucherdate: string;
    yearname: number;
    monthname: number;
    primeamt: number;
    status: string;
    voucherdt: { primeamt: number; subacccode: { name: string } };
}

const props = defineProps<{
    payment: Paginated<SupplierPayment>;
    branch: Array<{ id: number; branchname: string }>;
    supplier: Array<{ id: number; name: string }>;
}>();

const data = props.payment;

const selectedSupplier = ref(null);
const querySupplier = ref('');
const filteredSupplier = computed(() => {
    if (querySupplier.value === '') return props.supplier;

    return props.supplier.filter((n) => n.name && n.name.toLowerCase().includes(querySupplier.value.toLowerCase()));
});

const selectedVoucher = ref(null);
const queryVoucher = ref('');
const filteredVoucher = computed(() => {
    if (queryVoucher.value === '') return props.payment.data;

    return props.payment.data.filter((n) => n.vouchernumber && n.vouchernumber.toLowerCase().includes(queryVoucher.value.toLowerCase()));
});

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});

const selectedDate = ref(null);
const queryDate = ref('');

const filteredDate = computed(() => {
    const filtered = queryDate.value
        ? props.payment.data.filter((v) => v.voucherdate?.toLowerCase().includes(queryDate.value.toLowerCase()))
        : props.payment.data;

    // unique date list
    const map = new Map();
    filtered.forEach((v) => {
        if (v.voucherdate && !map.has(v.voucherdate)) {
            map.set(v.voucherdate, v.voucherdate);
        }
    });

    return Array.from(map.values());
});

const selectedYear = ref(null);
const queryYear = ref('');

const filteredYear = computed(() => {
    // filter vouchers based on queryYear
    const filtered = queryYear.value ? props.payment.data.filter((v) => v.yearname?.includes(queryYear.value)) : props.payment.data;

    // return unique year names
    return [...new Set(filtered.map((v) => v.yearname).filter(Boolean))];
});

const selectedMonth = ref(null);
const queryMonth = ref('');

const filteredMonth = computed(() => {
    // filter vouchers based on queryMonth
    const filtered = queryMonth.value ? props.payment.data.filter((v) => v.monthname?.includes(queryMonth.value)) : props.payment.data;

    // return unique month names
    return [...new Set(filtered.map((v) => v.monthname).filter(Boolean))];
});

const selectedStatus = ref(null);
const queryStatus = ref('');
const filteredStatus = computed(() => {
    const filtered = queryStatus.value
        ? props.payment.data.filter((v) => v.status?.toLowerCase().includes(queryStatus.value.toLowerCase()))
        : props.payment.data;

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
    if (selectedSupplier.value) params.subacccode = selectedSupplier.value.subcode;
    if (selectedVoucher.value) params.vouchernumber = selectedVoucher.value.vouchernumber;
    if (selectedDate.value) params.voucherdate = selectedDate.value;
    if (selectedBranch.value) params.branch_id = selectedBranch.value.id;
    if (selectedYear.value) params.yearname = selectedYear.value;
    if (selectedMonth.value) params.monthname = selectedMonth.value;
    if (selectedStatus.value) params.status = selectedStatus.value;
    console.log('Search params:', params);
    router.get(route('suppliersPayble.manage'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('suppliersInvoice.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('suppliersInvoice.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const manage = () => {
    router.visit(route('suppliersPayble.index'), {
        preserveScroll: true,
        preserveState: true,
    });
};

const onReport = async (vhd: number) => {
    const url = route('voucherheader.singleReport', {
        voucherID: vhd,
    });

    window.open(url, '_blank');
};
</script>

<template>
    <Head title="Supplier Payable" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
                <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="manage"
                    ><Plus></Plus> Supplier Payable
                </Button>
                <Combobox v-model="selectedSupplier">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select Supplier"
                            @input="querySupplier = $event.target.value"
                            :display-value="(c) => c?.name ?? ''"
                        />
                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                        </ComboboxButton>

                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg">
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
                                    {{ one.name }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedVoucher">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select voucher"
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
                <Combobox v-model="selectedDate">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border px-3 py-2 text-sm"
                            placeholder="Select date"
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
                            placeholder="Select Period"
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
                    <h2 class="text-lg font-semibold text-gray-800">Supplier manage payable</h2>
                    <p class="text-sm text-gray-500">Manage all Supplier manage payable from here.</p>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Supplier Name</TableHead>
                            <TableHead>Voucher Number</TableHead>
                            <TableHead>Branch</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Year</TableHead>
                            <TableHead>Period</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Report</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(supplier, index) in data.data" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ supplier.voucherdt?.[0]?.subacccode?.name }}</TableCell>
                            <TableCell>{{ supplier?.vouchernumber }}</TableCell>
                            <TableCell>{{ supplier?.branch.branchname }}</TableCell>
                            <TableCell>{{ supplier?.voucherdate }}</TableCell>
                            <TableCell>{{ supplier?.yearname }}</TableCell>
                            <TableCell>{{ supplier?.monthname }}</TableCell>
                            <TableCell>{{ supplier?.status }}</TableCell>
                            <TableCell class="text-center">
                                <div class="group relative inline-block">
                                    <Button class="m-0.5 cursor-pointer" size="sm" variant="outline" @click="onReport(supplier.id)"
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

                        <span> Showing {{ payment.from }} to {{ payment.to }} of {{ payment.total }} results </span>
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
    </AppLayout>
</template>
