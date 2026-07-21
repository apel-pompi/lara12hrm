<script setup lang="ts">
import echo from '@/echo';
import axios from 'axios';
import { ref } from 'vue';

import { FaceSmileIcon, PaperAirplaneIcon, PaperClipIcon, PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    conversation: Object,
});

const emit = defineEmits(['send', 'data.data']);

const message = ref('');

const sending = ref(false);

const attachment = ref(null);

const image = ref(null);

const sendMessage = async () => {
    if (message.value.trim() === '') return;

    sending.value = true;
    if (!props.conversation?.id) {
        return;
    }
    try {
        const { data } = await axios.post(
            '/messages/send',

            {
                conversation_id: props.conversation?.id,

                message: message.value,
            },
        );

        message.value = '';

        attachment.value = null;

        image.value = null;
    } catch (error) {
        console.error(error);
    } finally {
        sending.value = false;
    }
};

const onEnter = (e) => {
    if (e.shiftKey) return;

    e.preventDefault();

    sendMessage();
};

let typingTimer = null;

const typing = () => {
    if (!props.conversation?.id) return;
    echo.private(`conversation.${props.conversation.id}`).whisper('typing', {
        typing: true,
    });

    clearTimeout(typingTimer);

    typingTimer = setTimeout(() => {
        echo.private(`conversation.${props.conversation.id}`).whisper('typing', {
            typing: false,
        });
    }, 1200);
};
</script>

<template>
    <div class="border-t bg-white">
        <!-- Attachment Preview -->

        <div v-if="attachment || image" class="border-b bg-slate-50 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-700">
                        {{ attachment?.name ?? image?.name }}
                    </p>
                </div>

                <button
                    @click="
                        attachment = null;
                        image = null;
                    "
                    class="rounded-lg p-1 hover:bg-slate-200"
                >
                    <XMarkIcon class="h-5 w-5" />
                </button>
            </div>
        </div>

        <!-- Composer -->

        <div class="flex items-end gap-3 p-4">
            <!-- Emoji -->

            <button class="rounded-xl p-2 hover:bg-slate-100">
                <FaceSmileIcon class="h-6 w-6" />
            </button>

            <!-- Attachment -->

            <label class="cursor-pointer rounded-xl p-2 hover:bg-slate-100">
                <PaperClipIcon class="h-6 w-6" />

                <input type="file" class="hidden" @change="attachment = $event.target.files[0]" />
            </label>

            <!-- Image -->

            <label class="cursor-pointer rounded-xl p-2 hover:bg-slate-100">
                <PhotoIcon class="h-6 w-6" />

                <input type="file" accept="image/*" class="hidden" @change="image = $event.target.files[0]" />
            </label>

            <!-- Text -->

            <textarea
                v-model="message"
                rows="1"
                placeholder="Type your message..."
                @keydown.enter="onEnter"
                @input="typing"
                class="max-h-40 flex-1 resize-none rounded-2xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:outline-none"
            />

            <!-- Send -->

            <button
                @click="sendMessage"
                :disabled="sending"
                class="rounded-2xl bg-blue-600 p-3 text-white transition hover:bg-blue-700 disabled:opacity-50"
            >
                <PaperAirplaneIcon class="h-6 w-6" />
            </button>
        </div>
    </div>
</template>
