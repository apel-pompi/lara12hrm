<script setup lang="ts">
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';
const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string };
    application: {
        id: number;
        name: string;
        status: string;
        stage: string;
        created_at: string;
        updated_at: string;
        workflow: { id: number; name?: string };
        partner_branch: { id: number; branch_name: string; partner: { id: number; name: string } };
        product: { id: number; name: string };
        user: { id: number; name: string };
    };
    appdoclist: { id: number; created_at: string; updated_at: string; stage: { stagename: string }; user: { name: string } };
    productFeesHd: any[];
    totalNetAmount: number;
    total_payable: number;
    total_income: number;
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

</script>

<template>
    <ApplicationLayout
        :student="props.student"
        :application="props.application"
        :totalNetAmount="props.totalNetAmount"
        :total_payable="props.total_payable"
        :total_income="props.total_income"
        :studentService="studentService"
    >
        <!-- Activity timeline -->
        <div class="space-y-4 p-4">
            <!-- collapsed accordion style statuses -->
            Application Payments
        </div>
        
    </ApplicationLayout>
</template>
