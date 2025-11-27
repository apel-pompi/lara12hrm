<script setup lang="ts">
import ApplicationLayout from './applicationLayout.vue';


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
            <div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">
                <!-- <div class="flex items-center gap-2 border-b border-gray-200 px-6 py-4">
                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                    <h2 class="text-sm font-semibold text-gray-800"></h2>
                </div> -->

                <div class="space-y-6 border-b p-6">
                    <!-- Timeline Item -->
                    <div class="relative flex items-start gap-4">
                        <!-- Left line & dot -->
                        <!-- <div class="flex flex-col items-center">
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-blue-500 shadow"></div>
                            <div class="mt-1 w-px flex-1 bg-gray-200"></div>
                        </div> -->

                        <!-- Content -->
                        <!-- <div class="flex-1">
                            <p class="text-sm leading-5">
                                <span class="font-semibold text-gray-800">Ma. Ashrafur Rahmnan</span>
                                <span class="text-gray-600"> updated the application start date :</span>
                                <span class="font-medium text-gray-900"></span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400"></p>
                        </div> -->
                    </div>

                    <!-- Timeline Item -->
                    <div class="relative flex items-start gap-4">
                        <!-- Left line & dot -->
                        <!-- <div class="flex flex-col items-center">
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-blue-500 shadow"></div>
                            <div class="mt-1 w-px flex-1 bg-gray-200"></div>
                        </div> -->

                        <!-- Content -->
                        <!-- <div class="flex-1">
                            <p class="text-sm leading-5">
                                <span class="font-semibold text-gray-800">Md. Ashrafur Rahman</span>
                                <span class="text-gray-600"> updated the application start date :</span>
                                <span class="font-medium text-gray-900">12-12-2025</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400"></p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </ApplicationLayout>
</template>
