<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Eye, Plus, SquarePen, Trash } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from 'vue-sonner';

export interface LeavePlan {
    id: number;
    leavename: string;
}
export interface Employee {
    empid: number;
    empname: string;
}
export interface Leave {
    id: number;
    leaveplan_id: string;
    empid: string;
    fromdate: string;
    todate: string;
    days: string;
    substitute: string;
    reason: string;
    status: string;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Leave', href: '/leave' }];

const props = defineProps<{
    leaves: Leave[];
    leaveplan: LeavePlan[];
    employee: Employee[];
}>();

const data = props.leaves;


const fromdate = ref<string | null>(null);
const todate = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

interface FormErrors {
    leaveplan_id?: string;
    empid?: string;
    fromdate: string;
    todate: string;
    days: string;
    substitute: string;
    reason: string;
    status: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    leaveplan_id: '',
    empid: '',
    fromdate: '',
    todate: '',
    days: '',
    substitute: '',
    reason: '',
    status: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const exportPdf = (id: number) => {
    window.open(route('leave.exportPdf', id), '_blank')
}

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/leaveplan/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching leaveplan details.');
            return;
        }

        const data = await res.json();

        Object.assign(form, data.data);
        form.id = data.data.id;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('leave.update', form.id) : route('leave.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Leave ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('leave.index'), {
                    only: ['leaves'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', {
                description: firstError,
            });
        },
    });
};
const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this leaveplan?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/leaveplan/show/${id}`, {
        onSuccess: () => {
            toast.success('Leave Plan deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};


watch(fromdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.fromdate = newDate.toISOString().split('T')[0];
    }
});

watch(todate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.todate = newDate.toISOString().split('T')[0];
    }
    
});

watch([fromdate, todate], ([newFrom, newTo]) => {
  if (newFrom && newTo) {
    const start = new Date(newFrom);
    const end = new Date(newTo);
    const diffTime = end.getTime() - start.getTime();
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

    form.days = diffDays > 0 ? diffDays.toString() : "0"; 
  } else {
    form.days = "0";
  }
});

</script>

<template>
    <Head title="Leave Paln" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Leave Request </Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Leave Name</TableHead>
                            <TableHead>Employee Name</TableHead>
                            <TableHead>From Date</TableHead>
                            <TableHead>To Date</TableHead>
                            <TableHead>Total Days</TableHead>
                            <TableHead>Substitute</TableHead>
                            <TableHead>Reason</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(leave, index) in data" :key="leave.id ?? index">
                            <TableCell>{{ leave.leave_plan.leavename }}</TableCell>
                            <TableCell>{{ leave.employee.empname }}</TableCell>
                            <TableCell>{{ leave.fromdate }}</TableCell>
                            <TableCell>{{ leave.todate }}</TableCell>
                            <TableCell>{{ leave.days }}</TableCell>
                            <TableCell>{{ leave.substitute_employee.empname }}</TableCell>
                            <TableCell>{{ leave.reason }}</TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="exportPdf(leave.id)"><Eye></Eye></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(leave.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(leave.id)"><Trash></Trash></Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm"></div>
                <div class="space-x-2"></div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Leave Request' : 'Create Leave Request' }}</DialogTitle>
                    <DialogDescription> Make changes to your leave request here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-2 gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="leaveplan_id">Leave Name</Label>
                            <Select v-model="form.leaveplan_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Leave Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="leaveplans in props.leaveplan" :key="leaveplans.id" :value="leaveplans.id">
                                            {{ leaveplans.leavename }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.leaveplan_id" class="text-sm text-red-600">{{ errors.leaveplan_id }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="empid">Employee Name</Label>
                            <Select v-model="form.empid">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Employee Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="employees in props.employee" :key="employees.empid" :value="employees.empid">
                                            {{ employees.empname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.empid" class="text-sm text-red-600">{{ errors.empid }}</span>
                        </div>

                        <div class="grid gap-2">
                            <Label for="fromdate">From Date</Label>
                            <VueDatePicker
                                v-model="fromdate"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="From date"
                                auto-apply
                            />
                            <span v-if="errors?.fromdate" class="text-sm text-red-600">{{ errors.fromdate }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="todate">To Date</Label>
                            <VueDatePicker
                                v-model="todate"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="To date"
                                auto-apply
                            />
                            <span v-if="errors?.todate" class="text-sm text-red-600">{{ errors.todate }}</span>
                        </div>
                    </div>
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="days">Total Days</Label>
                            <Input class="max-w-sm" placeholder="Total Days" id="empid" v-model="form.days" autofocus />
                            <span v-if="errors?.days" class="text-sm text-red-600">{{ errors.days }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="substitute">Substitute</Label>
                            <Select v-model="form.substitute">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Substitute Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="employees in props.employee" :key="employees.empid" :value="employees.empid">
                                            {{ employees.empname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.substitute" class="text-sm text-red-600">{{ errors.substitute }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="reason">Reason</Label>
                            <Textarea class="max-w-sm" placeholder="Write Reason" id="reason" v-model="form.reason" autofocus></Textarea>
                            <span v-if="errors?.reason" class="text-sm text-red-600">{{ errors.reason }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Button :disabled="form.processing" @click="submit">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                            </Button>
                        </div>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
