<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { CornerDownLeft, Plus, RefreshCcw, Search, SquarePen } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

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

export interface DocumentType {
    id: number;
    docname: string;
    adddate: string;
    docusage_count: number;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Documetn Type', href: '/documenttype' }];

const props = defineProps<{
    documenttype: Paginated<DocumentType>;
    filters: { docname: string };
    alldocument: { id: number; docname: string }[];
}>();

const data = props.documenttype;

interface FormErrors {
    docname?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    docname: '',
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
        const res = await fetch(`/documenttype/${id}/edit`);

        if (res.status === 403) {
            const response = await res.json();
            toast.error(response.message);
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
    const action = isEditMode.value && form.id ? route('documenttype.update', form.id) : route('documenttype.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Worksflows Document Type ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('documenttype.index'), {
                    only: ['w_document_types'],
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

const toggleStatus = (documenttype: DocumentType, checked: boolean) => {
    router.put(
        route('documenttype.updateStatus', documenttype.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                documenttype.active = checked ? 1 : 0;
                toast.success('Document Type  status update');
            },
        },
    );
};

const goToWorkflow = () => {
    router.visit('/workflow');
};

// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.alldocument : props.alldocument.filter((n) => n.docname)));

const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.docname = selecteName.value.docname;

    router.get(route('documenttype.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('documenttype.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('documenttype.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Document Type" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Action Section -->
                <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Left Side -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <!-- Back Button -->
                        <Button
                            class="w-full sm:w-auto dark:bg-black dark:text-white dark:hover:bg-gray-700"
                            variant="outline"
                            size="sm"
                            @click="goToWorkflow"
                        >
                            <CornerDownLeft class="mr-2 h-4 w-4" />
                            Back Workflows
                        </Button>

                        <!-- Create Button -->
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Document Type
                        </Button>

                        <!-- Search Combobox -->
                        <div class="w-full sm:w-64">
                            <Combobox v-model="selecteName">
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Search document type..."
                                        :display-value="(n) => n?.docname"
                                        @input="queryName = $event.target.value"
                                    />

                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>

                                    <!-- Dropdown -->
                                    <ComboboxOptions
                                        class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div v-if="filteredName.length === 0 && queryName !== ''" class="px-4 py-2 text-gray-500">Nothing found.</div>

                                        <ComboboxOption
                                            v-for="n in filteredName"
                                            :key="n.id"
                                            :value="n"
                                            class="ui-active:bg-indigo-600 ui-active:text-white relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span class="block truncate">
                                                {{ n.docname }}
                                            </span>

                                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                <CheckIcon class="h-5 w-5" />
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="search">
                                <Search class="mr-1 h-4 w-4" />
                                Search
                            </Button>

                            <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="refresh">
                                <RefreshCcw class="mr-1 h-4 w-4" />
                                Refresh
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Document Type</h2>
                        <p class="text-sm text-gray-500">Manage all document type from here.</p>
                    </div>
                    <Table>
                        <TableHeader class="bg-gray-100 dark:bg-gray-800">
                            <TableRow>
                                <TableHead>Document Type</TableHead>
                                <TableHead>Added Date</TableHead>
                                <TableHead>Total Usage</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(documenttype, index) in data.data"
                                :key="documenttype.id ?? index"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <TableCell class="font-medium">
                                    {{ documenttype.docname }}
                                </TableCell>

                                <TableCell>
                                    {{ documenttype.adddate }}
                                </TableCell>

                                <TableCell>
                                    <Badge variant="secondary">
                                        {{ documenttype.docusage_count }}
                                    </Badge>
                                </TableCell>

                                <TableCell>
                                    <Switch
                                        :model-value="Boolean(documenttype.active)"
                                        @update:model-value="(checked) => toggleStatus(documenttype, checked)"
                                    />
                                </TableCell>

                                <TableCell>
                                    {{ documenttype.user.name }}
                                </TableCell>

                                <TableCell class="text-center">
                                    <Button class="m-0.5" size="sm" variant="outline" @click="onEdit(documenttype.id)">
                                        <SquarePen class="h-4 w-4 text-green-600" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left -->
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span>Show</span>

                        <select v-model="perPage" @change="changePerPage" class="rounded-md border px-2 py-1 text-sm dark:bg-gray-900">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">
                                {{ size }}
                            </option>
                        </select>

                        <span> Showing {{ documenttype.from }} to {{ documenttype.to }} of {{ documenttype.total }} results </span>
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
                <DialogContent class="max-w-206.25">
                    <!-- Header -->
                    <DialogHeader>
                        <DialogTitle>
                            {{ isEditMode ? 'Edit Document Type' : 'Create Document Type' }}
                        </DialogTitle>
                        <DialogDescription> Manage your workflow document type details here. Click save when you're done. </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid gap-6">
                        <!-- Document Type Name -->
                        <div class="grid gap-2">
                            <Label for="docname">Workflow Document Type</Label>
                            <Input
                                id="docname"
                                v-model="form.docname"
                                placeholder="Enter workflow document type"
                                class="w-full md:max-w-sm"
                                autofocus
                            />
                            <span v-if="errors?.docname" class="text-sm text-red-600">
                                {{ errors.docname }}
                            </span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="flex items-center justify-between">
                        <!-- Close Left -->
                        <DialogClose as-child>
                            <Button type="button" variant="secondary">Close</Button>
                        </DialogClose>

                        <!-- Submit Right -->
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
