<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { SquarePen } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Pay Slip Generate', href: '/attendanceStatus' }];

const props = defineProps<{
    alldata: {
        absent: number;
        active: number;
        branch_id: number;
        deducthour: string;
        empid: number;
        hrsurplus: string;
        leave: number;
        monthname: number;
        nethour: string;
        payablehour: string;
        totalhour: string;
    };
}>();

interface FormErrors {
    hrsurplus?: string;
}

const errors = ref<FormErrors>();

const form = useForm({
    id: '',
    hrsurplus: '',
    branch_id: '',
    monthname: '',
    yearname: '',
});

const showEditDialog = ref(false);

const onEdit = async (id: number, branch_id: number, monthname: number, yearname: number) => {
    if (confirm('Are you surplus hour this employee ?')) form.id = id;
    form.branch_id = branch_id;
    form.monthname = monthname;
    form.yearname = yearname;

    showEditDialog.value = true;
};
const onConfirm = () => {
    if (form.hrsurplus == '') {
        alert('surplus hour is not empty');
        return;
    }

    form.put(
        route('attendanceStatus.update', {
            attendanceStatus: form.id,
            branch_id: form.branch_id,
            monthname: form.monthname,
            yearname: form.yearname,
        }),
        {
            onSuccess: () => {
                toast.success('Surplus Hour Updated successfully');
                form.reset();
                showEditDialog.value = false;
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
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
                    <h2 class="text-lg font-semibold text-gray-800">Employee Attendance Summary</h2>
                    <p class="text-sm text-gray-500">Monthly working, attendance & payable hours report</p>
                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <Table class="min-w-full">
                        <!-- Table Head -->
                        <TableHeader>
                            <TableRow class="bg-gray-50">
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Employee Name</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Department</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Designation</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Working Hours</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Attend Hours</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Deduct Hours</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Absent</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Leave</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">H.R. Surplus</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Net Hours</TableHead>
                                <TableHead class="px-4 py-3 font-semibold text-gray-700">Payable Hours</TableHead>
                                <TableHead class="px-4 py-3 text-center font-semibold text-gray-700">Action</TableHead>
                            </TableRow>
                        </TableHeader>

                        <!-- Body -->
                        <TableBody>
                            <TableRow
                                v-for="(keydata, index) in props.alldata"
                                :key="keydata.id ?? index"
                                class="border-t transition hover:bg-gray-50"
                            >
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

                                <!-- Work Hour -->
                                <TableCell class="px-4 py-3 font-medium">
                                    {{ keydata.workhour }}
                                </TableCell>

                                <!-- Attend -->
                                <TableCell class="px-4 py-3 font-semibold text-green-600">
                                    {{ keydata.totalhour }}
                                </TableCell>

                                <!-- Deduct -->
                                <TableCell class="px-4 py-3 font-semibold text-red-500">
                                    {{ keydata.deducthour }}
                                </TableCell>

                                <!-- Absent -->
                                <TableCell class="px-4 py-3">
                                    <Badge variant="secondary" class="bg-red-100 text-red-700">
                                        {{ keydata.absent }}
                                    </Badge>
                                </TableCell>

                                <!-- Leave -->
                                <TableCell class="px-4 py-3">
                                    <Badge variant="secondary" class="bg-yellow-100 text-yellow-700">
                                        {{ keydata.leave }}
                                    </Badge>
                                </TableCell>

                                <!-- Surplus -->
                                <TableCell class="px-4 py-3 font-medium text-blue-600">
                                    {{ keydata.hrsurplus }}
                                </TableCell>

                                <!-- Net -->
                                <TableCell class="px-4 py-3 font-semibold">
                                    {{ keydata.nethour }}
                                </TableCell>

                                <!-- Payable -->
                                <TableCell class="px-4 py-3">
                                    <Badge v-if="keydata.payablehour < keydata.workhour" class="bg-red-500 text-white" size="sm">
                                        {{ keydata.payablehour }}
                                    </Badge>

                                    <Badge v-else class="bg-green-500 text-white" size="sm">
                                        {{ keydata.payablehour }}
                                    </Badge>
                                </TableCell>

                                <!-- Action -->
                                <TableCell class="px-4 py-3 text-center">
                                    <div class="group relative inline-block" v-if="keydata.payablehour < keydata.workhour">
                                        <Button
                                            size="icon"
                                            variant="ghost"
                                            class="h-9 w-9 text-green-600 hover:bg-green-100"
                                            @click="onEdit(keydata.id, keydata.branch_id, keydata.monthname, keydata.yearname)"
                                        >
                                            <SquarePen class="h-4 w-4" />
                                        </Button>

                                        <!-- Tooltip -->
                                        <span
                                            class="absolute bottom-full left-1/2 z-10 mb-2 hidden -translate-x-1/2 rounded-lg bg-gray-800 px-2 py-1 text-xs text-white group-hover:block"
                                        >
                                            Edit Record
                                        </span>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showEditDialog">
            <DialogContent class="max-w-206.25">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle>H.R Surplus Surplus </DialogTitle>
                    <DialogDescription> Submit H.R Surplus hour Confirm. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">Input Surplus Hour<span class="text-red-500">*</span></Label>
                        <Input type="number" id="hrsurplus" placeholder="HH.MM" step="0.01" v-model="form.hrsurplus" class="max-w-sm" autofocus />

                        <span v-if="errors?.hrsurplus" class="text-sm text-red-600">{{ errors.hrsurplus }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="form.processing" @click="onConfirm">
                        <template v-if="form.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
