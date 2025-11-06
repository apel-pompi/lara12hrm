<script setup lang="ts">
import { Button } from '@/components/ui/button';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { type NavItem } from '@/types';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';

import { BookPlus, CloudUpload, Mail, MoveLeft, MoveRight, NotepadText, Users, X } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import ApplicationSidebar from './ApplicationSidebar.vue';
import { nextTick } from 'vue';

const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string };
    application: {
        id: number;
        name: string;
        status: string;
        created_at: string;
        updated_at: string;
        workflow: { id: number; name?: string };
        partner_branch: { id: number; branch_name: string; partner: { id: number; name: string } };
        product: { id: number; name: string };
        stage: { id: number; stagename: string };
        user: { id: number; name: string };
    };
    totalNetAmount: number;
    total_payable: number;
    total_income: number;
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const appdata = props.application;

const created_date = new Date(props.application.created_at);
const created_month = created_date.toLocaleDateString('en-US', { month: 'short' }); // Month Name
const created_day = created_date.toLocaleDateString('en-US', { day: '2-digit' }); // Day
const created_year = created_date.toLocaleDateString('en-US', { year: 'numeric' }); // YEar

const sidebarNavItems: NavItem[] = [
    {
        title: 'Activities',
        href: route('studentApplication.appActivities', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
    },
    {
        title: 'Documents',
        href: route('studentApplication.documentApplication', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
    },
    {
        title: 'Notes',
        href: route('studentApplication.notesApplication', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
    },
    {
        title: 'Tasks',
        href: route('studentApplication.tasksApplication', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
    },
    {
        title: 'Payment Schedule',
        href: route('studentApplication.paymentApplication', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
    },
];

const page = usePage<{
    ziggy: {
        location: string;
    };
}>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';

const form = useForm({
    id: null as number | null,
    data: '',
});

const nextStep = () => {
    form.post(
        route('studentApplication.documentNextStep', [props.student.id, appdata.id]),
        {
            preserveScroll: true,
            onSuccess: async () => {
                await router.reload({ only: ['flash'] });
                await nextTick();

                const message = usePage().props.flash?.message;
                if (message) {
                    toast('Success', { description: message });
                }
                setTimeout(() => {
                    form.reset();
                    router.visit(
                        route('studentApplication.documentApplication', [props.student.id, appdata.id]),
                        {
                            only: ['student_applications'],
                            preserveScroll: true,
                            preserveState: false,
                        }
                    );
                }, 300);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', { description: firstError });
            },
        }
    );
};

const backStep = () => {
    form.post(
        route('studentApplication.documentBackStep', [props.student.id, appdata.id]),
        {
            preserveScroll: true,
            onSuccess: async () => {
                await router.reload({ only: ['flash'] });
                await nextTick();

                const message = usePage().props.flash?.message;
                if (message) {
                    toast('Success', { description: message });
                }
                setTimeout(() => {
                    form.reset();
                    router.visit(
                        route('studentApplication.documentApplication', [props.student.id, appdata.id]),
                        {
                            only: ['student_applications'],
                            preserveScroll: true,
                            preserveState: false,
                        }
                    );
                }, 300);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', { description: firstError });
            },
        }
    );
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <div class="min-h-screen bg-gray-50 p-6 text-gray-800">
                <div class="border bg-white shadow-sm">
                    <!-- Header Row -->
                    <div class="flex flex-col gap-3 border-b px-4 py-3 md:flex-row md:items-center md:justify-between">
                        <!-- Status -->
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm font-semibold text-blue-600">
                                {{ appdata.status }}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <!-- <Button variant="outline" size="sm"> <SquarePen class="mr-1 h-4 w-4" /> Edit Partner & Product </Button> -->

                            <Button variant="outline" size="sm" @click="backStep"> <MoveLeft class="mr-1 h-4 w-4" /> Back to Previous Stage </Button>
                            <Button variant="secondary" size="sm" @click="nextStep">
                                Proceed to Next Stage <MoveRight class="ml-1 h-4 w-4" />
                            </Button>
                            <Button variant="destructive" size="sm"> <X class="mr-1 h-4 w-4" /> Discontinue </Button>
                        </div>
                    </div>

                    <!-- Application Info -->
                    <div class="grid grid-cols-2 gap-4 p-4 text-xs md:grid-cols-4">
                        <div>
                            <p class="text-gray-500">Course:</p>
                            <p class="font-medium">{{ appdata.product.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">University:</p>
                            <p class="font-medium">{{ appdata.partner_branch.partner.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Partner's Client Id:</p>
                            <p class="font-medium">{{ appdata.partner_branch.partner.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Application Id:</p>
                            <p class="font-medium">{{ appdata.id }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Branch:</p>
                            <p class="font-medium">{{ appdata.partner_branch.branch_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Workflow:</p>
                            <p class="font-medium">{{ appdata.workflow.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Started at:</p>
                            <p class="font-medium">
                                {{ new Date(appdata.created_at).toISOString().split('T')[0] }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Last Updated:</p>
                            <p class="font-medium">
                                {{ new Date(appdata.updated_at).toISOString().split('T')[0] }}
                            </p>
                        </div>
                    </div>

                    <!-- Stage + Progress -->
                    <div class="flex flex-col items-start justify-between gap-3 border-t px-4 py-3 md:flex-row md:items-center">
                        <div class="text-sm">
                            <span class="text-gray-500">Current Stage:</span>
                            <span class="ml-1 font-medium text-green-600">{{ appdata.stage.stagename }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Overall Progress:</span>
                            <div class="flex h-10 w-10 items-center justify-center rounded-full border text-xs font-medium">0%</div>
                        </div>
                    </div>

                    <!-- Super/Sub Agent Section -->
                    <div class="flex flex-col gap-4 border-t px-4 py-3 lg:flex-row lg:items-center">
                        <!-- Super Agent -->
                        <div>
                            <p class="text-xs text-gray-500">Super Agent</p>
                            <button class="rounded border border-blue-400 px-3 py-1 text-xs text-blue-500 hover:bg-blue-50">Super Agent</button>
                        </div>

                        <!-- Sub Agent -->
                        <div>
                            <p class="text-xs text-gray-500">Sub Agent</p>
                            <button class="rounded border border-blue-400 px-3 py-1 text-xs text-blue-500 hover:bg-blue-50">Sub Agent</button>
                        </div>

                        <!-- Assignees -->
                        <!-- <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-gray-500 uppercase">Started by</span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-200 text-[10px] font-semibold"> M </span>

                            <span class="ml-2 text-xs text-gray-500 uppercase">Assignees</span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-200 text-[10px] font-semibold"> MR </span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs">
                                <Users class="h-4 w-4" />
                            </span>
                        </div> -->

                        <!-- View More -->
                        <div class="lg:ml-auto">
                            <button class="rounded border border-blue-400 px-3 py-1 text-xs text-blue-600 hover:bg-blue-50">
                                View Other Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Two column layout -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left column (main) -->
                    <div class="space-y-4 lg:col-span-2">
                        <!-- Tabs -->
                        <div class="border bg-white shadow-sm">
                            <!-- Header -->
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b px-4 py-3">
                                <!-- Navigation -->
                                <div class="flex w-full flex-wrap items-center justify-between gap-3">
                                    <!-- Menu -->
                                    <div class="overflow-x-auto">
                                        <nav class="flex min-w-max space-x-2 text-sm">
                                            <Link
                                                v-for="item in sidebarNavItems"
                                                :key="item.href"
                                                :href="item.href"
                                                class="inline-flex items-center justify-center rounded-md border px-3 py-1 text-sm font-medium transition"
                                                :class="
                                                    currentPath === item.href
                                                        ? 'border-blue-500 bg-blue-500 text-white'
                                                        : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                                                "
                                            >
                                                {{ item.title }}
                                            </Link>
                                        </nav>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 text-sm text-slate-500">
                                        <Button variant="outline" size="sm"><NotepadText /></Button>
                                        <Button variant="outline" size="sm"><BookPlus /></Button>
                                        <Button variant="outline" size="sm"><CloudUpload /></Button>
                                        <Button variant="outline" size="sm"><Mail /></Button>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity timeline -->
                            <div class="space-y-4 p-4">
                                <slot></slot>
                            </div>
                        </div>
                    </div>

                    <!-- Right column (sidebar) -->
                    <ApplicationSidebar
                        :totalNetAmount="props.totalNetAmount"
                        :total_payable="props.total_payable"
                        :total_income="props.total_income"
                        :created_month="created_month"
                        :created_day="created_day"
                        :created_year="created_year"
                        :studentService="studentService"
                    />
                </div>
            </div>
        </div>
    </StudentLayout>
</template>
