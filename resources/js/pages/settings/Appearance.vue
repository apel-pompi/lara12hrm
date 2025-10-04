<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import Layout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <Layout>
            <div class="flex h-full flex-1 flex-col gap-6 rounded-xl bg-gray-50 p-6">
                <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                <Card class="border border-gray-200 shadow-sm">
                    <CardContent class="pt-4">
                        <AppearanceTabs />
                    </CardContent>
                </Card>
            </div>
        </Layout>
    </AppLayout>
</template>
