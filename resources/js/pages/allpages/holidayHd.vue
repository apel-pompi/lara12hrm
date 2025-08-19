<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Eye, Link, Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';

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

export interface HolidayHd {
    id: number;
    branch_id: string;
    holiyear: string;
    holimonth: string;
    holidays: string;
    holiworking: string;
    active: number;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Holiday', href: '/holidayHd' }];

const props = defineProps<{
    holidayHd: Paginated<HolidayHd>;
    branch: Branch[];
    year: string[];
    month: Record<string, string>;
    filters: { branch_id?: string; holiyear?: string; holimonth?: string };
}>();

const data = props.holidayHd;

interface FormErrors {
    branch_id?: string;
    holiyear?: string;
    holimonth?: string;
    holidays?: string;
    holiworking?: string;
    active?: number;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_id: '',
    holiyear: null as number | null,
    holimonth: null as number | null,
    holidays: null as number | null,
    holiworking: null as number | null,
    active: '0',
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
        const res = await fetch(`/holidayHd/${id}`);
        console.log(res);
        if (!res.ok) {
            toast.error('Server error while fetching holiday details.');
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
        const res = await fetch(`/holidayHd/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching holidayHd details.');
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
    const action = isEditMode.value && form.id ? route('holidayHd.update', form.id) : route('holidayHd.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `HolidayHd ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('holidayHd.index'), {
                    only: ['holiday_hds'],
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
    if (!confirm('Are you sure you want to delete this holidayHd?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/holidayHd/show/${id}`, {
        onSuccess: () => {
            toast.success('HolidayHd deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

watch(
    () => [form.holiyear, form.holimonth, form.holidays],
    ([year, month, holidays]) => {
        if (year && month && holidays !== null) {
            const totalDays = new Date(year, month, 0).getDate(); // month is 1-based
            const workingDays = totalDays - Number(holidays);
            form.holiworking = workingDays >= 0 ? workingDays : 0;
        } else {
            form.holiworking = null;
        }
    },
);

// Switch toggle handler
const toggleStatus = (holidayhd: HolidayHd) => {
  const newStatus = !Boolean(holidayhd.active); // boolean
  router.put(
    route('holidayhd.updateStatus', holidayhd.id),
    { active: newStatus ? 1 : 0 }, // server expects number
    {
      preserveState: true,
      onSuccess: () => {
        holidayhd.active = newStatus ? 1 : 0 // local update (number)
        toast.success('Holiday status update');
      }
    }
  )
}

const searchForm = ref({
    branch_id: props.filters.branch_id || '',
    holiyear: props.filters.holiyear || '',
    holimonth: props.filters.holimonth || '',
});

const search = () => {
    const params: Record<string, any> = {};
    if (searchForm.value.branch_id) params.branch_id = searchForm.value.branch_id;
    if (searchForm.value.holiyear) params.holiyear = searchForm.value.holiyear;
    if (searchForm.value.holimonth) params.holimonth = searchForm.value.holimonth;

    router.get(route('holidayHd.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('holidayHd.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};
</script>

<template>
    <Head title="Holiday" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Holiday </Button>
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
                    <Select v-model="searchForm.holiyear">
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
                    <Select v-model="searchForm.holimonth">
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
                            <TableHead>Holi Year</TableHead>
                            <TableHead>Holi Month</TableHead>
                            <TableHead>Holi Days</TableHead>
                            <TableHead>Working Days</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(holidayhd, index) in data.data" :key="holidayhd.id ?? index">
                            <TableCell>{{ holidayhd.branch?.branchname }}</TableCell>
                            <TableCell>{{ holidayhd.holiyear }}</TableCell>
                            <TableCell>{{ holidayhd.holimonth }}</TableCell>
                            <TableCell><a :href="`/holidaydt/${holidayhd.id}/create/`" class="text-blue-600 hover:text-blue-800 underline font-medium">{{ holidayhd.holidays }}</a></TableCell>
                            <TableCell>{{ holidayhd.holiworking }}</TableCell>
                            <TableCell>
                                <Switch v-model="holidayhd.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(holidayhd)">
                                </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(holidayhd.id)"><Eye></Eye></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(holidayhd.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(holidayhd.id)"><Trash></Trash></Button>
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
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Holiday' : 'Create Holiday' }}</DialogTitle>
                    <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
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
                            <Label for="holiyear">Holi Year</Label>
                            <Select v-model="form.holiyear">
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
                            <span v-if="errors?.holiyear" class="text-sm text-red-600">{{ errors.holiyear }}</span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="holimonth">Holi Month</Label>
                            <Select v-model="form.holimonth">
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
                            <span v-if="errors?.holimonth" class="text-sm text-red-600">{{ errors.holimonth }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="holidays">Holi Days</Label>
                            <Input class="max-w-sm" placeholder="Enter Holi Days" id="holidays" v-model="form.holidays" autofocus />
                            <span v-if="errors?.holidays" class="text-sm text-red-600">{{ errors.holidays }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="holiworking">Working Days</Label>
                            <Input class="max-w-sm" placeholder="Enter Working Days" id="holidays" v-model="form.holiworking" autofocus />
                            <span v-if="errors?.holiworking" class="text-sm text-red-600">{{ errors.holiworking }}</span>
                        </div>
                        <input type="hidden" value="0" v-model="form.active" class="form-radio text-primary-600" />
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
                        <Button type="button" variant="secondary"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
            <DialogContent class="max-w-4xl rounded-2xl p-6 shadow-xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show Holiday</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this Holiday. </DialogDescription>
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
                        <FormGroup label="Holi Year" htmlFor="holiyear">
                            <Input id="holiyear" v-model="form.holiyear" :disabled="!isEditMode" />
                        </FormGroup>
                        <!-- Holi Month -->
                        <FormGroup label="Holi Month" htmlFor="holimonth">
                            <Input id="holimonth" :modelValue="isEditMode ? form.holimonth : month[form.holimonth]" :disabled="!isEditMode" />
                        </FormGroup>
                    </div>
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <FormGroup label="Holi Days" htmlFor="holidays">
                            <Input id="holidays" v-model="form.holidays" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Working Days" htmlFor="holiworking">
                            <Input id="holiworking" v-model="form.holiworking" :disabled="!isEditMode" />
                        </FormGroup>
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
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="showDialogOpen = false"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
