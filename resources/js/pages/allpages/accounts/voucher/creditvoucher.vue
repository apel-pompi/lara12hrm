<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Voucher Header', href: '/voucherheader/credit' }];
</script>

<template>
    <Head title="Voucher Header" />
     <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <h1 class="text-2xl font-semibold">Credit Voucher Page</h1>
        </div>
     </AppLayout>
</template>
