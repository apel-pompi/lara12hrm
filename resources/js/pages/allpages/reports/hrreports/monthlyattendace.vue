<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import HrReportLayout from '@/layouts/settings/hrreportLayout.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm} from '@inertiajs/vue3';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { computed, ref } from 'vue';
import { FileText } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'HR Reports', href: '/hrreports' }];

const props = defineProps<{
    branch: { id: number; branchname: string }[];
    months: { id: number; name: string }[];
    years: { id: number; name: string }[];
}>();

const selecteBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => (queryBranch.value === '' ? props.branch : props.branch.filter((n) => n.name)));

const form = useForm({
    branch_id: '',
    yearname: '',
    monthname: '',
});

const onReport = async () => {
    if (!selecteBranch.value || !selecteBranch.value.id) {
        alert('Branch is not selected');
        return;
    }
    if (form.yearname == '') {
        alert('Year is not selected');
        return;
    }

    if (form.monthname == '') {
        alert('Month is not selected');
        return;
    }
    form.branch_id = selecteBranch.value.id;
    const url = route('hrreports.MonthlyAttendanceReport', {
        branch_id: form.branch_id,
        yearname: form.yearname,
        monthname: form.monthname,
    });

    window.open(url, '_blank');
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="HR Reports" />
        <HrReportLayout>
                    <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Monthly attendance Reports</h2>

                <!-- Input -->
                <div class="space-y-2">
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Branch</Label>
                    <Combobox v-model="selecteBranch">
                        <div class="relative">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select Branch"
                                @input="queryBranch = $event.target.value"
                                :display-value="(c) => (c ? c.branchname : '')"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>
                                <ComboboxOption
                                    v-for="branch in filteredBranch"
                                    :key="branch.id"
                                    :value="branch"
                                    class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                >
                                    {{ branch.branchname }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>
                <div class="space-y-2">
                    <Label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Year</Label>
                    <Select v-model="form.yearname">
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
                    <Select v-model="form.monthname">
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
        </HrReportLayout>
    </AppLayout>
</template>
