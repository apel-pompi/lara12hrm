<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { type SidebarProps } from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';

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

import {
    BadgeCent,
    Book,
    Calculator,
    CalendarCog,
    CalendarX2,
    ClipboardCheck,
    ClipboardPlus,
    FileCog,
    MessageCircleOff,
    MonitorCog,
    School,
    Settings,
    Upload,
    User,
    UserCog,
    BookOpenCheck
} from 'lucide-vue-next';

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
            icon: Settings,
            items: [
                {
                    title: 'Users Setting',
                    href: '/roles',
                    icon: UserCog,
                },
                {
                    title: 'General Setting',
                    href: '/settings/profile',
                    icon: FileCog,
                },
                {
                    title: 'CRM Setting',
                    href: '/general',
                    icon: MonitorCog,
                },
                {
                    title: 'HRM Setting',
                    href: '/companyinfo',
                    icon: CalendarCog,
                },
                {
                    title: 'Accounts Setting',
                    href: '/accountssetting',
                    icon: BadgeCent,
                },
            ],
        },
    ],
};

const HRMenu = {
    navHRmenu: [
        {
            title: 'HRM',
            url: '#',
            icon: ClipboardPlus,
            items: [
                {
                    title: 'Holiday',
                    href: '/holidayHd',
                    icon: CalendarX2,
                },
                {
                    title: 'Personal Info',
                    href: '/personalinfo',
                    icon: User,
                },
            ],
        },
    ],
};

const mainmenu = [
    {
        route: 'leave.index',
        title: 'Leave Request',
        icon: MessageCircleOff,
    },
    {
        route: 'imports.showImportForm',
        title: 'Upload Lead',
        icon: Upload,
    },
    {
        route: 'student.index',
        title: 'Student',
        icon: Book,
    },
    {
        route: 'partner.index',
        title: 'Partners',
        icon: School,
    },
    {
        route: 'accounts.index',
        title: 'Accounts',
        icon: Calculator,
    },
];

const reportdata = {
    navReport: [
        {
            title: 'Reports',
            url: '#',
            icon: ClipboardCheck,
            items: [
                {
                    title: 'Lead Reports',
                    href: '/leadreports',
                    icon: BookOpenCheck,
                },
                {
                    title: 'HR Reports',
                    href: '/hrreports',
                    icon: ClipboardCheck,
                },
            ],
        },
    ],
};
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
                    <SidebarContent>
                        <NavMain :items="HRMenu.navHRmenu" />
                    </SidebarContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in mainmenu" :key="item.title">
                            <SidebarMenuButton asChild>
                                <Link :href="route(item.route)" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>

                    <SidebarContent>
                        <NavMain :items="reportdata.navReport" />
                    </SidebarContent>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
        <SidebarFooter class="border-sidebar-border/70 border-t p-3 text-center text-xs text-gray-500">
            © {{ year }} {{ company?.companyname }}
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
