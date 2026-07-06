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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'WhatsApp Sync', href: '/whatsapp/whatsappSync' }];

const page = usePage();
const pagesData = (page.props as any).pages || [];
const numbersData = (page.props as any).numbers || [];

const selectedWaba = ref(pagesData.length ? pagesData[0].whatsapp_business_account_id : null);
const currentPage = ref(1);
const perPage = ref(10);

const paginatedNumbers = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return numbersData.slice(start, start + perPage.value);
});

const pageCount = computed(() => Math.max(1, Math.ceil(numbersData.length / perPage.value)));
const pageNumbers = computed(() => Array.from({ length: pageCount.value }, (_, index) => index + 1));
const showingStart = computed(() => (numbersData.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1));
const showingEnd = computed(() => Math.min(numbersData.length, currentPage.value * perPage.value));

watch(perPage, () => {
    if (currentPage.value > pageCount.value) {
        currentPage.value = pageCount.value;
    }
});

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const syncNumbers = async () => {
    if (!selectedWaba.value) {
        toast.error('Please select a WhatsApp Business Account first.');
        return;
    }

    try {
        const response = await fetch(`${route('whatsapp.syncWhatsAppNumbers')}?waba_id=${encodeURIComponent(selectedWaba.value)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Sync request failed');
        }

        if (data.success) {
            toast.success('WhatsApp numbers synced successfully.');
            router.visit(route('whatsapp.whatsappSync'), { replace: true });
        } else {
            toast.error(data.message || 'Unable to sync WhatsApp numbers.');
        }
    } catch (error) {
        toast.error(error instanceof Error ? error.message : 'Unable to sync WhatsApp numbers.');
    }
};

const deleteForm = useForm({});

const onDelete = async (numberId: number) => {
    if (!confirm('Are you sure you want to delete this WhatsApp number?')) {
        return;
    }

    if (deleteForm.processing) return;

    deleteForm.delete(`/whatsapp/show/${numberId}`, {
        onSuccess: () => {
            toast.success('WhatsApp number deleted successfully');
        },
        onError: () => {
            toast.error('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="WhatsApp Sync" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <div class="mb-6 flex w-full">
                    <div class="flex w-full items-center justify-center gap-3">
                        <select v-model="selectedWaba" class="rounded border px-3 py-2">
                            <option disabled value="">Select a WhatsApp account</option>
                            <option v-for="p in pagesData" :key="p.id || p.whatsapp_business_account_id" :value="p.whatsapp_business_account_id">
                                {{ p.whatsapp_business_account_id }} - {{ p.access_token ? 'Configured' : 'No Token' }}
                            </option>
                        </select>

                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="syncNumbers">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Sync WhatsApp Numbers
                        </Button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">WhatsApp Sync</h2>
                        <p class="text-sm text-gray-500">WhatsApp phone number synchronization settings</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-16">SL</TableHead>
                                <TableHead>Verified Name</TableHead>
                                <TableHead>Phone Number</TableHead>
                                <TableHead>Phone ID</TableHead>
                                <TableHead>WABA ID</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(number, index) in paginatedNumbers" :key="number.id">
                                <TableCell>{{ showingStart + Number(index) }}</TableCell>
                                <TableCell>{{ number.verified_name || '-' }}</TableCell>
                                <TableCell>{{ number.phoneno }}</TableCell>
                                <TableCell>{{ number.phone_id }}</TableCell>
                                <TableCell>{{ number.waba_id }}</TableCell>
                                <TableCell>{{ number.status || '-' }}</TableCell>
                                <TableCell class="text-center">
                                    <Button size="sm" variant="outline" class="text-red-600" @click="onDelete(number.id)"> Delete </Button>
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
                        <span>Showing {{ showingStart }} to {{ showingEnd }} of {{ numbersData.length }} results</span>
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
