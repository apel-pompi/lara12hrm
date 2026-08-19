<script setup lang="ts">
import echo from '@/echo';
import axios from 'axios';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

import { FaceSmileIcon, PaperAirplaneIcon, PaperClipIcon, PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    conversation: Object,
});

const emit = defineEmits(['sent']);

const message = ref('');
const sending = ref(false);
const attachment = ref(null);
const image = ref(null);
const showEmojiPicker = ref(false);

const emojis = [
    '😀', '😂', '😅', '🤣', '😊', '😇', '🥰', '😍', '🤩', '😘',
    '😜', '🤪', '🤫', '🤔', '🤐', '🤨', '😐', '😏', '😒', '🙄',
    '😬', '😔', '😪', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧',
    '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓', '🧐',
    '😕', '😟', '🙁', '😮', '😯', '😲', '😳', '🥺', '😦', '😧',
    '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓',
    '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀',
    '👍', '👎', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✌️', '🤞',
];

const appendEmoji = (emoji: string) => {
    message.value += emoji;
    showEmojiPicker.value = false;
};

const getErrorMessage = (error: any) => {
    const responseData = error?.response?.data;

    const validationErrors = responseData?.errors;
    if (validationErrors && typeof validationErrors === 'object') {
        const firstError = Object.values(validationErrors).flat().find((item) => typeof item === 'string');
        if (firstError) {
            return firstError;
        }
    }

    if (typeof responseData?.message === 'string' && responseData.message) {
        return responseData.message;
    }

    if (error instanceof Error && error.message) {
        return error.message;
    }

    return 'Unable to send message.';
};

const sendMessage = async () => {
    if (message.value.trim() === '' && !attachment.value && !image.value) return;

    sending.value = true;

    if (!props.conversation?.id) {
        sending.value = false;
        return;
    }

    try {
        const formData = new FormData();
        formData.append('conversation_id', props.conversation.id);
        
        if (message.value.trim() !== '') {
            formData.append('message', message.value);
        }

        if (attachment.value) {
            formData.append('attachment', attachment.value);
        }
        
        if (image.value) {
            formData.append('image', image.value);
        }

        const { data } = await axios.post('/messages/send', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });

        emit('sent', data.data);

        message.value = '';
        attachment.value = null;
        image.value = null;
    } catch (error) {
        const errorMessage = getErrorMessage(error);
        toast.error(errorMessage);
        console.error('Failed to send message', error);
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
    <div class="shrink-0 border-t bg-white relative">
        <!-- Attachment Preview -->
        <div v-if="attachment || image" class="border-b bg-slate-50 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 overflow-hidden">
                    <PhotoIcon v-if="image" class="h-5 w-5 text-blue-500 shrink-0" />
                    <PaperClipIcon v-else class="h-5 w-5 text-slate-500 shrink-0" />
                    <p class="truncate text-sm font-medium text-slate-700">
                        {{ attachment?.name ?? image?.name }}
                    </p>
                </div>
                <button
                    @click="attachment = null; image = null;"
                    class="ml-2 shrink-0 rounded-lg p-1 hover:bg-slate-200"
                >
                    <XMarkIcon class="h-5 w-5" />
                </button>
            </div>
        </div>

        <!-- Emoji Picker Popover -->
        <div v-if="showEmojiPicker" class="absolute bottom-full left-2 mb-2 w-72 rounded-xl border border-slate-200 bg-white p-2 shadow-xl sm:left-4 z-50">
            <div class="flex items-center justify-between border-b pb-2 mb-2">
                <span class="text-xs font-semibold text-slate-500 uppercase">Emojis</span>
                <button @click="showEmojiPicker = false" class="rounded-lg p-1 hover:bg-slate-100 text-slate-400">
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
            <div class="grid grid-cols-8 gap-1 max-h-48 overflow-y-auto p-1">
                <button
                    v-for="emoji in emojis"
                    :key="emoji"
                    @click="appendEmoji(emoji)"
                    class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-slate-100 text-xl transition-colors"
                >
                    {{ emoji }}
                </button>
            </div>
        </div>

        <!-- Composer Row -->
        <div class="flex items-end gap-1 px-2 py-2 sm:gap-2 sm:px-4 sm:py-3">
            <!-- Emoji -->
            <button @click="showEmojiPicker = !showEmojiPicker" class="shrink-0 rounded-xl p-2 hover:bg-slate-100 transition-colors" :class="showEmojiPicker ? 'bg-slate-100 text-blue-600' : 'text-slate-600'" title="Emoji">
                <FaceSmileIcon class="h-5 w-5" />
            </button>

            <!-- Attachment -->
            <label class="shrink-0 cursor-pointer rounded-xl p-2 hover:bg-slate-100 transition-colors text-slate-600" title="Attach file">
                <PaperClipIcon class="h-5 w-5" />
                <input type="file" class="hidden" @change="attachment = $event.target.files[0]; image = null;" />
            </label>

            <!-- Image -->
            <label class="shrink-0 cursor-pointer rounded-xl p-2 hover:bg-slate-100 transition-colors text-slate-600" title="Attach image">
                <PhotoIcon class="h-5 w-5" />
                <input type="file" accept="image/*" class="hidden" @change="image = $event.target.files[0]; attachment = null;" />
            </label>

            <!-- Textarea -->
            <textarea
                v-model="message"
                rows="1"
                placeholder="Type your message..."
                @keydown.enter="onEnter"
                @input="typing"
                class="max-h-36 min-h-[42px] flex-1 resize-none rounded-2xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:bg-white focus:outline-none sm:px-4 sm:py-3 sm:text-base"
            />

            <!-- Send -->
            <button
                @click="sendMessage"
                :disabled="sending || (message.trim() === '' && !attachment && !image)"
                class="shrink-0 rounded-2xl bg-blue-600 p-2.5 text-white transition hover:bg-blue-700 disabled:opacity-50 sm:p-3 flex items-center justify-center"
            >
                <div v-if="sending" class="h-5 w-5 sm:h-6 sm:w-6 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                <PaperAirplaneIcon v-else class="h-5 w-5 sm:h-6 sm:w-6" />
            </button>
        </div>
    </div>
</template>
