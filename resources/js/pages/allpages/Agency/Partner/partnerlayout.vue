<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Archive, Undo2, CornerDownLeft, Mail, MessageCircleMore, SquarePen, } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Activity', href: '/partner' }];

export interface Partner {
    id: number;
    name: string;
}

const props = defineProps<{
    partner: { id: number; name: string; user: { name: string } };
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

const updateStatus = (active: number) => {
    router.put(
        route('partner.updateStatus', props.partner.id),
        {
            active,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Partner status update');
            },
        },
    );
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="app-page">
            <div class="flex flex-col gap-6 p-4 lg:flex-row">
                <!-- LEFT SIDEBAR -->
                <aside class="flex w-full flex-col gap-6 rounded-xl bg-white p-4 shadow lg:w-1/4 dark:bg-gray-900">
                    <!-- Profile -->
                    <div class="flex flex-col items-center border-b pb-5 text-center">
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 text-xl font-bold dark:bg-gray-700">
                            {{ (props.partner.name?.charAt(0) ?? '').toUpperCase() }}</div>
                        <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.partner.name }}
                        </h2>


                        <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <MessageCircleMore />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">
                                    Compose SMS
                                </span>
                            </div>

                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <Mail />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">
                                    Compose email
                                </span>
                            </div>

                            <!-- <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <SquarePen />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Edit
                                </span>
                            </div> -->

                            <div>
                                <div v-if="props.partner.active == 1">
                                    <div class="group relative">
                                        <!-- Archive Button -->
                                        <button @click="updateStatus(0)"
                                            class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                            <Archive />
                                        </button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">
                                            Archive
                                        </span>
                                    </div>
                                </div>

                                <div v-else>
                                    <div class="group relative">
                                        <!-- Restore Button -->
                                        <button @click="updateStatus(1)"
                                            class="cursor-pointer text-[10px] uppercase hover:text-gray-700">
                                            <Undo2 />
                                        </button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100">
                                            Restore
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Forecast -->
                    <!-- Personal Details -->
                    <div class="border-b pb-5 text-sm">
                        <h4 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">General Information:</h4>
                        <p>Workflow <span class="text-gray-500">{{ props.partner.workflow_names }}</span></p>

                        <p class="mt-2 text-gray-600">Added From: <span class="font-medium">{{ props.partner?.user?.name
                                }}</span></p>
                    </div>
                    <div class="pt-2">
                        <Button size="sm"
                            class="w-full flex items-center justify-center gap-2 border-0 bg-gradient-to-r from-blue-500 to-indigo-500 text-white hover:from-blue-600 hover:to-indigo-600 dark:from-blue-600 dark:to-indigo-600 shadow-md hover:shadow-lg transition-all"
                            @click="goToPartner">
                            <ArrowLeft class="h-4 w-4" />
                            <span>Back to Partners</span>
                        </Button>
                    </div>
                </aside>

                <!-- MAIN CONTENT -->
                <main class="flex flex-1 flex-col gap-6">
                    <!-- Tabs -->
                    <nav class="text-md flex flex-wrap gap-4 border-b bg-white p-6 font-medium">
                        <div
                            class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border bg-gray-100 p-3">
                            <Button class="p-4 m-1 dark:bg-black" v-for="item in sidebarNavItems" :key="item.href"
                                variant="ghost" as-child>
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
