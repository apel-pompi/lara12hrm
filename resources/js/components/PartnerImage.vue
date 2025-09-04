<script setup lang="ts">
import { ref, watch } from 'vue';
import Label from './ui/label/Label.vue';
import Input from './ui/input/Input.vue';
import Button from './ui/button/Button.vue';
import { X } from 'lucide-vue-next';

// Props
const props = defineProps({
  Image: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

// Emit
const emit = defineEmits(['image']);

// Reactive state
const currentImage = props.Image ? `/storage/partner/${props.Image}` : null;
const preview = ref<string | null>(currentImage);
const oversizedImage = ref(false);
const showRevertBtn = ref(false);

// Watch for prop changes (optional)
watch(
  () => props.Image,
  (newVal) => {
    preview.value = newVal ? `/storage/partner/${newVal}` : null;
    showRevertBtn.value = false;
  }
);

// Handle image selection
const imageSelected = (e: Event) => {
  const file = (e.target as HTMLInputElement)?.files?.[0];
  if (!file) return;

  preview.value = URL.createObjectURL(file);
  oversizedImage.value = file.size > 3 * 1024 * 1024; // 3MB
  showRevertBtn.value = true;
  emit('image', file);
};

// Revert back to original image
const revertImageChange = () => {
  preview.value = props.Image ? `/storage/partner/${props.Image}` : null;
  oversizedImage.value = false;
  showRevertBtn.value = false;
  emit('image', null); // clear selected image
};
</script>

<template>
  <div>
    <span
      class="block text-sm font-medium text-slate-700 dark:text-slate-300"
      :class="{ '!text-red-500': oversizedImage }"
    >
      {{ oversizedImage ? 'The selected image exceeds 300Kb' : 'Image (Max size 300Kb)' }}
    </span>

    <Label
      for="image"
      class="relative mt-1 block h-[140px] cursor-pointer overflow-hidden rounded-md border border-slate-300 bg-slate-300"
      :class="{ '!border-red-500': oversizedImage }"
    >
      <img
        :src="preview || '/storage/employee/default.png'"
        class="h-full w-full object-cover object-center"
        alt="Preview"
      />
      <Button
        class="absolute top-2 right-2 grid h-8 w-8 place-items-center rounded-full bg-white/75 text-slate-700"
        v-if="showRevertBtn"
        @click.prevent="revertImageChange"
        type="button"
      >
        <X />
      </Button>
    </Label>

    <Input
      @input="imageSelected"
      type="file"
      name="image"
      id="image"
      accept="image/*"
      hidden
      :disabled="disabled"
    />
  </div>
</template>
