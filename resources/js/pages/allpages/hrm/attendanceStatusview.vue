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
    branch_id:'',
    monthname:'',
    yearname:'',
});

const showEditDialog = ref(false);

const onEdit = async (id: number,branch_id:number,monthname:number,yearname:number) => {
    if (confirm('Are you surplus hour this employee ?')) 
    form.id = id;
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
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4"></div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Employee Name</TableHead>
                            <TableHead>Department</TableHead>
                            <TableHead>Designation</TableHead>
                            <TableHead>Working Hours</TableHead>
                            <TableHead>Attend Hours</TableHead>
                            <TableHead>Deduct Hours</TableHead>
                            <TableHead>Absent</TableHead>
                            <TableHead>Leave</TableHead>
                            <TableHead>H.R. Surplus</TableHead>
                            <TableHead>Net Hours</TableHead>
                            <TableHead>Payable Hours</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(keydata, index) in props.alldata" :key="keydata.id ?? index">
                            <TableCell>{{ keydata.employee.empname }}</TableCell>
                            <TableCell>{{ keydata.employee.department.deptname }}</TableCell>
                            <TableCell>{{ keydata.employee.designation.desname }}</TableCell>
                            <TableCell>{{ keydata.workhour }}</TableCell>
                            <TableCell>{{ keydata.totalhour }}</TableCell>
                            <TableCell>{{ keydata.deducthour }}</TableCell>
                            <TableCell>{{ keydata.absent }}</TableCell>
                            <TableCell>{{ keydata.leave }}</TableCell>
                            <TableCell>{{ keydata.hrsurplus }}</TableCell>
                            <TableCell>{{ keydata.nethour }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary" class="bg-red-500" size="sm" v-if="keydata.payablehour < keydata.workhour">{{
                                    keydata.payablehour
                                }}</Badge>
                                <Badge variant="secondary" class="bg-green-500" size="sm" v-if="keydata.payablehour > keydata.workhour">{{
                                    keydata.payablehour
                                }}</Badge>
                            </TableCell>
                            
                            <TableCell>
                                <div class="group relative" v-if="keydata.payablehour < keydata.workhour">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onEdit(keydata.id,keydata.branch_id,keydata.monthname,keydata.yearname)">
                                        <SquarePen class="text-green-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Edit
                                    </span>
                                </div>
                            </TableCell>
                            
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
        <Dialog v-model:open="showEditDialog">
            <DialogContent class="max-w-[825px]">
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
