<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { h, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { cn, valueUpdater } from '@/lib/utils';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

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
import { ArrowUpDown, ChevronDown, CornerDownLeft, Plus } from 'lucide-vue-next';

import DropdownAction from '@/components/DataTable.vue';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Label from '@/components/ui/label/Label.vue';
import { toast } from 'vue-sonner';

import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Holiday Details', href: '/holidaydt' }];

export interface HolidayDt {
    id: number;
    holidate: string;
    holitypes: string;
    holihd_id: string;
}
export interface HolidayHd {
    id: number;
    branch_id: string;
    yearname: string;
    monthname: number;
    holidays: string;
    holiworking: string;
    active: string;
    branch?: Branch;
}

export interface Branch {
    id: number;
    branchname: string;
}

const props = defineProps<{
    holidayHd: HolidayHd;
    holidaydt: HolidayDt[];
}>();

const data = props.holidaydt;

const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const monthName = monthNames[props.holidayHd.monthname];

const columns: ColumnDef<HolidayDt, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<HolidayDt> }) => {
            const pageIndex = table.getState().pagination.pageIndex;
            const pageSize = table.getState().pagination.pageSize;
            const rowIndex = row.index;
            return h('div', rowIndex + 1 + pageIndex * pageSize);
        },
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'holidate',
        header: ({ column }: { column: Column<HolidayDt, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Date', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayDt> }) => h('div', { class: 'capitalize' }, row.getValue('holidate')),
    },

    {
        accessorKey: 'holitypes',
        header: ({ column }: { column: Column<HolidayDt, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Holi Types', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<HolidayDt> }) => h('div', { class: 'capitalize' }, row.getValue('holitypes')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<HolidayDt> }) => {
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
    holihd_id?: string;
    holidate?: string;
    holitypes?: string;
}

const dtdate = ref<string | null>(null);

watch(dtdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.holidate = newDate.toISOString().split('T')[0];
    }
});

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    holihd_id: props.holidayHd.id,
    holidate: '',
    holitypes: '',
    branch: '',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/holidaydt/${id}`);
        if (!res.ok) {
            toast.error('holiday details not nessary.');
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
        const res = await fetch(`/holidaydt/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching holiday details.');
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
    const action = isEditMode.value && form.id ? route('holidaydt.update', form.id) : route('holidaydt.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Holiday Details ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('holidaydt.create', props.holidayHd.id), {
                    only: ['holiday_dts'],
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
    if (!confirm('Are you sure you want to delete this holiday details ?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(route('holidaydt.destroy', id), {
        onSuccess: () => {
            toast.success('Holiday details deleted successfully');
            router.reload({
                only: ['holiday_dts'],
            });
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToHolidayHd = () => {
    router.visit('/holidayHd');
};
</script>
<template>
    <Head title="Holiday Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <!-- Header Section -->

            <!-- Top Row -->
            <div class="flex flex-col gap-3 p-3 lg:flex-row lg:items-center lg:justify-between">
                <!-- Left Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <Button variant="outline" size="sm" @click="goToHolidayHd" class="rounded-xl">
                        <CornerDownLeft class="mr-2 h-4 w-4" />
                        Manage Holiday
                    </Button>

                    <Button size="sm" @click="showDailogCreate" class="rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Holiday
                    </Button>
                </div>

                <!-- Column Dropdown -->
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="w-full rounded-xl sm:w-auto">
                            Columns
                            <ChevronDown class="ml-2 h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent class="w-52">
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

            <!-- Table Card -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Holiday Details List</h2>
                    <p class="text-sm text-gray-500">Manage all Holiday Details from here.</p>
                </div>
                <Table class="min-w-full">
                    <!-- Table Head -->
                    <TableHeader>
                        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id" class="bg-gray-50">
                            <TableHead
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                                :data-pinned="header.column.getIsPinned()"
                                :class="
                                    cn(
                                        'px-4 py-3 font-semibold whitespace-nowrap text-gray-700',
                                        { 'sticky z-10 bg-white': header.column.getIsPinned() },
                                        header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                    )
                                "
                            >
                                <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                            </TableHead>
                        </TableRow>
                    </TableHeader>

                    <!-- Table Body -->
                    <TableBody>
                        <template v-if="table.getRowModel().rows?.length">
                            <template v-for="row in table.getRowModel().rows" :key="row.id">
                                <TableRow :data-state="row.getIsSelected() && 'selected'" class="border-t transition hover:bg-gray-50">
                                    <TableCell
                                        v-for="cell in row.getVisibleCells()"
                                        :key="cell.id"
                                        :data-pinned="cell.column.getIsPinned()"
                                        :class="
                                            cn(
                                                'px-4 py-3 whitespace-nowrap',
                                                { 'sticky z-10 bg-white': cell.column.getIsPinned() },
                                                cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                            )
                                        "
                                    >
                                        <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                    </TableCell>
                                </TableRow>

                                <!-- Expanded Row -->
                                <TableRow v-if="row.getIsExpanded()">
                                    <TableCell :colspan="row.getAllCells().length" class="bg-gray-50 px-4 py-3 text-sm text-gray-600">
                                        {{ row.original }}
                                    </TableCell>
                                </TableRow>
                            </template>
                        </template>

                        <!-- No Data -->
                        <TableRow v-else>
                            <TableCell :colspan="columns.length" class="h-24 text-center text-gray-500"> No results found. </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 border bg-white px-4 py-4">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <!-- Left -->
                        <div class="text-sm text-gray-600">
                            {{ table.getFilteredSelectedRowModel().rows.length }}
                            of
                            {{ table.getFilteredRowModel().rows.length }}
                            row(s) selected.
                        </div>

                        <!-- Right -->
                        <div class="flex justify-end gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                class="rounded-xl"
                                :disabled="!table.getCanPreviousPage()"
                                @click="table.previousPage()"
                            >
                                Previous
                            </Button>

                            <Button
                                size="sm"
                                class="rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
                                :disabled="!table.getCanNextPage()"
                                @click="table.nextPage()"
                            >
                                Next
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Pagination -->
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-206.25">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Holiday Details' : 'Create Holiday Details' }}</DialogTitle>
                    <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-2 gap-5">
                    <div class="grid gap-y-3">
                        <Input hidden id="holihd_id" v-model="form.holihd_id" />
                        <Label>Year Name: {{ props.holidayHd.yearname }}</Label>
                        <Label>Branch Name: {{ props.holidayHd.branch.branchname }}</Label>
                    </div>
                    <div class="grid gap-y-3">
                        <Label>Month Name: {{ monthName }}</Label>
                        <Label>Holi Days: {{ props.holidayHd.holidays }}</Label>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-5">
                    <div class="grid gap-y-5">
                        <Label for="holidate">Holi Date</Label>
                        <VueDatePicker v-model="dtdate" :format="'yyyy-MM-dd'" :enable-time-picker="false" placeholder="Holi Date" auto-apply />
                        <span v-if="errors?.holidate" class="text-sm text-red-600">{{ errors.holidate }}</span>
                    </div>
                    <div class="grid gap-y-5">
                        <Label for="holitypes">Holi Types</Label>
                        <Select v-model="form.holitypes">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Types" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="Weekend Holiday">Weekend Holiday</SelectItem>
                                    <SelectItem value="General Holiday">General Holiday</SelectItem>
                                    <SelectItem value="National Holiday">National Holiday</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <span v-if="errors?.holitypes" class="text-sm text-red-600">{{ errors.holitypes }}</span>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <Button :disabled="form.processing" @click="submit">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                    </Button>
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
