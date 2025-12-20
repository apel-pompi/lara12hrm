<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import  VueDatePicker  from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Eye, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leave Request',
        href: '/dashboard/LeaveRequest',
    },
];

export interface Paginated<T> {
    data: T[];
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

export interface Leave {
    id: number;
    reference_id: string;
    description: string;
    remarks: string;
    status: string;
    user_id: number;
    created_at: number;
}

const props = defineProps<{

    leave: Paginated<Leave>;
}>();

const data = props.leave;

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const form = useForm({
    leave_id: '',
    leaveplan_id: '',
    empname: '',
    fromdate: '',
    todate: '',
    requestdays: '',
    approveddate: '',
    approveddays: '',
    substitute: '',
    contact_address: '',
    reason: '',
    status: '',
    username: '',
});

const approveddate = ref<string | null>(null);
const fromdate = ref<string | null>(null);

watch(approveddate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.approveddate = newDate.toISOString().split('T')[0];
    } else {
        form.approveddate = '';
    }
});

watch(fromdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.fromdate = newDate.toISOString().split('T')[0];
    }
});

watch([() => form.fromdate, () => form.approveddate], ([start, end]) => {
    if (start && end) {
        const s = new Date(start);
        const e = new Date(end);

        const diffTime = e.getTime() - s.getTime();
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;

        form.approveddays = diffDays > 0 ? diffDays : 0;
    } else {
        form.approveddays = 0;
    }
});

const showDialog = ref(false);

const onview = async (id: number) => {
    try {
        const res = await fetch(`/leave/show/${id}`);

        if (!res.ok) {
            toast.error('Server error while fetching attendance deduct details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.leave_id = data.id;
        form.leaveplan_id = data.leave_plan.leavename;
        form.empname = data.employee.empname;
        form.fromdate = data.fromdate;
        form.todate = data.todate;
        form.requestdays = data.requestdays;
        form.substitute = data.substitute;
        form.substitute = data.substitute_employee.empname;
        form.contact_address = data.contact_address;
        form.reason = data.reason;
        form.username = data.user.name;

        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
        toast.error('Network error occurred. Please try again.');
    }
};

const submit = (id:number) => {
    form.leave_id = id;
    form.post(route('approval.leaveApproved', { leave: id }), {
        onSuccess: () => {
            const flash = usePage().props.flash;
            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            setTimeout(() => {
                form.reset();
                router.visit(route('dashboard.LeaveRequest'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
        },
        onError: () => {
            const flash = usePage().props.flash;
            toast('error', {
                description: flash.error,
            });
        },
    });
};


const onDelete = (id: number) => {
    form.leave_id = id;
    form.status = 1;
    form.post(route('approval.leaveCancel', { leave: id }), {
        onSuccess: () => {
            const flash = usePage().props.flash;
            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            setTimeout(() => {
                form.reset();
                router.visit(route('dashboard.LeaveRequest'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
        },
        onError: () => {
            const flash = usePage().props.flash;
            toast('error', {
                description: flash.error,
            });
        },
    });
};
</script>

<template>
    <Head title="Leave Request" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>SL</TableHead>
                            <TableHead>Leave Name</TableHead>
                            <TableHead>Employee Name</TableHead>
                            <TableHead>From Date</TableHead>
                            <TableHead>To Date</TableHead>
                            <TableHead>Request Days</TableHead>
                            <TableHead>Substitute</TableHead>
                            <TableHead>Reason</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(leave, index) in data.data ?? []" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ leave.leave_plan.leavename }}</TableCell>
                            <TableCell>{{ leave.employee.empname }}</TableCell>
                            <TableCell>{{ leave.fromdate }}</TableCell>
                            <TableCell>{{ leave.todate }}</TableCell>
                            <TableCell>{{ leave.requestdays }}</TableCell>
                            <TableCell>{{ leave.substitute_employee.empname }}</TableCell>
                            <TableCell>{{ leave.reason }}</TableCell>
                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onview(leave.id)">
                                        <Eye class="text-green-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        View
                                    </span>
                                </div>
                                <div class="group relative inline-block">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onDelete(leave.id)"
                                        ><X class="text-red-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Cancel
                                    </span>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</div>
                <div class="space-x-2">
                    <Button
                        v-for="(link, index) in data.links"
                        :key="index"
                        :disabled="!link.url"
                        variant="outline"
                        size="sm"
                        :class="[link.active ? 'hover:outline' : '', !link.url ? 'cursor-not-allowed opacity-50' : '']"
                        @click="goToPage(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-3xl rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                <DialogHeader class="space-y-1.5">
                    <DialogTitle class="text-2xl font-semibold text-gray-900 dark:text-white"> Employee Leave Details </DialogTitle>
                    <DialogDescription class="text-sm text-gray-600 dark:text-gray-400">
                        Review employee leave information and approve if everything looks correct.
                    </DialogDescription>
                </DialogHeader>

                <!-- CONTENT -->
                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- LEFT SECTION -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Leave Name</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.leaveplan_id }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Employee Name</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.empname }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">From Date</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.fromdate }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">To Date</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.todate }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Days</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.requestdays }}</p>
                        </div>
                    </div>

                    <!-- RIGHT SECTION -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Substitute</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.substitute }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Address</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.contact_address }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Reason</p>
                            <p class="font-medium text-gray-900 dark:text-gray-200">{{ form.reason }}</p>
                        </div>

                        <div class="grid gap-2">
                            <Label class="text-gray-700 dark:text-gray-300">Approve Date</Label>
                            <VueDatePicker
                                v-model="approveddate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="Select approve date"
                                auto-apply
                            />
                        </div>

                        <div>
                            <Label class="text-gray-700 dark:text-gray-300">Approve Days</Label>
                            <Input type="text" v-model="form.approveddays" class="mt-1 w-full" readonly="readonly" />
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="mt-6 flex items-center justify-between">
                    <DialogClose as-child>
                        <Button variant="secondary" class="px-6" @click="showDialog = false"> Close </Button>
                    </DialogClose>

                    <Button class="px-6" :disabled="form.processing" @click="submit(form.leave_id)">
                        <template v-if="form.processing">Processing...</template>
                        <template v-else>Submit</template>
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
