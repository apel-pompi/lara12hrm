<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import LeadReportLayout from '@/layouts/settings/leadreportLayout.vue';
import { type BreadcrumbItem } from '@/types';

import { Head, useForm } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'HR Reports', href: '/hrreports' }];

const props = defineProps<{
    months: { id: number; name: string };
    years: { id: number; name: string };
    UsersWithRoles: [];
    isAdmin: number;
}>();

const form = useForm({
    employee: '',
    month: '',
    year: '',
});

const onReport = async () => {
    if (form.year == '') {
        alert('Year is not selected');
        return;
    }

    if (form.month == '') {
        alert('Month is not selected');
        return;
    }

    if (props.isAdmin) {
        const url = route("leadreports.MonthlyEmpLeadReport", {
            year: form.year,
            month: form.month,
            employee: form.employee || null
        });
        window.open(url, '_blank');
        return;
    }
    
    const url = route('leadreports.MonthlyLeadReport', {
        year: form.year,
        month: form.month,
    });
    window.open(url, '_blank');
    
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Lead Reports" />
        <LeadReportLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Monthly Reports</h2>
                <div v-if="props.isAdmin">
                    <div class="space-y-2">
                        <Select v-model="form.employee">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="All Employee" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="emp in props.UsersWithRoles" :key="emp.id" :value="emp.id">
                                        {{ emp.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
                <div v-else></div>
                <!-- Input -->
                <div class="space-y-2">
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Year</Label>
                    <Select v-model="form.year">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Year" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="year in props.years" :key="year.id" :value="year.id">
                                    {{ year.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
                <div class="space-y-2">
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Month</Label>
                    <Select v-model="form.month">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Month" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="month in props.months" :key="month.id" :value="month.id">
                                    {{ month.name }}
                                </SelectItem>
                            </SelectGroup>
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
        </LeadReportLayout>
    </AppLayout>
</template>
