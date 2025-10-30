<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { Loader2, Save } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

export interface Conversation {
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
    student: { id: number; status: string; fname: string; lname: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
    conversation: Paginated<Conversation>;
}>();

const data = props.conversation;

const form = useForm({
    discus: '',
});

const submit = () => {
    form.post(route('studentConversations.store', { student: props.student.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: 'Conversation created successfully.',
            });

            // Reset + Refresh data smoothly
            setTimeout(() => {
                form.reset();
                router.visit(route('studentConversations.index', { student: props.student.id }), {
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
    });
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
        route('studentConversations.index', { student: props.student.id }),
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
                <h2 class="text-center text-xl font-semibold text-gray-800 dark:text-gray-100">Conversations</h2>

                <!-- Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Write your conversation </label>
                    <Textarea
                        v-model="form.discus"
                        :value="form.discus"
                        @input="form.discus = $event.target.value"
                        placeholder="Enter your conversation..."
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                    <p v-if="form.errors.discus" class="text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.discus }}
                    </p>
                </div>

                <!-- Submit -->
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
                        <span>Showing {{ conversation.from }} to {{ conversation.to }} of {{ conversation.total }} results</span>
                    </div>

                    <div class="space-x-2">
                        <Button
                            v-for="(link, index) in conversation.links"
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
