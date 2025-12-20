<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import StudentAccountsLayout from '@/pages/allpages/Agency/Student/studentaccountsLayout.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Eye, FileText, LucideEdit, LucideSave, LucideTrash2, ShieldCheck, Undo2, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; fname: string; lname: string; status: string };
    mr: { insnumber: string; insdate: string; payterms: string; totalamt: string; netamount: string; status: string };
    mr_amount: { netamount: string };
    srinvoice: { insnumber: string; insdate: string; payterms: string; totalamt: string; netamount: string; status: string };
    sr_amount: { netamount: string };
}>();

const form = useForm({
    insnumber: '',
    insdate: '',
    netamount: '',
    payterms: '',
    refe_code: '',
    transno: '',
    mrdetails: [] as {
        amount: number;
        fees: string;
    }[],
    service: [] as {
        workflowname: string;
        partnername: string;
        branchname: string;
        productname: string;
        quotation_no: string;
        totalamount: string;
    }[],
    fees: [] as {
        fee_id: number;
        feename: string;
        amount: number;
    }[],
});

const showDialogReturn = ref(false);

const showDailog = async (id: number) => {
    if (props.mr == null) {
        toast('error', {
            description: 'Money receipt is not avaliable',
        });
        showDialogReturn.value = false;
    } else {
        form.mr_id = id;
        await fetchData(id);
        showDialogReturn.value = true;
    }
};

const fetchData = async (id: number) => {
    try {
        const url = route('studentAccounts.fetchMR', {
            student: props.student.id,
            mrid: id,
        });
        const res = await fetch(url);
        const data = await res.json();
        console.log(data)
        form.service =
            data.service?.map((s: any) => ({
                workflowname: s.workflowname,
                partnername: s.partnername,
                branchname: s.branchname,
                productname: s.productname,
                quotation_no: s.quotation_no,
                totalamount: s.totalamount,
            })) ?? [];

        form.insnumber = data.mrhd?.insnumber ?? '';
        form.insdate = data.mrhd?.insdate ?? '';
        form.netamount = data.mrhd?.netamount ?? '';
        form.payterms = data.mrhd?.payterms ?? '';
        form.refe_code = data.mrhd?.refe_code ?? '';
        form.transno = data.mrhd?.transno ?? '';

        form.fees =
            data.mrhd.mrdetails?.map((m: any) => ({
                fee_id: m.fees.id,
                feename: m.fees.name,
                amount: Number(m.amount),
            })) ?? [];
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const showDialogSR = ref(false);
const showSRDailog = async (id: number) => {
    if (props.mr == null) {
        toast('error', {
            description: 'Invocice Return is not avaliable',
        });
        showDialogSR.value = false;
    } else {
        form.mr_id = id;
        await fetchSRData(id);
        showDialogSR.value = true;
    }
};

const srform = useForm({
    service: [] as {
        fees: {
            name: string;
        };
        amount: number;
    }[],
    return: [] as {
        fee: {
            name: string;
        };
        amount: number;
    }[],
});

const fetchSRData = async (id: number) => {
    try {
        const url = route('studentAccounts.fetchSR', {
            student: props.student.id,
            srid: id,
        });
        const res = await fetch(url);
        const data = await res.json();
        srform.service =
            data.service?.map((s: any) => ({
                name: s.fees.name,
                amount: s.amount,
            })) ?? [];

        form.insnumber = data.mrhd?.insnumber ?? '';
        form.insdate = data.mrhd?.insdate ?? '';
        form.netamount = data.mrhd?.netamount ?? '';
        form.refe_code = data.mrhd?.refe_code ?? '';

        srform.return =
            data.return?.map((m: any) => ({
                name: m.fee.name,
                amount: m.amount,
            })) ?? [];
        srform.reset();
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const feesForm = useForm({
    selectedFees: [],
    refe_code: '',
    shortnote:''
});
// Editable fee state
const selectedFees = ref<
    Array<{
        fee_id: number;
        feename: string;
        amount: number;
    }>
>([]);

const totalAfterDiscount = computed(() => {
    const total = selectedFees.value.reduce((sum, f) => sum + Number(f.amount || 0), 0);
    return total;
});

function editFee(fee: any) {
    if (!fee.amount || Number(fee.amount) <= 0) {
        toast('error', {
            description: 'Invalid fee amount! Amount must be greater than 0.',
        });
        return;
    }
    const exists = selectedFees.value.find((f) => f.feename === fee.feename);
    if (!exists) {
        selectedFees.value.push({
            fee_id: fee.fee_id,
            feename: fee.feename,
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
    const action = route('studentAccounts.storeReturn', {
        student: props.student.id,
    });
    feesForm.selectedFees = selectedFees.value;
    feesForm.refe_code = form.insnumber;
    feesForm.post(action, {
        onSuccess: () => {
            feesForm.reset();
            selectedFees.value = [];
            feesForm.clearErrors();
            showDialogReturn.value = false;
            router.visit(route('studentAccounts.index'), {
                preserveScroll: true,
                preserveState: false,
            });
        },

        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const flash = usePage().props.flash;
            if (flash?.error) {
                toast('error', {
                    description: flash.error + firstError,
                });
            }
        },
    });
};

const deleteForm = useForm({});

const onDelete = async (invId: number) => {
    if (!confirm('Are you sure you want to cancel this invoice?')) return;

    if (deleteForm.processing) return;

    const newStatus = !Boolean(invId);

    router.post(
        route('studentAccounts.returnCancel', {
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
        route('studentAccounts.returnConfirm', {
            student: props.student.id,
            confirm: invId,
        }),
        { id: invId },
        {
            preserveState: true,
            onSuccess: () => {
                invId = newStatus ? 1 : 0;
                toast.success('Invoice confirm successfully');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onReport = (id: number) => {
    const url = route('studentAccounts.onReport', {
        student: props.student.id,
        confirm: id,
    });

    window.open(url, '_blank');
};

const canReturn = (inv) => {
    // Find SR invoices whose refe_code = MR.insnumber
    const relatedSR = props.srinvoice.filter((sr) => {
        return sr.refe_code === inv.insnumber && sr.status !== 'Cancel';
    });
    // No SR found => can return
    if (relatedSR.length === 0) {
        return false; // show button
    }
    // Calculate total SR amount
    const totalSR = relatedSR.reduce((sum, sr) => sum + Number(sr.netamount), 0);
    // If SR total == MR amount => return completed => hide button
    if (totalSR === Number(inv.netamount)) {
        return true; // hide button
    }
    // Otherwise allow
    return false; // show button
};
</script>

<template>
    <StudentLayout :student="props.student">
        <div class="space-y-4">
            <StudentAccountsLayout :student="props.student">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <Card class="h-auto w-full border-green-300">
                        <div class="overflow-x-auto text-sm">
                            <div class="mb-2 flex items-center justify-between px-2">
                                <h3 class="font-semibold">Money Receive</h3>
                                <h3 class="font-semibold">{{ props.mr_amount }}</h3>
                            </div>
                            <Table class="min-w-full">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Sl</TableHead>
                                        <TableHead>M.R No</TableHead>
                                        <TableHead>Date</TableHead>
                                        <TableHead>Net Amount</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead class="text-center">Action</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="(inv, index) in props.mr" :key="inv.id">
                                        <TableCell>{{ index + 1 }}</TableCell>
                                        <TableCell>{{ inv.insnumber }}</TableCell>
                                        <TableCell>{{ inv.insdate }}</TableCell>
                                        <TableCell>{{ inv.netamount }}</TableCell>
                                        <TableCell>{{ inv.status }}</TableCell>
                                        <TableCell class="text-center">
                                            <Button v-if="!canReturn(inv)" class="m-[2px]" variant="outline" size="sm" @click="showDailog(inv.id)">
                                                <Undo2 /> Return
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </Card>
                    <Card class="w-full border-green-300">
                        <div class="overflow-x-auto text-sm">
                            <div class="mb-2 flex items-center justify-between px-2">
                                <h3 class="font-semibold">Refund Invoices</h3>
                                <h3 class="font-semibold">{{ props.sr_amount }}</h3>
                            </div>
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
                                    <TableRow v-for="(inv, index) in props.srinvoice" :key="inv.id">
                                        <TableCell>{{ index + 1 }}</TableCell>
                                        <TableCell>{{ inv.insnumber }}</TableCell>
                                        <TableCell>{{ inv.insdate }}</TableCell>
                                        <TableCell>{{ inv.netamount }}</TableCell>
                                        <TableCell>{{ inv.status == 'Send' ? 'Approve Pending' : inv.status }}</TableCell>
                                        <TableCell class="text-center">
                                            <div class="group relative inline-block">
                                                <Button
                                                    v-if="inv.status == 'pending'"
                                                    class="m-[2px] cursor-pointer"
                                                    size="sm"
                                                    variant="outline"
                                                    @click="showSRDailog(inv.id)"
                                                    :disabled="form.processing"
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
                                                    v-if="inv.status == 'pending'"
                                                    class="m-[2px] cursor-pointer"
                                                    size="sm"
                                                    variant="outline"
                                                    @click="onDelete(inv.id)"
                                                    :disabled="form.processing"
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
                                                    Send
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
                </div>
            </StudentAccountsLayout>
            <Dialog v-model:open="showDialogReturn">
                <DialogContent
                    class="flex max-h-[90vh] w-[95vw] max-w-full flex-col rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
                >
                    <!-- Header -->
                    <DialogHeader class="flex-shrink-0 border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">Money Receipt Details</DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Fill in the details below to create a new sales return.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="flex-1 space-y-6 overflow-y-auto px-6 py-4">
                        <!-- Service Info -->
                        <div
                            v-for="(ser, index) in form.service"
                            :key="index"
                            class="grid grid-cols-1 gap-4 rounded-lg border p-4 md:grid-cols-3 dark:border-gray-700"
                        >
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Workflow</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ser.workflowname }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Partner</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ ser.partnername }}
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ser.branchname }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Product</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ser.productname }}</p>
                            </div>
                        </div>

                        <!-- MR Summary -->
                        <div class="grid grid-cols-1 gap-4 rounded-lg border p-4 md:grid-cols-3 dark:border-gray-700">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">MR No</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.insnumber }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">MR Date</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.insdate }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Payterms</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.payterms }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ref No</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.refe_code }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Transaction No</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.transno }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Received Amount</p>
                                <p class="font-bold text-green-600 dark:text-green-400">{{ form.netamount }}</p>
                            </div>
                        </div>

                        <!-- Fees Table -->
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="w-full table-auto text-sm">
                                <thead class="bg-gray-100 text-left dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2">Fee Name</th>
                                        <th class="px-3 py-2 text-right">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="(fee, fIndex) in form.fees"
                                        :key="fIndex"
                                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        @click="editFee(fee)"
                                    >
                                        <td class="px-3 py-2">{{ fee.feename }}</td>
                                        <td class="px-3 py-2 text-right">{{ Number(fee.amount).toFixed(2) }}</td>
                                    </tr>

                                    <tr class="bg-gray-200 font-semibold dark:bg-gray-700">
                                        <td class="px-3 py-2">Grand Total</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ form.fees.reduce((t, f) => t + (Number(f.amount) || 0), 0).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Editable Fee Box -->
                        <div v-if="selectedFees.length" class="mt-4 rounded-lg border border-blue-300 bg-blue-50 p-4 shadow-md">
                            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-blue-700">
                                <span>Student Invoice Fee Return</span>
                                <LucideEdit class="h-5 w-5 text-blue-700" />
                            </h3>

                            <div
                                v-for="(fee, index) in selectedFees"
                                :key="index"
                                class="mb-3 flex flex-col gap-4 rounded border border-gray-200 bg-white p-3 shadow-sm md:flex-row md:items-center"
                            >
                                <!-- Fee Details -->
                                <div class="flex-1">
                                    <p class="font-medium text-gray-700">
                                        Fee Name: <span class="font-normal">{{ fee.feename }}</span>
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
                            <!-- Note -->
                            <div class="mt-4 grid gap-2">
                                <Label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Note</Label>
                                <Textarea
                                    v-model="feesForm.shortnote"
                                    placeholder="Write a short note about this refund invoice..."
                                    class="focus:ring-opacity-50 rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                />
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
                            <template v-if="feesForm.processing">Submitting...</template>
                            <template v-else><LucideSave class="h-4 w-4" />Submit</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
            <!-- Show dialog sales Return -->
            <Dialog v-model:open="showDialogSR">
                <DialogContent
                    class="flex max-h-[90vh] w-[95vw] max-w-full flex-col rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
                >
                    <!-- Header -->
                    <DialogHeader class="flex-shrink-0 border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">Invoice Return Details</DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Fill in the details below to create a new sales return.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="flex-1 space-y-6 overflow-y-auto px-6 py-4">
                        <!-- MR Summary -->
                        <div class="grid grid-cols-1 gap-4 rounded-lg border p-4 md:grid-cols-3 dark:border-gray-700">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Invocie Return No</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.insnumber }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Return Date</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.insdate }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ref No</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.refe_code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Return Amount</p>
                                <p class="font-bold text-green-600 dark:text-green-400">{{ form.netamount }}</p>
                            </div>
                        </div>
                        <!-- M.R Fees Table -->
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="w-full table-auto text-sm">
                                <thead class="bg-gray-100 text-left dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-center" colspan="2">Money Receipt Details</th>
                                    </tr>
                                    <tr>
                                        <th class="px-3 py-2">Fee Name</th>
                                        <th class="px-3 py-2 text-right">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(ser, index) in srform.service" :key="index" class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="px-3 py-2">{{ ser.name }}</td>
                                        <td class="px-3 py-2 text-right">{{ Number(ser.amount).toFixed(2) }}</td>
                                    </tr>

                                    <tr class="bg-gray-200 font-semibold dark:bg-gray-700">
                                        <td class="px-3 py-2">Grand Total</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ srform.service.reduce((t, f) => t + (Number(f.amount) || 0), 0).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Fees Table -->
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="w-full table-auto text-sm">
                                <thead class="bg-gray-100 text-left dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-center" colspan="2">Invoice Rturn Details</th>
                                    </tr>
                                    <tr>
                                        <th class="px-3 py-2">Fee Name</th>
                                        <th class="px-3 py-2 text-right">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(fee, fIndex) in srform.return" :key="fIndex" class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="px-3 py-2">{{ fee.name }}</td>
                                        <td class="px-3 py-2 text-right">{{ Number(fee.amount).toFixed(2) }}</td>
                                    </tr>

                                    <tr class="bg-gray-200 font-semibold dark:bg-gray-700">
                                        <td class="px-3 py-2">Grand Total</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ srform.return.reduce((t, f) => t + (Number(f.amount) || 0), 0).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
        </div>
    </StudentLayout>
</template>
