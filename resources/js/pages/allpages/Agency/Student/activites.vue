<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string; };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <!-- Single Activity -->
            <!-- <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
               
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-500 font-semibold text-white">CW</div>

                
                <div class="flex-1">
                    <p class="text-sm">
                        <span class="font-semibold">Chirag Wadhwa</span>
                        <span class="ml-1 text-gray-600">started an application</span>
                    </p>
                    <p class="mt-2 font-medium text-gray-800">BA/BSc in Economics</p>
                    <p class="text-sm text-gray-500">Acadia University</p>
                </div>

                
                <div class="text-xs text-gray-500">19 Aug 2025, 07:27 PM</div>
            </div> -->

            
        </div>
    </StudentLayout>
</template>
