<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsReportsLayout from '@/layouts/settings/accountsReportsLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Accounts Reports', href: '/accountsreports' }];

interface Account {
    id: number;
    name: string;
    accounttype: string;
}

const props = defineProps<{
    accounts: Record<string, Account[]>;
}>();


const form = useForm({
    accounttype: '',
});

const onReport = async () => {
    
    const url = route('accountsreport.chartOfAccountReport', {
        accounttype: form.accounttype || null,
    });
    window.open(url, '_blank');
    
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Accounts Reports" />
        <AccountsReportsLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Chart of Accounts Reports</h2>

                <div class="space-y-2">
                    <Select v-model="form.accounttype">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="All Account Type" />
                        </SelectTrigger>

                        <SelectContent>
                            <SelectItem v-for="(items, type) in props.accounts" :key="type" :value="type">
                                {{ type }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <!-- Submit -->
                <div class="flex justify-center">
                    <div class="group relative">
                        <Button
                            @click="onReport"
                            class="cursor-pointer rounded-full border-blue-300 text-blue-600 transition hover:bg-blue-50 hover:text-blue-700"
                            variant="outline"
                            size="sm"
                        >
                            <FileText class="text-red-500" />
                        </Button>
                        <span
                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                        >
                            Report
                        </span>
                    </div>
                </div>
            </div>
        </AccountsReportsLayout>
    </AppLayout>
</template>
