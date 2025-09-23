<script setup lang="ts">
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
const props = defineProps<{
    student: { id: number;status:string };
    activity:[]
}>();
console.log(props.student)
</script>

<template>
    <StudentLayout :student="props.student">
        <div class="space-y-4">
           
        </div>
    </StudentLayout>
</template>
