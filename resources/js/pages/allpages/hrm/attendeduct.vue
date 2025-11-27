<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import HrmLayout from '@/layouts/settings/hrmLayout.vue';
import { cn, valueUpdater } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { h, ref } from 'vue';

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

import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'vue-sonner';

export interface Branch {
    id: number;
    branchname: string;
}

export interface AttenDeduct{
    id: number;
    branch_id: string;
    starttime: string;
    endtime: string;
    deduct: string;
    active: string;
    branch?: Branch;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Attendance Deduct', href: '/attendeduct' }];

const props = defineProps<{
    attendeduct: AttenDeduct[];
    branch: Branch[];
}>();

const data = props.attendeduct;

function formatTo12Hour(time: string): string {
    return new Date(`1970-01-01T${time}`).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
    });
}

const columns: ColumnDef<AttenDeduct, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<AttenDeduct> }) => {
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
        header: ({ column }: { column: Column<AttenDeduct, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Branch Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<AttenDeduct> }) => h('div', { class: 'capitalize' }, row.original.branch?.branchname ?? '—'),
    },

    {
        accessorKey: 'starttime',
        header: ({ column }: { column: Column<AttenDeduct, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Start Time', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<AttenDeduct> }) => h('div', { class: 'capitalize' }, formatTo12Hour(row.getValue('starttime'))),
    },
    {
        accessorKey: 'endtime',
        header: ({ column }: { column: Column<AttenDeduct, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['End Time', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<AttenDeduct> }) => h('div', { class: 'capitalize' }, formatTo12Hour(row.getValue('endtime'))),
    },
    {
        accessorKey: 'deduct',
        header: ({ column }: { column: Column<AttenDeduct, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Deduct Time', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<AttenDeduct> }) => h('div', { class: 'capitalize' }, row.getValue('deduct')),
    },
    {
        accessorKey: 'active',
        header: ({ column }: { column: Column<AttenDeduct, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<AttenDeduct> }) => {
            const status = row.getValue('active');
            if (status) {
                return h('div', h(Badge, 'Active'));
            } else {
                return h('div', h(Badge, { variant: 'outline' }, 'Inactive'));
            }
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<AttenDeduct> }) => {
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
    starttime?: string;
    endtime: string;
    deduct: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_id: '',
    starttime: '',
    endtime: '',
    deduct: '',
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
        const res = await fetch(`/attendeduct/${id}`);

        if (!res.ok) {
            toast.error('Server error while fetching attendance deduct details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.id = data.id;
        form.branch_id = String(data.branch_id);
        form.starttime = data.starttime;
        form.endtime = data.endtime;
        form.active = String(data.active);
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
        const res = await fetch(`/attendeduct/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching attendence deduct details.');
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
    const action = isEditMode.value && form.id ? route('attendeduct.update', form.id) : route('attendeduct.store');
    const method = isEditMode.value ? 'put' : 'post';
    form[method](action, {
        onSuccess: () => {
            const flash = usePage().props.flash;

            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            if (flash?.error) {
                toast('error', {
                    description: flash.error,
                });
                return;
            }
            showDialog.value = false;
            form.reset();
        },
        preserveScroll: true,
        preserveState: false,
        
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
    if (!confirm('Are you sure you want to delete this attendence deduct?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/attendeduct/show/${id}`, {
        onSuccess: () => {
            const flash = usePage().props.flash;

            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            if (flash?.error) {
                toast('error', {
                    description: flash.error,
                });
                return;
            }
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Attendance Deduct" />
        <HrmLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Input
                        class="max-w-sm"
                        placeholder="Filter Branch Name..."
                        :model-value="table.getColumn('branch_id')?.getFilterValue() as string"
                        @update:model-value="table.getColumn('branch_id')?.setFilterValue($event)"
                    />
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create </Button>
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
                        <DialogTitle>{{ isEditMode ? 'Edit Attendance Setting' : 'Create Attendance Setting' }}</DialogTitle>
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
                                <Label for="starttime">Start Time</Label><br />
                                <Input type="time" id="starttime" step="1" v-model="form.starttime" class="max-w-sm" autofocus />
                                <span v-if="errors?.starttime" class="text-sm text-red-600">{{ errors.starttime }}</span>
                            </div>

                            <div class="grid gap-2">
                                <Label for="endtime">End Time</Label>
                                <Input type="time" id="endtime" step="1" v-model="form.endtime" class="max-w-sm" autofocus />
                                <span v-if="errors?.endtime" class="text-sm text-red-600">{{ errors.endtime }}</span>
                            </div>
                        </div>
                        <div class="grid gap-y-3">
                            <div class="grid gap-2">
                                <Label for="active">Deduct Hours</Label>
                                <Input type="number" id="deduct"  v-model="form.deduct" class="max-w-sm" autofocus />
                                <span v-if="errors?.deduct" class="text-sm text-red-600">{{ errors.deduct }}</span>
                            </div>
                            <div class="grid gap-2">
                                <Label for="active">Status</Label>
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center space-x-2">
                                        <input type="radio" value="1" v-model="form.active" class="form-radio text-primary-600" />
                                        <span>Active</span>
                                    </label>

                                    <label class="inline-flex items-center space-x-2">
                                        <input type="radio" value="0" v-model="form.active" class="form-radio text-primary-600" />
                                        <span>Inactive</span>
                                    </label>
                                </div>
                                <span v-if="errors?.active" class="text-sm text-red-600">{{ errors.active }}</span>
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

            <!-- Show Dialog -->
            <Dialog v-model:open="showDialogOpen">
                <DialogContent class="max-w-4xl rounded-2xl p-6 shadow-xl">
                    <DialogHeader>
                        <DialogTitle class="text-2xl font-semibold">Show Attendance Deduct</DialogTitle>
                        <DialogDescription class="text-muted-foreground text-sm"> View the details of this attendance deduct. </DialogDescription>
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
                            <!-- Present Time -->
                            <FormGroup label="Start Time" htmlFor="starttime">
                                <Input id="starttime" v-model="form.starttime" :disabled="!isEditMode" />
                            </FormGroup>
                            <FormGroup label="End Time" htmlFor="endtime">
                                <Input id="endtime" v-model="form.endtime" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <FormGroup label="Deduct Time" htmlFor="deduct">
                                <Input id="deduct" v-model="form.deduct" :disabled="!isEditMode" />
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
        </HrmLayout>
    </AppLayout>
</template>
