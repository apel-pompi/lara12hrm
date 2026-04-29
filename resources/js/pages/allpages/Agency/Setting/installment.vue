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

export interface Installment {
    id: number;
    name: string;
    adddate: string;
    active: number;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Installment', href: '/installment' }];

const props = defineProps<{
    installment: Paginated<Installment>;
    installmentFilter: { name: string };
    filters: { name: string };
}>();

const data = props.installment;

const errors = ref<FormErrors>();

interface FormErrors {
    name?: string;
    adddate?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    name: '',
    adddate: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/installment/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching installment details.');
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
    const action = isEditMode.value && form.id ? route('installment.update', form.id) : route('installment.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Installment Name ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('installment.index'), {
                    only: ['installments'],
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

const toggleStatus = (installment: Installment, checked: boolean) => {
    router.put(
        route('installment.updateStatus', installment.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                installment.active = checked ? 1 : 0;
                toast.success('Installment  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this installment?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/installment/show/${id}`, {
        onSuccess: () => {
            toast.success('Installment deleted successfully');
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
const filteredName = computed(() => (queryName.value === '' ? props.installmentFilter : props.installmentFilter.filter((n) => n.name)));
const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.name = selecteName.value.name;

    router.get(route('installment.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('installment.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('installment.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Installment" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header Actions -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Actions -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <!-- Create -->
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Installment
                        </Button>

                        <!-- Search Combobox -->
                        <Combobox v-model="selecteName">
                            <div class="relative w-full sm:w-56">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Search installment..."
                                        :display-value="(n) => n?.name"
                                        @input="queryName = $event.target.value"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                </div>

                                <ComboboxOptions
                                    class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-xl ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div v-if="filteredName.length === 0 && queryName !== ''" class="px-4 py-2 text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="selected ? 'font-medium' : 'font-normal'" class="block truncate">
                                            {{ n.name }}
                                        </span>

                                        <span
                                            v-if="selected"
                                            class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>

                        <!-- Search -->
                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="search">
                            <Search class="mr-2 h-4 w-4" />
                            Search
                        </Button>

                        <!-- Refresh -->
                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="refresh">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Refresh
                        </Button>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Installment Plan</h2>
                        <p class="text-sm text-gray-500">Manage all installment records from here.</p>
                    </div>

                    <!-- Table -->
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100">
                                <TableHead>Name</TableHead>
                                <TableHead>Added Date</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="(acc, index) in data.data" :key="acc.id ?? index" class="transition hover:bg-gray-50">
                                <TableCell class="font-medium text-gray-800">
                                    {{ acc.name }}
                                </TableCell>

                                <TableCell>
                                    {{ acc.adddate }}
                                </TableCell>

                                <TableCell>
                                    {{ acc.user.name }}
                                </TableCell>

                                <TableCell>
                                    <Switch :model-value="Boolean(acc.active)" @update:model-value="(checked) => toggleStatus(acc, checked)" />
                                </TableCell>

                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button size="sm" variant="outline" @click="onEdit(acc.id)" class="hover:bg-indigo-50">
                                            <SquarePen class="h-4 w-4 text-indigo-600" />
                                        </Button>

                                        <Button size="sm" variant="outline" @click="onDelete(acc.id)" class="hover:bg-red-50">
                                            <Trash class="h-4 w-4 text-red-500" />
                                        </Button>
                                    </div>
                                </TableCell>
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

                        <span> Showing {{ installment.from }} to {{ installment.to }} of {{ installment.total }} results </span>
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

            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-3">
                        <DialogTitle class="text-lg font-semibold">
                            {{ isEditMode ? 'Edit Installment' : 'Create Installment' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{
                                isEditMode
                                    ? 'Update the installment details and click save.'
                                    : 'Fill in the details below to create a new installment.'
                            }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid gap-6 py-4">
                        <!-- Name -->
                        <div class="grid gap-2">
                            <Label for="name" class="font-medium">Name</Label>
                            <Input id="name" placeholder="Enter installment name" v-model="form.name" autofocus class="max-w-sm" />
                            <p v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
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
