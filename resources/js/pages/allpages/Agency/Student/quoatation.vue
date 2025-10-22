<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';

import { router, useForm } from '@inertiajs/vue3';
import { FileText, Plus, ShieldCheck, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; status: string };
    studentService: Array<{
        id: number;
        startdate: string;
        enddate: string;
        status: string;
        workflow: { id: number; name: string } | null;
        partnerBranch?: { id: number; partner?: { id?: number; name?: string } } | null;
        product?: { id: number; name: string } | null;
        productfees?: {
            id: number;
            name: string;
            netamount: number;
            details?: {
                id?: number;
                name?: string;
                amount: string;
                insqty: number;
                pay_type: string;
                totalamount: number;
                fees: { id: number; name: string };
            };
        } | null;
    }>;
    studentquoatation: Array<{
        id: number;
        quoat_no: string;
        amount: number;
        discount: number;
        netamount: number;
        adddate: Date;
        active: number;
        student: { id: number; name: string } | null;
        quoatation: { id: number; name: string };
        user: { id: number; name: string };
    }>;
}>();

console.log(props.studentquoatation)
const showDialogAdd = ref(false);

const form = useForm({
    student_id: props.student.id,
    service_ids: [] as number[],
    amount: '',
    note: '',
    fees: [] as { id: number; amount: number }[],
});

// When opening dialog populate fees from all services
const initFeesFromServices = () => {
      form.fees = props.studentService.flatMap((s) =>
        (s.productfees?.details || []).map((fee) => ({
          id: fee.fees_id,
          amount: Number(fee.totalamount ?? 0),
          product_id: s.product?.id ?? null,
        }))
      );

};


// compute overall total (from form.fees)
const totalNetAmount = computed(() => {
    return form.fees.reduce((s, f) => s + Number(f.amount || 0), 0);
});



const showDailog = () => {
    if (!props.studentService || props.studentService.length === 0) {
        toast('error', {
            description: 'Student Service not created. Please create service first.',
        });
    } else {
        initFeesFromServices();
        showDialogAdd.value = true;
    }
};

const submitGeneral = () => {
    const action = route('studentQuotations.generalStore', { student: props.student.id });
    form.service_ids = props.studentService.map((s) => s.id);

    form.amount = totalNetAmount.value;
    form.post(action, {
        onSuccess: () => {
            toast('Success', {
                description: 'Student Quotations created successfully',
            });
            setTimeout(() => {
                showDialogAdd.value = false;
                form.reset();
                router.visit(route('studentQuotations.index', props.student.id), {
                    only: ['student_quotations'],
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

const onDelete = async (quoatId: number) => {
    const quoatation = props.studentquoatation.find((q) => q.id === quoatId);
    if (!quoatation) return;

    const newStatus = !Boolean(quoatation.active);

    router.post(
        route('studentQuotations.generalDelete', {
            student: props.student.id,
            confirm: quoatation.id,
        }),
        { active: quoatId },
        {
            preserveState: true,
            onSuccess: () => {
                quoatation.active = newStatus ? 1 : 0;
                toast.success('Quotation delete successfully');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onConfirmGeneral = async (quoatId: number) => {
    const quoatation = props.studentquoatation.find((q) => q.id === quoatId);
    if (!quoatation) return;

    const newStatus = !Boolean(quoatation.active);

    router.put(
        route('studentQuotations.confirmGeneral', {
            student: props.student.id,
            confirm: quoatation.id,
        }),
        { active: quoatId },
        {
            preserveState: true,
            onSuccess: () => {
                quoatation.active = newStatus ? 1 : 0;
                toast.success('Quotation confirmed successfully');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onReportGeneral = async (quoatId: number) => {
    const url = route('studentQuotations.exportPdfGeneral', {
        student: props.student.id,
        quoatation: quoatId,
    });

    window.open(url, '_blank');
};


</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">Student Quoatations</div>
                <div class="space-x-2">
                    <Button size="sm" @click="showDailog"><Plus></Plus>Add</Button>
                </div>
            </div>
            <div class="flex items-start gap-3 rounded-xl">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Quoatations No</TableHead>
                            <TableHead>Net Amount</TableHead>
                            <TableHead>Notes</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(quoat, index) in props.studentquoatation" :key="quoat.id ?? index">
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ quoat.quotation_no }}</TableCell>
                            <TableCell>{{ quoat.sumamount }}</TableCell>
                            <TableCell>{{ quoat.notes }}</TableCell>
                            <TableCell>{{ quoat.adddate }}</TableCell>
                            <TableCell>{{ quoat.user.name }}</TableCell>
                            <TableCell>
                                <Button
                                    v-if="quoat.active == 0"
                                    class="m-[2px] cursor-pointer"
                                    size="sm"
                                    variant="outline"
                                    @click="onDelete(quoat.id)"
                                    ><Trash></Trash
                                ></Button>
                                <Button
                                    v-if="quoat.active == 0"
                                    class="m-[2px] cursor-pointer"
                                    size="sm"
                                    variant="outline"
                                    @click="onConfirmGeneral(quoat.id)"
                                    ><ShieldCheck></ShieldCheck
                                ></Button>
                                <Button
                                    v-if="quoat.active == 1"
                                    class="m-[2px] cursor-pointer"
                                    size="sm"
                                    variant="outline"
                                    @click="onReportGeneral(quoat.id)"
                                    ><FileText class="text-red-500"></FileText
                                ></Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Dialog General-->
            <Dialog v-model:open="showDialogAdd">
                <DialogContent
                    class="w-[95vw] max-w-full overflow-hidden rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
                >
                    <!-- Header -->
                    <DialogHeader class="border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-700">
                        <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">
                            Create Student Quotation
                        </DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Fill in the details below to create a new quotation.
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Form Body -->
                    <div class="max-h-80 flex-1 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">
                        <div
                            v-for="(quoat, index) in props.studentService"
                            :key="quoat?.id ?? index"
                            class="mb-4 grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-3 dark:border-gray-700 dark:bg-gray-900"
                        >
                            <div>
                                <span class="text-sm text-gray-500">Workflow:</span>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ quoat?.workflow?.name ?? '-' }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-100">Partner:</span>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ quoat?.partner_branch?.partner.name ?? '-' }}
                                    <br />
                                    <span class="text-sm text-gray-500 dark:text-gray-100">{{ quoat?.partner_branch?.branch_name ?? '-' }}</span>
                                </p>
                            </div>

                            <div>
                                <span class="text-sm text-gray-500">Product:</span>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ quoat?.product?.name ?? '-' }}</p>
                            </div>

                            <!-- Product Fees Table -->
                            <div v-if="quoat?.productfees" class="col-span-1 mt-3 overflow-x-auto md:col-span-3">
                                <div class="min-w-full overflow-x-auto">
                                    <table class="w-full border-collapse border border-gray-200 text-xs sm:text-sm dark:border-gray-700">
                                        <thead class="bg-gray-100 dark:bg-gray-800">
                                            <tr>
                                                <th class="border-b border-gray-300 px-2 py-2 text-left dark:border-gray-600">Fee Name</th>
                                                <th class="border-b border-gray-300 px-2 py-2 text-left dark:border-gray-600">Net Amount</th>
                                                <th class="border-b border-gray-300 px-2 py-2 text-left dark:border-gray-600">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Fee Details -->
                                            <tr
                                                v-for="fee in quoat.productfees.details"
                                                :key="fee.id"
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                            >
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">{{ fee.fees?.name ?? '-' }}</td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    <input
                                                        type="number"
                                                        class="w-full rounded-md border px-2 py-1 focus:ring focus:ring-blue-200"
                                                        v-model.number="fee.amount"
                                                    />
                                                </td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    <div class="flex flex-col gap-1">
                                                        <span>Ins Qty: {{ fee.insqty ?? '-' }}</span>
                                                        <span>Pay Type: {{ fee.pay_type ?? '-' }}</span>
                                                        <span>Total: {{ fee.totalamount ?? '-' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-50 font-medium dark:bg-gray-800">
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">Grand Total</td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    {{ quoat.productfees.netamount }}
                                                </td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">—</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="mt-4 grid gap-2">
                            <Label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Note</Label>
                            <Textarea
                                v-model="form.note"
                                placeholder="Write a short note about this quotation..."
                                class="focus:ring-opacity-50 rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                            />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end border-t border-gray-200 pt-3 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200"> Total Net Amount: </span>
                        <span class="ml-2 text-sm font-bold text-indigo-600 dark:text-indigo-400">
                            {{ totalNetAmount.toFixed(2) }}
                        </span>
                    </div>

                    <!-- Footer -->
                    <DialogFooter
                        class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 px-4 py-3 sm:flex-row sm:px-6 dark:border-gray-700"
                    >
                        <DialogClose as-child>
                            <Button type="button" variant="secondary" class="w-full px-4 py-2 sm:w-auto">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submitGeneral" class="w-full px-5 py-2 sm:w-auto">
                            <template v-if="form.processing">Creating...</template>
                            <template v-else>Create</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </StudentLayout>
</template>
