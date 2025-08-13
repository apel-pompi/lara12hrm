<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

import { usePage } from '@inertiajs/vue3';

interface Company {
  companyname: string;
  companylogo: string;
}

interface Auth {
  company: Company[];
}

const page = usePage<{ auth: Auth }>();
const company = page.props.auth.company?.[0];


</script>

<template>
    <AppLogoIcon class="fill-current text-white dark:text-black" />
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate font-semibold leading-none">{{ company?.companyname }}</span>
    </div>
</template>
