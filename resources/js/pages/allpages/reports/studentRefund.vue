<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import LeadReportLayout from '@/layouts/settings/leadreportLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker  from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { FileText } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Refund Summary', href: '/leadreports/revenue' }];

const props = defineProps<{
    UsersWithRoles: [];
    isAdmin: number;
}>();

const formdate = ref<string | null>(null);
const todate = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

const form = useForm({
    employee: '',
    formdate: '',
    todate: '',
});

watch(formdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.formdate = newDate.toISOString().split('T')[0];
    }
});

watch(todate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.todate = newDate.toISOString().split('T')[0];
    }
});

const onReport = async () => {
    if (form.formdate == '') {
        alert('Form date not selected');
        return;
    }

    if (form.todate == '') {
        alert('To Date is not selected');
        return;
    }

    const url = route('leadreports.studentRefundReport', {
        formdate: form.formdate,
        todate: form.todate,
        isAdmin:props.isAdmin,
        employee: form.employee || null
        
    });
    window.open(url, '_blank');
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Refund Summary Reports" />
        <LeadReportLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Student Refund Summary</h2>
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
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form Date</Label>
                    <VueDatePicker
                        v-model="formdate"
                        :max-date="maxDate"
                        :format="'yyyy-MM-dd'"
                        :enable-time-picker="false"
                        placeholder="Form Date"
                        auto-apply
                    />
                </div>
                <div class="space-y-2">
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</Label>
                    <VueDatePicker
                        v-model="todate"
                        :max-date="maxDate"
                        :format="'yyyy-MM-dd'"
                        :enable-time-picker="false"
                        placeholder="To Date"
                        auto-apply
                    />
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
