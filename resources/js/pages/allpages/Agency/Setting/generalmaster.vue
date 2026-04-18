<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import TableCell from '@/components/ui/table/TableCell.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm } from '@inertiajs/vue3';
import { HousePlus, PackageSearch, Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

export interface Master {
    id: number;
    catname: string;
    catadddate: string;
    partner_count: number;
    user_id: number;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Master Setup', href: '/general' }];

const props = defineProps<{
    mastercategory: Paginated<Master>;
    masterFillter: { catname: string };
    filters: { catname: string };
}>();

const data = props.mastercategory;

interface FormErrors {
    catname?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    catname: '',
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
        const res = await fetch(`/general/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching master category details.');
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
    const action = isEditMode.value && form.id ? route('general.update', form.id) : route('general.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Master Category ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('general.index'), {
                    only: ['master_categories'],
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

const toggleStatus = (mastercategory: Master) => {
    const newStatus = !Boolean(mastercategory.active); // boolean
    router.put(
        route('general.updateStatus', mastercategory.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                mastercategory.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Master Category  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this master Category?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/general/show/${id}`, {
        onSuccess: () => {
            toast.success('Master Category deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToPartnerType = () => {
    router.visit('/general/patnersetup');
};
const goToProductType = () => {
    router.visit('/general/productsetup');
};

// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.masterFillter : props.masterFillter.filter((n) => n.catname)));
const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.catname = selecteName.value.catname;

    router.get(route('general.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('general.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('general.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Master Setup" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Toolbar -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Side -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <!-- Create -->
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Master Category
                        </Button>

                        <!-- Search Combobox -->
                        <Combobox v-model="selecteName">
                            <div class="relative w-full sm:w-72">
                                <div class="relative">
                                    <ComboboxInput
                                        class="h-10 w-full rounded-xl border border-gray-300 bg-white py-2 pr-10 pl-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                        placeholder="Search category..."
                                        :display-value="(n) => n?.catname"
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
                                        No result found
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
                                        :key="n.id"
                                        :value="n"
                                        v-slot="{ selected }"
                                        class="relative cursor-pointer px-4 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-gray-800"
                                    >
                                        <span class="block truncate">
                                            {{ n.catname }}
                                        </span>

                                        <CheckIcon v-if="selected" class="absolute top-2.5 right-3 h-4 w-4 text-indigo-600" />
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>

                        <!-- Search -->
                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="search">
                            <Search class="mr-2 h-4 w-4" />
                            Search
                        </Button>

                        <!-- Refresh -->
                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="refresh">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Refresh
                        </Button>
                    </div>

                    <!-- Right Side -->
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="goToPartnerType">
                            <HousePlus class="mr-2 h-4 w-4" />
                            Partner Type
                        </Button>

                        <Button variant="outline" class="h-10 rounded-xl px-4" size="sm" @click="goToProductType">
                            <PackageSearch class="mr-2 h-4 w-4" />
                            Product Type
                        </Button>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Master Category</h2>
                        <p class="text-sm text-gray-500">Manage all master categories from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-100">
                                <TableHead class="font-semibold">Category Name</TableHead>
                                <TableHead class="font-semibold">Added Date</TableHead>
                                <TableHead class="text-center font-semibold">Usage</TableHead>
                                <TableHead class="text-center font-semibold">Status</TableHead>
                                <TableHead class="font-semibold">Added By</TableHead>
                                <TableHead class="text-center font-semibold">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow v-for="(master, index) in data.data" :key="master.id ?? index" class="hover:bg-gray-50">
                                <TableCell class="font-medium text-gray-800">
                                    {{ master.catname }}
                                </TableCell>

                                <TableCell>
                                    {{ master.catadddate }}
                                </TableCell>

                                <TableCell class="text-center">
                                    <Badge variant="secondary">
                                        {{ master.partner_count }}
                                    </Badge>
                                </TableCell>

                                <TableCell class="text-center">
                                    <Switch v-model="master.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(master)" />
                                </TableCell>

                                <TableCell>
                                    {{ master.user.name }}
                                </TableCell>

                                <TableCell>
                                    <div class="flex justify-center gap-2">
                                        <Button size="icon" variant="outline" class="h-9 w-9 rounded-lg" @click="onEdit(master.id)">
                                            <SquarePen class="h-4 w-4 text-blue-600" />
                                        </Button>

                                        <Button size="icon" variant="outline" class="h-9 w-9 rounded-lg" @click="onDelete(master.id)">
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

                    <!-- Footer -->
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

                            <span> Showing {{ mastercategory.from }} to {{ mastercategory.to }} of {{ mastercategory.total }} entries </span>
                        </div>

                        <!-- Pagination -->
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
                <DialogContent class="w-full max-w-lg rounded-2xl p-6 sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-4">
                        <DialogTitle class="text-xl font-semibold">
                            {{ isEditMode ? 'Edit Master Category' : 'Create Master Category' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            Make changes to your master category here. Click save when you're done.
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="mt-6 space-y-5">
                        <!-- Input -->
                        <div class="space-y-2">
                            <Label for="catname" class="font-medium">Master Category Name</Label>
                            <Input id="catname" v-model="form.catname" placeholder="Enter Master Category Name" class="w-full" autofocus />
                            <span v-if="errors?.catname" class="text-sm text-red-600">
                                {{ errors.catname }}
                            </span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <DialogClose as-child>
                            <Button type="button" variant="secondary" class="w-full sm:w-auto"> Close </Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submit" class="w-full sm:w-auto">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
