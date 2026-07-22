<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Bell, Mail, Menu } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);
</script>

<template>
    <header
        class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:border-slate-800 dark:bg-slate-950/95 dark:supports-[backdrop-filter]:bg-slate-950/60"
    >
        <div class="flex h-20 items-start justify-between px-4 py-3 sm:px-6 lg:px-8">
            <!-- Left Section: Logo & Company Name -->
            <div class="flex min-w-0 items-center gap-3">
                <!-- Sidebar Trigger for Mobile -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="h-9 w-9">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only">Navigation Menu</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <AppLogoIcon class="size-6 fill-current text-black dark:text-white" />
                            </SheetHeader>
                        </SheetContent>
                    </Sheet>
                </div>

                <!-- Logo & Company Name -->
                <Link :href="route('dashboard')" class="flex items-start gap-3 transition-opacity hover:opacity-80">
                    <!-- Logo Badge -->
                    <div
                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 text-sm font-bold text-white shadow-md dark:from-blue-700 dark:to-blue-800"
                    >
                        <AppLogoIcon class="h-6 w-6 fill-current" />
                    </div>
                    <!-- Company Name (larger, aligned top) -->
                    <div class="-mt-0.5 hidden flex-col gap-0.5 sm:flex">
                        <p class="text-lg leading-tight font-extrabold text-slate-900 dark:text-white">Gangchill Group</p>
                        <p class="text-xs leading-tight text-slate-500 dark:text-slate-400">Student Management System For Foreign Study</p>
                    </div>
                </Link>
            </div>

            <!-- Right Section: Actions & User Menu -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Notification Icons (hidden on mobile) -->
                <div class="hidden items-center gap-2 sm:flex">
                    <TooltipProvider :delay-duration="200">
                        <Tooltip>
                            <TooltipTrigger :as-child="true">
                                <Button variant="ghost" size="icon" class="relative h-9 w-9 hover:bg-slate-100 dark:hover:bg-slate-900">
                                    <Mail class="h-5 w-5 text-slate-600 dark:text-slate-400" />
                                    <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-orange-500"></span>
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent side="bottom">
                                <p>Messages</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>

                    <TooltipProvider :delay-duration="200">
                        <Tooltip>
                            <TooltipTrigger :as-child="true">
                                <Button variant="ghost" size="icon" class="relative h-9 w-9 hover:bg-slate-100 dark:hover:bg-slate-900">
                                    <Bell class="h-5 w-5 text-slate-600 dark:text-slate-400" />
                                    <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent side="bottom">
                                <p>Notifications</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </div>

                <!-- Divider -->
                <div class="hidden h-6 w-px bg-slate-200 sm:block dark:bg-slate-700"></div>

                <!-- User Profile Dropdown -->
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="relative h-10 w-auto rounded-lg px-2 transition-colors hover:bg-slate-100 sm:px-3 dark:hover:bg-slate-900"
                        >
                            <div class="flex items-center gap-2">
                                <Avatar class="h-8 w-8 border border-slate-200 dark:border-slate-700">
                                    <AvatarImage v-if="auth.user?.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                    <AvatarFallback class="bg-blue-600 text-sm font-semibold text-white">
                                        {{ getInitials(auth.user?.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="hidden flex-col items-start sm:flex">
                                    <p class="text-xs font-semibold text-slate-900 dark:text-white">
                                        {{ auth.user?.name }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Admin</p>
                                </div>
                            </div>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-56">
                        <UserMenuContent :user="auth.user" />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>

        <!-- Breadcrumb Section -->
        <div v-if="props.breadcrumbs.length > 1" class="border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900/50">
            <div class="flex h-12 items-center px-4 text-slate-600 sm:px-6 lg:px-8 dark:text-slate-400">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </header>
</template>
