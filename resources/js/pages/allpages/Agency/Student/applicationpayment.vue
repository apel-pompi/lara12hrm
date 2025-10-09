<script setup lang="ts">
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';

const props = defineProps<{ 
    student: any, application: any
    totalNetAmount: number,
    total_payable: number,
    total_income: number,
 }>();
</script>

<template>
    <ApplicationLayout
    :student="props.student"
    :application="props.application"
    :totalNetAmount="props.totalNetAmount"
    :total_payable="props.total_payable"
    :total_income="props.total_income">
        <!-- Activity timeline -->
        <div class="space-y-4 p-4">
            <!-- collapsed accordion style statuses -->
            Aplication Payments
        </div>
    </ApplicationLayout>
</template>
