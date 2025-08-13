<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { type SidebarProps } from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { CalendarX2, Settings, SquareTerminal } from 'lucide-vue-next';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

const data = {
    navMain: [
        {
            title: 'Master Setup',
            url: '#',
            icon: SquareTerminal,
            items: [
                {
                    title: 'Branch Master',
                    href: '/branch',
                },
                {
                    title: 'Department',
                    href: '/department',
                },
                {
                    title: 'Designation',
                    href: '/designation',
                },
                {
                    title: 'Leave Plan',
                    href: '/leaveplan',
                },
                {
                    title: 'Attendance Setting',
                    href: '/attensetting',
                },
            ],
        },
        {
            title: 'Setting',
            url: '#',
            icon: Settings,
            items: [
                {
                    title: 'Role',
                    href: '/roles',
                },
                {
                    title: 'User Permission',
                    href: '/userpermission',
                },
                {
                    title: 'Company',
                    href: '/companyinfo',
                },
            ],
        },
    ],
};

const menuitems = [
    {
        title: 'Holiday',
        icon: CalendarX2,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" v-bind="props">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Application</SidebarGroupLabel>
                <SidebarGroupContent>
                    <SidebarContent>
                        <NavMain :items="data.navMain" />
                    </SidebarContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in menuitems" :key="item.title">
                            <SidebarMenuButton asChild>
                                <Link :href="route('holidayHd.index')" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
