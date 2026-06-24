<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/socialmediaLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';

import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { RefreshCcw } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [{ title: 'Facebook Form Sync', href: '/facebook/facebookForm' }];

const page = usePage();
const pagesData = (page.props as any).pages || [];
const formsData = (page.props as any).forms || [];

const selectedPage = ref(pagesData.length ? pagesData[0].page_id : null);
const currentPage = ref(1);
const perPage = ref(10);

const paginatedForms = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return formsData.slice(start, start + perPage.value);
});

const pageCount = computed(() => Math.max(1, Math.ceil(formsData.length / perPage.value)));
const pageNumbers = computed(() => Array.from({ length: pageCount.value }, (_, index) => index + 1));
const showingStart = computed(() => (formsData.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1));
const showingEnd = computed(() => Math.min(formsData.length, currentPage.value * perPage.value));

watch(perPage, () => {
    if (currentPage.value > pageCount.value) {
        currentPage.value = pageCount.value;
    }
});

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const syncForms = async () => {
    if (!selectedPage.value) {
        toast.error('Please select a Facebook page first.');
        return;
    }

    try {
        const response = await fetch(`${route('facebook.syncFacebookForms')}?page_id=${encodeURIComponent(selectedPage.value)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        });

        if (!response.ok) {
            throw new Error('Sync request failed');
        }

        const data = await response.json();

        if (data.success) {
            toast.success('Facebook forms synced successfully.');
            router.visit(route('facebook.facebookForm'), { replace: true });
        } else {
            toast.error(data.message || 'Unable to sync Facebook forms.');
        }
    } catch {
        toast.error('Unable to sync Facebook forms.');
    }
};

const deleteForm = useForm({});

const onDelete = async (formId: number) => {
    if (!confirm('Are you sure you want to delete this form?')) {
        return;
    }
    if (deleteForm.processing) return;
    deleteForm.delete(`/facebook/show/${formId}`, {
        onSuccess: () => {
            toast.success('Facebook form deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });

};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Facebook Form Sync" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <div class="mb-6 flex w-full">
                    <div class="flex w-full items-center justify-center gap-3">
                        <select v-model="selectedPage" class="rounded border px-3 py-2">
                            <option disabled value="">Select a page</option>
                            <option v-for="p in pagesData" :key="p.id || p.page_id" :value="p.page_id">
                                {{ p.page_id }} - {{ p.access_token ? 'Configured' : 'No Token' }}
                            </option>
                        </select>

                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="syncForms">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Sync Facebook Forms
                        </Button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Facebook Form Sync</h2>
                        <p class="text-sm text-gray-500">Facebook Form synchronization settings</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-16">SL</TableHead>
                                <TableHead>Form Name</TableHead>
                                <TableHead>Form ID</TableHead>
                                <TableHead>Page ID</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(form, index) in paginatedForms" :key="form.id">
                                <TableCell>{{ showingStart + Number(index) }}</TableCell>
                                <TableCell>{{ form.form_name }}</TableCell>
                                <TableCell>{{ form.facebook_form_id }}</TableCell>
                                <TableCell>{{ form.page_id }}</TableCell>
                                <TableCell>{{ form.status }}</TableCell>
                                <TableCell class="text-center">
                                    <Button size="sm" variant="outline" class="text-red-600" @click="onDelete(form.id)">
                                        Delete
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
                        <span>Showing {{ showingStart }} to {{ showingEnd }} of {{ formsData.length }} results</span>
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
            </div>
        </AgencyLayout>
    </AppLayout>
</template>
