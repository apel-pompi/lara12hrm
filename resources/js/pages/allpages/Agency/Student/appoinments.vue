<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { router, useForm } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import { Loader2, Save } from 'lucide-vue-next';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

export interface Appoiments {
    id: number;
    name: string;
    datetime: string;
    discus: string;
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
    student: { id: number; status: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
    appoiment:Paginated<Appoiments>;
}>();

const form = useForm({
    apdate: '',
    discus: '',
});
const data = props.appoiment;

const dob = ref<string | null>(null);

watch(dob, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.apdate = newDate.toLocaleString();
    }
});


const submit = () => {
    form.post(
        route('studentAppointements.store', { student: props.student.id }),
        {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success', {
                    description: 'Appoinment created successfully.',
                });

                // Reset + Refresh data smoothly
                setTimeout(() => {
                    form.reset();
                    router.visit(route('studentAppointements.index', { student: props.student.id }), {
                        only: ['student_utilities'],
                        preserveScroll: true,
                        preserveState: false,
                        replace: true,
                    });
                }, 200);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', {
                    description: firstError || 'Something went wrong, please try again.',
                });
            },
        }
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

const perPage = ref(10);

const changePerPage = () => {
    router.get(
        route('studentAppointements.index', { student: props.student.id }),
        { per_page: perPage.value },
        { preserveState: false, replace: true },
    );
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
            <div class="mx-auto max-w-md space-y-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <!-- Title -->
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Appointments</h2>

                <!-- Date & Time Picker -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Date & Time</label>
                    <VueDatePicker
                        v-model="dob"
                        :format="'yyyy-MM-dd HH:mm'"
                        :enable-time-picker="true"
                        :time-picker-24-hour="true"
                        placeholder="Select date and time"
                        auto-apply
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Reason -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                    <Textarea
                        v-model="form.discus"
                        placeholder="Enter reason for appointment"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <Button :disabled="form.processing" @click="submit" class="bg-indigo-600 text-white hover:bg-indigo-700" size="sm">
                        <template v-if="form.processing">
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                            Submitting...
                        </template>
                        <template v-else>
                            <Save class="mr-2 h-4 w-4" />
                            Submit
                        </template>
                    </Button>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>SL</TableHead>
                            <TableHead>Discusition Details</TableHead>
                            <TableHead>Added Date</TableHead>
                            <TableHead>Added BY</TableHead>
                            
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(stud, index) in data.data" :key="stud.id ?? index">
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ stud.discus }}</TableCell>
                            <TableCell>{{ formatDate(stud.created_at) }}</TableCell>
                            <TableCell>{{ stud.user.name }}</TableCell>
                            
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="flex items-center justify-end space-x-2 py-4">
                    <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                        <label for="per-page" class="text-gray-600">Show:</label>
                        <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100]" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span>Showing {{ appoiment.from }} to {{ appoiment.to }} of {{ appoiment.total }} results</span>
                    </div>

                    <div class="space-x-2">
                        <Button
                            v-for="(link, index) in appoiment.links"
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
        </div>
    </StudentLayout>
</template>
