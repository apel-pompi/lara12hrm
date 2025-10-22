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
import { Plus, RefreshCcw, Search, Trash } from 'lucide-vue-next';

import { toast } from 'vue-sonner';

export interface Quoatations {
    id: number;
    name: string;
    adddate: string;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Quoatations Name', href: '/quoatations' }];

const props = defineProps<{
    quoatations: Paginated<Quoatations>;
    quoatationsFilter: { name: string };
    filters: { name: string };
}>();

const data = props.quoatations;

interface FormErrors {
    name?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    active: false,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('quoatations.update', form.id) : route('quoatations.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Quoatations purpose ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('quoatations.index'), {
                    only: ['transaction_names'],
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

const toggleStatus = (quoatations: Quoatations) => {
    const newStatus = !Boolean(quoatations.active); // boolean
    router.put(
        route('quoatations.updateStatus', quoatations.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                quoatations.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Quoatations status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this quoatations name?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/quoatations/show/${id}`, {
        onSuccess: () => {
            toast.success('Quoatations name deleted successfully');
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
const filteredName = computed(() => (queryName.value === '' ? props.quoatationsFilter : props.quoatationsFilter.filter((n) => n.name)));
const search = () => {
    const params: Record<string, any> = {};

    if (selecteName.value) params.name = selecteName.value.name;

    router.get(route('quoatations.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('quoatations.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Quoatations Name" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create </Button>

                    <!-- Search start -->
                    <div class="grid gap-2">
                        <Combobox v-model="selecteName">
                            <div class="relative w-48">
                                <!-- Input -->
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select name..."
                                        :display-value="(n) => n?.name"
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
                    </div>
                    <div class="grid gap-2">
                        <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                    </div>
                    <div class="grid gap-2">
                        <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                    </div>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Quoatations Name</TableHead>
                                <TableHead>Added Date</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(trn, index) in data.data" :key="trn.id ?? index">
                                <TableCell>{{ trn.name }}</TableCell>
                                <TableCell>{{ trn.adddate }}</TableCell>
                                <TableCell>{{ trn.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="trn.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(trn)"> </Switch>
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(trn.id)"><Trash></Trash></Button>
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
                <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-3">
                        <DialogTitle class="text-lg font-semibold">
                            {{ isEditMode ? 'Edit quoatations name' : 'Create quoatations name' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{
                                isEditMode
                                    ? 'Update the quoatations name details and click save.'
                                    : 'Fill in the details below to create a new quoatations name.'
                            }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid gap-6 py-4">
                        <!-- Transaction Type -->
                        <div class="grid gap-2">
                            <Label for="name" class="font-medium">Quoatations Name</Label>
                            <Input id="name" placeholder="Enter Quoatations purpose" v-model="form.name" autofocus class="max-w-sm" />
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
