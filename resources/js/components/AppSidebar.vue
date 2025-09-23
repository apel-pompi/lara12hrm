<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { type SidebarProps } from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { CalendarX2, MonitorX, Settings, SquareTerminal, User } from 'lucide-vue-next';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

interface Company {
  companyname: string;
}

interface Auth {
  company: Company[];
}

const year = new Date().getFullYear();
const page = usePage<{ auth: Auth }>();
const company = page.props.auth.company?.[0];

const data = {
    navMain: [
        {
            title: 'Master Setup',
            url: '#',
            icon: SquareTerminal,
            items: [
                {
                    title: 'General Setting',
                    href: '/settings/profile',
                },
                {
                    title: 'Agency Setting',
                    href: '/general',
                },
                {
                    title: 'HRM Setting',
                    href: '/branch',
                },
                {
                    title: 'Accounts Setting',
                    href: '/settings/profile',
                },
                {
                    title: 'Users Setting',
                    href: '/roles',
                },
                
            ],
        },
        
    ],
};

const menuholiday = [
    {
        title: 'Holiday',
        icon: CalendarX2,
    },
    
];

const menupersonal = [
    {
        title:'Personal Info',
        icon:User
    }
];
const menuleave = [
    {
        route:'leave.index',
        title:'Leave Request',
        icon:MonitorX
    },
    {
        route:'student.index',
        title: 'Student',
        icon:MonitorX
    },
    {
        route:'partner.index',
        title: 'Partners',
        icon:MonitorX
    },
    {
        route:'product.index',
        title: 'Product',
        icon:MonitorX
    }
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
                <SidebarGroupContent>
                    <SidebarContent>
                        <NavMain :items="data.navMain" />
                    </SidebarContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in menuholiday" :key="item.title">
                            <SidebarMenuButton asChild>
                                <Link :href="route('holidayHd.index')" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in menupersonal" :key="item.title">
                            <SidebarMenuButton asChild>
                                <Link :href="route('personalinfo.index')" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in menuleave" :key="item.title">
                            <SidebarMenuButton asChild>
                                <Link :href="route(item.route)" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
        <SidebarFooter class="border-t border-sidebar-border/70 p-3 text-xs text-gray-500 text-center">
            © {{ year }} {{ company?.companyname }}
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
