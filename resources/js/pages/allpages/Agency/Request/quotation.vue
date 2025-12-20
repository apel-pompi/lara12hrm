<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Eye, Link, ShieldCheck, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quotation Request',
        href: '/dashboard/QuotationRequest',
    },
];

export interface Quotation {
    id: number;
    quotation_no: string;
    notes: string;
    adddate: string;
    active: number;
    description: number;
    student: { lname: string; fname: string; gender: number };
    user: { name: string };
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
const props = defineProps<{
    quotation: Paginated<Quotation>;
    isadmin: number;
}>();

const data = props.quotation;

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const form = useForm({
    quoat_id: '',
});

const onConfirm = async (id: number) => {
    form.quoat_id = id;

    router.put(route('approval.QuoattionConfirm', { quotation: id }), form, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.error(firstError as string);
        },
    });
};

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to cancel this quoattion?')) return;
    form.quoat_id = id;

    router.put(route('approval.QuoattionCancel', { quotation: id }), form, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.error(firstError as string);
        },
    });
};

const ViewDailog = ref(false);

const viewForm = useForm({
    quotation_no: '',
    adddate: '',
    totalamount: '',
    quoatuser: '',
    fname: '',
    lname: '',
    student_id: '',
    gender: '',
    email: '',
    phone: '',
    country: '',
    workflow: '',
    partner: '',
    partnerbranch: '',
    product: '',
    viewfees: [] as {
        feename: string;
        amount: number;
        productfee: number;
    }[],
});
const onView = async (id: number) => {
    try {
        const url = route('approval.QuoattionView', {
            quotation: id,
        });
        const res = await fetch(url);
        if (!res.ok) {
            toast.error('Server error while fetching application details.');
            return;
        }
        const data = await res.json();
        const resData = data.data;
        const workflows = data.workflow;
        const fees = data.fees;
        viewForm.quotation_no = resData.quotation_no;
        viewForm.adddate = resData.adddate;
        viewForm.totalamount = resData.totalamount;
        viewForm.fname = resData.student.fname;
        viewForm.lname = resData.student.lname;
        viewForm.phone = resData.student.phone;
        viewForm.student_id = resData.student.student_id;
        viewForm.gender = resData.student.gender;
        viewForm.email = resData.student.email;
        viewForm.quoatuser = resData.user.name;
        viewForm.country = resData.student.country.name;
        viewForm.workflow = workflows.workflow.name;
        viewForm.partner = workflows.partner_branch.partner.name;
        viewForm.partnerbranch = workflows.partner_branch.branch_name;
        viewForm.product = workflows.product.name;
        viewForm.viewfees =
            fees?.map((s: any) => ({
                feename: s.fee.name,
                amount: s.amount,
                productfee: s.productfee?.amount ?? "Extra Adding",
            })) ?? [];
        ViewDailog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const onReport = (id : number) => {
    router.visit(route('studentQuotations.index', { student: id }));
}
</script>

<template>
    <Head title="Quotation Request" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>SL</TableHead>
                            <TableHead>Quotation No</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Quotation Amount</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Quotation By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(quoat, index) in data.data ?? []" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ quoat.quotation_no }}</TableCell>
                            <TableCell>{{ quoat.student.fname }} {{ quoat.student.lname }}</TableCell>
                            <TableCell>{{ quoat.totalamount }}</TableCell>
                            <TableCell>{{ quoat.adddate }}</TableCell>
                            <TableCell>{{ quoat.user.name }}</TableCell>
                            <TableCell>
                                <div v-if="isadmin">
                                    <div v-if="quoat.active == 0">
                                        <div class="group relative inline-block">
                                            <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onView(quoat.description)">
                                                <Eye class="text-purple-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                View
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onConfirm(quoat.quotation_no)">
                                                <ShieldCheck class="text-green-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Approved
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onDelete(quoat.quotation_no)"
                                                ><X class="text-red-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Cancel
                                            </span>
                                        </div>
                                    </div>
                                    <p v-else-if="quoat.active == 1">
                                        Approved
                                    </p>
                                    <p v-else-if="quoat.active == 2">
                                        Cancel
                                    </p>
                                    <p v-else></p>
                                </div>
                                <div v-else>
                                    <p v-if="quoat.active == 0">Approval Pending</p>
                                    <p v-else-if="quoat.active == 1">
                                        <div class="group relative inline-block">
                                            <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="onReport(quoat.reference_id)">
                                                <Link class="text-yellow-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                View Quotation
                                            </span>
                                        </div>
                                        
                                    </p>
                                    <p v-else-if="quoat.active == 2">Cancel</p>
                                    <p v-else></p>
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
        <Dialog v-model:open="ViewDailog">
            <DialogContent
                class="flex max-h-[90vh] w-[95vw] max-w-full flex-col rounded-2xl bg-white shadow-xl sm:max-w-lg md:max-w-2xl lg:max-w-4xl dark:bg-gray-900"
            >
                <!-- Header -->
                <DialogHeader class="flex-shrink-0 border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <DialogTitle class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">Student Quoatations View </DialogTitle>
                    <DialogDescription class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Fill in the details below to view quoatations.
                    </DialogDescription>
                </DialogHeader>

                <!-- Scrollable Content -->
                <div class="flex-1 space-y-4 overflow-y-auto px-6 py-4">
                    <!-- Student & Quotation Info -->
                    <div class="space-y-6">
                        <!-- Student Information Card -->
                        <div class="rounded-2xl border bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Student Information</h3>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Student ID:</span>
                                        {{ viewForm.student_id }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Student Name:</span>
                                        {{ viewForm.fname }} {{ viewForm.lname }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Gender:</span>
                                        {{
                                            viewForm.gender == 1
                                                ? 'Man'
                                                : viewForm.gender == 2
                                                  ? 'Woman'
                                                  : viewForm.gender == 3
                                                    ? "Other's"
                                                    : 'Unknown'
                                        }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Phone:</span>
                                        {{ viewForm.phone }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Email:</span>
                                        {{ viewForm.email }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Destination Country:</span>
                                        {{ viewForm.country }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Quotation No:</span>
                                        {{ viewForm.quotation_no }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Quotation Date:</span>
                                        {{ viewForm.adddate }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Prepared By:</span>
                                        {{ viewForm.quoatuser }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Workflow Card -->
                        <div class="rounded-2xl border bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Workflow & Product Information</h3>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Workflow:</span>
                                        {{ viewForm.workflow }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Partner Name:</span>
                                        {{ viewForm.partner }}
                                        <br />
                                        <span class="text-sm text-gray-500">{{ viewForm.partnerbranch }}</span>
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600 dark:text-gray-300">Product Name:</span>
                                        {{ viewForm.product }}
                                    </p>
                                </div>
                            </div>
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
                                            <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Product Amount</th>
                                            <th class="border-b border-gray-300 px-3 py-2 text-left dark:border-gray-600">Quoatations Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(fee, fIndex) in viewForm.viewfees"
                                            :key="fIndex"
                                            class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.feename }}</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.productfee }}</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">{{ fee.amount }}</td>
                                        </tr>
                                        <tr class="bg-gray-200 font-medium dark:bg-gray-700">
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">Grand Total</td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                                                {{ viewForm.viewfees.reduce((total, f) => total + Number(f.productfee || 0), 0).toFixed(2) }}
                                            </td>
                                            <td class="border-b border-gray-200 px-3 py-2 dark:border-gray-700">
                                                {{ viewForm.viewfees.reduce((total, f) => total + Number(f.amount || 0), 0).toFixed(2) }}
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
