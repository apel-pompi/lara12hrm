<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref,watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface Transaction {
    id: number;
    name: string;
    trncode: string;
    lastnumber: number;
    increment: number;
    active: number;
}

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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Transaction Number', href: '/transaction' }];

const props = defineProps<{
    tranaction: Paginated<Transaction>;
    filters: { name?: string };
}>();

const data = props.tranaction;

interface FormErrors {
    name?: string;
    trncode?: string;
    lastnumber?: number;
    increment?: number;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    trncode: '',
    lastnumber: '',
    increment: '',
    active: false,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    form.post(route('transaction.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: `Transaction created successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('transaction.index'), {
                    only: ['transactions'],
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

const toggleStatus = (transaction: Transaction) => {
    const newStatus = !Boolean(transaction.active); // boolean
    router.put(
        route('transaction.updateStatus', transaction.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                transaction.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Transaction Code status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this transaction?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/transaction/show/${id}`, {
        onSuccess: () => {
            toast.success('Transaction deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};



const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

watch(
  () => form.name,
  (newVal) => {
    if (newVal === 'Student ID') {
      form.trncode = 'STU-';
    } else if (newVal === 'Money Received') {
      form.trncode = 'MR--';
    } else if (newVal === 'Quoatations No') {
      form.trncode = 'QTN-';
    } else if (newVal === 'Invoice No') {
      form.trncode = 'INV-';
    } else if (newVal === 'Amount Refund') {
      form.trncode = 'SR--';
    } else if (newVal === 'Opening Blance') {
      form.trncode = 'OB--';
    } else if (newVal === 'Journal Voucher') {
      form.trncode = 'JV--';
    } else if (newVal === 'Payment Voucher') {
      form.trncode = 'PAY-';
    } else if (newVal === 'Receipt Voucher') {
      form.trncode = 'RCV-';
    } else if (newVal === 'Reverse Voucher') {
      form.trncode = 'REV-';
    } else if (newVal === 'Supplier No') {
      form.trncode = 'SUP-';
    } else if (newVal === 'Supplier Invoice') {
      form.trncode = 'AP--';
    } else if (newVal === 'Supplier Payment') {
      form.trncode = 'APV-';
    }  else {
      form.trncode = '';
    }
  }
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Source" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create </Button>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Transaction Name</TableHead>
                                <TableHead>Transaction Code</TableHead>
                                <TableHead>Lastnumber</TableHead>
                                <TableHead>Increment</TableHead>
                                <TableHead>Added By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(trn, index) in data.data" :key="trn.id ?? index">
                                <TableCell>{{ trn.name }}</TableCell>
                                <TableCell>{{ trn.trncode }}</TableCell>
                                <TableCell>{{ trn.lastnumber }}</TableCell>
                                <TableCell>{{ trn.increment }}</TableCell>
                                <TableCell>{{ trn.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="trn.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(trn)"> </Switch>
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button  size="sm" variant="outline" @click="onDelete(trn.id)"><Trash></Trash></Button>
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
            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                    <!-- Header -->
                    <DialogHeader class="border-b pb-3">
                        <DialogTitle class="text-lg font-semibold">
                            {{ isEditMode ? 'Edit transaction number' : 'Create transaction number' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{
                                isEditMode
                                    ? 'Update the transaction number details and click save.'
                                    : 'Fill in the details below to create a new transaction number.'
                            }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid grid-cols-1 gap-6 py-4 md:grid-cols-2">
                        <!-- Transaction Name -->
                        <div class="grid gap-2">
                            <Label for="name" class="font-medium">Transaction Name<span class="text-red-500">*</span></Label>
                            <Select v-model="form.name">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Student ID">Student ID</SelectItem>
                                        <SelectItem value="Money Received">Money Received</SelectItem>
                                        <SelectItem value="Quoatations No">Quoatations No</SelectItem>
                                        <SelectItem value="Invoice No">Invoice No</SelectItem>
                                        <SelectItem value="Amount Refund">Amount Refund</SelectItem>
                                        <SelectItem value="Opening Blance">Opening Blance</SelectItem>
                                        <SelectItem value="Journal Voucher">Journal Voucher</SelectItem>
                                        <SelectItem value="Payment Voucher">Payment Voucher</SelectItem>
                                        <SelectItem value="Receipt Voucher">Receipt Voucher</SelectItem>
                                        <SelectItem value="Reverse Voucher">Reverse Voucher</SelectItem>
                                        <SelectItem value="Supplier No">Supplier No</SelectItem>
                                        <SelectItem value="Supplier Invoice">Supplier Invoice</SelectItem>
                                        <SelectItem value="Supplier Payment">Supplier Payment</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Transaction Code -->
                        <div class="grid gap-2">
                            <Label for="trncode" class="font-medium">Transaction Code<span class="text-red-500">*</span></Label>
                            <Input id="trncode" v-model="form.trncode" class="w-full" readonly autofocus />
                            <p v-if="form.errors.trncode" class="text-sm text-red-600">
                                {{ form.errors.trncode }}
                            </p>
                        </div>
                        <!-- Last Number -->
                        <div class="grid gap-2">
                            <Label for="lastnumber" class="font-medium">Last Number<span class="text-red-500">*</span></Label>
                            <Input id="lastnumber" v-model="form.lastnumber" class="w-full" placeholder="Input last number" autofocus />
                            <p v-if="form.errors.lastnumber" class="text-sm text-red-600">
                                {{ form.errors.lastnumber }}
                            </p>
                        </div>

                        <!-- Increment -->
                        <div class="grid gap-2">
                            <Label for="increment" class="font-medium">Increment<span class="text-red-500">*</span></Label>
                            <Input id="increment" v-model="form.increment" class="w-full" placeholder="Input Increment" autofocus />
                            <p v-if="form.errors.increment" class="text-sm text-red-600">
                                {{ form.errors.increment }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="flex justify-end space-x-2 border-t pt-4">
                        <DialogClose as-child>
                            <Button type="button" variant="secondary">Cancel</Button>
                        </DialogClose>
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
