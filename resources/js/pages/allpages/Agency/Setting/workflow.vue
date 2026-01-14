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
import { HousePlus, Plus, PlusCircle, RefreshCcw, Search, SquarePen } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import draggable from 'vuedraggable';

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

export interface Workflow {
    id: number;
    name: string;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Workflows', href: '/workflow' }];

const props = defineProps<{
    workflow: Paginated<Workflow>;
    filters: { name?: string };
    allworkflow: { id: number; name: string }[];
}>();

const data = props.workflow;

const items = ref<{ id: number; name: string }[]>([]);

const newItem = ref('');

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push({
            id: items.value.length + 1, // Auto increment id
            name: newItem.value,
        });
        newItem.value = ''; // input reset
    }
};
const removeItem = (id: number) => {
    items.value = items.value.filter((item) => item.id !== id);
};

interface FormErrors {
    name?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    stagename: '',
    stage: '',
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
        const res = await fetch(`/workflow/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching department details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.data);
        form.id = data.data.id;
        const stageNames = data.data.stagename.split(',');
        const stageIds = data.data.stage.split(',');
        items.value = stageNames.map((name: string, index: number) => ({
            id: Number(stageIds[index]) || index + 1,
            name: name,
        }));
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const reordered = items.value.map((item, index) => ({
        id: index + 1,
        name: item.name,
    }));
    form.stagename = reordered.map((item) => item.name).join(',');
    form.stage = reordered.map((item) => item.id).join(',');

    const action = isEditMode.value && form.id ? route('workflow.update', form.id) : route('workflow.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Worksflows ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                items.value = [];
                form.reset();
                router.visit(route('workflow.index'), {
                    only: ['workflows'],
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

const toggleStatus = (workflow: Workflow) => {
    const newStatus = !Boolean(workflow.active); // boolean
    router.put(
        route('workflow.updateStatus', workflow.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                workflow.active = newStatus ? 1 : 0; // local update (number)
                toast.success('General Services  status update');
            },
        },
    );
};

const goToDocumentList = (id: number) => {
    router.visit(route('documentlist.index', { id }));
};

const goToDocumentType = () => {
    router.visit('/documenttype');
};

const selectedName = ref(null);
const queryName = ref('');
const filteredName = computed(() => {
    if (queryName.value === '') return props.allworkflow;

    return props.allworkflow.filter((n) => n.name && n.name.toLowerCase().includes(queryName.value.toLowerCase()));
});

const search = () => {
    const params: Record<string, any> = {};

    if (selectedName.value) params.name = selectedName.value.name;

    router.get(route('workflow.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('workflow.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('workflow.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Workflows" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
                <div class="flex items-center justify-between py-4">
                    
                    <div class="flex flex-wrap items-center gap-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Workflows
                    </Button>
                        <div class="w-full sm:w-1/2 lg:w-auto">
                            <Combobox v-model="selectedName">
                                <div class="relative w-full md:w-48">
                                    <ComboboxInput
                                        class="w-full rounded-md border px-3 py-2 text-sm"
                                        placeholder="Select Name"
                                        @input="queryName = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>

                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                    >
                                        <div v-if="filteredName.length === 0 && queryName !== ''" class="text-gray-500 select-none">
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="one in filteredName"
                                            :key="one.id"
                                            :value="one"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ one.name }}
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
                        <div class="w-full sm:w-auto">
                            <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                        </div>
                        <div class="w-full sm:w-auto">
                            <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                        </div>
                    </div>

                    <div>
                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="goToDocumentType"
                            ><HousePlus></HousePlus> Document Type
                        </Button>
                    </div>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>General Services </TableHead>
                                <TableHead>Total Partners</TableHead>
                                <TableHead>Added Person</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(workflow, index) in data.data" :key="workflow.id ?? index">
                                <TableCell>{{ workflow.name }}</TableCell>
                                <TableCell></TableCell>
                                <TableCell>{{ workflow.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="workflow.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(workflow)">
                                    </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button size="sm" variant="outline" @click="onEdit(workflow.id)"><SquarePen></SquarePen></Button>
                                    <Button size="sm" variant="outline" @click="goToDocumentList(workflow.id)"><PlusCircle></PlusCircle></Button>
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
                        <span>Showing {{ workflow.from }} to {{ workflow.to }} of {{ workflow.total }} results</span>
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
                    <DialogHeader>
                        <DialogTitle>{{ isEditMode ? 'Edit Workflows' : 'Create Workflows' }}</DialogTitle>
                        <DialogDescription> Make changes to your workflows here. Click save when you're done. </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-5">
                        <div class="grid gap-y-3">
                            <div class="grid gap-2">
                                <Label for="name">Workflow Name</Label>
                                <Input class="max-w-sm" placeholder="Enter Workflow Name" id="name" v-model="form.name" autofocus />
                                <span v-if="errors?.name" class="text-sm text-red-600">{{ errors.name }}</span>
                            </div>
                            <div class="grid gap-2">
                                <Label for="stagename">Workflow Stage</Label>

                                <draggable v-model="items" item-key="id" class="space-y-2">
                                    <template #item="{ element, index }">
                                        <div class="flex items-center justify-between rounded border bg-gray-50 p-2">
                                            <span class="text-sm">{{ index + 1 }}. {{ element.name }}</span>
                                            <Button size="sm" variant="destructive" @click="removeItem(element.id)"> Delete </Button>
                                        </div>
                                    </template>
                                </draggable>
                                <div v-if="items.length === 0" class="text-gray-400 italic">No items yet. Please add one.</div>
                                <div class="mx-auto mt-6 max-w-md">
                                    <div class="flex gap-2">
                                        <Input v-model="newItem" placeholder="Enter stage name..." />
                                        <Button size="sm" @click="addItem" variant="outline">Add</Button>
                                    </div>
                                </div>
                            </div>
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
        </AgencyLayout>
    </AppLayout>
</template>
