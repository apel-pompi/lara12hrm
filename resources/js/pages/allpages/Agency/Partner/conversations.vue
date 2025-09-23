<script setup lang="ts">
import PartnerLayout from '@/pages/allpages/Agency/Partner/partnerlayout.vue';
const props = defineProps<{
   partner: { id: number;status:string };
}>();
</script>

<template>
    <PartnerLayout :partner="props.partner">
        <div class="space-y-4">
            <!-- Single Activity -->
            <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <p class="text-center">conversations</p>
            </div>
        </div>
    </PartnerLayout>
</template>
