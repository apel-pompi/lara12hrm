<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface StudentStage {
    id: number;
    name: string;
    adddate: string;
    user_id: number;
    active: number;
    usage_count: number;
    user: {
        id: number;
        name: string;
    };
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
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Stage', href: '/studentStage' }];

const props = defineProps<{
    studentStage: Paginated<StudentStage>;
    filters: { name?: string };
    searchName: { name: string };
}>();

const data = props.studentStage;

interface FormErrors {
    name?: string;
    adddate?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const today = new Date().toISOString().split('T')[0];
const form = useForm({
    id: null as number | null,
    name: '',
    adddate: today, // default today
    active: false,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/studentStage/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching document type details.');
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
    const action = isEditMode.value && form.id ? route('studentStage.update', form.id) : route('studentStage.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Student Stage ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('studentStage.index'), {
                    only: ['student_stages'],
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

const toggleStatus = (studentStage: StudentStage, checked: boolean) => {
    router.put(
        route('studentStage.updateStatus', studentStage.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                studentStage.active = checked ? 1 : 0;
                toast.success('Student stage status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this Student stage?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/studentStage/show/${id}`, {
        onSuccess: () => {
            toast.success('Student stage deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.searchName : props.searchName.filter((n) => n.name)));

const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.name = selecteName.value.name;

    router.get(route('studentStage.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('studentStage.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('studentStage.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Stage" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Search Section -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Side -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Add Stage
                        </Button>

                        <!-- Search Combobox -->
                        <Combobox v-model="selecteName">
                            <div class="relative w-full sm:w-64">
                                <div class="relative">
                                    <ComboboxInput
                                        class="h-10 w-full rounded-xl border border-gray-300 bg-white py-2 pr-10 pl-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                        placeholder="Search stage name..."
                                        :display-value="(n) => n?.name"
                                        @input="queryName = $event.target.value"
                                    />

                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                </div>

                                <ComboboxOptions
                                    class="absolute z-20 mt-2 max-h-60 w-full overflow-auto rounded-xl border bg-white py-2 shadow-xl dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div v-if="filteredName.length === 0 && queryName !== ''" class="px-4 py-2 text-sm text-gray-500">
                                        No stage found
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
                                        :key="n.id"
                                        :value="n"
                                        v-slot="{ selected }"
                                        class="relative cursor-pointer px-4 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-gray-800"
                                    >
                                        <span class="block truncate">
                                            {{ n.name }}
                                        </span>

                                        <CheckIcon v-if="selected" class="absolute top-2.5 right-3 h-4 w-4 text-indigo-600" />
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>

                        <!-- Buttons -->
                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="search">
                            <Search class="mr-2 h-4 w-4" />
                            Search
                        </Button>

                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="refresh">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Reset
                        </Button>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Student Stages</h2>
                        <p class="text-sm text-gray-500">Manage all student stage records from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-100">
                                <TableHead class="font-semibold">Stage Name</TableHead>
                                <TableHead class="font-semibold">Added Date</TableHead>
                                <TableHead class="font-semibold">Usage</TableHead>
                                <TableHead class="text-center font-semibold">Status</TableHead>
                                <TableHead class="font-semibold">Added By</TableHead>
                                <TableHead class="text-center font-semibold">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="(student, index) in data.data" :key="student.id ?? index" class="hover:bg-gray-50">
                                <TableCell class="font-medium text-gray-800">
                                    {{ student.name }}
                                </TableCell>

                                <TableCell>
                                    {{ student.adddate }}
                                </TableCell>

                                <TableCell>
                                    <Badge variant="secondary"> {{ student.usage_count }} </Badge>
                                </TableCell>

                                <TableCell class="text-center">
                                    <Switch
                                        :model-value="Boolean(student.active)"
                                        @update:model-value="(checked) => toggleStatus(student, checked)"
                                    />
                                </TableCell>

                                <TableCell>
                                    {{ student.user.name }}
                                </TableCell>

                                <TableCell>
                                    <div class="flex justify-center gap-2">
                                        <Button size="icon" variant="outline" class="h-9 w-9 rounded-lg" @click="onEdit(student.id)">
                                            <SquarePen class="h-4 w-4 text-blue-600" />
                                        </Button>

                                        <Button size="icon" variant="outline" class="h-9 w-9 rounded-lg" @click="onDelete(student.id)">
                                            <Trash class="h-4 w-4 text-red-600" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>

                            <!-- Empty -->
                            <TableRow v-if="data.data.length === 0">
                                <TableCell colspan="6" class="py-10 text-center text-gray-500"> No Data Found </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Footer Pagination -->
                    <div class="flex flex-col gap-4 border-t px-4 py-4 md:flex-row md:items-center md:justify-between">
                        <!-- Left -->
                        <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center">
                            <div class="flex items-center gap-2">
                                <span>Show</span>

                                <select v-model="perPage" @change="changePerPage" class="rounded-lg border px-2 py-1">
                                    <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                        {{ size }}
                                    </option>
                                </select>
                            </div>

                            <span> Showing {{ studentStage.from }} to {{ studentStage.to }} of {{ studentStage.total }} entries </span>
                        </div>

                        <!-- Right -->
                        <div class="flex flex-wrap gap-2">
                            <Button
                                v-for="(link, index) in data.links"
                                :key="index"
                                :disabled="!link.url"
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
            </div>
            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-3">
                        <DialogTitle class="text-lg font-semibold">
                            {{ isEditMode ? 'Edit Student Stage' : 'Create Student Stage' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Update the stage details and click save.' : 'Fill in the details below to create a new student stage.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid gap-6 py-4">
                        <!-- Name -->
                        <div class="grid gap-2">
                            <Label for="name" class="font-medium">Stage Name</Label>
                            <Input id="name" placeholder="Enter stage name" v-model="form.name" autofocus class="max-w-sm" />
                            <p v-if="errors?.name" class="text-sm text-red-600">
                                {{ errors.name }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="flex justify-end space-x-2 border-t pt-4">
                        <DialogClose as-child>
                            <Button type="button" variant="secondary">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
