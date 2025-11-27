<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from "@/components/ui/sonner"
import { usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { watch, onMounted } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

// Handle initial flash message
onMounted(() => {
    const flash = page.props.flash;
    showToast(flash);
});

// Watch for navigation changes and flash updates
watch(() => page.props.flash, (newFlash) => {
    showToast(newFlash);
}, { deep: true });

const showToast = (flash: any) => {
    if (!flash.message) return;

    if (flash.error) {
        toast.error(flash.message, {
            duration: 5000,
            dismissible: true,
        });
    } else if (flash.success) {
        toast.success(flash.message, {
            duration: 3000,
            dismissible: true,
        });
    } else {
        toast(flash.message, {
            duration: 4000,
            dismissible: true,
        });
    }
};
</script>

<template>
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
    <Toaster />
</template>
