<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { HandCoins } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: '/accounts',
    },
];

const props = defineProps<{
    invoice?: Array<{
        insnumber: string;
        student?: { student_id: string; fname: string; lname: string; phone: string };
        details_sum_amount?: number;
    }>;
}>();


const onCreateMR = async (invId: number, sid: number) => {
    router.visit(route('accounts.createMR', { insid: invId, sid: sid }));
};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4"></div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Create MR</TableHead>
                            <TableHead>Invoice No</TableHead>
                            <TableHead>Student ID</TableHead>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Total Recevable</TableHead>
                            <TableHead>Due Amount</TableHead>
                            
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(inv, index) in props.invoice ?? []" :key="index">
                        <TableRow v-if="inv.details_sum_amount >= 0"> 
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>
                                <div class="group relative inline-block">
                                    <Button
                                        class="m-[2px] cursor-pointer"
                                        size="sm"
                                        variant="outline"
                                        @click="onCreateMR(inv.insnumber, inv.student_id)"
                                        ><HandCoins class="text-red-500"></HandCoins
                                    ></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Create Money Received
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell>{{ inv.insnumber }}</TableCell>
                            <TableCell>{{ inv.student.student_id }}</TableCell>
                            <TableCell>{{ inv.student.fname }} {{ inv.student.lname }}</TableCell>
                            <TableCell>{{ inv.student.phone }}</TableCell>
                            <TableCell>{{ inv.netamount }}</TableCell>
                            <TableCell>{{ inv.details_sum_amount }}</TableCell>
                        </TableRow>
                        <TableRow v-else>
                            
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm">Showing results</div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm">
                        <span></span>
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
