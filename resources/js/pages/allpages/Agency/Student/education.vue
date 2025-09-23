<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
const props = defineProps<{
    student: { id: number;status:string };
}>();
</script>

<template>
    <StudentLayout :student="props.student">
        <div class="space-y-4">
            <!-- Single Activity -->
            <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <p class="text-center">Education</p>
            </div>
        </div>
    </StudentLayout>
</template>
