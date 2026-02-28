<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Eye, Link, RefreshCcw, ShieldCheck, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Invoice Return Request',
        href: '/dashboard/ReturnRequest',
    },
];

export interface Return {
    id: number;
    insnumber: string;
    insdate: string;
    netamount: number;
    status:number;
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
    refund: Paginated<Return>;
    isadmin: number;
}>();

const data = props.refund;



const form = useForm({
    returnId: '',
    insnumber: '',
    insdate: '',
    netamount: '',
    refe_code: '',
    shortnote: '',
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

const onConfirm = async (id: number) => {
    form.returnId = id;

    router.put(route('approval.ReturnConfirm', { return: id }), form, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {},
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.error(firstError as string);
        },
    });
};

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to cancel this return invoice?')) return;
    form.returnId = id;

    router.put(route('approval.ReturnCancel', { return: id }), form, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {},
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.error(firstError as string);
        },
    });
};

const showDialogSR = ref(false);
const showSRDailog = async (id: number) => {
    if (id == null) {
        toast('error', {
            description: 'Invocice Return is not avaliable',
        });
        showDialogSR.value = false;
    } else {
        await fetchSRData(id);
        showDialogSR.value = true;
    }
};
const fetchSRData = async (srid: number) => {
    try {
        const url = route('studentAccounts.fetchSR', {
            student: data.data?.[0]?.student?.id,
            srid: srid,
        });
        const res = await fetch(url);
        const result = await res.json();
        
        form.service =
            result.service?.map((s: any) => ({
                name: s.fees.name,
                amount: s.amount,
            })) ?? [];

        form.insnumber = result.mrhd?.insnumber ?? '';
        form.insdate = result.mrhd?.insdate ?? '';
        form.netamount = result.mrhd?.netamount ?? '';
        form.refe_code = result.mrhd?.refe_code ?? '';
        form.shortnote = result.mrhd?.shortnote ?? '';

        form.return =
            result.return?.map((m: any) => ({
                name: m.fee.name,
                amount: m.amount,
            })) ?? [];
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const onReport = (id: number) => {
     const url = route('studentAccounts.onReport', {
        student: data.data?.[0]?.student?.id,
        confirm: id,
    });

    window.open(url, '_blank');
};

const refresh = () => {
    router.get(route('dashboard.ReturnRequest'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('dashboard.ReturnRequest'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <Head title="Refund Request" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
            <div class="flex flex-wrap items-center gap-4 py-4">
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>SL</TableHead>
                            <TableHead>Invoice No</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Return Amount</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Return By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(quoat, index) in data.data ?? []" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ quoat.insnumber }}</TableCell>
                            <TableCell>{{ quoat.student.fname }} {{ quoat.student.lname }}</TableCell>
                            <TableCell>{{ quoat.netamount }}</TableCell>
                            <TableCell>{{ quoat.insdate }}</TableCell>
                            <TableCell>{{ quoat.user.name }}</TableCell>
                            <TableCell>
                                <div v-if="quoat.status == null">
                                    <div v-if="isadmin">
                                        <div class="group relative inline-block">
                                            <Button
                                                class="cursor-pointer"
                                                size="sm"
                                                variant="outline"
                                                @click="showSRDailog(quoat.description)"
                                            >
                                                <Eye class="text-purple-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                View
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button class="cursor-pointer" size="sm" variant="outline" @click="onConfirm(quoat.id)">
                                                <ShieldCheck class="text-green-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Approved
                                            </span>
                                        </div>
                                        <div class="group relative inline-block">
                                            <Button class="cursor-pointer" size="sm" variant="outline" @click="onDelete(quoat.id)"
                                                ><X class="text-red-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                Cancel
                                            </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="group relative inline-block">
                                            <Button class="cursor-pointer" size="sm" variant="outline" @click="onReport(quoat.id)">
                                                <Link class="text-yellow-500"
                                            /></Button>
                                            <span
                                                class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                            >
                                                View Return
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>Confirmed</div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                    <label for="per-page" class="text-gray-600">Show:</label>
                    <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                        <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                    </select>
                    <span>Showing {{ refund.from }} to {{ refund.to }} of {{ refund.total }} results</span>
                </div>
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
                                <tr v-for="(ser, index) in form.service" :key="index" class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="px-3 py-2">{{ ser.name }}</td>
                                    <td class="px-3 py-2 text-right">{{ Number(ser.amount).toFixed(2) }}</td>
                                </tr>

                                <tr class="bg-gray-200 font-semibold dark:bg-gray-700">
                                    <td class="px-3 py-2">Grand Total</td>
                                    <td class="px-3 py-2 text-right">
                                        {{ form.service.reduce((t, f) => t + (Number(f.amount) || 0), 0).toFixed(2) }}
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
                                <tr v-for="(fee, fIndex) in form.return" :key="fIndex" class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="px-3 py-2">{{ fee.name }}</td>
                                    <td class="px-3 py-2 text-right">{{ Number(fee.amount).toFixed(2) }}</td>
                                </tr>

                                <tr class="bg-gray-200 font-semibold dark:bg-gray-700">
                                    <td class="px-3 py-2">Grand Total</td>
                                    <td class="px-3 py-2 text-right">
                                        {{ form.return.reduce((t, f) => t + (Number(f.amount) || 0), 0).toFixed(2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="grid grid-cols-1 gap-4 rounded-lg border p-4 md:grid-cols-3 dark:border-gray-700">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Invocie Refund Note</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ form.shortnote }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter
                    class="flex shrink-0 flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 sm:flex-row sm:justify-end dark:border-gray-700"
                >
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="w-full px-4 py-2 sm:w-auto">Cancel</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
