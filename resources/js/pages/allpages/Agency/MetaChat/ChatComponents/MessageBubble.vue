<script setup lang="ts">
import { CheckIcon } from '@heroicons/vue/24/solid';
import { computed } from 'vue';
const props = defineProps({
    message: {
        type: Object,

        required: true,
    },
});

const formatDate = (date?: string) => {
    if (!date) return '';

    const d = new Date(date);

    return d.toLocaleTimeString('en-BD', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
};

const isMine = computed(() => {
    return props.message.direction === 'outbound';
});
</script>

<template>
    <div class="mb-5 flex" :class="message.direction == 'outbound' ? 'justify-end' : 'justify-start'">
        <div class="max-w-[70%]">
            <!-- Sender -->

            <div v-if="message.direction == 'inbound' && message.sender" class="mb-1 ml-2 text-xs font-medium text-slate-500">
                {{ message.sender }}
            </div>

            <!-- Bubble -->

            <div
                class="rounded-2xl px-4 py-3 shadow-sm"
                :class="message.direction == 'outbound' ? 'rounded-br-md bg-blue-600 text-white' : 'rounded-bl-md bg-white text-slate-700'"
            >
                <!-- Text -->

                <p class="text-sm leading-6 break-words whitespace-pre-wrap">
                    {{ message.message }}
                </p>
            </div>

            <!-- Footer -->

            <div class="mt-1 flex items-center gap-1" :class="message.direction == 'outbound' ? 'justify-end' : 'justify-start'">
                <span class="text-xs text-slate-400">
                    {{ formatDate(message.created_at) }}
                </span>

                <template v-if="isMine">
                    <!-- Sent -->

                    <CheckIcon v-if="message.status == 'sent'" class="h-4 w-4 text-slate-400" />

                    <!-- Delivered -->

                    <div v-else-if="message.status == 'delivered'" class="flex -space-x-1">
                        <CheckIcon class="h-4 w-4 text-slate-400" />
                        <CheckIcon class="h-4 w-4 text-slate-400" />
                    </div>

                    <!-- Read -->

                    <div v-else-if="message.status == 'read'" class="flex -space-x-1">
                        <CheckIcon class="h-4 w-4 text-blue-500" />
                        <CheckIcon class="h-4 w-4 text-blue-500" />
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
