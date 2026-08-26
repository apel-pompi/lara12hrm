<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/socialmediaLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';

import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { PenBoxIcon, Plus, RefreshCcw, Search, Trash2Icon } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [{ title: 'Userwise Forms', href: '/facebook/facebookForm' }];

const page = usePage();

const userWiseForms = (page.props as any).userWiseForms || [];
const forms = (page.props as any).forms || [];
const users = (page.props as any).users || [];

const showDialog = ref(false);
const isEditMode = ref(false);
const editingId = ref<number | null>(null);
const selectedForm = ref<any | null>(null);
const selectedTeam = ref<any | null>(null);
const formQuery = ref('');
const teamQuery = ref('');
const counselorQuery = ref('');
const form = useForm({
    form_id: '',
    team_id: '',
    counsilor_id: [] as string[],
    status: 1,
});

const filteredForms = computed(() => {
    if (!formQuery.value.trim()) {
        return forms;
    }
    return forms.filter((item: any) => item.form_name.toLowerCase().includes(formQuery.value.toLowerCase()));
});

const filteredTeams = computed(() => {
    if (!teamQuery.value.trim()) {
        return users;
    }
    return users.filter((user: any) => user.name.toLowerCase().includes(teamQuery.value.toLowerCase()));
});

const filteredCounselors = computed(() => {
    const excludedId = selectedTeam.value?.id ? String(selectedTeam.value.id) : null;
    const baseList = users.filter((user: any) => user.id !== Number(excludedId));
    if (!counselorQuery.value.trim()) {
        return baseList;
    }
    return baseList.filter((user: any) => user.name.toLowerCase().includes(counselorQuery.value.toLowerCase()));
});

const selectedCounselorUsers = computed(() => {
    const excludedId = selectedTeam.value?.id ? String(selectedTeam.value.id) : null;
    return users.filter(
        (user: any) => user.id !== Number(excludedId) && form.counsilor_id.includes(String(user.id))
    );
});

const isCounselorSelected = (id: number | string) => form.counsilor_id.includes(String(id));

const toggleCounselor = (id: number | string) => {
    const value = String(id);
    if (form.counsilor_id.includes(value)) {
        form.counsilor_id = form.counsilor_id.filter((item: any) => item !== value);
        return;
    }
    form.counsilor_id = [...form.counsilor_id, value];
};

const removeCounselor = (id: number | string) => {
    form.counsilor_id = form.counsilor_id.filter((item: any) => item !== String(id));
};

watch(selectedForm, (value) => {
    form.form_id = value?.id ? String(value.id) : '';
});

watch(selectedTeam, (value) => {
    form.team_id = value?.id ? String(value.id) : '';
    if (value?.id) {
        form.counsilor_id = form.counsilor_id.filter((item: any) => item !== String(value.id));
    }
});

const currentPage = ref(1);
const perPage = ref(10);

const paginatedForms = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return userWiseForms.slice(start, start + perPage.value);
});

const pageCount = computed(() => Math.max(1, Math.ceil(userWiseForms.length / perPage.value)));
const pageNumbers = computed(() => Array.from({ length: pageCount.value }, (_, index) => index + 1));
const showingStart = computed(() => (userWiseForms.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1));
const showingEnd = computed(() => Math.min(userWiseForms.length, currentPage.value * perPage.value));

watch(perPage, () => {
    if (currentPage.value > pageCount.value) {
        currentPage.value = pageCount.value;
    }
});

const showDialogCreate = () => {
    isEditMode.value = false;
    editingId.value = null;
    selectedForm.value = null;
    selectedTeam.value = null;
    form.reset();
    form.form_id = '';
    form.team_id = '';
    form.counsilor_id = [];
    form.status = 1;
    formQuery.value = '';
    teamQuery.value = '';
    counselorQuery.value = '';
    showDialog.value = true;
};

const showDialogEdit = (userForm: any) => {
    isEditMode.value = true;
    editingId.value = userForm.id;
    selectedForm.value = forms.find((item: any) => item.id === Number(userForm.form_id)) ?? null;
    selectedTeam.value = users.find((item: any) => item.id === Number(userForm.team_id)) ?? null;
    form.reset();
    form.form_id = String(userForm.form_id);
    form.team_id = String(userForm.team_id);
    form.counsilor_id = Array.isArray(userForm.counsilor_id) ? userForm.counsilor_id.map(String) : JSON.parse(userForm.counsilor_id || '[]').map(String);
    form.status = userForm.status;
    formQuery.value = '';
    teamQuery.value = '';
    counselorQuery.value = '';
    showDialog.value = true;
};

const submit = () => {
    if (isEditMode.value && editingId.value) {
        form.put(route('userwise-form.update', { userWiseForm: editingId.value }), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Userwise form updated successfully');
                showDialog.value = false;
                router.visit(route('userwise-form.index'), { replace: true });
            },
            onError: () => {
                toast.error('Please fix validation errors');
            },
        });
        return;
    }

    form.post(route('userwise-form.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Userwise form created successfully');
            showDialog.value = false;
            router.visit(route('userwise-form.index'), { replace: true });
        },
        onError: () => {
            toast.error('Please fix validation errors');
        },
    });
};

const deleteForm = useForm({});


const getUserName = (userId: number | string) => {
    const selected = users.find((item: any) => item.id === Number(userId));
    return selected?.name ?? '-';
};

const getCounselorNames = (counselorIds: any) => {
    const ids = Array.isArray(counselorIds) ? counselorIds : JSON.parse(counselorIds || '[]');
    return ids
        .map((id: number) => users.find((item: any) => item.id === Number(id))?.name)
        .filter(Boolean) as string[];
};

const search = () => {
    router.get(route('userwise-form.index'), {}, { preserveState: false, replace: true });
};

const refresh = () => {
    router.get(route('userwise-form.index'), {}, { replace: true });
};

const onDelete = async (formId: number) => {
    if (!confirm('Are you sure you want to delete this record?')) {
        return;
    }
    if (deleteForm.processing) return;
    deleteForm.delete(route('userwise-form.destroy', { userWiseForm: formId }), {
        onSuccess: () => {
            toast.success('Userwise form deleted successfully');
            router.visit(route('userwise-form.index'), { replace: true });
        },
        onError: () => {
            toast.error('Something went wrong!');
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Userwise Forms" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Actions -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <!-- Create -->
                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDialogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Create
                        </Button>

                        <!-- Search Combobox -->
                        

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

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Userwise Forms</h2>
                        <p class="text-sm text-gray-500">Manage your userwise forms</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-16">SL</TableHead>
                                <TableHead>Form Name</TableHead>
                                <TableHead>Team Leader</TableHead>
                                <TableHead>Counselors</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(userForm, index) in paginatedForms" :key="userForm.id">
                                <TableCell>{{ showingStart + Number(index) }}</TableCell>
                                <TableCell>{{ userForm.form.form_name }}</TableCell>
                                <TableCell>{{ getUserName(userForm.team_id) }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="name in getCounselorNames(userForm.counsilor_id)"
                                            :key="name"
                                            class="rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-100"
                                        >
                                            {{ name }}
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell>{{ userForm.status === 1 ? 'Active' : 'Inactive' }}</TableCell>
                                <TableCell class="text-center space-x-2">
                                    <Button size="sm" variant="outline" class="text-blue-600" @click="showDialogEdit(userForm)">
                                        <PenBoxIcon class="mr-1 h-4 w-4" />
                                    </Button>
                                    <Button size="sm" variant="outline" class="text-red-600" @click="onDelete(userForm.id)">
                                        <Trash2Icon class="mr-1 h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="mt-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                        <span>Show</span>
                        <select v-model="perPage" class="rounded-md border px-2 py-1 text-sm">
                            <option v-for="size in [10, 25, 50, 100]" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span>Showing {{ showingStart }} to {{ showingEnd }} of {{ userWiseForms.length }} results</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button size="sm" variant="outline" :disabled="currentPage === 1" @click="currentPage = Math.max(1, currentPage - 1)">
                            Previous
                        </Button>
                        <template v-for="pageNumber in pageNumbers" :key="pageNumber">
                            <button
                                type="button"
                                class="rounded border px-2 py-1 text-sm"
                                :class="pageNumber === currentPage ? 'bg-gray-200' : 'bg-white'"
                                @click="currentPage = pageNumber"
                            >
                                {{ pageNumber }}
                            </button>
                        </template>
                        <Button size="sm" variant="outline" :disabled="currentPage === pageCount" @click="currentPage = Math.min(pageCount, currentPage + 1)">
                            Next
                        </Button>
                    </div>
                </div>

                <Dialog v-model:open="showDialog">
                    <DialogContent class="max-w-xl rounded-2xl shadow-lg sm:max-w-2xl">
                        <DialogHeader>
                            <DialogTitle>{{ isEditMode ? 'Update Userwise Form' : 'Create Userwise Form' }}</DialogTitle>
                            <DialogDescription>
                                {{ isEditMode ? 'Update the selected record.' : 'Select the Facebook form, team leader, and counselors below.' }}
                            </DialogDescription>
                        </DialogHeader>
                        <div class="grid gap-4 p-4">
                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Form Name</label>
                                <Combobox v-model="selectedForm">
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                            placeholder="Search or select a form"
                                            :display-value="(item: any) => item?.form_name ?? ''"
                                            @input="formQuery = $event.target.value"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>

                                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">
                                            <template v-if="filteredForms.length === 0 && formQuery !== ''">
                                                <div class="cursor-default px-4 py-2 text-gray-500">No form found.</div>
                                            </template>
                                            <ComboboxOption
                                                v-for="item in filteredForms"
                                                :key="item.id"
                                                :value="item"
                                                v-slot="{ selected, active }"
                                            >
                                                <li
                                                    class="relative cursor-pointer select-none py-2 pr-4 pl-10"
                                                    :class="active ? 'bg-indigo-600 text-white' : 'text-gray-900'"
                                                >
                                                    <span class="block truncate" :class="selected ? 'font-medium' : 'font-normal'">{{ item.form_name }}</span>
                                                    <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                        <CheckIcon class="h-5 w-5" />
                                                    </span>
                                                </li>
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </div>
                                </Combobox>
                                <p v-if="form.errors.form_id" class="text-sm text-red-600">{{ form.errors.form_id }}</p>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Team Leader</label>
                                <Combobox v-model="selectedTeam">
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                            placeholder="Search or select a team leader"
                                            :display-value="(item: any) => item?.name ?? ''"
                                            @input="teamQuery = $event.target.value"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>

                                        <ComboboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">
                                            <template v-if="filteredTeams.length === 0 && teamQuery !== ''">
                                                <div class="cursor-default px-4 py-2 text-gray-500">No team leader found.</div>
                                            </template>
                                            <ComboboxOption
                                                v-for="user in filteredTeams"
                                                :key="user.id"
                                                :value="user"
                                                v-slot="{ selected, active }"
                                            >
                                                <li
                                                    class="relative cursor-pointer select-none py-2 pr-4 pl-10"
                                                    :class="active ? 'bg-indigo-600 text-white' : 'text-gray-900'"
                                                >
                                                    <span class="block truncate" :class="selected ? 'font-medium' : 'font-normal'">{{ user.name }}</span>
                                                    <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                        <CheckIcon class="h-5 w-5" />
                                                    </span>
                                                </li>
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </div>
                                </Combobox>
                                <p v-if="form.errors.team_id" class="text-sm text-red-600">{{ form.errors.team_id }}</p>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Counselors</label>
                                <div>
                                    <input
                                        type="text"
                                        v-model="counselorQuery"
                                        placeholder="Search counselors..."
                                        class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                    />
                                    <div class="mt-2 max-h-56 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-sm">
                                        <div v-if="filteredCounselors.length === 0" class="px-3 py-2 text-sm text-gray-500">No counselors found.</div>
                                        <button
                                            v-for="user in filteredCounselors"
                                            :key="user.id"
                                            type="button"
                                            class="flex w-full items-center justify-between gap-3 px-3 py-2 text-left text-sm text-gray-700 hover:bg-slate-100"
                                            @click.prevent="toggleCounselor(user.id)"
                                        >
                                            <span class="truncate">{{ user.name }}</span>
                                            <CheckIcon v-if="isCounselorSelected(user.id)" class="h-4 w-4 text-indigo-600" />
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span
                                        v-for="user in selectedCounselorUsers"
                                        :key="user.id"
                                        class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700"
                                    >
                                        {{ user.name }}
                                        <button
                                            type="button"
                                            @click.prevent="removeCounselor(user.id)"
                                            class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-slate-200 text-slate-700 hover:bg-slate-300"
                                        >
                                            ×
                                        </button>
                                    </span>
                                </div>
                                <p v-if="form.errors.counsilor_id" class="text-sm text-red-600">{{ form.errors.counsilor_id }}</p>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium">Status</label>
                                <select v-model="form.status" class="w-full rounded border px-3 py-2">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <p v-if="form.errors.status" class="text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>
                        </div>
                        <DialogFooter class="flex justify-end gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary">Cancel</Button>
                            </DialogClose>
                            <Button :disabled="form.processing" @click="submit">
                                {{ form.processing ? (isEditMode ? 'Updating...' : 'Saving...') : (isEditMode ? 'Update' : 'Save') }}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </AgencyLayout>
    </AppLayout>
</template>
