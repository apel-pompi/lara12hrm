<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { CornerDownLeft, Eye, FileText, LucideEdit, LucideSave, LucideTrash2, Plus, ShieldCheck, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Money Receive',
        href: '/invoicelist/DueInvoiceList',
    },
];

const props = defineProps<{
    invoicemr: {
        id: number;
        insnumber: string;
        insdate: string;
        disc_amt: number;
        totalamt: number;
        netamount: number;
    };
    invoicemrSum:number;
    invoice: {
        id: number;
        insnumber: string;
        insdate: string;
        netamount: number;
        details: {
            id: number;
            amount: string;
            product_id: number;
            invoice_hd_id: number;
            fee: { id: number; name: string };
        };
    };
    student: { id: number; fname: string; lname: string; student_id: number };
}>();


const showDialogCreate = ref(false);

const showDailog = () => {
    showDialogCreate.value = true;
};

// Selected fees array
const selectedFees = ref<
    Array<{
        fee_id: number;
        product_id: number;
        invoice_hd_id: number;
        fee_name: string;
        amount: number;
        original_amount: number;
    }>
    
>([]);

// Form for payment type and discount
const feesForm = useForm({
    fees: [],
    paytype: '',
    bankname: '',
    bankbranch: '',
    chequeno: '',
    transactionNo: '',
    discount: 0,
    netamount: 0,
    shortnote:'',
});

// Add fee row to editable box
function addFeeRow(dt: any) {
    if (!dt.fee) return;

    // Prevent duplicate
    if (!selectedFees.value.some((f) => f.fee_id === dt.fee.id)) {
        selectedFees.value.push({
            fee_id: dt.fee.id,
            product_id: dt.product_id,
            invoice_hd_id: dt.invoice_hd_id,
            fee_name: dt.fee.name,
            amount: Number(dt.amount) || 0,
            original_amount: Number(dt.amount) || 0,
        });
    }
}
// Delete fee row
function deleteFeeRow(index: number) {
    selectedFees.value.splice(index, 1);
}

// Validate amount input
function validateAmount(fee: any) {
    if (fee.amount <= 0 || isNaN(fee.amount)) {
        fee.amount = 1;
        toast('error', {
            description: 'Amount cannot be 0 or negative. Automatically set to 1.',
        });
    }

    if (fee.amount > fee.original_amount) {
        fee.amount = fee.original_amount;
        toast('warning', {
            description: `Cannot exceed original amount (${fee.original_amount}).`,
        });
    }
}

// Total receive amount for editable box (selectedFees + discount)
const totalReceiveAmount = computed(() => {
    const totalFees = selectedFees.value.reduce((sum, fee) => {
        return sum + (Number(fee.amount) || 0);
    }, 0);

    const discount = Number(feesForm.discount) || 0;

    return totalFees - discount;
});



const submitMR = () => {
    if (!feesForm.paytype) {
        toast('error', {
            description: 'Please select a Payment Type before submitting.',
        });
        return;
    }

    if (feesForm.paytype === 'Bank') {
        if (!feesForm.bankname || feesForm.bankname.trim() === '') {
            toast('error', {
                description: 'Please enter the Bank Name.',
            });
            return;
        }
        if (!feesForm.bankbranch || feesForm.bankbranch.trim() === '') {
            toast('error', {
                description: 'Please enter the Branch Name.',
            });
            return;
        }
    }

    if (feesForm.paytype === 'Cheque') {
        if (!feesForm.bankname || feesForm.bankname.trim() === '') {
            toast('error', {
                description: 'Please enter the Bank Name.',
            });
            return;
        }

        if (!feesForm.chequeno || feesForm.chequeno.trim() === '') {
            toast('error', {
                description: 'Please enter the Check Number.',
            });
            return;
        }
    }

    if (feesForm.paytype === 'Card') {
        if (!feesForm.transactionNo || feesForm.transactionNo.trim() === '') {
            toast('error', {
                description: 'Please enter the Bank Name.',
            });
            return;
        }
    }

    const discount = Number(feesForm.discount) || 0;
    const totalAmount = selectedFees.value.reduce((sum, item) => sum + item.amount, 0);
    if (discount > totalAmount) {
        toast('error', {
            description: 'Discount amount cannot be greater than total amount.',
        });
        return;
    }

    feesForm.fees = selectedFees.value
    feesForm.paytype = feesForm.paytype
    feesForm.bankname = feesForm.bankname
    feesForm.bankbranch = feesForm.bankbranch
    feesForm.chequeno = feesForm.chequeno
    feesForm.transactionNo = feesForm.transactionNo
    feesForm.discount = Number(feesForm.discount) || 0
    feesForm.netamount = totalAmount
    
    feesForm.post(
        route('invoicelist.storeMR', {
            insnumber: props.invoice.insnumber,
            student: props.student.student_id,
        }),
        {
            preserveState: true,
            onSuccess: () => {
                
                feesForm.reset();
                showDialogCreate.value = false;
            },
            onError: (errors) => {
                console.error(errors);
                toast('error', { description: 'Something went wrong during submission.' });
            },
            
        }
    );
};

const ViewDailog = ref(false);
const viewForm = useForm({
    student_id: '',
    stundent_fname: '',
    stundent_lname: '',
    student_gender: '',
    student_phone: '',
    student_email: '',
    student_country: '',
    student_invno: '',
    student_invdate: '',
    student_invby: '',
    disc_amt:'',
    netamount:'',
    viewfees: [] as {
        feename: string;
        amount: number;
    }[],
});

const onView = async (invId: number) => {
    try {
        const url = route('invoicelist.onView', {
            confirm: invId,
        });
        const res = await fetch(url);
        if (!res.ok) {
            toast.error('Server error while fetching money receive details.');
            return;
        }
        const data = await res.json();

        viewForm.student_id = data.data.student.student_id;
        viewForm.stundent_fname = data.data.student.fname;
        viewForm.stundent_lname = data.data.student.lname;
        viewForm.student_gender = data.data.student.gender;
        viewForm.student_phone = data.data.student.phone;
        viewForm.student_email = data.data.student.email;
        viewForm.student_country = data.data.student.country.name;
        viewForm.student_invno = data.invoice.insnumber;
        viewForm.student_invdate = data.invoice.insdate;
        viewForm.student_invby = data.invoice.user.name;
        viewForm.disc_amt = data.data.disc_amt;
        viewForm.netamount = data.data.netamount;
        viewForm.viewfees =
            data.data.mrdetails?.map((s: any) => ({
                feename: s.fees.name,
                amount: s.amount,
            })) ?? [];
        ViewDailog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const deleteForm = useForm({});

const onCancel = async (invId: number) => {
    if (!confirm('Are you sure you want to cancel this money receive?')) return;

    if (deleteForm.processing) return;

    const newStatus = !Boolean(invId);

    router.post(
        route('invoicelist.onCancel', {
            confirm: invId,
        }),
        { id: invId },
        {
            preserveState: true,
            onSuccess: () => {
                invId = newStatus ? 1 : 0;
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onConfirm = async (invId: number) => {
    if (!confirm('Are you sure you want to confirm this money receive?')) return;

    if (deleteForm.processing) return;

    const newStatus = !Boolean(invId);

    router.post(
        route('invoicelist.onConfirm', {
            confirm: invId,
        }),
        { id: invId },
        {
            preserveState: true,
            onSuccess: () => {
                feesForm.reset();
                invId = newStatus ? 1 : 0;
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onReport = async (invId: number) => {
    const url = route('invoicelist.onReport', {
        onReport: invId,
    });

    window.open(url, '_blank');
};

const goToAccounts = () => {
    router.visit('/invoicelist/DueInvoiceList');
};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="goToAccounts"><CornerDownLeft></CornerDownLeft>Back To Accounts </Button>
                </div>
                <div class="space-x-2"></div>
            </div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Invoices -->
                <Card class="w-full border-green-300">
                    <div class="overflow-x-auto text-sm">
                        <!-- Header section -->
                        <div class="mb-2 flex items-center justify-between px-2">
                            <h3 class="font-semibold">Money Receive</h3>
                            <span v-if="props.invoice.netamount==props.invoicemrSum">
                                Amount receive not pending
                            </span>
                            <span v-else>
                                <Button variant="outline" size="sm" @click="showDailog()"> <Plus class="mr-1" /> Create </Button>
                            </span>
                        </div>

                        <!-- Table -->
                        <Table class="min-w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>MR No</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Receive Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-center">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(inv, index) in props.invoicemr" :key="index">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell>{{ inv.insnumber }}</TableCell>
                                    <TableCell>{{ inv.insdate }}</TableCell>
                                    <TableCell>{{ inv.netamount }}</TableCell>
                                    <TableCell>{{ inv.status }}</TableCell>
                                    <TableCell>
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status == 'Open'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onView(inv.id)"
                                                ><Eye class="text-green-500"></Eye
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                View
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status == 'Open'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onCancel(inv.id)"
                                                ><X class="text-red-500"></X
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Cancel
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status == 'Open'"
                                                class="m-[2px] cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="onConfirm(inv.id)"
                                                ><ShieldCheck class="text-black"></ShieldCheck
                                            ></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Approved
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button
                                                v-if="inv.status === 'Confirmed'"
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
                <!-- Quotations -->
                <Card class="w-full border-green-300 shadow-sm">
                    <div class="space-y-6 p-4 text-sm">
                        <!-- Student Details -->
                        <div>
                            <h3 class="mb-3 border-b border-green-200 pb-1 text-base font-semibold text-green-700">Student Details</h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                                <p>
                                    <span class="font-medium text-gray-600">Name:</span> {{ props.student.student.fname }}
                                    {{ props.student.student.lname }}
                                </p>
                                <p><span class="font-medium text-gray-600">Student ID:</span> {{ props.student.student.student_id }}</p>
                                <p><span class="font-medium text-gray-600">Phone:</span> {{ props.student.student.phone }}</p>
                                <p><span class="font-medium text-gray-600">Email:</span> {{ props.student.student.email }}</p>
                            </div>
                        </div>

                        <!-- Workflow Details -->
                        <div>
                            <h3 class="mb-3 border-b border-green-200 pb-1 text-base font-semibold text-green-700">Workflow Details</h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                                <p><span class="font-medium text-gray-600">Partner Name:</span> {{ props.student.partner_branch.partner.name }}</p>
                                <p><span class="font-medium text-gray-600">Branch:</span> {{ props.student.partner_branch.branch_name }}</p>
                                <p><span class="font-medium text-gray-600">Product Name:</span> {{ props.student.product.name }}</p>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
        <Dialog v-model:open="showDialogCreate">
            <DialogContent
                class="w-[95vw] max-w-full overflow-hidden rounded-2xl bg-white shadow-2xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
            >
                <!-- Header -->
                <DialogHeader class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100"> 🧾 Create Money Receipt </DialogTitle>
                    <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Fill in the details below to create a new money receipt.
                    </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="max-h-[70vh] overflow-y-auto px-5 py-6">
                    <!-- Product Fees Table -->
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <table class="w-full border-collapse text-sm text-gray-700 dark:text-gray-300">
                            <thead class="bg-gray-100 dark:bg-gray-700/80">
                                <tr>
                                    <th class="px-3 py-2 text-left">#</th>
                                    <th class="px-3 py-2 text-left">Fee Name</th>
                                    <th class="px-3 py-2 text-left">Amount</th>
                                </tr>
                            </thead>

                            <tbody class="border-t">
                                <template v-for="(dt, j) in props.invoice.details" :key="j">
                                    <tr class="cursor-pointer transition hover:bg-blue-50 dark:hover:bg-gray-700/50" @click="addFeeRow(dt)">
                                        <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ j + 1 }}</td>
                                        <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ dt.fee?.name }}</td>
                                        <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ dt.amount }}</td>
                                    </tr>
                                </template>

                                <!-- Grand Total -->
                                <tr class="bg-gray-50 font-semibold dark:bg-gray-800/80">
                                    <td colspan="2" class="px-3 py-2 text-right">Grand Total:</td>
                                    <td class="px-3 py-2 text-right text-blue-600">
                                        {{ props.invoice.netamount }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Editable Fee Box -->
                    <div
                        v-if="selectedFees.length"
                        class="mt-6 rounded-xl border border-blue-300 bg-gradient-to-br from-blue-50 to-indigo-50 p-5 shadow-lg dark:from-gray-800 dark:to-gray-900"
                    >
                        <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-blue-700 dark:text-blue-400">
                            <LucideEdit class="h-5 w-5" /> Edit Selected Fees
                        </h3>

                        <div
                            v-for="(fee, index) in selectedFees"
                            :key="fee.fee_id"
                            class="mb-3 flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-2 shadow-sm transition hover:shadow-md md:flex-row md:items-center dark:border-gray-700 dark:bg-gray-800"
                        >
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Fee Name:
                                    <span class="font-normal text-gray-600 dark:text-gray-400">{{ fee.fee_name }}</span>
                                </p>
                                <input
                                    type="number"
                                    v-model.number="fee.amount"
                                    min="1"
                                    step="any"
                                    @input="validateAmount(fee)"
                                    class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>

                            <!-- Delete -->
                            <div class="flex justify-end pt-6 md:justify-center">
                                <Button variant="destructive" size="sm" class="flex items-center gap-1" @click="deleteFeeRow(index)">
                                    <LucideTrash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>

                        <!-- Payment + Discount Section -->
                        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                                <Select v-model="feesForm.paytype">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="Bkash">Bkash</SelectItem>
                                            <SelectItem value="Nagad">Nagad</SelectItem>
                                            <SelectItem value="Cash">Cash</SelectItem>
                                            <SelectItem value="Bank">Bank</SelectItem>
                                            <SelectItem value="Cheque">Cheque</SelectItem>
                                            <SelectItem value="Card">Card</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div v-if="feesForm.paytype === 'Bank' || feesForm.paytype === 'Cheque'">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Name</label>
                                <input
                                    v-model="feesForm.bankname"
                                    type="text"
                                    placeholder="Enter Bank Name"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>

                            <div v-if="feesForm.paytype === 'Bank'">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Branch Name</label>
                                <input
                                    v-model="feesForm.bankbranch"
                                    type="text"
                                    placeholder="Enter Branch Name"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>

                            <div v-if="feesForm.paytype === 'Cheque'">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cheque No</label>
                                <input
                                    v-model="feesForm.chequeno"
                                    type="text"
                                    placeholder="Enter Cheque Number"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>

                            <div v-if="feesForm.paytype === 'Card' || feesForm.paytype === 'Bkash' || feesForm.paytype === 'Nagad'">
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction No</label>
                                <input
                                    v-model="feesForm.transactionNo"
                                    type="text"
                                    placeholder="Enter Transaction Number"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>

                            <!-- Discount -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Any Discount?</label>
                                <input
                                    v-model.number="feesForm.discount"
                                    type="number"
                                    min="0"
                                    placeholder="Enter Discount"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                                />
                            </div>
                        </div>
                        <!-- Note -->
                            <div class="mt-4 grid gap-2">
                                <Label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Note</Label>
                                <Textarea
                                    v-model="feesForm.shortnote"
                                    placeholder="Write a short note about this money receive..."
                                    class="focus:ring-opacity-50 rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                />
                            </div>
                        <!-- Grand Total -->
                        <div class="mt-6 flex items-center justify-between rounded-lg bg-blue-50 px-5 py-3 shadow-sm dark:bg-gray-700/40">
                            <p class="text-base font-semibold text-gray-700 dark:text-gray-200">Grand Total (After Discount)</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ totalReceiveAmount.toFixed(2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter
                    class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 px-4 py-4 sm:flex-row sm:px-6 dark:border-gray-700"
                >
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="w-full px-4 py-2 sm:w-auto">Cancel</Button>
                    </DialogClose>
                    <Button :disabled="feesForm.processing" @click="submitMR" class="w-full px-5 py-2 sm:w-auto">
                        <template v-if="feesForm.processing">Creating...</template>
                        <template v-else><LucideSave class="h-4 w-4" />Create</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="ViewDailog">
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
                    <!-- Student & Invoice Info -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">Student ID:</span> {{ viewForm.student_id }}</p>
                            <p>
                                <span class="font-medium text-gray-700 dark:text-gray-200">Student Name:</span> {{ viewForm.stundent_fname }}
                                {{ viewForm.stundent_lname }}
                            </p>
                            <p>
                                <span class="font-medium text-gray-700 dark:text-gray-200">Gender:</span>
                                {{
                                    viewForm.student_gender == 1
                                        ? 'Man'
                                        : viewForm.student_gender == 2
                                          ? 'Woman'
                                          : viewForm.student_gender == 3
                                            ? "Other's"
                                            : 'Unknown'
                                }}
                            </p>
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">Phone:</span> {{ viewForm.student_phone }}</p>
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">Email:</span> {{ viewForm.student_email }}</p>
                            <p>
                                <span class="font-medium text-gray-700 dark:text-gray-200">Destination Country:</span>
                                {{ viewForm.student_country }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">Invoice No:</span> {{ viewForm.student_invno }}</p>
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">Invoice Date:</span> {{ viewForm.student_invdate }}</p>
                            <p><span class="font-medium text-gray-700 dark:text-gray-200">By:</span> {{ viewForm.student_invby }}</p>
                        </div>
                    </div>

                    <!-- fees List -->
                    <div class="space-y-4">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <!-- Fees Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[500px] table-auto border-collapse border border-gray-200 text-sm dark:border-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Fee Name</th>
                                            <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(fee, fIndex) in viewForm.viewfees"
                                            :key="fIndex"
                                            class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.feename }}</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.amount }}</td>
                                        </tr>
                                        <tr class="bg-gray-200 font-medium dark:bg-gray-700">
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">Discount</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                                                {{ viewForm.disc_amt }}
                                            </td>
                                        </tr>
                                        <tr class="font-medium dark:bg-gray-700">
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">Grand Total</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                                                {{ viewForm.netamount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
