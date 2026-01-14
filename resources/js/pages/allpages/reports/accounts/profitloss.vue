<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsReportsLayout from '@/layouts/settings/accountsReportsLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, useForm } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';
import { computed, ref } from 'vue';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Accounts Reports', href: '/accountsreports' }];

const props = defineProps<{
    branch: Array<{ id: number; name: string }>;
    months: { id: number; name: string }[];
    years: { id: number; name: string }[];
}>();

const form = useForm({
    branch_id: '',
    yearname: '',
    monthname: '',
    type: '',
});

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});

const onReport = async () => {
    // ===== VALIDATION =====
    if (!form.yearname) {
        alert('Year is not selected');
        return;
    }

    if (!form.monthname) {
        alert('Month is not selected');
        return;
    }

    if (!form.type) {
        alert('Type is not selected');
        return;
    }

    // ===== BUILD URL =====
    const url = route('accountsreport.ProfitLossreport', {
        branch_id: selectedBranch.value ? selectedBranch.value.id : null,
        yearname: form.yearname,
        monthname: form.monthname,
        type: form.type,
    });

    // ===== OPEN REPORT =====
    window.open(url, '_blank');
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profit Loss Report" />
        <AccountsReportsLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Profit Loss Reports</h2>
                <div class="space-y-2">
                    <Combobox v-model="selectedBranch">
                        <div class="relative">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Branch"
                                @input="queryBranch = $event.target.value"
                                :display-value="(c) => c?.branchname ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredBranch.length === 0 && queryBranch !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredBranch"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.branchname }}
                                    </span>
                                    <span
                                        v-if="selected"
                                        class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon class="h-5 w-5" />
                                    </span>
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>
                </div>

                <div class="space-y-2">
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
                <div class="space-y-2">
                    <Select v-model="form.type">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="Summary">Summary</SelectItem>
                                <SelectItem value="Details">Details</SelectItem>
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
        </AccountsReportsLayout>
    </AppLayout>
</template>
