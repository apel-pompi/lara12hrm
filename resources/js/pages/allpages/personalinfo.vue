<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import { Badge } from '@/components/ui/badge';
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
import { ArrowUpDown, Calendar as CalendarIcon, ChevronDown, Plus } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

import DropdownAction from '@/components/DataTable.vue';
import ImageUpload from '@/components/EmployeeImage.vue';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { CalendarDate, DateFormatter, getLocalTimeZone, today } from '@internationalized/date';
import { toast } from 'vue-sonner';

export interface Branch {
    id: number;
    branchname: string;
}

export interface Department {
    id: number;
    deptname: string;
}

export interface Designation {
    id: number;
    desname: string;
}

export interface PersonalInfo {
    id: number;
    empid: number;
    empname: string;
    joindate: string;
    branch_id: number;
    dept_id: number;
    des_id: number;
    dateofbirth: string;
    gender: number;
    present: string;
    permanent: string;
    phonepersonal: string;
    phoneoffice: string;
    email: string;
    blood: string;
    nidpass: string;
    photo: any;
    active: number;
    branch?: Branch;
    department?: Department;
    designation?: Designation;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Personal Information', href: '/personalinfo' }];

const props = defineProps<{
    personalinfo: PersonalInfo[];
    branch: Branch[];
    department: Department[];
    designation: Designation[];
}>();

const data = props.personalinfo;
const personalInfo = (props.personalinfo as PersonalInfo[])[0];
const currentImage = personalInfo?.photo ? `/storage/employee/${personalInfo?.photo}` : null;


const columns: ColumnDef<PersonalInfo, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<PersonalInfo> }) => {
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
        accessorKey: 'empid',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Employee ID', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'capitalize' }, row.getValue('empid')),
    },

    {
        accessorKey: 'empname',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Employee Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'capitalize' }, row.getValue('empname')),
    },

    {
        accessorKey: 'branch_id',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Branch Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'capitalize' }, row.original.branch?.branchname ?? '—'),
    },

    {
        accessorKey: 'dept_id',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Department', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'capitalize' }, row.original.department?.deptname ?? '—'),
    },
    {
        accessorKey: 'des_id',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Designation', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'capitalize' }, row.original.designation?.desname ?? '—'),
    },
    {
        accessorKey: 'phoneoffice',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Phone No', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'lowercase' }, row.getValue('phoneoffice')),
    },
    {
        accessorKey: 'blood',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Blood Group', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => h('div', { class: 'upercase' }, row.getValue('blood')),
    },
    {
        accessorKey: 'active',
        header: ({ column }: { column: Column<PersonalInfo, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<PersonalInfo> }) => {
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
        cell: ({ row }: { row: Row<PersonalInfo> }) => {
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
const birthvalue = ref<CalendarDate>();
const maxDate = today(getLocalTimeZone());
const df = new DateFormatter('en-CA', { dateStyle: 'short' });

interface FormErrors {
    empid: string;
    empname: string;
    joindate: string;
    branch_id: string;
    dept_id: string;
    des_id: string;
    dateofbirth: string;
    gender: string;
    present: string;
    permanent: string;
    phonepersonal: string;
    phoneoffice: string;
    email: string;
    blood: string;
    nidpass: string;
    photo: any;
    active: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    empid: '',
    empname: '',
    joindate: '',
    branch_id: '',
    dept_id: '',
    des_id: '',
    dateofbirth: '',
    gender: '',
    present: '',
    permanent: '',
    phonepersonal: '',
    phoneoffice: '',
    email: '',
    blood: '',
    nidpass: '',
    photo: null as File | null,
    active: '',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

// Format to YYYY-MM-DD
function formatDate(date: CalendarDate | undefined) {
    if (!date) return '';
    return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
}

// Watch calendar selection → update form
watch(value, (newVal) => {
    form.joindate = formatDate(newVal);
});

watch(birthvalue, (newVal) => {
    form.dateofbirth = formatDate(newVal);
});

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/personalinfo/${id}`);
        if (!res.ok) {
            toast.error('Server error while fetching personal info details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.id = data.id;
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
        const res = await fetch(`/personalinfo/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching personal info details.');
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
    if (isEditMode.value && form.id) {
        const action = route('personalinfo.update', form.id);

        const formData = new FormData();

        Object.entries(form).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        });

        if (form.photo instanceof File) {
            formData.append('photo', form.photo);
        }
        formData.append('_method', 'PUT');

        router.post(action, formData, {
            forceFormData: true,
            onSuccess: () => {
                toast('Success', {
                    description: 'Personal information updated successfully',
                });

                setTimeout(() => {
                    form.reset();
                    router.visit(route('personalinfo.index').toString(), {
                        only: ['personalinfos'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', {
                    description: firstError,
                });
            },
        });
    } else {
        form.post(route('personalinfo.store'), {
            onSuccess: () => {
                toast('Success', {
                    description: 'Personal information updated successfully',
                });

                setTimeout(() => {
                    form.reset();
                    router.visit(route('personalinfo.index').toString(), {
                        only: ['personalinfos'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', {
                    description: firstError,
                });
            },
        });
    }
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this personal info?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/personalinfo/show/${id}`, {
        onSuccess: () => {
            toast.success('Personal Info deleted successfully');
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
    <Head title="Personal Information" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Input
                    class="max-w-sm"
                    placeholder="Filter Branch Name..."
                    :model-value="table.getColumn('empid')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('empid')?.setFilterValue($event)"
                />
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Personal Info </Button>
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
            <DialogContent class="h-[auto] w-full max-w-[95vw] overflow-y-auto sm:max-w-[900px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Personal Info' : 'Create Personal Info' }}</DialogTitle>
                    <DialogDescription>Fill in the employee details below. Click save when you're done.</DialogDescription>
                </DialogHeader>

                <!-- Responsive grid layout -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="empid">Employee ID</Label>
                            <Input id="empid" placeholder="Enter Employee ID" v-model="form.empid" autofocus />
                            <span v-if="errors?.empid" class="text-sm text-red-600">{{ errors.empid }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="empname">Employee Name</Label>
                            <Input id="empname" placeholder="Enter Employee Name" v-model="form.empname" autofocus />
                            <span v-if="errors?.empname" class="text-sm text-red-600">{{ errors.empname }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="joindate">Joining Date</Label>
                            <Popover>
                                <PopoverTrigger as-child>
                                    <Button
                                        variant="outline"
                                        :class="cn('w-full justify-start text-left font-normal', !value && 'text-muted-foreground')"
                                    >
                                        <CalendarIcon class="mr-2 h-4 w-4" />
                                        {{ value ? df.format(value.toDate(getLocalTimeZone())) : 'Pick a joining date' }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto p-0">
                                    <Calendar v-model="value" :maxValue="maxDate" autofocus />
                                </PopoverContent>
                            </Popover>
                            <span v-if="errors?.joindate" class="text-sm text-red-600">{{ errors.joindate }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="dateofbirth">Date of Birth</Label>
                            <Popover>
                                <PopoverTrigger as-child>
                                    <Button
                                        variant="outline"
                                        :class="cn('w-full justify-start text-left font-normal', !birthvalue && 'text-muted-foreground')"
                                    >
                                        <CalendarIcon class="mr-2 h-4 w-4" />
                                        {{ birthvalue ? df.format(birthvalue.toDate(getLocalTimeZone())) : 'Pick a date of birth' }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto p-0">
                                    <Calendar v-model="birthvalue" :maxValue="maxDate" autofocus />
                                </PopoverContent>
                            </Popover>
                            <span v-if="errors?.dateofbirth" class="text-sm text-red-600">{{ errors.dateofbirth }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="phonepersonal">Personal Phone No</Label>
                            <Input id="phonepersonal" placeholder="Enter personal phone no" v-model="form.phonepersonal" autofocus />
                            <span v-if="errors?.phonepersonal" class="text-sm text-red-600">{{ errors.phonepersonal }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="phoneoffice">Official Phone No</Label>
                            <Input id="phoneoffice" placeholder="Enter official phone no" v-model="form.phoneoffice" autofocus />
                            <span v-if="errors?.phoneoffice" class="text-sm text-red-600">{{ errors.phoneoffice }}</span>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="branch_id">Branch Name</Label>
                            <Select v-model="form.branch_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Branch" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="b in branch" :key="b.id" :value="b.id">{{ b.branchname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.branch_id" class="text-sm text-red-600">{{ errors.branch_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="dept_id">Department Name</Label>
                            <Select v-model="form.dept_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Department" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="d in department" :key="d.id" :value="d.id">{{ d.deptname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.dept_id" class="text-sm text-red-600">{{ errors.dept_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="des_id">Designation</Label>
                            <Select v-model="form.des_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Designation" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="g in designation" :key="g.id" :value="g.id">{{ g.desname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.des_id" class="text-sm text-red-600">{{ errors.des_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input id="email" placeholder="Enter email" v-model="form.email" autofocus />
                            <span v-if="errors?.email" class="text-sm text-red-600">{{ errors.email }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="photo" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                Employee Photo <span class="text-red-500">*</span>
                            </Label>
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <ImageUpload @image="(file) => (form.photo = file)" :Image="personalInfo?.photo" :disabled="form.processing" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Recommended size: 256x256px</p>
                                    <p class="text-xs text-gray-500">Max size: 2MB</p>
                                </div>
                                <span v-if="form.errors.photo" class="text-xs text-red-600">{{ form.errors.photo }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="permanent">Permanent Address</Label>
                            <Input id="permanent" placeholder="Enter permanent address" v-model="form.permanent" />
                            <span v-if="errors?.permanent" class="text-sm text-red-600">{{ errors.permanent }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="present">Present Address</Label>
                            <Input id="present" placeholder="Enter present address" v-model="form.present" />
                            <span v-if="errors?.present" class="text-sm text-red-600">{{ errors.present }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="nidpass">NID / Passport No</Label>
                            <Input id="nidpass" placeholder="Enter NID or Passport No" v-model="form.nidpass" />
                            <span v-if="errors?.nidpass" class="text-sm text-red-600">{{ errors.nidpass }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="blood">Blood Group</Label>
                            <Select v-model="form.blood">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Blood" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="O Positive">O Positive</SelectItem>
                                        <SelectItem value="O Negative">O Negative</SelectItem>

                                        <SelectItem value="A Positive">A Positive</SelectItem>
                                        <SelectItem value="A Negative">A Negative</SelectItem>

                                        <SelectItem value="B Positive">B Positive</SelectItem>
                                        <SelectItem value="B Negative">B Negative</SelectItem>

                                        <SelectItem value="AB Positive">AB Positive</SelectItem>
                                        <SelectItem value="AB Negative">AB Negative</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.blood" class="text-sm text-red-600">{{ errors.blood }}</span>
                        </div>
                        <div class="space-y-2">
                            <Label for="gender">Gender</Label>
                            <Select v-model="form.gender">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Gender" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="1">Man</SelectItem>
                                        <SelectItem value="2">Woman</SelectItem>
                                        <SelectItem value="3">Other's</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.gender" class="text-sm text-red-600">{{ errors.gender }}</span>
                        </div>
                        <div class="space-y-2">
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
                        <div class="pt-4">
                            <Button :disabled="form.processing" @click="submit" class="w-full">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                            </Button>
                        </div>
                    </div>
                </div>

                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="w-full sm:w-auto">Close</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
            <DialogContent class="h-[auto] w-full max-w-[95vw] overflow-y-auto sm:max-w-[900px]">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show employee details</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this employee. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Employee ID" htmlFor="empid">
                                <Input id="empid" v-model="form.empid" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Employee Name" htmlFor="empname">
                                <Input id="empname" v-model="form.empname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Joining Date" htmlFor="joindate">
                                <Input id="joindate" v-model="form.joindate" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Date of Birth" htmlFor="dateofbirth">
                                <Input id="dateofbirth" v-model="form.dateofbirth" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Personal Phone No" htmlFor="phonepersonal">
                                <Input id="phonepersonal" v-model="form.phonepersonal" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Official Phone No" htmlFor="phoneoffice">
                                <Input id="phoneoffice" v-model="form.phoneoffice" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                    </div>
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Branch Name" htmlFor="branch_id">
                                <Input id="branch_id" v-model="form.branch.branchname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Department Name" htmlFor="dept_id">
                                <Input id="dept_id" v-model="form.department.deptname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Designation" htmlFor="des_id">
                                <Input id="des_id" v-model="form.designation.desname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Email" htmlFor="email">
                                <Input id="email" v-model="form.email" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Email" htmlFor="email">
                                <Input id="email" v-model="form.email" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4">
                            <h3 class="mb-3 text-sm font-medium text-gray-800">Employee Photo</h3>
                            <div class="flex items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white p-6">
                                <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-gray-200">
                                    <img :src="currentImage || '/storage/employee/default.png'" alt="preview" class="object-cover object-center" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Permanent Address" htmlFor="permanent">
                                <Input id="permanent" v-model="form.permanent" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Present Address" htmlFor="present">
                                <Input id="present" v-model="form.present" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="NID or Passport No" htmlFor="nidpass">
                                <Input id="nidpass" v-model="form.nidpass" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Blood Group" htmlFor="blood">
                                <Input id="blood" v-model="form.blood" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Gender" htmlFor="gender">
                                <select id="gender" v-model="form.gender" :disabled="!isEditMode">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
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
