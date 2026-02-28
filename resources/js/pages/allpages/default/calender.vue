<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Calender', href: '/dashboard/calender' }];

export interface Appoinment {
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

const props = defineProps<{
    appoinments: Paginated<Appoinment>;
    filters: { name?: string };
}>();

const data = props.appoinments;

const showDialog = ref(false);

const conversations = ref<any[]>([]);
const onConversation = async (studentId: number, id: number) => {
    showDialog.value = true;
    try {
        const res = await fetch(`/student/activities/${studentId}/conversations/${id}/fetchData`);

        if (!res.ok) {
            toast.error('Server error while fetching conversation details.');
            return;
        }

        const result = await res.json();

        if (result.success) {
            conversations.value = result.data;
            showDialog.value = true;
        }

        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};



const perPage = ref(10);

const changePerPage = () => {
    router.get(
        route('dashboard.calendar'),
        { per_page: perPage.value },
        { preserveState: false, replace: true }
    );
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Student Appoinments" />
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
            <div class="mt-5 rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Time</TableHead>
                            <TableHead>Note</TableHead>
                            <TableHead>Employee Name</TableHead>
                            <TableHead class="text-center">All Conversation</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(app, index) in data.data" :key="index">
                            <TableCell>{{ app.student.fname }} {{ app.student.lname }}</TableCell>
                            <TableCell>{{ app.student.phone }}</TableCell>
                            <TableCell>{{ app.datetime.split(' ')[0] }}</TableCell>
                            <TableCell>{{ app.datetime.split(' ')[1] }}</TableCell>
                            <TableCell>{{ app.discus }}</TableCell>
                            <TableCell>{{ app.user.name }}</TableCell>
                            <TableCell class="text-center">
                                <div class="group relative inline-block">
                                    <Button class="cursor-pointer" size="sm" variant="outline" @click="onConversation(app.student_id, app.id)"
                                        ><Eye class="text-green-500"
                                    /></Button>
                                    <span
                                        class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform rounded bg-gray-700 px-2 py-1 text-xs whitespace-nowrap text-white group-hover:block"
                                    >
                                        Conversation
                                    </span>
                                </div>
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
                    <span>Showing {{ appoinments.from }} to {{ appoinments.to }} of {{ appoinments.total }} results</span>
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
        <template>
            <Dialog v-model:open="showDialog">
                <DialogContent class="flex max-h-[90vh] max-w-[95vw] flex-col gap-0 p-0 sm:max-w-4xl">
                    <DialogHeader class="p-6 pb-2">
                        <DialogTitle class="text-xl font-semibold tracking-tight">Student Conversations</DialogTitle>
                        <DialogDescription> A historical log of all discussions recorded for this student. </DialogDescription>
                    </DialogHeader>

                    <div class="flex-1 overflow-auto px-6 py-4">
                        <div class="rounded-md border">
                            <Table>
                                <TableHeader class="bg-muted/50">
                                    <TableRow>
                                        <TableHead class="w-[120px] font-bold">Date</TableHead>
                                        <TableHead class="w-[100px] font-bold">Time</TableHead>
                                        <TableHead class="min-w-[300px] font-bold">Conversation</TableHead>
                                    </TableRow>
                                </TableHeader>

                                <TableBody>
                                    <template v-if="conversations.length > 0">
                                        <TableRow v-for="(conv, index) in conversations" :key="index" class="hover:bg-muted/30 transition-colors">
                                            <TableCell class="font-medium tabular-nums">
                                                {{ conv.datetime.split(' ')[0] }}
                                            </TableCell>
                                            <TableCell class="text-muted-foreground tabular-nums">
                                                {{ conv.datetime.split(' ')[1] }}
                                            </TableCell>
                                            <TableCell class="py-4 leading-relaxed whitespace-pre-wrap">
                                                {{ conv.discus }}
                                            </TableCell>
                                        </TableRow>
                                    </template>

                                    <TableRow v-else>
                                        <TableCell colspan="3" class="h-32 text-center">
                                            <div class="text-muted-foreground flex flex-col items-center justify-center">
                                                <p class="text-sm italic">No conversation history found.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <DialogFooter class="bg-muted/20 border-t p-6 pt-2">
                        <DialogClose as-child>
                            <Button type="button" variant="outline"> Close </Button>
                        </DialogClose>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </template>
    </AppLayout>
</template>
