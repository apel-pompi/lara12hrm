<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import TableCell from '@/components/ui/table/TableCell.vue';
import { CornerDownLeft, Plus, RefreshCcw, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface PartnerType {
    id: number;
    partnertypename: string;
    mastercaterory_id: string;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Type Setup', href: '/patnersetup' }];

const props = defineProps<{
    partnertype: Paginated<PartnerType>;
    mastersetup: { id: number; catname: string }[];
}>();

const data = props.partnertype;

interface FormErrors {
    partnertypename?: string;
    mastercaterory_id?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    partnertypename: '',
    mastercaterory_id: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('general.patnersetup.update', form.id) : route('general.patnersetup');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Partner Type ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('general.patnersetup'), {
                    only: ['partner_type_setups'],
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

const toggleStatus = (partnertype: PartnerType, checked: boolean) => {
    router.put(
        route('general.patnersetupUpdateStatus', partnertype.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                partnertype.active = checked ? 1 : 0;
                toast.success('Partner Type  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this partner type setup?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/general/patnersetup/show/${id}`, {
        onSuccess: () => {
            toast.success('Partner Type deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToMasterCategory = () => {
    router.visit('/general');
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
const refresh = () => {
    router.get(route('general.patnersetup'), {}, { replace: true });
};
const perPage = ref(10);

const changePerPage = () => {
    router.get(route('general.patnersetup'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Partner Type Setup" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header Section -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Left Buttons -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                        <Button
                            class="w-full sm:w-auto dark:bg-black dark:text-white dark:hover:bg-gray-700"
                            variant="outline"
                            size="sm"
                            @click="goToMasterCategory"
                        >
                            <CornerDownLeft class="mr-2 h-4 w-4" />
                            Back Master Category
                        </Button>

                        <Button
                            class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm"
                            @click="showDailogCreate"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Partner Type
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
                        <h2 class="text-lg font-semibold text-gray-800">Partners Type</h2>
                        <p class="text-sm text-gray-500">Manage all partners type records from here.</p>
                    </div>
                    <Table>
                        <TableHeader class="bg-gray-100 dark:bg-gray-800">
                            <TableRow>
                                <TableHead class="min-w-55">Partner Type Name</TableHead>
                                <TableHead class="min-w-55">Master Category</TableHead>
                                <TableHead class="min-w-55">Added By</TableHead>
                                <TableHead class="min-w-55 text-center">Status</TableHead>
                                <TableHead class="min-w-55 text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(patnersetup, index) in data"
                                :key="patnersetup.id ?? index"
                                class="transition hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <TableCell class="font-medium">
                                    {{ patnersetup.partnertypename }}
                                </TableCell>

                                <TableCell>
                                    <span
                                        class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-200"
                                    >
                                        {{ patnersetup.mastercategory.catname }}
                                    </span>
                                </TableCell>

                                <TableCell>
                                    {{ patnersetup.user.name }}
                                </TableCell>

                                <TableCell class="text-center">
                                    <Switch
                                        :model-value="Boolean(patnersetup.active)"
                                        @update:model-value="(checked) => toggleStatus(patnersetup, checked)"
                                    />
                                </TableCell>

                                <TableCell class="text-center">
                                    <Button
                                        class="border-red-200 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30"
                                        size="sm"
                                        variant="outline"
                                        @click="onDelete(patnersetup.id)"
                                    >
                                        <Trash class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <!-- Empty State -->
                            <TableRow v-if="data.length === 0">
                                <TableCell colspan="5" class="py-10 text-center text-gray-500"> No Partner Type Found </TableCell>
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
                <DialogContent class="w-full max-w-lg rounded-2xl p-6 sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-4">
                        <DialogTitle class="text-xl font-semibold">
                            {{ isEditMode ? 'Edit Partner Type' : 'Create Partner Type' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            Make changes to your partner type here. Click save when you're done.
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="mt-6 space-y-5">
                        <!-- Master Category -->
                        <div class="space-y-2">
                            <Label for="mastercaterory_id" class="font-medium">Master Category</Label>
                            <Select v-model="form.mastercaterory_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Master Category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="master in props.mastersetup" :key="master.id" :value="master.id">
                                            {{ master.catname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.mastercaterory_id" class="text-sm text-red-600">
                                {{ errors.mastercaterory_id }}
                            </span>
                        </div>

                        <!-- Partner Type Name -->
                        <div class="space-y-2">
                            <Label for="partnertypename" class="font-medium">Partner Type Name</Label>
                            <Input
                                id="partnertypename"
                                v-model="form.partnertypename"
                                placeholder="Enter Partner Type Name"
                                class="w-full"
                                autofocus
                            />
                            <span v-if="errors?.partnertypename" class="text-sm text-red-600">
                                {{ errors.partnertypename }}
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
