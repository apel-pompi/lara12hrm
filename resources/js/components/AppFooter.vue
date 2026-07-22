<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

interface Company {
    companyname: string;
}

interface Auth {
    company?: Company[];
}

const year = new Date().getFullYear();
const page = usePage<{ auth?: Auth }>();
const company = page.props.auth?.company?.[0];
</script>

<template>
    <footer class="sticky bottom-0 z-10 border-t bg-background/95 backdrop-blur p-3 text-center text-xs text-gray-500">
        © {{ year }} {{ company?.companyname }}
    </footer>
</template>
