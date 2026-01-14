<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { CornerDownLeft, Plus, SquarePen, RefreshCcw, Search, } from 'lucide-vue-next';
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
    totaluse: number;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Documetn Type', href: '/documenttype' }];

const props = defineProps<{
    documenttype: Paginated<DocumentType>;
    filters: { docname: string };
    alldocument:{id:number;docname:string}[]
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

const toggleStatus = (documenttype: DocumentType) => {
    const newStatus = !Boolean(documenttype.active); // boolean
    router.put(
        route('documenttype.updateStatus', documenttype.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                documenttype.active = newStatus ? 1 : 0; // local update (number)
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
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="goToWorkflow"><CornerDownLeft></CornerDownLeft>Back Workflows </Button>
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Document Type </Button>
                    <!-- Search Box -->
                        <div class="flex w-full items-center gap-2 sm:w-auto">
                            <Combobox v-model="selecteName" class="w-full sm:w-56">
                                <div class="relative w-full">
                                    <!-- Input -->
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="search name..."
                                            :display-value="(n) => n?.docname"
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
                                                {{ n.docname }}
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
                            <Button class="w-full sm:w-auto dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="search">
                                <Search class="mr-1 h-4 w-4" /> Search
                            </Button>
                            <Button variant="outline" size="sm" @click="refresh" class="w-full sm:w-auto dark:bg-black dark:text-white dark:hover:bg-gray-600">
                                <RefreshCcw class="mr-1 h-4 w-4" /> Refresh
                            </Button>
                        </div>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
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
                            <TableRow v-for="(documenttype, index) in data.data" :key="documenttype.id ?? index">
                                <TableCell>{{ documenttype.docname }}</TableCell>
                                <TableCell>{{ documenttype.adddate }}</TableCell>
                                <TableCell>{{ documenttype.totaluse }}</TableCell>
                                <TableCell>
                                    <Switch v-model="documenttype.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(documenttype)">
                                    </Switch>
                                </TableCell>
                                <TableCell>{{ documenttype.user.name }}</TableCell>

                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(documenttype.id)"
                                        ><SquarePen></SquarePen
                                    ></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                    <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                        <label for="per-page" class="text-gray-600">Show:</label>
                        <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span>Showing {{ documenttype.from }} to {{ documenttype.to }} of {{ documenttype.total }} results</span>
                    </div>
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
