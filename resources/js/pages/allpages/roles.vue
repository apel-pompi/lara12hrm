<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
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
import { ArrowUpDown, ChevronDown, Plus, User, X } from 'lucide-vue-next';

import DropdownAction from '@/components/DataTable.vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Roles', href: '/roles' }];

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
    roles: Roles[];
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
    checkboxes.forEach(checkbox => {
        checkbox.checked = target.checked;
        const value = parseInt(checkbox.value);
        if (target.checked) {
            // Add if not already in form.permissionid
            if (!form.permissionid.includes(value)) {
                form.permissionid.push(value);
            }
        }else{
            // Remove if it exists
            const index = form.permissionid.indexOf(value);
            if (index !== -1) {
                form.permissionid.splice(index, 1);
            }
        }
    });
};
</script>

<template>
    <Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Input
                    class="max-w-sm"
                    placeholder="Filter Roles Name..."
                    :model-value="table.getColumn('name')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('name')?.setFilterValue($event)"
                />
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Roles </Button>
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
            <DialogContent class="max-w-[950px] overflow-hidden rounded-2xl p-0 shadow-xl">
                <DialogHeader class="border-b px-6 pt-6 pb-4">
                    <DialogTitle class="text-2xl font-semibold">
                        {{ isEditMode ? 'Edit Role' : 'Create Role' }}
                    </DialogTitle>
                    <DialogDescription class="mt-1 flex items-center text-gray-500"> Configure role permissions and access levels </DialogDescription>
                </DialogHeader>

                <!-- Content Scroll Area -->
                <div class="max-h-[65vh] space-y-6 overflow-y-auto px-6 py-4">
                    <!-- Role Name -->
                    <div class="space-y-2">
                        <Label class="flex items-center text-sm font-medium text-gray-700">
                            <User class="mr-2 h-4 w-4 text-gray-500" />
                            Role Name
                        </Label>
                        <div class="relative max-w-md">
                            <Input
                                v-model="form.name"
                                class="w-full rounded-lg border border-gray-300 py-2 pr-3 pl-9 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g. Admin"
                            />
                            <span v-if="errors?.name" class="text-sm text-red-600">{{ errors.name }}</span>
                        </div>
                    </div>

                    <!-- Permission Groups -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
                        <div
                            v-for="(group, index) in permissionGroups"
                            :key="index"
                            class="overflow-hidden rounded-xl border bg-white shadow-sm"
                        >
                            <!-- Group Header -->
                            <div class="flex items-center bg-blue-600 px-4 py-3 text-white">
                                <input
                                    type="checkbox"
                                    :id="`basic_checkbox_${index}`"
                                    class="h-4 w-4 text-white"
                                    @change="checkPermissionByGroup(index.toString(), $event)"
                                />
                                <label :for="`basic_checkbox_${index}`" class="ml-3 cursor-pointer text-sm font-semibold">
                                    {{ group.group_name }}
                                </label>
                            </div>

                            <!-- Permissions -->
                            <div class="space-y-2 p-4">
                                <div 
                                    v-for="(permission, i) in permissionsByGroup[group.group_name]" 
                                    :key="i" 
                                    class="flex items-center space-x-2"
                                >
                                    <input
                                        type="checkbox"
                                        :id="`basic_checkbox_a${permission.id}`" 
                                        :value="permission.id"
                                        v-model="form.permissionid"
                                        :class="`h-4 w-4 text-blue-600 basic_checkbox_${index}`"
                                    />
                                    <label :for="`basic_checkbox_a${permission.id}`" class="text-sm text-gray-700">
                                        {{ permission.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="flex items-center justify-between border-t bg-gray-50 px-6 py-4">
                    <DialogClose as-child>
                        <Button
                            type="button"
                            variant="ghost"
                            class="flex items-center rounded-lg px-6 py-2 text-base font-medium text-gray-700 hover:bg-gray-100"
                        >
                            <X class="mr-2 h-4 w-4" />
                            Cancel
                        </Button>
                    </DialogClose>

                    <Button :disabled="form.processing" @click="submit">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        
    </AppLayout>
</template>
