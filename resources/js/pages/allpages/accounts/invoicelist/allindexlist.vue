<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router } from '@inertiajs/vue3';
import { FileText, RefreshCcw, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'All Invoices',
        href: '/invoicelist/AllInvoiceList',
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

export interface Invoice {
    insnumber: number;
    student: {
        student_id: string;
        fname: string;
        lname: string;
        phone: string;
    };
    details_sum_amount?: number;
}

const props = defineProps<{
    invoice: Paginated<Invoice>;

    allstudent: {
        insnumber: string;
        insdate: string;
        student: {
            student_id: string;
            fname: string;
            lname: string;
            phone: string;
        };
    }[];
}>();

const data = props.invoice;

const selectedName = ref<null | string>(null);
const queryName = ref('');

const filteredName = computed(() => {
    const students = props.allstudent.map((i) => i.student);

    const unique = students.filter((s, index, self) => index === self.findIndex((t) => t.student_id === s.student_id));

    if (!queryName.value) return unique;

    return unique.filter((s) => `${s.fname} ${s.lname}`.toLowerCase().includes(queryName.value.toLowerCase()));
});

const selectedPhone = ref<null | string>(null);
const queryPhone = ref('');

const filteredPhone = computed(() => {
    const students = props.allstudent.map((i) => i.student);

    const unique = students.filter((s, index, self) => index === self.findIndex((t) => t.student_id === s.student_id));

    if (!queryPhone.value) return unique;

    return unique.filter((s) => `${s.phone}`.toLowerCase().includes(queryPhone.value.toLowerCase()));
});

const selectedInvoice = ref(null);
const queryInvoice = ref('');
const filteredInvoice = computed(() => {
    if (queryInvoice.value === '') return props.allstudent;

    return props.allstudent.filter((n) => n.insnumber && n.insnumber.toLowerCase().includes(queryInvoice.value.toLowerCase()));
});

const selectedInvoiceDate = ref<string | null>(null);
const queryInvoiceDate = ref('');

const filteredInvoiceDate = computed(() => {
    const dates = props.allstudent.map((i) => ({ insdate: i.insdate })).filter((i) => i.insdate);

    const uniqueDates = dates.filter((d, index, self) => index === self.findIndex((t) => t.insdate === d.insdate));

    if (!queryInvoiceDate.value) return uniqueDates;

    return uniqueDates.filter((d) => d.insdate.toLowerCase().includes(queryInvoiceDate.value.toLowerCase()));
});

const selectedStudentID = ref<string | null>(null);
const queryStudentID = ref('');

const filteredStudentID = computed(() => {
    const studentIDs = props.allstudent.map((i) => ({ student_id: i.student.student_id })).filter((i) => i.student_id);

    const uniqueStudentIDs = studentIDs.filter((d, index, self) => index === self.findIndex((t) => t.student_id === d.student_id));

    if (!queryStudentID.value) return uniqueStudentIDs;
    return uniqueStudentIDs.filter((d) => d.student_id.toLowerCase().includes(queryStudentID.value.toLowerCase()));
});





const search = () => {
    const params: Record<string, any> = {};
    if (selectedName.value) params.fname = selectedName.value.fname;
    if (selectedName.value) params.lname = selectedName.value.lname;
    if (selectedPhone.value) params.phone = selectedPhone.value.phone;
    if (selectedInvoiceDate.value) params.insdate = selectedInvoiceDate.value.insdate;
    if (selectedStudentID.value) params.student_id = selectedStudentID.value.student_id;
    if (selectedInvoice.value) params.insnumber = selectedInvoice.value.insnumber;
    router.get(route('invoicelist.AllInvoiceList'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('invoicelist.AllInvoiceList'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('invoicelist.AllInvoiceList'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const onReport = async (invId: number, student_id: number) => {
    const url = route('studentAccounts.onReport', {
        student: student_id,
        confirm: invId,
    });

    window.open(url, '_blank');
};
</script>

<template>
    <Head title="All Invoices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex flex-wrap items-center gap-4 py-4">
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedInvoice">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select invoice..."
                                :display-value="(invoice) => invoice?.insnumber ?? ''"
                                @input="queryInvoice = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredInvoice.length === 0 && queryInvoice !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredInvoice"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.insnumber }}</span>
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
                    <Combobox v-model="selectedStudentID">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select student ID..."
                                :display-value="(invoice) => invoice?.student_id ?? ''"
                                @input="queryInvoice = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredStudentID.length === 0 && queryStudentID !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredStudentID"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.student_id }}</span>
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
                    <Combobox v-model="selectedInvoiceDate">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select invoice date..."
                                :display-value="(invoice) => invoice?.insdate ?? ''"
                                @input="queryInvoiceDate = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredInvoiceDate.length === 0 && queryInvoiceDate !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredInvoiceDate"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.insdate }}</span>
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
                    <Combobox v-model="selectedName">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select Student name..."
                                :display-value="(n) => (n ? `${n.fname} ${n.lname}` : '')"
                                @input="queryName = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

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
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedPhone">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select student phone..."
                                :display-value="(student) => student?.phone ?? ''"
                                @input="queryPhone = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

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
                                    :key="n.student_id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.phone }}</span>
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
                <Table class="min-w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Invoice No</TableHead>
                            <TableHead>Invoice Date</TableHead>
                            <TableHead>Student ID</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Amount</TableHead>
                            <TableHead>Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(inv, index) in data.data ?? []" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ inv.insnumber }}</TableCell>
                            <TableCell>{{ inv.insdate }}</TableCell>
                            <TableCell>{{ inv.student.student_id }}</TableCell>
                            <TableCell>{{ inv.student.fname }} {{ inv.student.lname }}</TableCell>
                            <TableCell>{{ inv.student.phone }}</TableCell>
                            <TableCell>{{ inv.netamount }}</TableCell>
                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button
                                        v-if="inv.status === 'Confirmed'"
                                        class="m-[2px] cursor-pointer"
                                        size="sm"
                                        variant="outline"
                                        @click="onReport(inv.id, inv.student_id)"
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
            </div>

            <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                    <label for="per-page" class="text-gray-600">Show:</label>
                    <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                        <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                    </select>
                    <span>Showing {{ invoice.from }} to {{ invoice.to }} of {{ invoice.total }} results</span>
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
    </AppLayout>
</template>
