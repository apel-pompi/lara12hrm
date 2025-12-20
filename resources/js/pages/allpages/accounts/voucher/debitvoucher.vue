<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Voucher Header', href: '/voucherheader/debit' }];
</script>

<template>
    <Head title="Voucher Header" />
     <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <h1 class="text-2xl font-semibold">Debit Voucher Page</h1>
        </div>
     </AppLayout>
</template>
