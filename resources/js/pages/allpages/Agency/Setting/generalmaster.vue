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
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex flex-col gap-4 py-4 md:flex-row md:items-center md:justify-between">
                    <!-- Left actions -->
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center">
                        <!-- Create Master Category -->
                        <Button variant="outline" size="sm" @click="showDailogCreate" class="w-full sm:w-auto">
                            <Plus class="mr-2 h-4 w-4" /> Master Category
                        </Button>

                        <!-- Search Box -->
                        <div class="flex w-full items-center gap-2 sm:w-auto">
                            <Combobox v-model="selecteName" class="w-full sm:w-56">
                                <div class="relative w-full">
                                    <!-- Input -->
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="search name..."
                                            :display-value="(n) => n?.catname"
                                            @input="queryName = $event.target.value"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>
                                    </div>

                                    <!-- Options -->
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredName.length === 0 && queryName !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="n in filteredName"
                                            :key="n.id"
                                            :value="n"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ n.catname }}
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
                        </div>

                        <!-- Search + Refresh -->
                        <div class="flex gap-2">
                            <Button variant="outline" size="sm" @click="search" class="w-full sm:w-auto">
                                <Search class="mr-1 h-4 w-4" /> Search
                            </Button>
                            <Button variant="outline" size="sm" @click="refresh" class="w-full sm:w-auto">
                                <RefreshCcw class="mr-1 h-4 w-4" /> Refresh
                            </Button>
                        </div>
                    </div>

                    <!-- Right actions -->
                    <div class="flex justify-end gap-2">
                        <Button variant="outline" size="sm" @click="goToPartnerType"> <HousePlus class="mr-1 h-4 w-4" /> Partner Type </Button>
                        <Button variant="outline" size="sm" @click="goToProductType"> <PackageSearch class="mr-1 h-4 w-4" /> Product Type </Button>
                    </div>
                </div>

                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Catergory Name</TableHead>
                                <TableHead>Added Date</TableHead>
                                <TableHead>Total Usage</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(master, index) in data.data" :key="master.id ?? index">
                                <TableCell>{{ master.catname }}</TableCell>
                                <TableCell>{{ master.catadddate }}</TableCell>
                                <TableCell></TableCell>
                                <TableCell>
                                    <Switch v-model="master.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(master)"> </Switch>
                                </TableCell>
                                <TableCell>{{ master.user.name }}</TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(master.id)"><SquarePen></SquarePen></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(master.id)"><Trash></Trash></Button>
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
