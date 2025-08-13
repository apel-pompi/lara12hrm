<script setup lang="ts">
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
    <div class="flex items-center">
        <!-- Logo -->
        <img
            v-if="company?.companylogo"
            :src="`/storage/company/${company.companylogo}`"
            alt="Company Logo"
            class="w-10 object-cover"
        />
        
    </div>
</template>
