<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { FileText, Plus, ShieldCheck, Trash } from 'lucide-vue-next';
import { computed, nextTick, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; status: string };
    service: { id:number;workflow: string; partner: string; partnerbranch: string; product: string; amount: string; status: string; product_id: number };
    quoatation: { id: number; product_id: number; quotation_no: string; totalamount: string; notes: string; status: number; active: number };
}>();

const form = useForm({
    student_id: props.student.id,
    product_id: '',
    service_id:'',
    grandTotal: '',
    note: '',
    fees: [] as { id: number; amount: number }[],
});

const fees = ref<any[]>([]);
const fetchData = async (id: number) => {
    try {
        const url = route('studentQuotations.fetchData', {
            student: props.student.id,
            product: id,
        });
        const res = await fetch(url);
        const data = await res.json();
        if (data.success) {
            fees.value = data.fees;
            form.fee = data.fees.map((f: any) => ({
                feesid: f.feesid,
                amount: f.amount ?? 0,
            }));
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const showDialogAdd = ref(false);

const showDailog = async (sid: number, id: number) => {
    await fetchData(id);
    form.product_id = id;
    form.service_id = sid;
    showDialogAdd.value = true;
};

const grandTotal = computed(() => {
    return fees.value
        .reduce((sum, f) => {
            const amt = parseFloat(f.amount) || 0;
            return sum + amt;
        }, 0)
        .toFixed(2);
});

const submitGeneral = () => {
    form.fees = fees.value.map((f) => ({
        feesid: f.feesid,
        amount: f.amount,
        insqty: f.insqty,
        pay_type: f.pay_type,
        totalamount: f.totalamount,
    }));
    form.grandTotal = grandTotal;
    const action = route('studentQuotations.store', {
        student: props.student.id,
    });

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

const onDelete = async (quoatId: number, id:number) => {
    router.post(
        route('studentQuotations.destory', {
            student: props.student.id,
            product: quoatId,
        }),
        {
            status: id
        },
        {
            preserveState: true,
            onSuccess: () => {
                const flash = usePage().props.flash;
                if (flash.message) {
                    toast('Success', {
                        description: flash.message,
                    });
                }
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onConfirm = async (quoatId: number, id:number) => {
    router.put(
        route('studentQuotations.confirm', {
            student: props.student.id,
            product: quoatId,
        }),
        {
            status: id
        },
        {
            preserveState: true,
            onSuccess: () => {
                const flash = usePage().props.flash;
                if (flash.message) {
                    toast('Success', {
                        description: flash.message,
                    });
                }
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onReport = async (quoatId: number, id:number) => {
    const url = route('studentQuotations.exportPdfGeneral', {
        student: props.student.id,
        product: quoatId,
        quoatation: id,
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
                    
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Services -->
                <Card class="w-full border-green-300">
                    <div class="overflow-x-auto text-sm">
                        <h3 class="mb-2 ml-2 font-semibold">Service's</h3>
                        <Table class="min-w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>Workflow</TableHead>
                                    <TableHead>Partner</TableHead>
                                    <TableHead>Product</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-center">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(ser, index) in props.service" :key="index">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell>{{ ser.workflow }}</TableCell>
                                    <TableCell
                                        >{{ ser.partner }}<br />
                                        <span class="font-mono text-sm text-gray-400">{{ ser.partnerbranch }}</span>
                                    </TableCell>
                                    <TableCell>{{ ser.product }}</TableCell>
                                    <TableCell>{{ ser.amount }}</TableCell>
                                    <TableCell
                                        ><span class="font-mono text-sm text-gray-400">{{ ser.status }}</span></TableCell
                                    >
                                    <TableCell class="text-center">
                                        <Button class="m-[2px]" variant="outline" size="sm" @click="showDailog(ser.id,ser.product_id)">
                                            <Plus />
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </Card>
                <!-- Quotations -->
                <Card class="h-auto w-full border-green-300">
                    <div class="overflow-x-auto text-sm">
                        <h3 class="mb-2 ml-2 font-semibold">Quotations</h3>
                        <Table class="min-w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Sl</TableHead>
                                    <TableHead>Quoatations No</TableHead>
                                    <TableHead>Net Amount</TableHead>
                                    <TableHead>Notes</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-center">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(quoat, index) in props.quoatation" :key="index">
                                    <TableCell>{{ index + 1 }}</TableCell>
                                    <TableCell>{{ quoat.quotation_no }}</TableCell>
                                    <TableCell>{{ quoat.totalamount }}</TableCell>
                                    <TableCell>{{ quoat.notes }}</TableCell>
                                    <TableCell>
                                        <span v-if="quoat.active==0">Open</span>
                                        <span v-if="quoat.active==1">Confirmed</span>
                                        <span v-if="quoat.active==2">Cancel</span>
                                    </TableCell>
                                    <TableCell class="text-center">
                                        <div v-if="quoat.active==0" class="flex justify-center gap-2">
                                            <!-- Delete Button -->
                                            <div class="group relative">
                                                <Button @click="onDelete(quoat.product_id,quoat.id)" class="m-[2px] cursor-pointer" variant="outline" size="sm">
                                                    <Trash />
                                                </Button>
                                                <span
                                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                                >
                                                    Cancel
                                                </span>
                                            </div>

                                            <!-- Shield Button -->
                                            <div class="group relative">
                                                <Button
                                                    @click="onConfirm(quoat.product_id,quoat.id)"
                                                    class="m-[2px] cursor-pointer"
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <ShieldCheck />
                                                </Button>
                                                <span
                                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                                >
                                                    Confirm
                                                </span>
                                            </div>
                                        </div>
                                        <div v-if="quoat.active==1" class="flex justify-center gap-2">
                                            <!-- File Button -->
                                            <div class="group relative">
                                                <Button @click="onReport(quoat.product_id,quoat.id)" class="m-[2px] cursor-pointer" variant="outline" size="sm">
                                                    <FileText class="text-red-500" />
                                                </Button>
                                                <span
                                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                                >
                                                    Report
                                                </span>
                                            </div>
                                        </div>
                                        <div v-else></div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </Card>
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
                            class="mb-4 grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-3 dark:border-gray-700 dark:bg-gray-900"
                        >
                            <!-- Product Fees Table -->
                            <div class="col-span-1 mt-3 overflow-x-auto md:col-span-3">
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
                                            <tr v-for="(fee, index) in fees" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">{{ fee.feename }}</td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    <input
                                                        type="number"
                                                        class="w-full rounded-md border px-2 py-1 focus:ring focus:ring-blue-200"
                                                        v-model="fee.amount"
                                                    />
                                                </td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    <div class="flex flex-col gap-1">
                                                        <span>Ins Qty: {{ fee.insqty }}</span>
                                                        <span>Pay Type: {{ fee.pay_type }}</span>
                                                        <span>Total: {{ fee.totalamount }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-50 font-medium dark:bg-gray-800">
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">Grand Total</td>
                                                <td class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">{{ grandTotal }}</td>
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
