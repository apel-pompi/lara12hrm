<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    student: { id: number; fname: string; lname: string; status: string };
}>();

const navMenu = [
    {
        title: 'Invoice',
        href: route('studentAccounts.index',props.student.id),
    },
    {
        title: 'Refund',
        href: route('studentAccounts.return',props.student.id),
    }
];

const NavItems = computed<NavItem[]>(() => {
    return navMenu;
});
</script>

<template>
    <div class="flex flex-col space-y-2 py-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div class="text-sm font-semibold text-gray-800">
            <Button class="mr-1 dark:bg-black" v-for="item in NavItems" :key="item.href" variant="link" as-child>
                <Link :href="item.href">
                    {{ item.title }}
                </Link>
            </Button>
        </div>
    </div>
   
    <section class="bg-white p-4 shadow dark:bg-gray-900">
        <slot />
    </section>
</template>
