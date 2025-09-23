<script setup lang="ts">
import ProductLayout from '@/pages/allpages/Agency/Product/productlayout.vue';
const props = defineProps<{
   product: { id: number;status:string };
}>();
</script>

<template>
    <ProductLayout :product="props.product">
        <div class="space-y-4">
            <!-- Single Activity -->
            <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-4 shadow-sm">
                <p class="text-center">Document</p>
            </div>
        </div>
    </ProductLayout>
</template>
