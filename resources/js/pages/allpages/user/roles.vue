<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import UserLayout from '@/layouts/settings/userLayout.vue';
import { cn, valueUpdater } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { h, ref } from 'vue';

import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
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
import { ArrowUpDown, ChevronDown, Plus, User } from 'lucide-vue-next';

import DropdownAction from '@/components/DataTable.vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: '/roles' }];

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

export interface Roles {
    id: number;
    name: string;
    group_name: string;
    permissions: Record<string, string[]>;
}

type Permission = {
    id: number;
    name: string;
    group_name?: string;
};

type PermissionGroup = {
    group_name: string;
};

const props = defineProps<{
    roles: Paginated<Roles>;
    permissionGroups: PermissionGroup[];
    permissionsByGroup: Record<string, Permission[]>;
}>();

const data = props.roles;

//for permission collapse
const expandedGroups = ref<Record<number, Set<string>>>({});
function toggleGroup(roleId: number, groupName: string) {
    if (!expandedGroups.value[roleId]) {
        expandedGroups.value[roleId] = new Set();
    }
    const groupSet = expandedGroups.value[roleId];
    if (groupSet.has(groupName)) {
        groupSet.delete(groupName);
    } else {
        groupSet.add(groupName);
    }
}

const columns: ColumnDef<Roles, any>[] = [
    {
        id: 'sl',
        header: () => 'SL',
        cell: ({ row }: { row: Row<Roles> }) => {
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
        header: ({ column }: { column: Column<Roles, unknown> }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Role Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }: { row: Row<Roles> }) => h('div', { class: 'capitalize' }, row.getValue('name')),
    },

    {
        id: 'permissions',
        header: () => 'Permission Name',
        cell: ({ row }: { row: Row<Roles> }) => {
            const role = row.original;
            const permissions = role.permissions;

            return h('div', { class: 'space-y-3' }, [
                // Horizontal container for group names
                h(
                    'div',
                    { class: 'flex flex-wrap gap-2' },
                    Object.keys(permissions).map((group) =>
                        h(
                            'button',
                            {
                                key: group,
                                class: [
                                    'bg-teal-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow hover:bg-teal-700',
                                    expandedGroups.value[role.id]?.has(group) ? 'bg-teal-800' : '',
                                ],
                                onClick: () => toggleGroup(role.id, group),
                            },
                            group,
                        ),
                    ),
                ),

                // Permissions display (below the group buttons)
                ...Object.entries(permissions).map(([group, perms]) =>
                    expandedGroups.value[role.id]?.has(group)
                        ? h('div', { class: 'mt-2' }, [
                              h('div', { class: 'text-xs font-medium text-gray-500 mb-1' }, group),
                              h(
                                  'div',
                                  { class: 'flex flex-wrap gap-2' },
                                  perms.map((perm, i) =>
                                      h(
                                          'span',
                                          {
                                              key: i,
                                              class: 'border border-teal-600 text-teal-600 text-xs font-semibold px-3 py-1 rounded hover:bg-teal-50',
                                          },
                                          perm,
                                      ),
                                  ),
                              ),
                          ])
                        : null,
                ),
            ]);
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<Roles> }) => {
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
    name?: string;
}
const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    permissionid: [] as number[],
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('roles.update', form.id) : route('roles.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Roles ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('roles.index'), {
                    only: ['roles'],
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
const onShow = async (id: number) => {
    try {
        const res = await fetch(`/roles/${id}`);

        if (!res.ok) {
            toast.error('Roles show not necessary !');
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
        toast.error('Roles show not necessary !');
    }
};

const permissionGroupsEdit = ref<any[]>([]);
const permissionsByGroupEdit = ref<Record<string, any[]>>({});

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/roles/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching branch details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.roles);
        form.id = data.roles.id;
        form.permissionid = data.assignedPermissions; //pre check
        permissionGroupsEdit.value = data.permissionGroup;
        // Group permissions
        permissionsByGroupEdit.value = {};
        data.permissions.forEach((perm: any) => {
            const group = perm.group_name;
            if (!permissionsByGroupEdit.value[group]) {
                permissionsByGroupEdit.value[group] = [];
            }
            permissionsByGroupEdit.value[group].push(perm);
        });
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};
const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this roles?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/roles/show/${id}`, {
        onSuccess: () => {
            toast('Success', {
                description: `Roles delete successfully`,
            });
            setTimeout(() => {
                router.visit(route('roles.index'), {
                    only: ['roles'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const checkPermissionByGroup = (className: string | number, checkThis: Event) => {
    const target = checkThis.target as HTMLInputElement;
    const checkboxes = document.querySelectorAll<HTMLInputElement>(`.basic_checkbox_${className}`);
    checkboxes.forEach((checkbox) => {
        checkbox.checked = target.checked;
        const value = parseInt(checkbox.value);
        if (target.checked) {
            // Add if not already in form.permissionid
            if (!form.permissionid.includes(value)) {
                form.permissionid.push(value);
            }
        } else {
            // Remove if it exists
            const index = form.permissionid.indexOf(value);
            if (index !== -1) {
                form.permissionid.splice(index, 1);
            }
        }
    });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('roles.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Roles" />
        <UserLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Toolbar -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <!-- Create -->
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Create Roles
                    </Button>
                    <!-- Search Combobox -->
                    <Input
                        class="max-w-sm"
                        placeholder="Filter Roles Name..."
                        :model-value="table.getColumn('name')?.getFilterValue() as string"
                        @update:model-value="table.getColumn('name')?.setFilterValue($event)"
                    />

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button class="ml-auto dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline">
                                Columns <ChevronDown class="ml-2 h-4 w-4" />
                            </Button>
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

                <!-- Table Card -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Roles List</h2>
                        <p class="text-sm text-gray-500">Manage all roles from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-200" v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
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

                <!-- Pagination -->
                <div class="mt-5 flex flex-col items-center justify-between gap-4 md:flex-row">
                    <!-- Left -->
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                        <label>Show:</label>

                        <select v-model="perPage" @change="changePerPage" class="rounded-md border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                {{ size }}
                            </option>
                        </select>

                        <span> Showing {{ roles.from }} to {{ roles.to }} of {{ roles.total }} results </span>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap gap-2">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            variant="outline"
                            size="sm"
                            :class="[
                                link.active ? 'bg-indigo-600 text-white hover:bg-indigo-700' : '',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]"
                            @click="goToPage(link.url)"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="w-full max-w-lg rounded-xl border bg-white shadow-lg">
                    <!-- Header -->
                    <DialogHeader class="border-b px-5 py-4">
                        <DialogTitle class="text-xl font-semibold text-gray-800">
                            {{ isEditMode ? 'Edit Role' : 'Create Role' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500"> Configure role permissions and access levels </DialogDescription>
                    </DialogHeader>

                    <!-- Content -->
                    <div class="max-h-[70vh] space-y-6 overflow-y-auto px-5 py-5">
                        <!-- Role Name -->
                        <div>
                            <Label class="mb-1 flex items-center text-sm font-medium text-gray-700">
                                <User class="mr-2 h-4 w-4 text-gray-500" /> Role Name
                            </Label>
                            <Input
                                v-model="form.name"
                                placeholder="e.g. Admin"
                                class="w-full rounded-md border border-gray-200 py-2 pr-3 pl-9 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                            <span v-if="errors?.name" class="text-xs text-red-600">{{ errors.name }}</span>
                        </div>

                        <!-- Permission Groups -->
                        <div class="grid grid-cols-1 gap-6">
                            <div v-for="(group, index) in permissionGroups" :key="index" class="flex flex-col rounded-lg border bg-white shadow-sm">
                                <!-- Group Header -->
                                <div class="flex items-center border-b bg-gray-50 px-4 py-2">
                                    <input
                                        type="checkbox"
                                        :id="`basic_checkbox_${index}`"
                                        class="h-4 w-4 rounded text-blue-600 focus:ring-blue-500"
                                        @change="checkPermissionByGroup(index.toString(), $event)"
                                    />
                                    <label :for="`basic_checkbox_${index}`" class="ml-2 cursor-pointer text-sm font-semibold text-gray-700">
                                        {{ group.group_name }}
                                    </label>
                                </div>

                                <!-- Permissions -->
                                <div class="max-h-60 space-y-2 overflow-y-auto p-4">
                                    <div
                                        v-for="permission in permissionsByGroup[group.group_name]"
                                        :key="permission.id"
                                        class="flex items-center space-x-2"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="`basic_checkbox_a${permission.id}`"
                                            :value="permission.id"
                                            v-model="form.permissionid"
                                            :class="`h-4 w-4 rounded text-blue-600 focus:ring-blue-500 basic_checkbox_${index}`"
                                        />
                                        <label :for="`basic_checkbox_a${permission.id}`" class="text-sm text-gray-600">
                                            {{ permission.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="flex justify-end gap-3 border-t bg-gray-50 px-5 py-4">
                        <DialogClose as-child>
                            <Button variant="outline" class="rounded-md px-5 py-2 text-sm">Cancel</Button>
                        </DialogClose>
                        <Button @click="submit" :disabled="form.processing" class="rounded-md px-5 py-2 text-sm">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </UserLayout>
    </AppLayout>
</template>
