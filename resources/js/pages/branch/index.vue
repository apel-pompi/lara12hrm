<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head,router,useForm } from '@inertiajs/vue3';
import { h, ref } from 'vue'
import { Badge } from '@/components/ui/badge'
import FormGroup from '@/components/FormGroup.vue'

import { cn, valueUpdater } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  ColumnDef,
  Column,
  Row,
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown,Plus } from 'lucide-vue-next'

import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogClose,
} from '@/components/ui/dialog'

import DropdownAction from './DataTable.vue'

import { toast } from 'vue-sonner'



export interface Branch {
  id:number
  branchname: string
  description: string
  person: string
  designation: string
  address: string
  email: string
  phone: string
  active: number
}


const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Branch', href: '/branch' },
];

const props = defineProps<{
        branch: Branch[]
    }>()

const data = props.branch


const columns: ColumnDef<Branch, any>[] = [
      {
          id: 'sl',
          header: () => 'SL',
          cell: ({ row }: { row: Row<Branch> }) => {
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
          accessorKey: 'branchname',
          header: ({ column }: { column: Column<Branch, unknown> }) => {
          return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Branch Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'capitalize' }, row.getValue('branchname')),
      },

      {
          accessorKey: 'description',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Description', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'capitalize' }, row.getValue('description')),
      },

      {
          accessorKey: 'person',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Employee Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'capitalize' }, row.getValue('person')),
      },

      {
          accessorKey: 'designation',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Designation', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'lowercase' }, row.getValue('designation')),
      },
      {
          accessorKey: 'address',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Address', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'lowercase' }, row.getValue('address')),
      },
      {
          accessorKey: 'email',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'lowercase' }, row.getValue('email')),
      },
      {
          accessorKey: 'phone',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Phone', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => h('div', { class: 'lowercase' }, row.getValue('phone')),
      },
      {
          accessorKey: 'active',
          header: ({ column }: { column: Column<Branch, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Branch> }) => {
            const status = row.getValue('active');
            if(status){
              return h('div', h(Badge, 'Active'))
            }else{
              return h('div', h(Badge, {variant:'outline'}, 'Inactive'))
            }
          },
      },
      {
          id: 'actions',
          enableHiding: false,
          cell: ({ row }: { row: Row<Branch> }) => {
          const branch = row.original

              return h('div', { class: 'relative' }, h(DropdownAction, {
                  branch,
                  onShow,
                  onEdit,
                  onDelete,
                  onExpand: row.toggleExpanded,
              }))
          },
      },
  ]

 // Reactive states
 const sorting = ref([])
const columnFilters = ref([])
const columnVisibility = ref({})
const rowSelection = ref({})
const expanded = ref({})

const table = useVueTable({
  data,
  columns,
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getExpandedRowModel: getExpandedRowModel(),
  onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
  onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
  state: {
    get sorting() { return sorting.value },
    get columnFilters() { return columnFilters.value },
    get columnVisibility() { return columnVisibility.value },
    get rowSelection() { return rowSelection.value },
    get expanded() { return expanded.value },
    columnPinning: {
      left: ['status'],
    },
  },
})

interface FormErrors {
    branchname?: string;
    description?: string;
    person:string;
    contactperson?: string;
    designation?: string;
    address?: string;
    email?: string;
    phone?: string;
    active?: boolean;
}


const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branchname: '',
    description: '',
    person: '',
    designation: '',
    address: '',
    email: '',
    phone: '',
    active: '',
})



const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};


    
const onShow = async (id: number) => {
    try {
        const res = await fetch(`/branch/${id}`);

        if (!res.ok){
            toast.error('Server error while fetching branch details.');
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
        const res = await fetch(`/branch/${id}/edit`);
        
        if (!res.ok){
            toast.error('Server error while fetching branch details.');
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
  const action = isEditMode.value && form.id
                    ? route('branch.update', form.id)
                    : route('branch.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast("Success",{
                description: `Branch ${isEditMode.value ? 'updated' : 'created'} successfully`, 
            })
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('branch.index'), {
                    only: ['branches'],
                    preserveScroll: true,
                    preserveState: false
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast("Validation Error",{
                description: firstError,
            })
        },
    });
};
const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this branch?")) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/branch/show/${id}`, {
        onSuccess: () => {
            toast.success("Branch deleted successfully");
        },
        onError: () => {
            toast.success("Somethings wrong !");
        },
        preserveScroll: true,
        preserveState: false
    });
};
</script>

<template>
    <Head title="Branch" />
    <AppLayout :breadcrumbs="breadcrumbs">
      
        <div class="relative min-h-[100vh] flex-1 border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min px-4">
            <div class="flex gap-2 items-center py-4">
              <Input
                  class="max-w-sm"
                  placeholder="Filter Branch Name..."
                  :model-value="table.getColumn('branchname')?.getFilterValue() as string"
                  @update:model-value=" table.getColumn('branchname')?.setFilterValue($event)"
              />
            <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Branch </Button>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                <Button variant="outline" class="ml-auto">
                    Columns <ChevronDown class="ml-2 h-4 w-4" />
                </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                <DropdownMenuCheckboxItem
                    v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                    :key="column.id"
                    class="capitalize"
                    :model-value="column.getIsVisible()"
                    @update:model-value="(value) => {
                    column.toggleVisibility(!!value)
                    }"
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
                    v-for="header in headerGroup.headers" :key="header.id" :data-pinned="header.column.getIsPinned()"
                    :class="cn(
                        { 'sticky bg-background/95': header.column.getIsPinned() },
                        header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                    )"
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
                        v-for="cell in row.getVisibleCells()" :key="cell.id" :data-pinned="cell.column.getIsPinned()"
                        :class="cn(
                            { 'sticky bg-background/95': cell.column.getIsPinned() },
                            cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                        )"
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
                    <TableCell
                    :colspan="columns.length"
                    class="h-24 text-center"
                    >
                    No results.
                    </TableCell>
                </TableRow>
                </TableBody>
            </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
              <div class="flex-1 text-sm text-muted-foreground">
                  {{ table.getFilteredSelectedRowModel().rows.length }} of
                  {{ table.getFilteredRowModel().rows.length }} row(s) selected.
              </div>
              <div class="space-x-2">
                  <Button
                  variant="outline"
                  size="sm"
                  :disabled="!table.getCanPreviousPage()"
                  @click="table.previousPage()"
                  >
                  Previous
                  </Button>
                  <Button
                  variant="outline"
                  size="sm"
                  :disabled="!table.getCanNextPage()"
                  @click="table.nextPage()"
                  >
                  Next
                  </Button>
              </div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
          <DialogContent class="max-w-[825px]">
            <DialogHeader>
              <DialogTitle>{{ isEditMode ? 'Edit Branch' : 'Create Branch' }}</DialogTitle>
              <DialogDescription>
                Make changes to your profile here. Click save when you're done.
              </DialogDescription>
            </DialogHeader>
            <div class="grid grid-cols-2 gap-5">
              <div class="grid gap-y-3">
                <div class="grid gap-2">
                  <Label for="branchname">Branch Name</Label>
                  <Input
                    class="max-w-sm"
                    placeholder="Enter Branch Name"
                    id="branchname"
                    v-model="form.branchname"
                    autofocus
                  />
                  <span v-if="errors?.branchname" class="text-red-600 text-sm">{{ errors.branchname }}</span>
                </div>
                <div class="grid gap-2">
                    <Label for="person">Employee Name</Label>
                    <Input
                        class="max-w-sm"
                        placeholder="Enter Employee Name"
                        id="person"
                        v-model="form.person"
                        autofocus
                    />
                    <span v-if="errors?.person" class="text-red-600 text-sm">{{ errors.person }}</span>
                </div>
                <div class="grid gap-2">
                    <Label for="address">Address</Label>
                    <Textarea placeholder="Type your address here." v-model="form.address"></Textarea>
                </div>
                <div class="grid gap-2">
                    <Label for="phone">Phone No</Label>
                    <Input
                        class="max-w-sm"
                        placeholder="Enter Phone No"
                        id="phone"
                        v-model="form.phone"
                        autofocus
                    />
                    <span v-if="errors?.phone" class="text-red-600 text-sm">{{ errors.phone }}</span>
                </div>
              </div>
              <div class="grid gap-y-3">
                <div class="grid gap-2">
                    <Label for="designation">Designation</Label>
                    <Input
                        class="max-w-sm"
                        placeholder="Enter Designation"
                        id="designation"
                        v-model="form.designation"
                        autofocus
                    />
                    <span v-if="errors?.designation" class="text-red-600 text-sm">{{ errors.designation }}</span>
                </div>
                <div class="grid gap-2">
                  <Label for="description">Description</Label>
                  <Input
                    class="max-w-sm"
                    placeholder="Enter Description"
                    id="description"
                    v-model="form.description"
                    autofocus
                  />
                  <span v-if="errors?.description" class="text-red-600 text-sm">{{ errors.description }}</span>
                </div>
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        class="max-w-sm"
                        placeholder="Enter email"
                        id="email"
                        v-model="form.email"
                        autofocus
                    />
                    <span v-if="errors?.email" class="text-red-600 text-sm">{{ errors.email }}</span>
                </div>
                <div class="grid gap-2">
                    <Label for="active">Status</Label>
                    <div class="flex items-center space-x-6">
                        <label class="inline-flex items-center space-x-2">
                        <input
                            type="radio"
                            value="1"
                            v-model="form.active"
                            class="form-radio text-primary-600"
                        />
                        <span>Active</span>
                        </label>

                        <label class="inline-flex items-center space-x-2">
                        <input
                            type="radio"
                            value="0"
                            v-model="form.active"
                            class="form-radio text-primary-600"
                        />
                        <span>Inactive</span>
                        </label>
                    </div>
                    <span v-if="errors?.active" class="text-red-600 text-sm">{{ errors.active }}</span>
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
                <Button type="button" variant="secondary">
                    Close
                </Button>
                </DialogClose>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
          <DialogContent class="max-w-4xl p-6 rounded-2xl shadow-xl">
              <DialogHeader>
                <DialogTitle class="text-2xl font-semibold">Show Branch</DialogTitle>
                <DialogDescription class="text-sm text-muted-foreground">
                    View the details of this branch.
                </DialogDescription>
              </DialogHeader>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Branch Name -->
                    <FormGroup label="Branch Name" htmlFor="branchname">
                      <Input id="branchname" v-model="form.branchname" :disabled="!isEditMode" />
                    </FormGroup>
                    <!-- Employee Name -->
                    <FormGroup label="Employee Name" htmlFor="person">
                      <Input id="person" v-model="form.person" :disabled="!isEditMode" />
                    </FormGroup>
                    <!-- Address-->
                    <FormGroup label="Address" htmlFor="address">
                      <Textarea id="address" v-model="form.address" :disabled="!isEditMode"></Textarea>
                    </FormGroup>
                    <!-- Phone -->
                    <FormGroup label="Phone" htmlFor="phone">
                      <Input id="phone" v-model="form.phone" :disabled="!isEditMode" />
                    </FormGroup>
                </div>
                <!-- Left Column -->
                <div class="space-y-4">
                  <FormGroup label="Description" htmlFor="description">
                    <Input id="description" v-model="form.description" :disabled="!isEditMode" />
                  </FormGroup>

                  <FormGroup label="Designation" htmlFor="designation">
                    <Input id="designation" v-model="form.designation" :disabled="!isEditMode" />
                  </FormGroup>

                  <FormGroup label="Email" htmlFor="email">
                    <Input id="email" v-model="form.email" :disabled="!isEditMode" />
                  </FormGroup>
                  <FormGroup label="Status" htmlFor="active">
                    <template v-if="isEditMode">
                      
                    </template>
                    <template v-else>
                      
                    </template>
                  </FormGroup>
                </div>
              </div>

              <DialogFooter class="sm:justify-start">
                  <DialogClose as-child>
                    <Button variant="secondary" @click="showDialogOpen = false">
                          Close
                      </Button>
                  </DialogClose>
              </DialogFooter>
          </DialogContent>
      </Dialog>
    </AppLayout>
</template>