<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ShieldCheck} from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transfer Request',
        href: '/dashboard/TransferRequest',
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

export interface Transfer {
    id: number;
    reference_id: string;
    description: string;
    remarks: string;
    status: string;
    user_id: number;
    created_at: number;
}
const props = defineProps<{
    transfer: Paginated<Transfer>;
    users:[];
    isadmin: number;
}>();

const data = props.transfer;
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const form = useForm({
    student_id: '',
    user_id: '',
});

const showTransfer = ref(false);

const updateTransfer = (studentId:number) => {
    if (confirm('Transfer confirm this student?'))
    form.student_id = studentId;
    showTransfer.value = true;
};

const onConfirm = () => {

    form.put(route('studentActivities.confirmTransfer', { student: form.student_id }), {
        onSuccess: () => {
            const flash = usePage().props.flash;

            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            if (flash?.error) {
                toast('error', {
                    description: flash.error,
                });
                return;
            }
            setTimeout(() => {
                form.reset();
                router.visit(route('dashboard.TransferRequest'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            form.reset();
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
    <Head title="Transfer  Request" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead>Transfer By</TableHead>
                            <TableHead>Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(arc, index) in data.data ?? []" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ arc.student.fname }} {{ arc.student.lname }}</TableCell>
                            <TableCell>{{ arc.description }}</TableCell>
                            <TableCell>{{ arc.user.name }}</TableCell>
                            <TableCell>
                                <div class="group relative inline-block" v-if="props.isadmin && arc.status == null">
                                    <Button class="m-[2px] cursor-pointer" size="sm" variant="outline" @click="updateTransfer(arc.student.id)">
                                        <ShieldCheck class="text-red-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Approved
                                    </span>
                                </div>
                                <div class="group relative inline-block" v-else-if="!props.isadmin && arc.status == null">
                                    Transfer Pending
                                </div>
                                <div class="group relative inline-block" v-else-if="!props.isadmin && arc.status == 1">
                                    Transfer Confirmed
                                </div>
                                <div class="group relative inline-block" v-else-if="props.isadmin && arc.status == 1">
                                    Transfer Confirmed
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
        <Dialog v-model:open="showTransfer">
            <DialogContent class="max-w-[825px]">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle> Lead Transfer Confirm </DialogTitle>
                    <DialogDescription> Submit transfer request Confirm. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">Select user name<span class="text-red-500">*</span></Label>
                        <Select v-model="form.user_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select user" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <span v-if="form.errors.user_id" class="text-sm text-red-600">{{ form.errors.user_id }}</span> 
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="form.processing" @click="onConfirm">
                        <template v-if="form.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
