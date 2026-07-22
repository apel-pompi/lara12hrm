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
                <!-- Attachment -->
                <div v-if="message.attachment" class="mb-2">
                    <img v-if="message.message_type === 'image' || message.attachment.match(/\.(jpeg|jpg|gif|png)$/i)" :src="message.attachment" alt="Attachment" class="max-w-full rounded-lg max-h-64 object-cover" />
                    <a v-else :href="message.attachment" target="_blank" class="flex items-center gap-2 underline text-sm" :class="message.direction == 'outbound' ? 'text-white hover:text-slate-200' : 'text-blue-600 hover:text-blue-800'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Download File
                    </a>
                </div>

                <!-- Text -->
                <p v-if="message.message" class="text-sm leading-6 break-words whitespace-pre-wrap">
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
