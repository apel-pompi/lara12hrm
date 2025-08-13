<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { h, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn, valueUpdater } from '@/lib/utils';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import {
    Column,
    ColumnDef,
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    Row,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown, ChevronDown, Plus } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

import DropdownAction from '@/components/DataTable.vue';

import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { toast } from 'vue-sonner';

export interface Branch {
    id: number;
    branchname: string;
}

export interface HolidayHd {
    id: number;
    branch_id: string;
    holiyear: string;
    holimonth: string;
    holidays: string;
    holiworking: string;
    active: number;
    branch?: Branch;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Holiday', href: '/holidayHd' }];

const props = defineProps<{
    holidayHd: HolidayHd[];
    branch: Branch[];
    year: string;
    month: string;
}>();

const data = props.holidayHd;

const columns: ColumnDef<HolidayHd, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<HolidayHd> }) => {
            // Calculate SL number based on pagination
            const pageIndex = table.getState().pagination.pageIndex;
            const pageSize = table.getState().pagination.pageSize;
            const rowIndex = row.index;
            return h('div', rowIndex + 1 + pageIndex * pageSize);
        },
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'branch_id',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Branch Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => h('div', { class: 'capitalize' }, row.original.branch?.branchname ?? '—'),
    },
    {
        accessorKey: 'holiyear',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Holi Year', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => h('div', { class: 'capitalize' }, row.getValue('holiyear')),
    },
    {
        accessorKey: 'holimonth',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Holi Month', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => {
            const value = row.getValue<number>('holimonth');
            const monthNames = [
                '',
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            return h('div', { class: 'capitalize' }, monthNames[value] || '');
        },
    },
    {
        accessorKey: 'holidays',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Holi Days', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => {
            const id = row.original.id;
            const holidays = row.getValue<number>('holidays');
            return h(
                'a',
                {
                    href: `/holidaydt/${id}/create/`,
                    class: 'text-blue-600 underline hover:text-blue-800',
                },
                holidays?.toString() ?? '',
            );
        },
    },
    {
        accessorKey: 'holiworking',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Working Days', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => h('div', { class: 'capitalize' }, row.getValue('holiworking')),
    },
    {
        accessorKey: 'active',
        header: ({ column }: { column: Column<HolidayHd, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayHd> }) => {
            // Use a local ref for the switch status, initialized from row data
            const status = ref(!!row.getValue('active'));
            const isLoading = ref(false);
            // Define an async handler function to update status on server
            const onUpdate = async (newValue: number) => {
                isLoading.value = true;
                status.value = newValue;
                try {
                    await router.put(
                        route('holidayhd.updateStatus', { holidayhd: row.original.id }),
                        { active: newValue ? 1 : 0 }, // send boolean value, not ref
                        {
                            preserveScroll: true,
                            onSuccess: () => {
                                row.original.active = newValue ? 1 : 0; 
                                toast.success('Status updated successfully');
                                setTimeout(() => {
                                router.visit(route('holidayHd.index'), {
                                    only: ['holiday_hds'],
                                    preserveScroll: true,
                                    preserveState: false,
                                });
                            }, 200);
                            },
                            onError: () => {
                                toast.error('Failed to update status');
                                // revert UI on failure
                                status.value = !newValue;
                            },
                        },
                    );
                } catch (error) {
                    toast.error('Network error occurred: ' + error);
                    // revert UI on failure
                    status.value = !newValue;
                } finally {
                    isLoading.value = false;
                }
            };

            // Return the Switch component with v-model style binding and async update handler
            return h(Switch as any, {
                modelValue: status.value,
                disabled: isLoading.value,
                'onUpdate:modelValue': onUpdate,
            });
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<HolidayHd> }) => {
            const dataID = row.original;

            return h(
                'div',
                { class: 'relative' },
                h(DropdownAction, {
                    dataID,
                    onShow,
                    onEdit,
                    onDelete,
                    onExpand: row.toggleExpanded,
                }),
            );
        },
    },
];

// Reactive states
const sorting = ref([]);
const columnFilters = ref([]);
const columnVisibility = ref({});
const rowSelection = ref({});
const expanded = ref({});

const table = useVueTable({
    data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: (updaterOrValue) => valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: (updaterOrValue) => valueUpdater(updaterOrValue, expanded),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
        get expanded() {
            return expanded.value;
        },
        columnPinning: {
            left: ['status'],
        },
    },
});

interface FormErrors {
    branch_id?: string;
    holiyear?: string;
    holimonth?: string;
    holidays?: string;
    holiworking?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_id: '',
    holiyear: null as number | null,
    holimonth: null as number | null,
    holidays: null as number | null,
    holiworking: null as number | null,
    active: '',
    branch: undefined,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/holidayHd/${id}`);
        console.log(res);
        if (!res.ok) {
            toast.error('Server error while fetching holiday details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.id = data.id;
        form.branch = data.branch.branchname;
        isEditMode.value = false;
        showDialog.value = false;
        showDialogOpen.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
        toast.error('Network error occurred. Please try again.');
    }
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/holidayHd/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching holidayHd details.');
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
    const action = isEditMode.value && form.id ? route('holidayHd.update', form.id) : route('holidayHd.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `HolidayHd ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('holidayHd.index'), {
                    only: ['holiday_hds'],
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

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this holidayHd?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/holidayHd/show/${id}`, {
        onSuccess: () => {
            toast.success('HolidayHd deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

watch(
    () => [form.holiyear, form.holimonth, form.holidays],
    ([year, month, holidays]) => {
        if (year && month && holidays !== null) {
            const totalDays = new Date(year, month, 0).getDate(); // month is 1-based
            const workingDays = totalDays - Number(holidays);
            form.holiworking = workingDays >= 0 ? workingDays : 0;
        } else {
            form.holiworking = null;
        }
    },
);
</script>

<template>
    <Head title="Holiday" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Input
                    class="max-w-sm"
                    placeholder="Filter HolidayHd"
                    :model-value="table.getColumn('holiyear')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('holiyear')?.setFilterValue($event)"
                />
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Holiday </Button>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="ml-auto"> Columns <ChevronDown class="ml-2 h-4 w-4" /> </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuCheckboxItem
                            v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                            :key="column.id"
                            class="capitalize"
                            :model-value="column.getIsVisible()"
                            @update:model-value="
                                (value) => {
                                    column.toggleVisibility(!!value);
                                }
                            "
                        >
                            {{ column.id }}
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                            <TableHead
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                                :data-pinned="header.column.getIsPinned()"
                                :class="
                                    cn(
                                        { 'bg-background/95 sticky': header.column.getIsPinned() },
                                        header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                    )
                                "
                            >
                                <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="table.getRowModel().rows?.length">
                            <template v-for="row in table.getRowModel().rows" :key="row.id">
                                <TableRow :data-state="row.getIsSelected() && 'selected'">
                                    <TableCell
                                        v-for="cell in row.getVisibleCells()"
                                        :key="cell.id"
                                        :data-pinned="cell.column.getIsPinned()"
                                        :class="
                                            cn(
                                                { 'bg-background/95 sticky': cell.column.getIsPinned() },
                                                cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                            )
                                        "
                                    >
                                        <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="row.getIsExpanded()">
                                    <TableCell :colspan="row.getAllCells().length">
                                        {{ row.original }}
                                    </TableCell>
                                </TableRow>
                            </template>
                        </template>

                        <TableRow v-else>
                            <TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm">
                    {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }} row(s) selected.
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()"> Previous </Button>
                    <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()"> Next </Button>
                </div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Holiday' : 'Create Holiday' }}</DialogTitle>
                    <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-2 gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="branch_id">Branch Name</Label>
                            <Select v-model="form.branch_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Branch" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="branches in branch" :key="branches.id" :value="branches.id">
                                            {{ branches.branchname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.branch_id" class="text-sm text-red-600">{{ errors.branch_id }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="holiyear">Holi Year</Label>
                            <Select v-model="form.holiyear">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="years in year" :key="years" :value="years">
                                            {{ years }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.holiyear" class="text-sm text-red-600">{{ errors.holiyear }}</span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="holimonth">Holi Month</Label>
                            <Select v-model="form.holimonth">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Month" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="(label, key) in month" :key="key" :value="key">
                                            {{ label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.holimonth" class="text-sm text-red-600">{{ errors.holimonth }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="holidays">Holi Days</Label>
                            <Input class="max-w-sm" placeholder="Enter Holi Days" id="holidays" v-model="form.holidays" autofocus />
                            <span v-if="errors?.holidays" class="text-sm text-red-600">{{ errors.holidays }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="holiworking">Working Days</Label>
                            <Input class="max-w-sm" placeholder="Enter Working Days" id="holidays" v-model="form.holiworking" autofocus />
                            <span v-if="errors?.holiworking" class="text-sm text-red-600">{{ errors.holiworking }}</span>
                        </div>
                        <input type="hidden" value="0" v-model="form.active" class="form-radio text-primary-600" />
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

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
            <DialogContent class="max-w-4xl rounded-2xl p-6 shadow-xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show Holiday</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this Holiday. </DialogDescription>
                </DialogHeader>
                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Branch Name -->
                        <FormGroup label="Branch Name" htmlFor="branch_id">
                            <div v-if="!isEditMode" class="bg-muted rounded-md border px-3 py-2 text-sm">
                                {{ form.branch }}
                            </div>
                        </FormGroup>
                        <!-- Holi Year -->
                        <FormGroup label="Holi Year" htmlFor="holiyear">
                            <Input id="holiyear" v-model="form.holiyear" :disabled="!isEditMode" />
                        </FormGroup>
                        <!-- Holi Month -->
                        <FormGroup label="Holi Month" htmlFor="holimonth">
                            <Input id="holimonth" :modelValue="isEditMode ? form.holimonth : month[form.holimonth]" :disabled="!isEditMode" />
                        </FormGroup>
                    </div>
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <FormGroup label="Holi Days" htmlFor="holidays">
                            <Input id="holidays" v-model="form.holidays" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Working Days" htmlFor="holiworking">
                            <Input id="holiworking" v-model="form.holiworking" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Status" htmlFor="active">
                            <div class="flex items-center space-x-6">
                                <label class="inline-flex items-center space-x-2">
                                    <span
                                        class="inline-block rounded-full px-3 py-1 text-sm font-medium"
                                        :class="form.active == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    >
                                        {{ form.active == '1' ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                        </FormGroup>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="showDialogOpen = false"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
