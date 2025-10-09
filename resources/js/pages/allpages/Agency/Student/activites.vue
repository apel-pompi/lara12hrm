<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string; };
}>();
//console.log(props.user);
</script>

<template>
    <StudentLayout :student="props.student">
        <div class="space-y-4">
            <!-- Single Activity -->
            <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <!-- Avatar -->
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-500 font-semibold text-white">CW</div>

                <!-- Content -->
                <div class="flex-1">
                    <p class="text-sm">
                        <span class="font-semibold">Chirag Wadhwa</span>
                        <span class="ml-1 text-gray-600">started an application</span>
                    </p>
                    <p class="mt-2 font-medium text-gray-800">BA/BSc in Economics</p>
                    <p class="text-sm text-gray-500">Acadia University</p>
                </div>

                <!-- Date -->
                <div class="text-xs text-gray-500">19 Aug 2025, 07:27 PM</div>
            </div>

            <!-- Another Activity -->
            <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-500 font-semibold text-white">MR</div>

                <div class="flex-1">
                    <p class="text-sm">
                        <span class="font-semibold">M Khalil Rahman</span>
                        <span class="ml-1 text-gray-600">completed an application</span>
                    </p>
                    <p class="mt-2 font-medium text-gray-800">Bachelor of Business (Honours) in International Business and Marketing</p>
                    <p class="text-sm text-gray-500">Taylors University Malaysia</p>
                </div>

                <div class="text-xs text-gray-500">18 Aug 2025, 06:11 PM</div>
            </div>
        </div>
    </StudentLayout>
</template>
