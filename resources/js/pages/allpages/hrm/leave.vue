<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { FileText, Plus, RefreshCcw, Search, ShieldCheck, SquarePen, X } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface LeavePlan {
    id: number;
    leavename: string;
}
export interface Employee {
    empid: number;
    empname: string;
}
export interface Substitute {
    empid: number;
    empname: string;
}
export interface Leave {
    id: number;
    leaveplan_id: string;
    empid: string;
    fromdate: string;
    todate: string;
    requestdays: string;
    approveddate: string;
    approveddays: string;
    substitute: string;
    contact_address: string;
    reason: string;
    status: string;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Leave', href: '/leave' }];

const props = defineProps<{
    leaves: Paginated<Leave>;
    filters: { name: string };
    leaveplan: LeavePlan[];
    employee: Employee[];
    substitute: Substitute[];
}>();

const data = props.leaves;

const fromdate = ref<string | null>(null);
const todate = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

interface FormErrors {
    leaveplan_id?: string;
    empid?: string;
    fromdate: string;
    todate: string;
    requestdays: string;
    approveddate: string;
    approveddays: string;
    substitute: string;
    contact_address: string;
    reason: string;
    status: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);

const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    leaveplan_id: '',
    empid: '',
    fromdate: '',
    todate: '',
    requestdays: '',
    approveddate: '',
    approveddays: '',
    substitute: '',
    contact_address: '',
    reason: '',
    status: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const balanceLeave = ref({
    allow: 0,
    taken: 0,
    balance: 0,
});

const fetchLeave = async () => {
    if (!form.empid || !form.leaveplan_id) return;

    const res = await fetch(`/leave/${form.leaveplan_id}/${form.empid}/fetchUserLeave`);
    balanceLeave.value = await res.json();
};

watch(
    () => [form.empid, form.leaveplan_id],
    async () => {
        if (form.empid && form.leaveplan_id) {
            await fetchLeave();
        }
    },
);

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/leave/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching leave details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.data);
        form.id = data.data.id;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('leave.update', form.id) : route('leave.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Leave ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('leave.index'), {
                    only: ['leaves'],
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
const deleteForm = useForm({});

const onConfirm = async (id: number) => {
    if (!confirm('Are you sure you want to send this leave for approval')) return;

    if (deleteForm.processing) return;

    deleteForm.post(`/leave/confirm/${id}`, {
        onSuccess: () => {
            toast.success('Leave approval sending successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to cancel this leave?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/leave/show/${id}`, {
        onSuccess: () => {
            toast.success('Leave cancel successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const exportPdf = (id: number) => {
    window.open(route('leave.exportPdf', id), '_blank');
};

watch(fromdate, (newDate) => {
    if (newDate) {
        form.fromdate = newDate;
    } else {
        form.fromdate = '';
    }
});

watch(todate, (newDate) => {
    if (newDate) {
        form.todate = newDate;
    } else {
        form.todate = '';
    }
});

watch([fromdate, todate], ([newFrom, newTo]) => {
    if (newFrom && newTo) {
        const start = new Date(newFrom + 'T00:00:00');
        const end = new Date(newTo + 'T00:00:00');
        const diffTime = end.getTime() - start.getTime();
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

        form.requestdays = diffDays > 0 ? diffDays.toString() : '0';
    } else {
        form.requestdays = '0';
    }
});

// Combobox states
const selectedLeaveName = ref(null); // name
const queryLeaveName = ref('');

// Filtered lists
const filteredLeaveName = computed(() => (queryLeaveName.value === '' ? props.leaveplan : props.leaveplan.filter((n) => n.leavename)));

const selectedEmployee = ref(null);
const queryEmployee = ref('');

const filteredEmployee = computed(() => {
    const filtered = queryEmployee.value
        ? props.employee.filter((v) => v.empname?.toLowerCase().includes(queryEmployee.value.toLowerCase()))
        : props.employee;

    // unique by empname but keep full object
    const map = new Map();

    filtered.forEach((v) => {
        if (v.empname && !map.has(v.empname)) {
            map.set(v.empname, v);
        }
    });

    return Array.from(map.values());
});

const selectedFormDate = ref(null);
const queryFormDate = ref('');
const filteredFormDate = computed(() => {
    const filtered = queryFormDate.value ? data.data.filter((v) => v.fromdate?.toLowerCase().includes(queryFormDate.value.toLowerCase())) : data.data;

    // unique by fromdate but keep full object
    const map = new Map();

    filtered.forEach((v) => {
        if (v.fromdate && !map.has(v.fromdate)) {
            map.set(v.fromdate, v);
        }
    });

    return Array.from(map.values());
});

const selectedToDate = ref(null);
const queryToDate = ref('');
const filteredToDate = computed(() => {
    const filtered = queryToDate.value ? data.data.filter((v) => v.todate?.toLowerCase().includes(queryToDate.value.toLowerCase())) : data.data;

    // unique by fromdate but keep full object
    const map = new Map();

    filtered.forEach((v) => {
        if (v.todate && !map.has(v.todate)) {
            map.set(v.todate, v);
        }
    });

    return Array.from(map.values());
});

const selectedSubstitute = ref(null);
const querySubstitute = ref('');

const filteredSubstitute = computed(() => {
    const filtered = querySubstitute.value
        ? props.employee.filter((v) => v.empname?.toLowerCase().includes(querySubstitute.value.toLowerCase()))
        : props.employee;

    // unique by empname but keep full object
    const map = new Map();

    filtered.forEach((v) => {
        if (v.empname && !map.has(v.empname)) {
            map.set(v.empname, v);
        }
    });

    return Array.from(map.values());
});

const search = () => {
    const params: Record<string, any> = {};

    if (selectedLeaveName.value) params.leavename = selectedLeaveName.value.leavename;
    if (selectedEmployee.value) params.empname = selectedEmployee.value.empname;
    if (selectedFormDate.value) params.fromdate = selectedFormDate.value.fromdate;
    if (selectedToDate.value) params.todate = selectedToDate.value.todate;
    if (selectedSubstitute.value) params.subemp = selectedSubstitute.value.empname;

    router.get(route('leave.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('leave.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('leave.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <Head title="Leave Paln" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
                <Button variant="outline" size="sm" @click="showDailogCreate" class="w-40 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
                    ><Plus></Plus> Create
                </Button>
                <Combobox v-model="selectedLeaveName">
                    <div class="relative">
                        <!-- Input -->
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select name..."
                                :display-value="(n) => n?.leavename"
                                @input="queryLeaveName = $event.target.value"
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
                                v-if="filteredLeaveName.length === 0 && queryLeaveName !== ''"
                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                            >
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="n in filteredLeaveName"
                                :key="n.id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ n.leavename }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedEmployee">
                    <div class="relative">
                        <!-- Input -->
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select name..."
                                :display-value="(n) => n?.empname"
                                @input="queryEmployee = $event.target.value"
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
                                v-if="filteredEmployee.length === 0 && queryEmployee !== ''"
                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                            >
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="n in filteredEmployee"
                                :key="n.id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ n.empname }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedFormDate">
                    <div class="relative">
                        <!-- Input -->
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select fromdate..."
                                :display-value="(n) => n?.fromdate"
                                @input="queryFormDate = $event.target.value"
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
                                v-if="filteredFormDate.length === 0 && queryFormDate !== ''"
                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                            >
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="n in filteredFormDate"
                                :key="n.id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ n.fromdate }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedToDate">
                    <div class="relative">
                        <!-- Input -->
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select todate..."
                                :display-value="(n) => n?.todate"
                                @input="queryToDate = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>
                        </div>

                        <!-- Options -->
                        <ComboboxOptions
                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                        >
                            <div v-if="filteredToDate.length === 0 && queryToDate !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="n in filteredToDate"
                                :key="n.id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ n.todate }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Combobox v-model="selectedSubstitute">
                    <div class="relative">
                        <!-- Input -->
                        <div class="relative w-full">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select substitute..."
                                :display-value="(n) => n?.empname"
                                @input="querySubstitute = $event.target.value"
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
                                v-if="filteredSubstitute.length === 0 && querySubstitute !== ''"
                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                            >
                                Nothing found.
                            </div>

                            <ComboboxOption
                                v-for="n in filteredSubstitute"
                                :key="n.id"
                                :value="n"
                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                v-slot="{ selected }"
                            >
                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                    {{ n.empname }}
                                </span>
                                <span v-if="selected" class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                    <CheckIcon class="h-5 w-5" />
                                </span>
                            </ComboboxOption>
                        </ComboboxOptions>
                    </div>
                </Combobox>
                <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="search"
                    ><Search></Search> Search
                </Button>
                <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="refresh"
                    ><RefreshCcw></RefreshCcw> Refresh
                </Button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Leave request list</h2>
                    <p class="text-sm text-gray-500">Manage all Leave request from here.</p>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow class="bg-gray-100 hover:bg-gray-200">
                            <TableHead>Leave Name</TableHead>
                            <TableHead>Employee Name</TableHead>
                            <TableHead>From Date</TableHead>
                            <TableHead>To Date</TableHead>
                            <TableHead>Request Days</TableHead>
                            <TableHead>Approved Date</TableHead>
                            <TableHead>Approved Days</TableHead>
                            <TableHead>Substitute</TableHead>
                            <TableHead>Reason</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(leave, index) in data.data" :key="leave.id ?? index">
                            <TableCell>{{ leave.leave_plan.leavename }}</TableCell>
                            <TableCell>{{ leave.employee.empname }}</TableCell>
                            <TableCell>{{ leave.fromdate }}</TableCell>
                            <TableCell>{{ leave.todate }}</TableCell>
                            <TableCell>{{ leave.requestdays }}</TableCell>
                            <TableCell>{{ leave.approveddate }}</TableCell>
                            <TableCell>{{ leave.approveddays }}</TableCell>
                            <TableCell>{{ leave.substitute_employee.empname }}</TableCell>
                            <TableCell>{{ leave.reason }}</TableCell>
                            <TableCell class="text-center">
                                <div v-if="leave.status == 0" class="flex items-center justify-center gap-2">
                                    <div class="group relative">
                                        <Button @click="onEdit(leave.id)" variant="outline" size="icon" class="cursor-pointer">
                                            <SquarePen class="h-4 w-4 text-blue-500" />
                                        </Button>
                                        <span
                                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Edit
                                        </span>
                                    </div>
                                    <div class="group relative">
                                        <Button @click="onDelete(leave.id)" variant="outline" size="icon" class="cursor-pointer">
                                            <X class="h-4 w-4 text-red-500" />
                                        </Button>
                                        <span
                                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Cancel
                                        </span>
                                    </div>

                                    <div class="group relative">
                                        <Button @click="onConfirm(leave.id)" variant="outline" size="icon" class="cursor-pointer">
                                            <ShieldCheck class="h-4 w-4 text-green-500" />
                                        </Button>
                                        <span
                                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Send
                                        </span>
                                    </div>
                                </div>
                                <div v-else-if="leave.status == 1" class="flex items-center justify-center gap-2">Leave Cancel</div>
                                <div v-else-if="leave.status == 2" class="flex items-center justify-center gap-2">Approval Pending</div>
                                <div v-else-if="leave.status == 3" class="flex items-center justify-center gap-2">
                                    <div class="group relative">
                                        <Button @click="exportPdf(leave.id)" variant="outline" size="icon" class="cursor-pointer">
                                            <FileText class="h-4 w-4 text-red-500" />
                                        </Button>
                                        <span
                                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Report
                                        </span>
                                    </div>
                                </div>
                                <div v-else></div>
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
                    <span>Showing {{ leaves.from }} to {{ leaves.to }} of {{ leaves.total }} results</span>
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
            <DialogContent class="max-w-206.25">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Leave Request' : 'Create Leave Request' }}</DialogTitle>
                    <DialogDescription> Make changes to your leave request here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-2 gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="empid">Employee Name<span class="text-red-500">*</span></Label>
                            <Select v-model="form.empid">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Employee Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="employees in props.employee" :key="employees.id" :value="employees.id">
                                            {{ employees.empname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.empid" class="text-sm text-red-600">{{ errors.empid }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="leaveplan_id">Leave Name<span class="text-red-500">*</span></Label>
                            <Select v-model="form.leaveplan_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Leave Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="leaveplans in props.leaveplan" :key="leaveplans.id" :value="leaveplans.id">
                                            {{ leaveplans.leavename }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.leaveplan_id" class="text-sm text-red-600">{{ errors.leaveplan_id }}</span>
                        </div>

                        <div v-if="balanceLeave.balance !== null" class="mt-4 rounded bg-gray-100 p-3">
                            <p><b>Total Allow:</b> {{ balanceLeave.allow }}</p>
                            <p><b>Taken:</b> {{ balanceLeave.taken }}</p>
                            <p><b>Balance:</b> {{ balanceLeave.balance }}</p>
                        </div>

                        <div class="grid gap-2">
                            <Label for="fromdate">From Date<span class="text-red-500">*</span></Label>
                            <VueDatePicker
                                v-model="fromdate"
                                :disabled="balanceLeave?.balance === 0"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="From date"
                                auto-apply
                                model-type="format"
                            />
                            <span v-if="errors?.fromdate" class="text-sm text-red-600">{{ errors.fromdate }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="todate">To Date<span class="text-red-500">*</span></Label>
                            <VueDatePicker
                                v-model="todate"
                                :disabled="balanceLeave?.balance === 0"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="To date"
                                auto-apply
                                model-type="format"
                            />
                            <span v-if="errors?.todate" class="text-sm text-red-600">{{ errors.todate }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="days">Total Days<span class="text-red-500">*</span></Label>
                            <Input
                                class="max-w-sm"
                                placeholder="Total Days"
                                id="empid"
                                v-model="form.requestdays"
                                autofocus
                                :disabled="balanceLeave?.balance === 0"
                            />
                            <span v-if="errors?.requestdays" class="text-sm text-red-600">{{ errors.requestdays }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="substitute">Substitute<span class="text-red-500">*</span></Label>
                            <Select v-model="form.substitute">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Substitute Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="employees in props.substitute" :key="employees.id" :value="employees.id">
                                            {{ employees.empname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.substitute" class="text-sm text-red-600">{{ errors.substitute }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="contact_address">Contact address during leave period<span class="text-red-500">*</span></Label>
                            <Textarea
                                class="max-w-sm"
                                placeholder="Write address"
                                id="contact_address"
                                v-model="form.contact_address"
                                autofocus
                            ></Textarea>
                            <span v-if="errors?.contact_address" class="text-sm text-red-600">{{ errors.contact_address }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="reason">Reason<span class="text-red-500">*</span></Label>
                            <Textarea class="max-w-sm" placeholder="Write Reason" id="reason" v-model="form.reason" autofocus></Textarea>
                            <span v-if="errors?.reason" class="text-sm text-red-600">{{ errors.reason }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Button :disabled="form.processing" @click="submit">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                            </Button>
                        </div>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
