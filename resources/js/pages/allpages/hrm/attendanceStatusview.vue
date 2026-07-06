<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
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
    }[];
    monthname: number;
    yearname: number;
    branch_id: number;
}>();
console.log(props.alldata);
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
    form.branch_id = props.branch_id;
    form.monthname = props.monthname;
    form.yearname = props.yearname;
    showDialog.value = true;
};

const submit = () => {
    if (!selectedRecord.value) return;
    showDialog.value = false;
    form.put(route('attendanceStatus.update', { attendanceStatus: selectedRecord.value.id }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Surplus hours updated');
        },
        onError: (errors) => {
            toast.error('Please check the input value');
            console.error(errors);
        },
    });
};
</script>

<template>
    <Head title="Pay Slip Generate View" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 py-4 md:px-6">
            <!-- Table Card -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <!-- Header -->
                <div class="border-b bg-gray-50 px-5 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Employee Attendance Summary</h2>
                            <p class="text-sm text-gray-500">Monthly working, attendance &amp; payable hours report</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                {{ monthLabel }}
                            </span>
                            <span class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold text-gray-700">
                                {{ props.yearname }}
                            </span>
                        </div>
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
                                <TableHead class="px-4 py-3 text-center font-semibold text-gray-700">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <!-- Body -->
                        <TableBody>
                            <TableRow
                                v-for="(keydata, index) in props.alldata"
                                :key="keydata.empid ?? index"
                                class="border-t transition hover:bg-gray-50"
                            >
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
                            Adjust payable hours for <span class="font-medium text-gray-700">{{ selectedRecord?.employee?.empname ?? '' }}</span> by
                            adding surplus hours.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="space-y-5 py-5">
                        <div class="space-y-2">
                            <Label for="hrsurplus" class="text-sm font-semibold text-gray-700">Surplus Hours</Label>
                            <div class="relative">
                                <Plus class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input id="hrsurplus" v-model="form.hrsurplus" class="pl-9" placeholder="e.g. 2, 1:30, 1.5" type="text" />
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
                        <Button
                            type="button"
                            :disabled="form.processing"
                            class="gap-2 bg-emerald-600 font-medium text-white hover:bg-emerald-700"
                            @click="submit"
                        >
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
