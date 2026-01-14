<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsReportsLayout from '@/layouts/settings/accountsReportsLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, useForm } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { FileText } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Accounts Reports', href: '/accountsreports' }];

interface Account {
    id: number;
    accountcode: string;
    description: string;
}

const props = defineProps<{
    accounts: Record<string, Account[]>;
    branch: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    branch_id: '',
    account: '',
    startdate: '',
    enddate: '',
});

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});

const selectedAccount = ref(null);
const queryAccount = ref('');
const filteredAccount = computed(() => {
    if (queryAccount.value === '') return props.accounts;

    return props.accounts.filter((n) => n.description && n.description.toLowerCase().includes(queryAccount.value.toLowerCase()));
});

const sdate = ref<string | null>(null);

const maxDate = today(getLocalTimeZone());

watch(sdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.startdate = newDate.toISOString().split('T')[0];
    }
});

const edate = ref<string | null>(null);

watch(edate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.enddate = newDate.toISOString().split('T')[0];
    }
});

const onReport = async () => {

    // ===== VALIDATION =====
    if (!selectedAccount.value || !selectedAccount.value.accountcode) {
        alert('Account is not selected');
        return;
    }

    if (!form.startdate) {
        alert('Start Date is not selected');
        return;
    }

    if (!form.enddate) {
        alert('End Date is not selected');
        return;
    }

    if (new Date(form.startdate) > new Date(form.enddate)) {
        alert('Start Date cannot be greater than End Date');
        return;
    }

    // ===== BUILD URL =====
    const url = route('accountsreport.ActoGLReport', {
        branch_id: selectedBranch.value ? selectedBranch.value.id : null,
        account: selectedAccount.value.accountcode,
        startdate: form.startdate,
        enddate: form.enddate,
    });

    // ===== OPEN REPORT =====
    window.open(url, '_blank');
};


</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Account to General Ledger Reports" />
        <AccountsReportsLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Account to General Ledger Reports</h2>
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
                    <Combobox v-model="selectedAccount">
                        <div class="relative">
                            <ComboboxInput
                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Select account..."
                                :display-value="(account) => account?.description ?? ''"
                                @input="queryAccount = $event.target.value"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div
                                    v-if="filteredAccount.length === 0 && queryAccount !== ''"
                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                >
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="n in filteredAccount"
                                    :key="n.id"
                                    :value="n"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']"> {{ n.description }}</span>
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
                    <VueDatePicker
                        v-model="sdate"
                        :max-date="maxDate"
                        :format="'yyyy-MM-dd'"
                        :enable-time-picker="false"
                        placeholder="Select Date"
                        auto-apply
                    />
                </div>
                <div class="space-y-2">
                    <VueDatePicker
                        v-model="edate"
                        :max-date="maxDate"
                        :format="'yyyy-MM-dd'"
                        :enable-time-picker="false"
                        placeholder="Select Date"
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
        </AccountsReportsLayout>
    </AppLayout>
</template>
