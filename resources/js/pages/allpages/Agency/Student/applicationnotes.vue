<script setup lang="ts">
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';

const props = defineProps<{ student: any, application: any }>();
</script>

<template>
    <ApplicationLayout :student="props.student" :application="props.application">
        <!-- Activity timeline -->
        <div class="space-y-4 p-4">
            <!-- collapsed accordion style statuses -->
            Application Notes
        </div>
    </ApplicationLayout>
</template>
