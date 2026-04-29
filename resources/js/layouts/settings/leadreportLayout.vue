<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { PanelLeft } from 'lucide-vue-next';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Employee wise Sales',
        href: '/leadreports/',
    },
    {
        title: 'Student Ledger',
        href: '/leadreports/ledger',
    },
    {
        title: 'Student Transaction',
        href: '/leadreports/transaction',
    },
    {
        title: 'Revenue Analysis',
        href: '/leadreports/revenue',
    },
    {
        title: 'Refund Summary',
        href: '/leadreports/refund',
    },
];

const page = usePage<{
    ziggy: {
        location: string;
    };
}>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
const activeItem = sidebarNavItems.find((item) => item.href === currentPath) ?? sidebarNavItems[0];
</script>

<template>
    <div class="min-h-screen w-full">
        <div class="flex min-h-screen flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-y-0 lg:space-x-12">
            <div class="lg:hidden">
                <div
                    class="m-3 flex items-center justify-between rounded-xl border border-white/60 bg-white/80 p-3 shadow-sm backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/70"
                >
                    <div>
                        <p class="text-muted-foreground text-xs font-medium tracking-[0.2em] uppercase">Reports Menu</p>
                        <p class="text-foreground text-sm font-semibold">{{ activeItem.title }}</p>
                    </div>
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="outline" size="icon" class="rounded-lg">
                                <PanelLeft class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-0 sm:w-[340px]">
                            <SheetHeader class="border-b px-5 py-4 text-left">
                                <SheetTitle>Lead Reports</SheetTitle>
                            </SheetHeader>
                            <nav class="flex flex-col gap-2 p-4">
                                <Button
                                    v-for="item in sidebarNavItems"
                                    :key="item.href"
                                    variant="ghost"
                                    :class="['w-full justify-start rounded-lg px-3 py-2 text-left', { 'bg-muted': currentPath === item.href }]"
                                    as-child
                                >
                                    <Link :href="item.href">
                                        {{ item.title }}
                                    </Link>
                                </Button>
                            </nav>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>

            <aside class="hidden w-full max-w-xl bg-white/80 text-center backdrop-blur-sm lg:block lg:w-48 dark:border-gray-800 dark:bg-gray-900/70">
                <Heading title="Sales Reports" description="Manage your sales reports" />
                <nav class="flex flex-col items-center space-y-1 space-x-0">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <section
                class="dark:bg-gray-9002 flex-1 border border-white/60 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
            >
                <slot />
            </section>
        </div>
    </div>
</template>
