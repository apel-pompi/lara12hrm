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

export interface HolidayHd {
    id: number;
    branch_id: string;
    yearname: string;
    monthname: string;
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
    filters: { branch_id?: string; yearname?: string; monthname?: string };
}>();

const data = props.holidayHd;

interface FormErrors {
    branch_id?: string;
    yearname?: string;
    monthname?: string;
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
    yearname: null as number | null,
    monthname: null as number | null,
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
    () => [form.yearname, form.monthname, form.holidays],
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
const toggleStatus = (holidayhd: HolidayHd, checked: boolean) => {
    router.put(
        route('holidayhd.updateStatus', holidayhd.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                holidayhd.active = checked ? 1 : 0;
                toast.success('Holiday status update');
            },
        },
    );
};

const searchForm = ref({
    branch_id: props.filters.branch_id || '',
    yearname: props.filters.yearname || '',
    monthname: props.filters.monthname || '',
});

const search = () => {
    const params: Record<string, any> = {};
    if (searchForm.value.branch_id) params.branch_id = searchForm.value.branch_id;
    if (searchForm.value.yearname) params.yearname = searchForm.value.yearname;
    if (searchForm.value.monthname) params.monthname = searchForm.value.monthname;

    router.get(route('holidayHd.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('holidayHd.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('holidayHd.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const getMonthName = (m) => {
    return new Date(0, m - 1).toLocaleString('en-US', { month: 'long' });
};
</script>

<template>
    <Head title="Holiday" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <!-- Responsive Header + Search Section -->

            <!-- Filters -->
            <div
                class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6">
                    <Button
                        variant="default"
                        size="sm"
                        @click="showDailogCreate"
                        class="w-40 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
                    >
                        <Plus class="h-4 w-4" />
                        Create
                    </Button>
                    <Select v-model="searchForm.branch_id">
                        <SelectTrigger class="w-full rounded-xl border-gray-300">
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
                    <Select v-model="searchForm.yearname">
                        <SelectTrigger class="w-full rounded-xl border-gray-300">
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
                    <Select v-model="searchForm.monthname">
                        <SelectTrigger class="w-full rounded-xl border-gray-300">
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
                    <!-- Search -->
                    <Button variant="outline" size="sm" @click="search" class="rounded-xl border-indigo-300 hover:bg-indigo-50">
                        <Search class="mr-2 h-4 w-4" />
                        Search
                    </Button>

                    <!-- Refresh -->
                    <Button variant="outline" size="sm" @click="refresh" class="rounded-xl border-gray-300 hover:bg-gray-100">
                        <RefreshCcw class="mr-2 h-4 w-4" />
                        Refresh
                    </Button>
                </div>
            </div>

            <!-- Modern Responsive Holiday Table -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Title -->
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Holiday Configuration List</h2>
                    <p class="text-sm text-gray-500">Manage all Holiday Configuration from here.</p>
                </div>
                <Table class="min-w-full">
                    <TableHeader>
                        <TableRow class="bg-gray-50">
                            <TableHead class="px-4 py-3 font-semibold text-gray-700">Branch Name</TableHead>
                            <TableHead class="px-4 py-3 font-semibold text-gray-700">Year</TableHead>
                            <TableHead class="px-4 py-3 font-semibold text-gray-700">Month</TableHead>
                            <TableHead class="px-4 py-3 font-semibold text-gray-700">Holiday Days</TableHead>
                            <TableHead class="px-4 py-3 font-semibold text-gray-700">Working Days</TableHead>
                            <TableHead class="px-4 py-3 text-center font-semibold text-gray-700">Status</TableHead>
                            <TableHead class="px-4 py-3 text-center font-semibold text-gray-700">Action</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-for="(holidayhd, index) in data.data" :key="holidayhd.id ?? index" class="border-t transition hover:bg-gray-50">
                            <!-- Branch -->
                            <TableCell class="px-4 py-3 font-medium whitespace-nowrap text-gray-800">
                                {{ holidayhd.branch?.branchname }}
                            </TableCell>

                            <!-- Year -->
                            <TableCell class="px-4 py-3 text-gray-600">
                                {{ holidayhd.yearname }}
                            </TableCell>

                            <!-- Month -->
                            <TableCell class="px-4 py-3 text-gray-600">
                                {{ getMonthName(holidayhd.monthname) }}
                            </TableCell>

                            <!-- Holiday Days -->
                            <TableCell class="px-4 py-3">
                                <a
                                    :href="`/holidaydt/${holidayhd.id}/create/`"
                                    class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700 hover:bg-blue-200"
                                >
                                    {{ holidayhd.holidays }} Days
                                </a>
                            </TableCell>

                            <!-- Working -->
                            <TableCell class="px-4 py-3 font-medium text-green-700">
                                {{ holidayhd.holiworking }}
                            </TableCell>

                            <!-- Status -->
                            <TableCell class="px-4 py-3 text-center">
                                <Switch
                                    :model-value="Boolean(holidayhd.active)"
                                    @update:model-value="(checked) => toggleStatus(holidayhd, checked)"
                                />
                            </TableCell>

                            <!-- Action -->
                            <TableCell class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <Button size="icon" variant="ghost" class="h-8 w-8 text-blue-600 hover:bg-blue-100" @click="onShow(holidayhd.id)">
                                        <Eye class="h-4 w-4" />
                                    </Button>

                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="h-8 w-8 text-amber-600 hover:bg-amber-100"
                                        @click="onEdit(holidayhd.id)"
                                    >
                                        <SquarePen class="h-4 w-4" />
                                    </Button>

                                    <Button size="icon" variant="ghost" class="h-8 w-8 text-red-600 hover:bg-red-100" @click="onDelete(holidayhd.id)">
                                        <Trash class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Footer Pagination -->
                <div class="flex flex-col gap-4 border-t bg-gray-50 px-4 py-4 md:flex-row md:items-center md:justify-between">
                    <!-- Left -->
                    <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <label>Show</label>

                            <select
                                v-model="perPage"
                                @change="changePerPage"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                    {{ size }}
                                </option>
                            </select>
                        </div>

                        <span> Showing {{ holidayHd.from }} to {{ holidayHd.to }} of {{ holidayHd.total }} results </span>
                    </div>

                    <!-- Right -->
                    <div class="flex flex-wrap justify-center gap-2 md:justify-end">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            size="sm"
                            variant="outline"
                            @click="goToPage(link.url)"
                            :class="[
                                link.active ? 'border-indigo-600 bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700',
                                !link.url ? 'cursor-not-allowed opacity-50' : '',
                            ]"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-206.25">
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
                            <Label for="yearname">Holi Year</Label>
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
                            <Label for="monthname">Holi Month</Label>
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
                        <FormGroup label="Holi Year" htmlFor="yearname">
                            <Input id="yearname" v-model="form.yearname" :disabled="!isEditMode" />
                        </FormGroup>
                        <!-- Holi Month -->
                        <FormGroup label="Holi Month" htmlFor="monthname">
                            <Input id="monthname" :modelValue="isEditMode ? form.monthname : month[form.monthname]" :disabled="!isEditMode" />
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
