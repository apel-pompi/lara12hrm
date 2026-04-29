<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router } from '@inertiajs/vue3';
import { Eye, RefreshCcw, Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Outstanding Invoices',
        href: '/invoicelist/DueInvoiceList',
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
    allinvoice: { id: number; insnumber: string; insdate: string; student: { student_id: string | null } };
}>();

const data = props.invoice;

const selectedName = ref<null | string>(null);
const queryName = ref('');

const filteredName = computed(() => {
    const students = props.allinvoice.map((i) => i.student);

    const unique = students.filter((s, index, self) => index === self.findIndex((t) => t.student_id === s.student_id));

    if (!queryName.value) return unique;

    return unique.filter((s) => `${s.fname} ${s.lname}`.toLowerCase().includes(queryName.value.toLowerCase()));
});

const selectedPhone = ref<null | string>(null);
const queryPhone = ref('');

const filteredPhone = computed(() => {
    const students = props.allinvoice.map((i) => i.student);

    const unique = students.filter((s, index, self) => index === self.findIndex((t) => t.student_id === s.student_id));

    if (!queryPhone.value) return unique;

    return unique.filter((s) => `${s.phone}`.toLowerCase().includes(queryPhone.value.toLowerCase()));
});

const selectedInvoice = ref(null);
const queryInvoice = ref('');
const filteredInvoice = computed(() => {
    if (queryInvoice.value === '') return props.allinvoice;

    return props.allinvoice.filter((n) => n.insnumber && n.insnumber.toLowerCase().includes(queryInvoice.value.toLowerCase()));
});

const selectedInvoiceDate = ref<string | null>(null);
const queryInvoiceDate = ref('');

const filteredInvoiceDate = computed(() => {
    const dates = props.allinvoice.map((i) => ({ insdate: i.insdate })).filter((i) => i.insdate);

    const uniqueDates = dates.filter((d, index, self) => index === self.findIndex((t) => t.insdate === d.insdate));

    if (!queryInvoiceDate.value) return uniqueDates;

    return uniqueDates.filter((d) => d.insdate.toLowerCase().includes(queryInvoiceDate.value.toLowerCase()));
});

const selectedStudentID = ref<any | null>(null);
const queryStudentID = ref('');

const filteredStudentID = computed(() => {
    const list = props.allinvoice.filter((n) => n.student?.student_id);

    const uniqueMap = new Map<string, (typeof list)[0]>();

    list.forEach((item) => {
        const sid = item.student!.student_id!;
        if (!uniqueMap.has(sid)) {
            uniqueMap.set(sid, item);
        }
    });

    const uniqueList = Array.from(uniqueMap.values());

    if (!queryStudentID.value) return uniqueList;

    return uniqueList.filter((n) => n.student!.student_id!.toLowerCase().includes(queryStudentID.value.toLowerCase()));
});

const onCreateMR = async (invId: number, sid: number) => {
    router.visit(route('invoicelist.createMR', { insid: invId, sid: sid }));
};

const search = () => {
    const params: Record<string, any> = {};

    if (selectedName.value) params.fname = selectedName.value.fname;
    if (selectedName.value) params.lname = selectedName.value.lname;
    if (selectedPhone.value) params.phone = selectedPhone.value.phone;
    if (selectedInvoiceDate.value) params.insdate = selectedInvoiceDate.value.insdate;
    if (selectedStudentID.value) params.student_id = selectedStudentID.value.student.student_id;
    if (selectedInvoice.value) params.insnumber = selectedInvoice.value.insnumber;

    router.get(route('invoicelist.DueInvoiceList'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('invoicelist.DueInvoiceList'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('invoicelist.DueInvoiceList'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <Head title="Outstanding Invoices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
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
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>

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
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedStudentID">
                    <div class="relative w-full md:w-48">
                        <ComboboxInput
                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Select student ID..."
                            :display-value="(invoice) => invoice?.student.student_id ?? ''"
                            @input="queryStudentID = $event.target.value"
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
                                :key="n.student_id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.student.student_id }}</span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
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
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>

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
                            <div v-if="filteredPhone.length === 0 && queryPhone !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
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
                    <h2 class="text-lg font-semibold text-gray-800">Outstanding Invoices</h2>
                    <p class="text-sm text-gray-500">Manage all Outstanding Invoices from here.</p>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Invoice No</TableHead>
                            <TableHead>Invoice Date</TableHead>
                            <TableHead>Student ID</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Due Amount</TableHead>
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
                            <TableCell>{{ inv.details_sum_amount }}</TableCell>
                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button class="cursor-pointer" size="sm" variant="outline" @click="onCreateMR(inv.insnumber, inv.student_id)"
                                        ><Eye class="text-green-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        View M.R
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

                        <span> Showing {{ invoice.from }} to {{ invoice.to }} of {{ invoice.total }} results </span>
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
