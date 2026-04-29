<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import HrmLayout from '@/layouts/settings/hrmLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Eye, Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

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

export interface SalaryType {
    id: number;
    branch_id: string;
    name: string;
    property: string;
    amounttype: string;
    percentage: string;
    amount: string;
    active: string;
    user: { id: number; name: string };
    branch?: Branch;
}

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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Work Hour Setup', href: '/workhour' }];

const props = defineProps<{
    salaryType: Paginated<SalaryType>;
    branch: Branch[];
    filters: { branch_id?: string; name?: string; property?: string; amounttype?: string; percentage?: string; amount?: string; active?: string };
}>();

const data = props.salaryType;

interface FormErrors {
    branch_id?: string;
    name?: string;
    property?: string;
    amounttype?: string;
    percentage?: string;
    amount?: string;
    active?: number;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_id: '',
    name: '',
    property: '',
    amounttype: '',
    percentage: '',
    amount: '',
    active: null as number | null,
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
        const res = await fetch(`/salarytype/${id}`);
        if (!res.ok) {
            toast.error('Server error while fetching salary type setup details.');
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
        const res = await fetch(`/salarytype/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching salary type setup details.');
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
    const action = isEditMode.value && form.id ? route('salarytype.update', form.id) : route('salarytype.store');
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
    if (!confirm('Are you sure you want to delete this salary type setup?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/salarytype/show/${id}`, {
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

const searchForm = ref({
    branch_id: props.filters.branch_id || '',
    name: props.filters.name || '',
    property: props.filters.property || '',
    percentage: props.filters.percentage || '',
    active: props.filters.active || '',
});

const search = () => {
    const params: Record<string, any> = {};
    if (searchForm.value.branch_id) params.branch_id = searchForm.value.branch_id;
    if (searchForm.value.name) params.name = searchForm.value.name;
    if (searchForm.value.property) params.property = searchForm.value.property;
    if (searchForm.value.percentage) params.percentage = searchForm.value.percentage;
    if (searchForm.value.active) params.active = searchForm.value.active;

    router.get(route('salarytype.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('salarytype.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};

// Switch toggle handler
const toggleStatus = (salarytype: SalaryType, checked: boolean) => {
    router.put(
        route('salarytype.updateStatus', salarytype.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                salarytype.active = checked ? 1 : 0;
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
        },
    );
};

watch(
    () => form.amounttype,
    () => {
        form.amount = '';
        form.percentage = '';
    },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Salary Type Setup" />
        <HrmLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Salary Type </Button>
                    <!-- Search start -->
                    <div class="grid gap-2">
                        <Select v-model="searchForm.branch_id">
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
                    </div>
                    <div class="grid gap-2">
                        <Select v-model="searchForm.name">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Name" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="name in data.data" :key="name" :value="name.name">
                                        {{ name.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Select v-model="searchForm.property">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Property" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="property in data.data" :key="property" :value="property.property">
                                        {{ property.property }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                    </div>
                    <div class="grid gap-2">
                        <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                    </div>
                    <!-- Search start -->
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Salary Structure List</h2>
                        <p class="text-sm text-gray-500">Manage all Salary Structure from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-200">
                                <TableHead>Branch Name</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Property</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Percentage</TableHead>
                                <TableHead>Amount</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(salary, index) in data.data" :key="salary.id ?? index">
                                <TableCell>{{ salary.branch?.branchname }}</TableCell>
                                <TableCell>{{ salary.name }}</TableCell>
                                <TableCell>{{ salary.property }}</TableCell>
                                <TableCell>{{ salary.amounttype == 1 ? 'Percentage' : 'Amount' }}</TableCell>
                                <TableCell>{{ salary.percentage }}</TableCell>
                                <TableCell>{{ salary.amount }}</TableCell>
                                <TableCell>{{ salary.user.name }}</TableCell>
                                <TableCell>
                                    <Switch :model-value="Boolean(salary.active)" @update:model-value="(checked) => toggleStatus(salary, checked)">
                                    </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(salary.id)"
                                        ><Eye class="h-4 w-4 text-green-600"></Eye
                                    ></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(salary.id)"
                                        ><SquarePen class="h-4 w-4 text-indigo-600"></SquarePen
                                    ></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(salary.id)"
                                        ><Trash class="h-4 w-4 text-red-600"></Trash
                                    ></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex items-center justify-end space-x-2 py-4">
                    <div class="text-muted-foreground flex-1 text-sm">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</div>
                    <div class="space-x-2">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            variant="outline"
                            size="sm"
                            :class="[link.active ? 'hover:outline' : '', !link.url ? 'cursor-not-allowed opacity-50' : '']"
                            @click="goToPage(link.url)"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
        </HrmLayout>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Working Setup' : 'Create Working Setup' }}</DialogTitle>
                    <DialogDescription> Make changes to your working setup here. Click save when you're done. </DialogDescription>
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
                            <Label for="workhour">Type Name</Label>
                            <Input class="max-w-sm" placeholder="Enter Name" id="name" v-model="form.name" autofocus />
                            <span v-if="errors?.name" class="text-sm text-red-600">{{ errors.name }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="yearname">Property</Label>
                            <Select v-model="form.property">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Property" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Addition">Addition</SelectItem>
                                        <SelectItem value="Deduction">Deduction</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.property" class="text-sm text-red-600">{{ errors.property }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="amounttype">Type</Label>
                            <Select v-model="form.amounttype">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="1">Percentage</SelectItem>
                                        <SelectItem value="2">Amount</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.amounttype" class="text-sm text-red-600">{{ errors.amounttype }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="percentage">Percentage</Label>
                            <Input
                                class="max-w-sm"
                                placeholder="Enter Percentage"
                                id="percentage"
                                v-model="form.percentage"
                                :disabled="form.amounttype !== '1'"
                                autofocus
                            />
                            <span v-if="errors?.percentage" class="text-sm text-red-600">{{ errors.percentage }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="amount">Amount</Label>
                            <Input
                                class="max-w-sm"
                                placeholder="Enter Amount"
                                id="amount"
                                v-model="form.amount"
                                :disabled="form.amounttype !== '2'"
                                autofocus
                            />
                            <span v-if="errors?.amount" class="text-sm text-red-600">{{ errors.amount }}</span>
                        </div>
                        <input type="hidden" value="0" v-model="form.active" class="form-radio text-primary-600" />
                    </div>
                </div>
                <div class="justify-center text-center">
                    <Button :disabled="form.processing" @click="submit">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                    </Button>
                </div>
                <DialogFooter class="justify-end sm:justify-end">
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
                    <DialogTitle class="text-2xl font-semibold">Show Working Setup</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this Working Setup. </DialogDescription>
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
                        <FormGroup label="Name" htmlFor="name">
                            <Input id="name" v-model="form.name" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Property" htmlFor="property">
                            <Input id="property" v-model="form.property" :disabled="!isEditMode" />
                        </FormGroup>
                    </div>
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <FormGroup label="Type" htmlFor="amounttype">
                            <Input
                                id="amounttype"
                                v-model="form.amounttype"
                                :value="form.amounttype == 1 ? 'Percentage' : 'Amount'"
                                :disabled="!isEditMode"
                            />
                        </FormGroup>
                        <FormGroup label="Percentage" htmlFor="percentage">
                            <Input id="percentage" v-model="form.percentage" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="amount" htmlFor="amount">
                            <Input id="amount" v-model="form.amount" :disabled="!isEditMode" />
                        </FormGroup>
                    </div>
                </div>
                <DialogFooter class="sm:justify-end">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="showDialogOpen = false"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
