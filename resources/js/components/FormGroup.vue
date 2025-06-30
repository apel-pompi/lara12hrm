<script setup lang="ts">
import { Label } from '@/components/ui/label';

defineProps<{
  htmlFor: string
  label: string
}>()
</script>

<template>
  <div class="grid gap-2">
    <Label :for="htmlFor">{{ label }}</Label>
    <slot />
  </div>
</template>