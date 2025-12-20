<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router, useForm } from '@inertiajs/vue3';
import { FileText, Plus, ShieldCheck, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; status: string };
    service: {
        id: number;
        workflow: string;
        partner: string;
        partnerbranch: string;
        product: string;
        amount: string;
        status: string;
        product_id: number;
    };
    quoatation: { id: number; product_id: number; quotation_no: string; totalamount: string; notes: string; status: number; active: number };
    roles: { id: number };
    feestype: { id: number; name: string };
}>();

interface FeeRow {
    fees: any | null;
    query: string;
    ins_amount: number;
    insqty: number;
    pay_type: string;
    totalfees?: number;
}

const form = useForm({
    student_id: props.student.id,
    product_id: '',
    service_id: '',
    grandTotal: '',
    note: '',
    fees: [] as { id: number; amount: number }[],
    rows: [{ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '', totalfees: 0 } as FeeRow],
});

const filteredFeesType = (query: string) => {
    if (!query) return props.feestype;
    return props.feestype.filter((c) => c.name.toLowerCase().includes(query.toLowerCase()));
};
const addRow = () => {
    form.rows.push({ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '', totalfees: 0 });
};

const removeRow = (index: number) => {
    if (form.rows.length > 1) form.rows.splice(index, 1);
};

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

const AddFeesDialog = ref(false);
const AddFees = () => {
    AddFeesDialog.value = true;
    showDialogAdd.value = true;
};

const removeFee = (index) => {
    fees.value.splice(index, 1);
};

const saveAddedFees = () => {
    for (const row of form.rows) {
        if (!row.fees) {
            toast('Error', {
                description: 'Please select a Fee Type.',
            });

            return;
        }
        if (!row.ins_amount || row.ins_amount <= 0) {
            toast('Error', {
                description: 'Amount must be greater than 0.',
            });

            return;
        }

        if (!row.pay_type) {
            toast('Error', {
                description: 'Please select a Payment Type.',
            });

            return;
        }
    }
    form.rows.forEach((row) => {
        if (row.fees && row.ins_amount > 0 && row.insqty > 0) {
            fees.value.push({
                feename: row.fees.name,
                feesid: row.fees.id,
                amount: row.ins_amount,
                insqty: row.insqty,
                pay_type: row.pay_type,
                totalamount: (row.ins_amount * row.insqty).toFixed(2),
            });
        }
    });
    AddFeesDialog.value = false;
    form.rows = [{ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '', totalfees: 0 } as FeeRow];
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

const onDelete = async (quoatId: number, id: number) => {
    router.post(
        route('studentQuotations.destory', {
            student: props.student.id,
            product: quoatId,
        }),
        {
            status: id,
        },
        {
            preserveState: true,
            onSuccess: () => {
                
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};

const onConfirm = async (quoatId: number, id: number) => {
    router.put(
        route('studentQuotations.confirm', {
            student: props.student.id,
            product: quoatId,
        }),
        {
            status: id,
        },
        {
            preserveState: true,
            
        },
    );
};

const onReport = async (quoatId: number, id: number) => {
    const url = route('studentQuotations.exportPdfGeneral', {
        student: props.student.id,
        product: quoatId,
        quoatation: id,
    });

    window.open(url, '_blank');
};

const onReportApproved = async (quoatId: number, id: number) => {
    const url = route('studentQuotations.exportPdfApproved', {
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
                <div class="space-x-2"></div>
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
                                        <Button class="m-[2px]" variant="outline" size="sm" @click="showDailog(ser.id, ser.product_id)">
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
                                        <span v-if="quoat.active == 0">Open</span>
                                        <span v-if="quoat.active == 1">Confirmed</span>
                                        <span v-if="quoat.active == 2">Cancel</span>
                                    </TableCell>
                                    <TableCell class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <template v-if="quoat.active == 0">
                                                <!-- Cancel Button -->
                                                <div class="group relative">
                                                    <Button
                                                        @click="onDelete(quoat.product_id, quoat.id)"
                                                        variant="outline"
                                                        size="icon"
                                                        class="cursor-pointer rounded-full border-red-300 text-red-600 transition hover:bg-red-50 hover:text-red-700"
                                                    >
                                                        <Trash class="h-4 w-4" />
                                                    </Button>
                                                    <span
                                                        class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                                    >
                                                        Cancel
                                                    </span>
                                                </div>
                                                <!-- Confirm -->
                                                <div class="group relative">
                                                    <Button
                                                        @click="onConfirm(quoat.product_id, quoat.id)"
                                                        variant="outline"
                                                        size="icon"
                                                        class="cursor-pointer rounded-full border-green-300 text-green-600 transition hover:bg-green-50 hover:text-green-700"
                                                    >
                                                        <ShieldCheck class="h-4 w-4" />
                                                    </Button>
                                                    <span
                                                        class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                                    >
                                                        Confirm
                                                    </span>
                                                </div>

                                                <div v-if="props.roles[0] == 'superadmin'" class="group relative">
                                                    <Button
                                                        @click="onReportApproved(quoat.product_id, quoat.id)"
                                                        class="cursor-pointer rounded-full border-blue-300 text-blue-600 transition hover:bg-blue-50 hover:text-blue-700"
                                                        variant="outline"
                                                        size="sm"
                                                    >
                                                        <FileText class="text-red-500" />
                                                    </Button>
                                                    <span
                                                        class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                                    >
                                                        Report
                                                    </span>
                                                </div>
                                                <div v-else></div>
                                            </template>
                                            <template v-else-if="quoat.active == 1">
                                                <div class="group relative">
                                                    <Button
                                                        @click="onReport(quoat.product_id, quoat.id)"
                                                        variant="outline"
                                                        size="icon"
                                                        class="cursor-pointer rounded-full border-purple-300 text-purple-600 transition hover:bg-purple-50 hover:text-purple-700"
                                                    >
                                                        <FileText class="h-4 w-4" />
                                                    </Button>
                                                    <span
                                                        class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-[10px] text-white opacity-0 transition group-hover:opacity-100"
                                                    >
                                                        Report
                                                    </span>
                                                </div>
                                            </template>
                                            <template v-else></template>
                                        </div>
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
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex flex-col gap-1">
                                                            <span>Ins Qty: {{ fee.insqty }}</span>
                                                            <span>Pay Type: {{ fee.pay_type }}</span>
                                                            <span>Total: {{ fee.totalamount }}</span>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            @click="removeFee(index)"
                                                            class="ml-3 rounded-md bg-red-500 px-2 py-1 text-xs text-white hover:bg-red-600"
                                                        >
                                                            ✕
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-50 font-medium dark:bg-gray-800">
                                                <td colspan="3" class="border-b border-gray-200 px-2 py-2 dark:border-gray-700">
                                                    <Button variant="default" size="sm" @click="AddFees()">Add Fees</Button>
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
            <!-- Dialog General-->
            <Dialog v-model:open="AddFeesDialog">
                <DialogContent
                    class="w-[95vw] max-w-full overflow-hidden rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
                >
                    <!-- Header -->
                    <DialogHeader class="border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-700">
                        <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">
                            Another Fees add in student quotation
                        </DialogTitle>
                        <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Fill in the details below to add a new fees in quotation.
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Form Body -->
                    <div class="max-h-80 flex-1 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">
                        <div>
                            <div v-for="(row, index) in form.rows" :key="index" class="mb-4 rounded-xl border bg-gray-50 p-4">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                                    <div>
                                        <Label>Fee Type<span class="text-red-600">*</span></Label>
                                        <Combobox v-model="row.fees">
                                            <div class="relative">
                                                <ComboboxInput
                                                    class="w-full rounded-lg border-gray-300 bg-white py-2 pr-10 pl-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                                    placeholder="Search fees"
                                                    @input="row.query = $event.target.value"
                                                    :display-value="(c) => (c ? c.name : '')"
                                                />
                                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                                </ComboboxButton>
                                                <ComboboxOptions
                                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white py-1 text-sm shadow-lg"
                                                >
                                                    <div
                                                        v-if="filteredFeesType(row.query).length === 0 && row.query !== ''"
                                                        class="px-4 py-2 text-gray-500"
                                                    >
                                                        Nothing found.
                                                    </div>
                                                    <ComboboxOption
                                                        v-for="fees in filteredFeesType(row.query)"
                                                        :key="fees.id"
                                                        :value="fees"
                                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                                    >
                                                        {{ fees.name }}
                                                    </ComboboxOption>
                                                </ComboboxOptions>
                                            </div>
                                        </Combobox>
                                    </div>
                                    <div>
                                        <Label>Amount<span class="text-red-600">*</span></Label>
                                        <Input v-model.number="row.ins_amount" class="w-full" type="number" placeholder="0.00" />
                                    </div>
                                    <div>
                                        <Label>Qty<span class="text-red-600">*</span></Label>
                                        <Input v-model.number="row.insqty" class="w-full" type="number" placeholder="0" />
                                    </div>
                                    <div>
                                        <Label>Total Fee</Label>
                                        <Input
                                            v-model.number="row.totalfees"
                                            :value="(row.ins_amount * row.insqty).toFixed(2)"
                                            class="w-full"
                                            type="number"
                                            readonly
                                        />
                                    </div>
                                    <div>
                                        <Label>Payment Status <span class="text-red-600">*</span></Label>
                                        <Select v-model="row.pay_type">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Choose Payment Status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem value="Revenue">Revenue</SelectItem>
                                                    <SelectItem value="Refundable">Refundable</SelectItem>
                                                    <SelectItem value="Non Refundable">Non Refundable</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <!-- Delete Button -->
                                <div class="mt-2 flex justify-end">
                                    <Button variant="default" size="sm" @click="removeRow(index)" v-if="form.rows.length > 1"><Trash></Trash></Button>
                                </div>
                            </div>
                            <!-- Add Fee Button & Net Total -->
                            <div class="flex items-center justify-between border-t pt-4">
                                <Button variant="outline" @click="addRow"><Plus></Plus> Fee</Button>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter
                        class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 px-4 py-3 sm:flex-row sm:px-6 dark:border-gray-700"
                    >
                        <DialogClose as-child>
                            <Button type="button" variant="secondary" class="w-full px-4 py-2 sm:w-auto">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="saveAddedFees" class="w-full px-5 py-2 sm:w-auto">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>Save</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </StudentLayout>
</template>
