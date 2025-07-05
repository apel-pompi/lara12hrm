<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn, valueUpdater } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
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
import { AlertCircle, ArrowUpDown, ChevronDown, CircleAlertIcon, Loader2, Plus } from 'lucide-vue-next';

import { h, ref } from 'vue';

import DropdownAction from '@/components/DataTable.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'User Permission', href: '/userpermission' }];

export interface Users {
    id: number;
    name: string;
    username: string;
    email: string;
    roles: { name: string }[];
}

const props = defineProps<{
    users: Array<{
        id: number;
        name: string;
        username: string;
        email: string;
        roles: { name: string }[];
    }>;
    roles: any[];
}>();

const data = props.users;

const columns: ColumnDef<Users, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<Users> }) => {
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
        accessorKey: 'name',
        header: ({ column }: { column: Column<Users, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Full Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<Users> }) => h('div', { class: 'capitalize' }, row.getValue('name')),
    },

    {
        accessorKey: 'username',
        header: ({ column }: { column: Column<Users, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['User Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<Users> }) => h('div', row.getValue('username')),
    },

    {
        accessorKey: 'email',
        header: ({ column }: { column: Column<Users, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<Users> }) => h('div', row.getValue('email')),
    },

    {
        id: 'roles',
        header: ({ column }: { column: Column<Users, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Role Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<Users> }) => {
            const roles = row.original.roles || [];

            if (roles.length === 0) {
                return h('div', '-');
            }

            return h(
                'div',
                { class: 'flex flex-wrap gap-2' },
                roles.map((role) => h(Badge, () => role.name)),
            );
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<Users> }) => {
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

const showPassword = ref<boolean>(false);

interface FormErrors {
    name?: string;
    username?: string;
    email: string;
    password: string;
    password_confirmation: string;
    permission: [];
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    permissions: [],
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/userpermission/${id}`);
        console.log(res);
        if (!res.ok) {
            toast.error('Server error while fetching userpermission details.');
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
        toast.error('userpermission show not necessary !');
    }
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/userpermission/${id}/edit`);
        if (!res.ok) {
            toast.error('Server error while fetching userpermission details.');
            return;
        }
        const data = await res.json();

        Object.assign(form, {
            id: data.users.id,
            name: data.users.name,
            username: data.users.username,
            email: data.users.email,
            password: '',
            password_confirmation: '',
            permissions: data.users.roles, // assuming roles = array of role IDs
        });

        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('userpermission.update', form.id) : route('userpermission.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `User Permission ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('userpermission.index'), {
                    only: ['userpermission'],
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
    if (!confirm('Are you sure you want to delete this User Permission?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/userpermission/show/${id}`, {
        onSuccess: () => {
            toast.success('User Permission deleted successfully');
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
    <Head title="User Permission" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Input
                    class="max-w-sm"
                    placeholder="Filter Roles Name..."
                    :model-value="table.getColumn('name')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('name')?.setFilterValue($event)"
                />
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create User </Button>
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
        <!-- Enhanced User Dialog Component -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[950px] overflow-hidden rounded-2xl p-0 shadow-xl">
                <!-- Header with gradient background -->
                <DialogHeader class="border-b px-6 pt-6 pb-4">
                    <DialogTitle class="text-2xl font-semibold">
                        {{ isEditMode ? 'Edit User' : 'Create New User' }}
                    </DialogTitle>
                    <DialogDescription class="mt-1 flex items-center text-gray-500">
                        {{ isEditMode ? 'Update user information' : 'Fill in the details to create a new user account' }}
                    </DialogDescription>
                </DialogHeader>

                <!-- Form Container -->
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Left Column - Personal Info -->
                        <div class="space-y-5">
                            <h3 class="border-b pb-2 text-lg font-medium text-gray-700">Personal Information</h3>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="name" class="text-sm font-medium text-gray-700">Full Name</Label>
                                    <Input
                                        id="name"
                                        placeholder="John Doe"
                                        v-model="form.name"
                                        class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p v-if="errors?.name" class="mt-1 flex items-center text-sm text-red-500">
                                        <AlertCircle class="mr-1 h-4 w-4" /> {{ errors.name }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="username" class="text-sm font-medium text-gray-700">Username</Label>
                                    <Input
                                        id="username"
                                        placeholder="johndoe"
                                        v-model="form.username"
                                        class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p v-if="errors?.username" class="mt-1 flex items-center text-sm text-red-500">
                                        <AlertCircle class="mr-1 h-4 w-4" /> {{ errors.username }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="email" class="text-sm font-medium text-gray-700">Email Address</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        placeholder="john@example.com"
                                        v-model="form.email"
                                        class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p v-if="errors?.email" class="mt-1 flex items-center text-sm text-red-500">
                                        <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.email }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Security & Permissions -->
                        <div class="space-y-5">
                            <h3 class="border-b pb-2 text-lg font-medium text-gray-700">Security & Permissions</h3>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="password" class="text-sm font-medium text-gray-700">
                                        {{ isEditMode ? 'New Password' : 'Password' }}
                                    </Label>
                                    <div class="relative">
                                        <Input
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            placeholder="••••••••"
                                            v-model="form.password"
                                            class="pr-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        />
                                        <button
                                            type="button"
                                            class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                            @click="showPassword = !showPassword"
                                        >
                                            <IconEye v-if="!showPassword" class="h-5 w-5" />
                                            <IconEyeOff v-else class="h-5 w-5" />
                                        </button>
                                    </div>
                                    <p v-if="errors?.password" class="mt-1 flex items-center text-sm text-red-500">
                                        <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.password }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="confirm_password" class="text-sm font-medium text-gray-700"> Confirm Password </Label>
                                    <Input
                                        id="password_confirmation"
                                        :type="showPassword ? 'text' : 'password'"
                                        placeholder="••••••••"
                                        v-model="form.password_confirmation"
                                        class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <p v-if="errors?.password_confirmation" class="mt-1 flex items-center text-sm text-red-500">
                                        <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.password_confirmation }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="role" class="text-sm font-medium text-gray-700">User Role</Label>
                                    <select
                                        id="roles"
                                        v-model="form.permissions"
                                        multiple
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option v-for="role in props.roles" :key="role.id" :value="Number(role.id)">{{ role.name }}</option>
                                    </select>
                                    <p v-if="errors?.permission" class="mt-1 flex items-center text-sm text-red-500">
                                        <IconAlertCircle class="mr-1 h-4 w-4" /> {{ errors.permission }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer with Actions -->
                    <DialogFooter class="mt-8 border-t pt-6">
                        <DialogClose as-child>
                            <Button type="button" variant="outline" class="px-6"> Cancel </Button>
                        </DialogClose>
                        <Button
                            type="submit"
                            class="bg-indigo-600 px-6 transition-colors hover:bg-indigo-700"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            <template v-if="form.processing">
                                <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                Processing...
                            </template>
                            <template v-else>
                                {{ isEditMode ? 'Update User' : 'Create User' }}
                            </template>
                        </Button>
                    </DialogFooter>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
