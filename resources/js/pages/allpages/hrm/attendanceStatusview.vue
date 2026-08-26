<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Check, Loader2, Plus } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Pay Slip Generate', href: '/attendanceStatus' }];

const props = defineProps<{
    alldata: {
        id: number;
        empid: number;
        hrsurplus: string;
        payablehour: string;
        employee: {
            empname: string;
            department: { deptname: string };
            designation: { desname: string };
        };
        totalhour: number;
        absent: number;
        leave: number;
        deducthour: string;
        nethour: string;
        salary: string;
        payableamount: string;

    }[];
    monthname: number;
    yearname: number;
    branch_id: number;
    hasExistingData: boolean;
    dataMismatch: boolean;
}>();

const tableData = ref([...props.alldata]);

const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const monthLabel = computed(() => MONTHS[(props.monthname ?? 1) - 1] ?? '');

const showDialog = ref(false);
const selectedRecord = ref<any>(null);

const form = useForm({
    hrsurplus: '',
    branch_id: props.branch_id,
    monthname: props.monthname,
    yearname: props.yearname,
});

const openDialog = (record: any) => {
    selectedRecord.value = record;
    form.hrsurplus = record.hrsurplus ?? '';
    showDialog.value = true;
};

const submit = () => {
    if (!selectedRecord.value) return;

    form.put(
        route('attendanceStatus.update', selectedRecord.value.id),
        {
            preserveScroll: true,

            onSuccess: () => {
                const record = tableData.value.find(
                    item => item.id === selectedRecord.value.id
                );

                if (record) {
                    record.hrsurplus = form.hrsurplus;
                    record.payablehour = selectedRecord.value.payablehour;
                }

                showDialog.value = false;
            },
        }
    );
};

const deleteGeneratedData = () => {
    router.delete(route('attendanceStatus.destroy'), {
        data: {
            branch_id: props.branch_id,
            yearname: props.yearname,
            monthname: props.monthname,
        },
        preserveScroll: true,
    });
};
</script>

<template>

    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Pay Slip Generate View" />
        <div class="app-page">
            <!-- Table Card -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Header -->
                <!-- Header -->
                <div class="border-b border-gray-200 bg-white px-5 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        <!-- Title -->
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold tracking-tight text-gray-800">
                                    Employee Attendance Summary
                                </h2>

                                <p class="mt-0.5 text-sm text-gray-500">
                                    Monthly working, attendance & payable hours report
                                </p>
                            </div>
                        </div>

                        <!-- Period -->
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>

                                {{ monthLabel }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm font-semibold text-gray-700">
                                {{ props.yearname }}
                            </span>
                        </div>
                    </div>
                </div>


                <!-- Data Mismatch Warning -->
                <div v-if="dataMismatch"
                    class="border-b border-amber-200 bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 px-5 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        <!-- Warning Info -->
                        <div class="flex min-w-0 items-center gap-3">

                            <!-- Icon -->
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14A2 2 0 003.92 21h16.16a2 2 0 001.73-3.14l-8.1-14a2 2 0 00-3.42 0z" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-semibold text-amber-900">
                                        Attendance Data Changed
                                    </h3>

                                    <span
                                        class="rounded-full bg-amber-200/70 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-amber-800">
                                        Action Required
                                    </span>
                                </div>

                                <p class="mt-0.5 text-xs leading-5 text-amber-700">
                                    The current Attendance Data does not match the generated
                                    Attendance Status. Delete the existing data and generate it again.
                                </p>
                            </div>
                        </div>

                        <!-- Delete Button -->
                        <button type="button" @click="deleteGeneratedData"
                            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-red-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10" />
                            </svg>

                            Delete Old Data
                        </button>
                    </div>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <Table class="min-w-full">
                        <!-- Table Head -->
                        <TableHeader>
                            <TableRow class="bg-gray-50">
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">#</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Employee Name</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Department</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Designation</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Total Work</TableHead>
                                <TableHead class="px-4 py-3 text-center font-semibold text-red-700">Absent</TableHead>
                                <TableHead class="px-4 py-3 text-center font-semibold text-blue-700">Leave</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-red-600">Total Deduct</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-800">Net Work</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-orange-700">Surplus Hrs</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-800">Payable Hour</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-800">Salary</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-800">Payable Amount</TableHead>
                                <TableHead class="px-4 py-3 text-center font-semibold text-gray-700">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <!-- Body -->
                        <TableBody>
                            <TableRow v-for="(keydata, index) in tableData" :key="keydata.empid ?? index"
                                class="border-t transition hover:bg-gray-50">
                                <!-- # -->
                                <TableCell class="px-4 py-3 text-gray-500">{{ index + 1 }}</TableCell>

                                <!-- Employee -->
                                <TableCell class="px-4 py-3 font-medium whitespace-nowrap text-gray-800">
                                    {{ keydata.employee.empname }}
                                </TableCell>

                                <!-- Department -->
                                <TableCell class="px-4 py-3 whitespace-nowrap text-gray-600">
                                    {{ keydata.employee.department.deptname }}
                                </TableCell>

                                <!-- Designation -->
                                <TableCell class="px-4 py-3 whitespace-nowrap text-gray-600">
                                    {{ keydata.employee.designation.desname }}
                                </TableCell>

                                <!-- totalhour -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-green-100 text-green-700">
                                        {{ keydata.totalhour }}
                                    </Badge>
                                </TableCell>

                                <!-- absent -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-yellow-100 text-yellow-700">
                                        {{ keydata.absent }}
                                    </Badge>
                                </TableCell>

                                <!-- leave -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-red-100 text-red-700">
                                        {{ keydata.leave }}
                                    </Badge>
                                </TableCell>

                                <!-- deducthour -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-blue-100 text-blue-700">
                                        {{ keydata.deducthour }}
                                    </Badge>
                                </TableCell>

                                <!-- nethour -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-gray-100 text-gray-600">
                                        {{ keydata.nethour }}
                                    </Badge>
                                </TableCell>

                                <!-- hrsurplus -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-orange-100 text-orange-700">
                                        {{ keydata.hrsurplus }}
                                    </Badge>
                                </TableCell>

                                <!-- payablehour -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-emerald-100 text-emerald-700">
                                        {{ keydata.payablehour }}
                                    </Badge>
                                </TableCell>

                                <!-- salary -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-emerald-100 text-emerald-700">
                                        {{ keydata.salary }}
                                    </Badge>
                                </TableCell>

                                <!-- payableamount -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Badge variant="secondary" class="bg-emerald-100 text-emerald-700">
                                        {{ keydata.payableamount }}
                                    </Badge>
                                </TableCell>

                                <!-- Action -->
                                <TableCell class="px-4 py-3 text-center">
                                    <Button size="sm" variant="outline" @click="openDialog(keydata)"> Edit </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>

            <!-- Hrsurplus Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-md rounded-xl border border-gray-200 shadow-2xl">
                    <DialogHeader class="border-b border-gray-100 pb-4">
                        <DialogTitle class="text-xl font-bold text-gray-900">Update Surplus Hours</DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500">
                            Adjust payable hours for <span class="font-medium text-gray-700">{{
                                selectedRecord?.employee?.empname ?? '' }}</span> by
                            adding surplus hours.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="space-y-5 py-5">
                        <div class="space-y-2">
                            <Label for="hrsurplus" class="text-sm font-semibold text-gray-700">Surplus Hours</Label>
                            <div class="relative">
                                <Plus class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input id="hrsurplus" v-model="form.hrsurplus" class="pl-9"
                                    placeholder="e.g. 2, 1:30, 1.5" type="text" />
                            </div>
                            <p v-if="form.errors.hrsurplus" class="text-sm text-red-500">
                                {{ form.errors.hrsurplus }}
                            </p>
                        </div>
                    </div>

                    <DialogFooter class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                        <DialogClose as-child>
                            <Button type="button" variant="ghost" :disabled="form.processing">Cancel</Button>
                        </DialogClose>
                        <Button type="button" :disabled="form.processing"
                            class="gap-2 bg-emerald-600 font-medium text-white hover:bg-emerald-700" @click="submit">
                            <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <Check v-else class="h-4 w-4" />
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
