<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { FileText, LucideEdit, LucideSave, LucideTrash2, Plus, ShieldCheck, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; fname: string; lname: string; status: string };
    quoatation: {
        id: number;
        quotation_no: string;
        adddate: string;
        notes: string;
        sumamount: number;
        user: { id: number; name: string };
    }[];
    invoice: { id: number; insdate: string; insnumber: string; totalamt: string; status: string; netamount: string }[];
    application:{id:number;}
}>();

const form = useForm({
    quoat_id: '',
    studentId: '',
    student_fname: '',
    student_lname: '',
    gender: '',
    phone: '',
    email: '',
    descountry_id: '',
    quotation_no: '',
    q_adddate: '',
    q_user: '',
    services: [] as {
        partner: string;
        branch: string;
        product: string;
        fees: { fee_name: string; amount: number }[];
    }[],
});

const feesForm = useForm({
    selectedFees: [],
});
const showDialogAdd = ref(false);

const fetchData = async () => {
    try {
        const url = route('studentAccounts.create', {
            student: props.student.id,
            accounts: form.quoat_id,
        });
        const res = await fetch(url);
        const data = await res.json();
        const student = data.student ?? {};
        const quotation = data.quotation ?? {};

        form.studentId = student.id ?? '';
        form.student_fname = student.fname ?? '';
        form.student_lname = student.lname ?? '';
        form.gender = student.gender ?? '';
        form.phone = student.phone ?? '';
        form.email = student.email ?? '';
        form.descountry_id = student.descountry_id ?? '';

        form.quotation_no = quotation.quotation_no ?? '';
        form.q_adddate = quotation.adddate ?? '';
        form.q_user = quotation.user ?? '';

        form.services =
            data.services?.map((s: any) => ({
                partner: s.partner,
                branch: s.branch,
                product: s.product,
                product_id: s.product_id,
                fees:
                    s.fees?.map((f: any) => ({
                        fee_id: f.fee_id,
                        fee_name: f.fee_name,
                        amount: Number(f.amount ?? 0),
                    })) ?? [],
            })) ?? [];
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const showDailog = async (id: number) => {
    if(props.application==null){
        toast('error', {
            description: 'Application not entry at first entry Application Process',
        });
        showDialogAdd.value = false;
    }else{
        form.quoat_id = id;
        const test = await fetchData();
        console.log(test)
        showDialogAdd.value = true;
    }
    
};

// Editable fee state
const selectedFees = ref<
    Array<{
        product_id: number;
        product_name: string;
        fee_id: number;
        fee_name: string;
        amount: number;
    }>
>([]);

const totalAfterDiscount = computed(() => {
    const total = selectedFees.value.reduce((sum, f) => sum + Number(f.amount || 0), 0);
    return total;
});

function editFee(fee: any, service: any) {
    if (!fee.amount || Number(fee.amount) <= 0) {
        toast('error', {
            description: 'Invalid fee amount! Amount must be greater than 0.',
        });
        return;
    }
    const exists = selectedFees.value.find((f) => f.fee_name === fee.fee_name && f.product_id === service.product_id);
    if (!exists) {
        selectedFees.value.push({
            product_id: service.product_id,
            product_name: service.product,
            fee_id: fee.fee_id,
            fee_name: fee.fee_name,
            amount: fee.amount,
        });
    }
}
function validateAmount(fee: any) {
    if (fee.amount <= 0 || isNaN(fee.amount)) {
        fee.amount = 1;
        toast('error', {
            description: 'Amount cannot be 0 or negative. Automatically set to 1.',
        });
    }
}

function deleteFeeRow(index: number) {
    selectedFees.value.splice(index, 1);
}

const submitInvoice = () => {
    
    const action = route('studentAccounts.store', {
        student: props.student.id,
        accounts: form.quoat_id,
    });
    feesForm.selectedFees = selectedFees.value;
    feesForm.post(action, {
        onSuccess: () => {
            toast('Success', {
                description: 'Student Invoice created successfully',
            });
            setTimeout(() => {
                showDialogAdd.value = false;
                form.reset();
                router.visit(route('studentAccounts.index', props.student.id), {
                    only: ['student_invoices'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            if (Object.keys(errors).length) {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', { description: firstError });
            }
        },
        onFinish: () => {
            if (form.hasErrors && form.errors.message) {
                toast('Error', { description: form.errors.message });
            }
        },
    });
};

const deleteForm = useForm({});

const onDelete = async (invId: number) => {
    if (!confirm('Are you sure you want to delete this invoice?')) return;

    if (deleteForm.processing) return;

    const newStatus = !Boolean(invId);

    router.post(
        route('studentAccounts.onDelete', {
            student: props.student.id,
            confirm: invId,
        }),
        { id: invId },
        {
            preserveState: true,
            onSuccess: () => {
                invId = newStatus ? 1 : 0;
                toast.success('Invoice delete successfully');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onConfirm = async (invId: number) => {
    if (!confirm('Are you sure you want to confirm this invoice?')) return;

    if (deleteForm.processing) return;

    const newStatus = !Boolean(invId);

    router.post(
        route('studentAccounts.onConfirm', {
            student: props.student.id,
            confirm: invId,
        }),
        { id: invId },
        {
            preserveState: true,
            onSuccess: () => {
                invId = newStatus ? 1 : 0;
                toast.success('Invoice delete successfully');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};


const onReport = async (invId: number) => {
    const url = route('studentAccounts.onReport', {
        student: props.student.id,
        confirm: invId,
    });

    window.open(url, '_blank');
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <div class="flex flex-col space-y-2 py-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="text-sm font-semibold text-gray-800">Student Accounts</div>
            </div>

            <!-- Responsive Grid -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Quotations -->
                <Card class="h-auto w-full border-green-300">
                    <div class="overflow-x-auto text-sm">
                        <h3 class="mb-2 ml-2 font-semibold">Quotations</h3>
                        <Table class="min-w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>Quoatations No</TableHead>
                                    <TableHead>Receivable Amount</TableHead>
                                    <TableHead>Notes</TableHead>
                                    <TableHead class="text-center">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(quoat, index) in props.quoatation" :key="quoat.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell>{{ quoat.quotation_no }}</TableCell>
                                    <TableCell>{{ quoat.sumamount }}</TableCell>
                                    <TableCell>{{ quoat.notes }}</TableCell>
                                    <TableCell class="text-center">
                                        <Button class="m-[2px]" variant="outline" size="sm" @click="showDailog(quoat.id)"> <Plus /> Invoice </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </Card>

                <!-- Invoices -->
                <Card class="w-full border-green-300">
                    <div class="overflow-x-auto text-sm">
                        <h3 class="mb-2 ml-2 font-semibold">Invoices</h3>
                        <Table class="min-w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>Invoices No</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Net Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-center">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(inv, index) in props.invoice" :key="inv.id">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell>{{ inv.insnumber }}</TableCell>
                                    <TableCell>{{ inv.insdate }}</TableCell>
                                    <TableCell>{{ inv.netamount }}</TableCell>
                                    <TableCell>{{ inv.status }}</TableCell>
                                    <TableCell class="text-center">
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status == 'pending'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onDelete(inv.id)"
                                                :disabled="form.processing"
                                                ><X></X
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Cancel
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status == 'pending'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onConfirm(inv.id)"
                                                ><ShieldCheck></ShieldCheck
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Approved
                                            </span>
                                        </div>
                                        
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status === 'Confirmed' || inv.status === 'Delivered'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onReport(inv.id)"
                                                ><FileText class="text-red-500"></FileText
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Report
                                            </span>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </Card>
            </div>

            <!-- Dialog Quoatations-->
            <Dialog v-model:open="showDialogAdd">
                <DialogContent
                    class="flex max-h-[90vh] w-[95vw] max-w-full flex-col rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
                >
                    <!-- Header -->
                    <DialogHeader class="flex-shrink-0 border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100"> Create Student Invoice </DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Fill in the details below to create a new Invoice.
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Scrollable Content -->
                    <div class="flex-1 space-y-4 overflow-y-auto px-6 py-4">
                        <!-- Student & Quotation Info -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1">
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Student ID:</span> {{ form.studentId }}</p>
                                <p>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">Student Name:</span> {{ form.student_fname }}
                                    {{ form.student_lname }}
                                </p>
                                <p>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">Gender:</span>
                                    {{ form.gender == 1 ? 'Man' : form.gender == 2 ? 'Woman' : form.gender == 3 ? "Other's" : 'Unknown' }}
                                </p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Phone:</span> {{ form.phone }}</p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Email:</span> {{ form.email }}</p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Destination Country:</span> {{ form.descountry_id }}</p>
                            </div>
                            <div class="space-y-1">
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Quotation No:</span> {{ form.quotation_no }}</p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">Quotation Date:</span> {{ form.q_adddate }}</p>
                                <p><span class="font-medium text-gray-700 dark:text-gray-200">By:</span> {{ form.q_user }}</p>
                            </div>
                        </div>

                        <!-- Services List -->
                        <div class="space-y-4">
                            <div
                                v-for="(service, index) in form.services"
                                :key="index"
                                class="rounded-lg border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                            >
                                <!-- Service Info -->
                                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">Partner:</span>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ service.partner }}<br />
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ service.branch }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">Product:</span>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ service.product }}</p>
                                    </div>
                                </div>

                                <!-- Fees Table -->
                                <div class="overflow-x-auto">
                                    <table
                                        class="w-full min-w-[500px] table-auto border-collapse border border-gray-200 text-sm dark:border-gray-700"
                                    >
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Fee Name</th>
                                                <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(fee, fIndex) in service.fees"
                                                :key="fIndex"
                                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                                @click="editFee(fee, service)"
                                            >
                                                <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.fee_name }}</td>
                                                <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.amount }}</td>
                                            </tr>
                                            <tr class="bg-gray-200 font-medium dark:bg-gray-700">
                                                <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">Grand Total</td>
                                                <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                                                    {{ service.fees.reduce((total, f) => total + Number(f.amount), 0) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Editable Fee Box -->
                        <div v-if="selectedFees.length" class="mt-4 rounded-lg border border-blue-300 bg-blue-50 p-4 shadow-md">
                            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-blue-700">
                                <span>Student Invoice Fee</span>
                                <LucideEdit class="h-5 w-5 text-blue-700" />
                            </h3>

                            <div
                                v-for="(fee, index) in selectedFees"
                                :key="fee.fee_id + '-' + fee.product_id"
                                class="mb-3 flex flex-col gap-4 rounded border border-gray-200 bg-white p-3 shadow-sm md:flex-row md:items-center"
                            >
                                <!-- Fee Details -->
                                <div class="flex-1">
                                    <p class="font-medium text-gray-700">
                                        Product: <span class="font-normal">{{ fee.product_name }}</span>
                                    </p>
                                    <p class="font-medium text-gray-700">
                                        Fee Name: <span class="font-normal">{{ fee.fee_name }}</span>
                                    </p>
                                    <input
                                        type="number"
                                        v-model.number="fee.amount"
                                        min="1"
                                        step="any"
                                        @input="validateAmount(fee)"
                                        class="mt-1 w-full rounded border border-gray-300 px-2 py-1 shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none md:w-32"
                                    />
                                </div>

                                <!-- Delete Button -->
                                <div class="flex justify-end">
                                    <Button variant="destructive" @click="deleteFeeRow(index)" class="flex items-center gap-1">
                                        <LucideTrash2 class="h-4 w-4" /> Delete
                                    </Button>
                                </div>
                            </div>

                            <!-- Grand Total -->
                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <!-- Grand Total -->
                                <div class="mt-6 flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3 shadow-sm">
                                    <p class="text-base font-semibold text-gray-700">Grand Total</p>
                                    <p class="text-xl font-bold text-blue-600">{{ totalAfterDiscount.toFixed(2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter
                        class="flex flex-shrink-0 flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 sm:flex-row sm:justify-end dark:border-gray-700"
                    >
                        <DialogClose as-child>
                            <Button type="button" variant="secondary" class="w-full px-4 py-2 sm:w-auto">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="feesForm.processing" @click="submitInvoice" class="w-full px-5 py-2 sm:w-auto">
                            <template v-if="feesForm.processing">Creating...</template>
                            <template v-else><LucideSave class="h-4 w-4" />Create</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </StudentLayout>
</template>
