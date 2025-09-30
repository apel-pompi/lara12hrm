<script setup lang="ts">
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';


const props = defineProps<{ 
    student: any; 
    application: any;
}>();

</script>

<template>
    <ApplicationLayout :student="props.student" :application="props.application">
        <!-- Activity timeline -->
        <div class="space-y-4 p-4">
            <!-- collapsed accordion style statuses -->
            <div v-for="stage in props.application.workflow.stages || []" :key="stage.id" class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center gap-2 border-b border-gray-200 px-6 py-4">
                    <div class="h-3 w-3 rounded-full bg-blue-500"></div>
                    <h2 class="text-sm font-semibold text-gray-800">{{ stage.stagename }}</h2>
                </div>

                <div class="space-y-6 border-b p-6">
                    <!-- Timeline Item -->
                    <div class="relative flex items-start gap-4">
                        <!-- Left line & dot -->
                        <div class="flex flex-col items-center">
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-blue-500 shadow"></div>
                            <div class="mt-1 w-px flex-1 bg-gray-200"></div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <p class="text-sm leading-5">
                                <span class="font-semibold text-gray-800">M Khalil Rahman</span>
                                <span class="text-gray-600"> updated the application start date :</span>
                                <span class="font-medium text-gray-900">2025-09-01</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">17 Wed, Sep 2025 12:12 PM</p>
                        </div>
                    </div>

                    <!-- Timeline Item -->
                    <div class="relative flex items-start gap-4">
                        <!-- Left line & dot -->
                        <div class="flex flex-col items-center">
                            <div class="h-3 w-3 rounded-full border-2 border-white bg-blue-500 shadow"></div>
                            <div class="mt-1 w-px flex-1 bg-gray-200"></div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <p class="text-sm leading-5">
                                <span class="font-semibold text-gray-800">M Khalil Rahman</span>
                                <span class="text-gray-600"> updated the application start date :</span>
                                <span class="font-medium text-gray-900">2025-09-01</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">17 Wed, Sep 2025 12:12 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ApplicationLayout>
</template>
