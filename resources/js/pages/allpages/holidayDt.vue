<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { h, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
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
import { ArrowUpDown, Calendar as CalendarIcon, ChevronDown, CornerDownLeft, Plus } from 'lucide-vue-next';

import DropdownAction from '@/components/DataTable.vue';
import { Calendar } from '@/components/ui/calendar';
import { CalendarDate, DateFormatter, getLocalTimeZone, today } from '@internationalized/date';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Label from '@/components/ui/label/Label.vue';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
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
    holiyear: string;
    holimonth: number;
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
const monthName = monthNames[props.holidayHd.holimonth];

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

const value = ref<CalendarDate>();
const maxDate = today(getLocalTimeZone());
const df = new DateFormatter('en-CA', { dateStyle: 'short' });

interface FormErrors {
    holihd_id?: string;
    holidate?: string;
    holitypes?: string;
}

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

// Format to YYYY-MM-DD
function formatDate(date: CalendarDate | undefined) {
    if (!date) return '';
    return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
}

// Watch calendar selection → update form
watch(value, (newVal) => {
    form.holidate = formatDate(newVal);
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
        // Set date picker value
        if (data.data.holidate) {
            const [year, month, day] = data.data.holidate.split('-').map(Number);
            value.value = new CalendarDate(year, month, day);
        } else {
        }

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
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToHolidayHd"><CornerDownLeft></CornerDownLeft> Manage Holiday </Button>
                
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
                    <DialogTitle>{{ isEditMode ? 'Edit Holiday Details' : 'Create Holiday Details' }}</DialogTitle>
                    <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-2 gap-5">
                    <div class="grid gap-y-3">
                        <Input hidden id="holihd_id" v-model="form.holihd_id" />
                        <Label>Year Name: {{ props.holidayHd.holiyear }}</Label>
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
                        <Popover>
                            <PopoverTrigger as-child>
                                <Button variant="outline" :class="cn('justify-start text-left font-normal', !value && 'text-muted-foreground')">
                                    <CalendarIcon class="mr-2 h-4 w-4" />
                                    {{ value ? df.format(value.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                </Button>
                            </PopoverTrigger>
                            <PopoverContent class="w-auto p-0">
                                <Calendar v-model="value" :maxValue="maxDate" initial-focus />
                            </PopoverContent>
                        </Popover>
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
