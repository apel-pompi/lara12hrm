<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import NavUser from '@/components/NavUser.vue';
import { ref, onMounted, onUnmounted } from "vue";

const time = ref<string>("");
const updateTime = () => {
    const now = new Date();

    // Format time: HH:MM:SS
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, "0");
    const seconds = String(now.getSeconds()).padStart(2, "0");

    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12; // convert 0 → 12

    time.value = `${String(hours).padStart(2, "0")}:${minutes}:${seconds} ${ampm}`;
};
let interval: number;
onMounted(() => {
    updateTime();
    interval = window.setInterval(updateTime, 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});




withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(),{
    breadcrumbs:()=>[]
});
</script>

<template>
    <header
        class="grid grid-cols-3 h-16 shrink-0 items-center border-b border-gray-800 
           px-4 md:px-6 transition-[width,height] ease-linear 
           group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="flex justify-center items-center">
            <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl 
                   font-mono font-bold tracking-widest text-black 
                   px-2 sm:px-4 md:px-6 py-1 md:py-2">
            {{ time }}
        </span>
        </div>
        <div class="flex justify-end items-center gap-2 ml-auto">
            <NavUser />
        </div>
    </header>
</template>
