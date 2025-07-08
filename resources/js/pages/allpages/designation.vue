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

import DropdownAction from '@/components/DataTable.vue'

import { toast } from 'vue-sonner'



export interface Designation {
  id:number
  desname: string
  active: string
}


const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Designation', href: '/designation' },
];

const props = defineProps<{
        designation: Designation[]
    }>()

const data = props.designation


const columns: ColumnDef<Designation, any>[] = [
      {
          id: 'sl',
          header: () => 'SL',
          cell: ({ row }: { row: Row<Designation> }) => {
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
          accessorKey: 'desname',
          header: ({ column }: { column: Column<Designation, unknown> }) => {
          return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Designation Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Designation> }) => h('div', { class: 'capitalize' }, row.getValue('desname')),
      },
      {
          accessorKey: 'active',
          header: ({ column }: { column: Column<Designation, unknown> }) => 
              {
              return h(Button, {
                  variant: 'ghost',
                  onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
              }, 
              () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
          },
          cell: ({ row }: { row: Row<Designation> }) => {
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
          cell: ({ row }: { row: Row<Designation> }) => {
          const dataID = row.original

              return h('div', { class: 'relative' }, h(DropdownAction, {
                  dataID,
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
    desname?: string;
    active?: string;
}


const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    desname: '',
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
        const res = await fetch(`/designation/${id}`);

        if (!res.ok){
            toast.error('Server error while fetching designation details.');
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
        const res = await fetch(`/designation/${id}/edit`);
        
        if (!res.ok){
            toast.error('Server error while fetching designation details.');
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
                    ? route('designation.update', form.id)
                    : route('designation.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast("Success",{
                description: `Designation ${isEditMode.value ? 'updated' : 'created'} successfully`, 
            })
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('designation.index'), {
                    only: ['designations'],
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
    if (!confirm("Are you sure you want to delete this designation?")) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/designation/show/${id}`, {
        onSuccess: () => {
            toast.success("Designation deleted successfully");
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
    <Head title="Designation" />
    <AppLayout :breadcrumbs="breadcrumbs">
      
        <div class="relative min-h-[100vh] flex-1 border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min px-4">
            <div class="flex gap-2 items-center py-4">
              <Input
                  class="max-w-sm"
                  placeholder="Filter Designation Name..."
                  :model-value="table.getColumn('desname')?.getFilterValue() as string"
                  @update:model-value=" table.getColumn('desname')?.setFilterValue($event)"
              />
            <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Designation </Button>
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
              <DialogTitle>{{ isEditMode ? 'Edit Designation' : 'Create Designation' }}</DialogTitle>
              <DialogDescription>
                Make changes to your profile here. Click save when you're done.
              </DialogDescription>
            </DialogHeader>
            <div class="grid gap-5">
              <div class="grid gap-y-3">
                <div class="grid gap-2">
                  <Label for="desname">Designation Name</Label>
                  <Input
                    class="max-w-sm"
                    placeholder="Enter Designation Name"
                    id="desname"
                    v-model="form.desname"
                    autofocus
                  />
                  <span v-if="errors?.desname" class="text-red-600 text-sm">{{ errors.desname }}</span>
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
                <DialogTitle class="text-2xl font-semibold">Show Designation</DialogTitle>
                <DialogDescription class="text-sm text-muted-foreground">
                    View the details of this designation.
                </DialogDescription>
              </DialogHeader>
              <div class="grid gap-6 mt-4">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Branch Name -->
                    <FormGroup label="Designation Name" htmlFor="desname">
                      <Input id="desname" v-model="form.desname" :disabled="!isEditMode" />
                    </FormGroup>
                    <FormGroup label="Status" htmlFor="active">
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center space-x-2">
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full"
                                    :class="form.active == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                {{ form.active == '1' ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </div>
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