<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Voucher Header', href: '/voucherheader' }];
</script>

<template>
    <Head title="Voucher Header" />
     <AppLayout :breadcrumbs="breadcrumbs">
        <template #default>
            <h1 class="text-2xl font-semibold mb-4">Credit Voucher Header Page</h1>
            <!-- Add your voucher header content here -->
        </template>
     </AppLayout>
</template>
