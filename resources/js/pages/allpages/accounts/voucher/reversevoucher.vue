<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Reverse Voucher', href: '/voucherheader/reverse' }];
</script>

<template>
    <Head title="Reverse Voucher" />
     <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <h1 class="text-2xl font-semibold">Reverse Voucher Page</h1>
        </div>
     </AppLayout>
</template>
