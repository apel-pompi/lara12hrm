<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import LeadReportLayout from '@/layouts/settings/leadreportLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

import { Head, router, useForm } from '@inertiajs/vue3';
import { FileText, RefreshCcw } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Transaction', href: '/leadreports/ledger' }];

const props = defineProps<{
    student: { id: number; student_id: string;phone:string; };
}>();

const selectedStudent = ref<{ id: number; student_id: string; phone: string } | null>(null);
const selectedPhone = ref<{ id: number; student_id: string; phone: string } | null>(null);

const queryStudent = ref('');
const queryPhone = ref('');

const filteredStudent = computed(() => {
    if (queryStudent.value === '') return props.student;
    return props.student.filter((n) =>
        n.student_id.toLowerCase().includes(queryStudent.value.toLowerCase())
    );
});

const filteredPhone = computed(() => {
    if (queryPhone.value === '') return props.student;
    return props.student.filter((n) =>
        n.phone.toLowerCase().includes(queryPhone.value.toLowerCase())
    );
});


const form = useForm({
    student_id: '',
});

watch(selectedStudent, (val) => {
    if (val) {
        selectedPhone.value = val; // sync phone
        form.student_id = val.id;
    } else {
        selectedPhone.value = null;
        form.student_id = '';
    }
});

watch(selectedPhone, (val) => {
    if (val) {
        selectedStudent.value = val; // sync student
        form.student_id = val.id;
    } else {
        selectedStudent.value = null;
        form.student_id = '';
    }
});

const isStudentDisabled = computed(() => selectedPhone.value !== null);
const isPhoneDisabled = computed(() => selectedStudent.value !== null);

const onReport = async () => {
    if (form.student_id == '') {
        alert('Student ID is not selected');
        return;
    }

    const url = route('leadreports.studentLedgerReport', {
        student: form.student_id,
    });
    window.open(url, '_blank');
};

const onRefresh = () => {
    router.get(route('leadreports.studentLedger'), {}, { replace: true });
};

</script> 
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Transaction Reports" />
        <LeadReportLayout>
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Student Transaction</h2>
                <div>
                    <div class="space-y-2">
                        <div class="w-full lg:w-auto">
                            <Combobox v-model="selectedStudent" :disabled="isStudentDisabled">
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border px-3 py-2 text-sm"
                                        placeholder="Select Student ID"
                                        @input="queryStudent = $event.target.value"
                                        :display-value="(c) => c?.student_id ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>

                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                    >
                                        <div v-if="filteredStudent.length === 0 && queryStudent !== ''" class="px-4 py-2 text-gray-500 select-none">
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="one in filteredStudent"
                                            :key="one.id"
                                            :value="one"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ one.student_id }}
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
                        <div class="w-full lg:w-auto">
                            <Combobox v-model="selectedPhone" :disabled="isPhoneDisabled">
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border px-3 py-2 text-sm"
                                        placeholder="Search Student Phone"
                                        @input="queryPhone = $event.target.value"
                                        :display-value="(c) => c?.phone ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>

                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                    >
                                        <div v-if="filteredPhone.length === 0 && queryPhone !== ''" class="px-4 py-2 text-gray-500 select-none">
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="one in filteredPhone"
                                            :key="one.id"
                                            :value="one"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ one.phone }}
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
                    </div>
                </div>
                <!-- Input -->

                <!-- Submit -->
                <div class="flex justify-center">
                    <div class="group relative p-5">
                        <Button
                            @click="onRefresh"
                            class="cursor-pointer"
                            variant="outline"
                            size="sm"
                        >
                            <RefreshCcw class="text-red-500" />
                        </Button>
                        <span
                            class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                        >
                            Refresh
                        </span>
                    </div>
                    <div class="group relative p-5">
                        <Button
                            @click="onReport"
                            class="cursor-pointer"
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
