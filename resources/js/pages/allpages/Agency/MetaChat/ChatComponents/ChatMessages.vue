<script setup lang="ts">
import { nextTick, ref, watch } from 'vue';
import MessageBubble from './MessageBubble.vue';

const props = defineProps<{
    messages: any[];
}>();

const container = ref<HTMLElement | null>(null);

const scrollBottom = async () => {
    await nextTick();

    if (!container.value) return;

    container.value.scrollTop = container.value.scrollHeight;
};

watch(
    () => props.messages,

    () => {
        scrollBottom();
    },

    {
        deep: true,

        immediate: true,
    },
);
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: 0.25s;
}

.fade-enter-from {
    opacity: 0;

    transform: translateY(10px);
}

.fade-enter-to {
    opacity: 1;

    transform: translateY(0);
}
</style>
<template>
    <div ref="container" class="flex-1 overflow-y-auto bg-[#efeae2]">
        <div class="mx-auto max-w-5xl px-8 py-8">
            <!-- Date -->

            <div class="mb-8 flex justify-center">
                <span class="rounded-full bg-white px-4 py-1 text-xs shadow"> Today </span>
            </div>

            <!-- Empty -->

            <div v-if="messages.length == 0" class="py-20 text-center text-slate-400">No Messages</div>

            <!-- Messages -->

            <TransitionGroup name="fade" tag="div">
                <MessageBubble v-for="message in messages" :key="message.id" :message="message" />
            </TransitionGroup>
        </div>
    </div>
</template>
