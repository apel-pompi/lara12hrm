<script setup lang="ts">
import { type SidebarProps } from '@/components/ui/sidebar'
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import NavMain from '@/components/NavMain.vue'
import NavUser from '@/components/NavUser.vue'
import NavFooter from '@/components/NavFooter.vue';

import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenuItem,
  SidebarMenuButton

} from '@/components/ui/sidebar'

import {
  SquareTerminal,
  Settings,
} from 'lucide-vue-next'

const props = withDefaults(defineProps<SidebarProps>(), {
  collapsible: 'icon',
})

// This is sample data.
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
  
}
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
            <NavMain :items="data.navMain" />
        </SidebarContent>
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
