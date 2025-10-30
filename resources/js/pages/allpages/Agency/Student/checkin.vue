<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { DoorClosed, DoorOpen } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

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

export interface CheckIN {
    id: number;
    student_id: string;
    status: string;
    user_id: number;
    created_at: number;
}

const props = defineProps<{
    student: { id: number; status: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
    checkin: Paginated<CheckIN>;
}>();
const data = props.checkin;

const form = useForm({
    status: 'Check IN',
});

const checkIn = () => {
    form.post(
        route('studentCheckin.store', {
            student: props.student.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success', {
                    description: `Student check in successfully`,
                });
                setTimeout(() => {
                    form.reset();
                    router.visit(route('studentCheckin.index', { student: props.student.id }), {
                        only: ['student_check_logs'],
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
        },
    );
};

const checkOut = () => {
    form.post(
        route('studentCheckin.checkOut', {
            student: props.student.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success', {
                    description: `Student check out successfully`,
                });
                setTimeout(() => {
                    form.reset();
                    router.visit(route('studentCheckin.index', { student: props.student.id }), {
                        only: ['student_check_logs'],
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
        },
    );
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <!-- Single Activity -->
            <div class="flex items-center gap-4 space-x-4">
                <!-- Check In -->
                <div class="group relative">
                    <Button
                        @click="checkIn"
                        class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-green-400 bg-green-50 text-green-600 transition-all hover:bg-green-100"
                    >
                        <DoorOpen class="h-5 w-5" />
                    </Button>
                    <span
                        class="absolute -bottom-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                    >
                        Check In
                    </span>
                </div>

                <!-- Check Out -->
                <div class="group relative">
                    <Button
                        @click="checkOut"
                        class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-red-400 bg-red-50 text-red-600 transition-all hover:bg-red-100"
                    >
                        <DoorClosed class="h-5 w-5" />
                    </Button>
                    <span
                        class="absolute -bottom-7 left-1/2 -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                    >
                        Check Out
                    </span>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Student Name</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Time</TableHead>
                            <TableHead>Action User</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(stud, index) in data.data" :key="stud.id ?? index">
                            <TableCell> {{ stud.student.fname }} {{ stud.student.lname }} </TableCell>
                            <TableCell>{{ stud.status }}</TableCell>
                            <TableCell>{{ formatDate(stud.created_at) }}</TableCell>
                            <TableCell>{{ stud.user.name }}</TableCell>
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
    </StudentLayout>
</template>
