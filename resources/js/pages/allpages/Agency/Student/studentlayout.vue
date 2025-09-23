<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Archive, Coffee, CornerDownLeft, Flame, Mail, MessageCircleMore, Snowflake, SquarePen, TriangleAlert } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Activity', href: '/student' }];

export interface Student {
    id: number;
    name: string;
}

const props = defineProps<{
    student: { id: number; status: string };
}>();
console.log(props.student.status);
const lead = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentApplication.index', props.student.id),
    },
];

const prospect = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Quotations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentApplication.index', props.student.id),
    },
];
const client = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Quoations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentApplication.index', props.student.id),
    },
];

const archive = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Quoations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentApplication.index', props.student.id),
    },
]


const sidebarNavItems = computed<NavItem[]>(() => {
    if (!props.student) return [];
    
    if (props.student.status === 1) {
        return lead;
    } else if (props.student.status === 2) {
        return prospect;
    } else if (props.student.status === 3) {
        return client;
    } else {
        return archive;
    }
    
});

const goToStudent = () => {
    router.visit('/student');
};

const updateRate = (status: number) => {
    router.put(
        route('studentActivities.updateRate', props.student.id),
        {
            student_id: props.student.id,
            status,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Partner  status update');
            },
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border bg-gray-100 px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 pt-4 pl-4">
                <div class="flex-1 text-sm">
                    <Heading title="Student Actvities" description="Manage your student activities and account settings" />
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" @click="goToStudent"><CornerDownLeft></CornerDownLeft> Back</Button>
                </div>
            </div>
            <div class="flex flex-col gap-6 p-4 lg:flex-row">
                <!-- LEFT SIDEBAR -->
                <aside class="flex w-full flex-col gap-6 rounded-xl bg-white p-4 shadow lg:w-1/4 dark:bg-gray-900">
                    <!-- Profile -->
                    <div class="flex flex-col items-center border-b pb-5 text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 text-xl font-bold dark:bg-gray-700">AR</div>
                        <span class="mt-2 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800"> Prospect </span>
                        <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">Md. Ashrafur Rahman</h2>

                        <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                            <button @click="updateRate(1)" title="Lost" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                <Snowflake></Snowflake>Lost
                            </button>
                            <button @click="updateRate(2)" title="Cold" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                <Coffee></Coffee>Cold
                            </button>
                            <button @click="updateRate(3)" title="Warm" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                <TriangleAlert></TriangleAlert>Warm
                            </button>
                            <button @click="updateRate(4)" title="Hot" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                <Flame></Flame>Hot
                            </button>
                        </div>
                        <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <MessageCircleMore />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Compose SMS
                                </span>
                            </div>

                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <Mail />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Compose email
                                </span>
                            </div>

                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <SquarePen />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Edit
                                </span>
                            </div>

                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <Archive />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Archive
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Forecast -->
                    <div class="border-b pb-5">
                        <h3 class="pb-5 text-[12px] font-semibold text-gray-700 dark:text-gray-300">APPLICATION SALES FORECAST</h3>
                        <p class="mt-1 flex items-center pb-5 text-sm"><span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span> 0.00 CAD</p>
                        <h3 class="mt-2 pb-5 text-[12px] font-semibold text-gray-700 dark:text-gray-300">INTERESTED SERVICES SALES FORECAST</h3>
                        <p class="mt-1 flex items-center pb-5 text-sm"><span class="mr-2 h-2 w-2 rounded-full bg-green-500"></span> 0.00 CAD</p>
                    </div>

                    <!-- Personal Details -->
                    <div class="border-b pb-5 text-sm">
                        <h4 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">PERSONAL DETAILS:</h4>
                        <p>Tag(s): <span class="text-gray-500">-</span></p>

                        <div class="mt-2 flex items-center gap-2">
                            <Button class="rounded-lg bg-blue-500 px-3 py-1 text-xs text-white shadow hover:bg-blue-600">Activate</Button>
                            <Button class="rounded-lg bg-gray-300 px-3 py-1 text-xs text-gray-700 shadow hover:bg-gray-400">De-Activate</Button>
                        </div>

                        <p class="mt-2 text-gray-600">Added From: <span class="font-medium">Testing 1</span></p>
                        <p class="text-gray-600">Internal Id: <span class="font-medium">12345678-8876</span></p>
                    </div>
                </aside>

                <!-- MAIN CONTENT -->
                <main class="flex flex-1 flex-col gap-6">
                    <!-- Tabs -->
                    <nav class="text-md flex flex-wrap gap-4 border-b bg-white p-6 font-medium">
                        <div class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border bg-gray-100 p-3">
                            <Button v-for="item in sidebarNavItems" :key="item.href" variant="ghost" as-child>
                                <Link :href="item.href">
                                    {{ item.title }}
                                </Link>
                            </Button>
                        </div>
                    </nav>

                    <section class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                        <slot />
                    </section>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
