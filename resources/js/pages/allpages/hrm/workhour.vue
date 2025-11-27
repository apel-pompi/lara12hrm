<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import HrmLayout from '@/layouts/settings/hrmLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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

export interface WorkHours {
    id: number;
    branch_id: string;
    workhour: string;
    yearname: string;
    monthname: string;
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
    workhour: Paginated<WorkHours>;
    branch: Branch[];
    year: string[];
    month: Record<string, string>;
    filters: { branch_id?: string; workhour?: string; yearname?: string; monthname?: string; active?: string };
}>();

const data = props.workhour;

interface FormErrors {
    branch_id?: string;
    workhour?: string;
    yearname?: string;
    monthname?: string;
    active?: number;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_id: '',
    workhour: null as number | null,
    yearname: null as number | null,
    monthname: null as number | null,
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
        const res = await fetch(`/workhour/${id}`);
        if (!res.ok) {
            toast.error('Server error while fetching working hour setup details.');
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
        const res = await fetch(`/workhour/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching working hour setup details.');
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
    const action = isEditMode.value && form.id ? route('workhour.update', form.id) : route('workhour.store');
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
    if (!confirm('Are you sure you want to delete this Working hour setup?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/workhour/show/${id}`, {
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
    yearname: props.filters.yearname || '',
    monthname: props.filters.monthname || '',
    active: props.filters.active || '',
});

const search = () => {
    const params: Record<string, any> = {};
    if (searchForm.value.branch_id) params.branch_id = searchForm.value.branch_id;
    if (searchForm.value.yearname) params.yearname = searchForm.value.yearname;
    if (searchForm.value.monthname) params.monthname = searchForm.value.monthname;
    if (searchForm.value.active) params.active = searchForm.value.active;

    router.get(route('workhour.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('workhour.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};

// Switch toggle handler
const toggleStatus = (workhour: WorkHours) => {
    const newStatus = !Boolean(workhour.active); // boolean
    router.put(
        route('workhour.updateStatus', workhour.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                workhour.active = newStatus ? 1 : 0; // local update (number)
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

const getMonthName = (m) => {
  return new Date(0, m - 1).toLocaleString("en-US", { month: "long" });
};
</script>

<template>
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Working Hour Setup" />
        <HrmLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Working Hour </Button>
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
                        <Select v-model="searchForm.yearname">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Year" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="years in year" :key="years" :value="years">
                                        {{ years }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Select v-model="searchForm.monthname">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Month" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="(label, key) in month" :key="key" :value="key">
                                        {{ label }}
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
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Branch Name</TableHead>
                                <TableHead>Working Hour</TableHead>
                                <TableHead>Year</TableHead>
                                <TableHead>Month</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(work, index) in data.data" :key="work.id ?? index">
                                <TableCell>{{ work.branch?.branchname }}</TableCell>
                                <TableCell>{{ work.workhour }}</TableCell>
                                <TableCell>{{ work.yearname }}</TableCell>
                                <TableCell>{{ getMonthName(work.monthname) }}</TableCell>
                                <TableCell>{{ work.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="work.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(work)"> </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(work.id)"><Eye></Eye></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(work.id)"><SquarePen></SquarePen></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(work.id)"><Trash></Trash></Button>
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
                            <Label for="workhour">Working Hour</Label>
                            <Input class="max-w-sm" placeholder="Enter Working Hour" id="workhour" v-model="form.workhour" autofocus />
                            <span v-if="errors?.workhour" class="text-sm text-red-600">{{ errors.workhour }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="yearname">Year</Label>
                            <Select v-model="form.yearname">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="years in year" :key="years" :value="years">
                                            {{ years }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.yearname" class="text-sm text-red-600">{{ errors.yearname }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="monthname">Month</Label>
                            <Select v-model="form.monthname">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Month" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="(label, key) in month" :key="key" :value="key">
                                            {{ label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.monthname" class="text-sm text-red-600">{{ errors.monthname }}</span>
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
                        <!-- Holi Year -->
                        <FormGroup label="Working Hour" htmlFor="workhour">
                            <Input id="workhour" v-model="form.workhour" :disabled="!isEditMode" />
                        </FormGroup>
                        
                    </div>
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Holi Month -->
                        <FormGroup label="Year" htmlFor="yearname">
                            <Input id="yearname" v-model="form.yearname" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Month" htmlFor="monthname">
                            <Input id="monthname" :modelValue="isEditMode ? form.monthname : month[form.monthname]" :disabled="!isEditMode" />
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
