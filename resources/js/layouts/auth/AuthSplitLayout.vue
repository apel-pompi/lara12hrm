<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const loginimage = page.props.company.loginimage;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
        :style="{
            backgroundImage: `url('/storage/company/${loginimage}')`,
            backgroundPosition: 'center',
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
        }"
    >
        <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-r">
            <div class="absolute inset-0" />
            <Link :href="route('home')" class="relative z-20 items-center text-lg font-medium">
                <AppLogoIcon class="size-35 fill-current text-black" />
            </Link>
            <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg text-black">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-black">{{ quote.author }}</footer>
                </blockquote>
            </div>
        </div>
        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-87.5">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-muted-foreground text-sm" v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
#loginleft {
    background-image: var(--bg-image);
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}
</style>
