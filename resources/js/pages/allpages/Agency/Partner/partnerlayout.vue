<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Archive, Coffee, CornerDownLeft, Flame, Mail, MessageCircleMore, Snowflake, SquarePen, TriangleAlert } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Activity', href: '/partner' }];

export interface Partner {
    id: number;
    name: string;
}

const props = defineProps<{
    partner: { id: number };
}>();

const sidebarNavItems = computed<NavItem[]>(() => {
    if (!props.partner) return [];

    return [
        {
            title: 'Applications',
            href: route('PartnerActivities.application', props.partner.id),
        },
        {
            title: 'Products',
            href: route('PartnerActivities.product', props.partner.id),
        },
        {
            title: 'Branches',
            href: route('PartnerActivities.branch', props.partner.id),
        },
        {
            title: 'Aggrements',
            href: route('PartnerActivities.aggrements', props.partner.id),
        },
        {
            title: 'Contacts',
            href: route('PartnerActivities.contacts', props.partner.id),
        },
        {
            title: 'Notes & Terms',
            href: route('PartnerActivities.notes', props.partner.id),
        },
        {
            title: 'Documents',
            href: route('PartnerActivities.documents', props.partner.id),
        },
        {
            title: 'Appoinments',
            href: route('PartnerActivities.appoinments', props.partner.id),
        },
        {
            title: 'Accounts',
            href: route('PartnerActivities.accounts', props.partner.id),
        },
        {
            title: 'Conversations',
            href: route('PartnerActivities.conversations', props.partner.id),
        },
        {
            title: 'Tasks',
            href: route('PartnerActivities.tasks', props.partner.id),
        },
        {
            title: "other's Information",
            href: route('PartnerActivities.others', props.partner.id),
        },
        {
            title: 'Promotions',
            href: route('PartnerActivities.promotions', props.partner.id),
        },
    ];
});

const goToPartner = () => {
    router.visit('/partner');
};

const updateRate = (status: number) => {
    router.put(
        route('studentActivities.updateRate', props.partner.id),
        { 
            partner_id:props.partner.id,
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
                    <Heading title="Partner Actvities" description="Manage your partner activities and account settings" />
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" @click="goToPartner"><CornerDownLeft></CornerDownLeft> Back</Button>
                </div>
            </div>
            <div class="flex flex-col gap-6 p-4 lg:flex-row">
                <!-- LEFT SIDEBAR -->
                <aside class="flex w-full flex-col gap-6 rounded-xl bg-white p-4 shadow lg:w-1/4 dark:bg-gray-900">
                    <!-- Profile -->
                    <div class="flex flex-col items-center border-b pb-5 text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 text-xl font-bold dark:bg-gray-700">W</div>
                        <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">World University Of Bangladesh</h2>

                        
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
                    <!-- Personal Details -->
                    <div class="border-b pb-5 text-sm">
                        <h4 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">General Information:</h4>
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
