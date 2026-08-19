<script setup lang="ts">
import { CheckBadgeIcon, CheckIcon, PaperClipIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
    conversation: any;
    active: boolean;
}>();

const platformName = (platform: string) => {
    return (
        {
            whatsapp: 'WhatsApp',
            messenger: 'Messenger',
            instagram: 'Instagram',
        }[platform] ?? 'Unknown'
    );
};

const platformDot = (platform: string) => {
    return (
        {
            whatsapp: 'bg-green-500',
            messenger: 'bg-blue-500',
            instagram: 'bg-pink-500',
        }[platform] ?? 'bg-slate-400'
    );
};

const statusColor = (status: string) => {
    return (
        {
            'New Lead': 'bg-blue-100 text-blue-700',
            Student: 'bg-green-100 text-green-700',
            Visa: 'bg-purple-100 text-purple-700',
            Admission: 'bg-orange-100 text-orange-700',
        }[status] ?? 'bg-slate-100 text-slate-600'
    );
};

const initials = (name?: string) => {
    if (!name) return '?';

    return name
        .split(' ')
        .map((i) => i[0])
        .join('')
        .substring(0, 2)
        .toUpperCase();
};

const formatDate = (date?: string) => {
    if (!date) return '';

    const d = new Date(date);
    const now = new Date();

    if (d.toDateString() === now.toDateString()) {
        return d.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    const yesterday = new Date();

    yesterday.setDate(now.getDate() - 1);

    if (d.toDateString() === yesterday.toDateString()) {
        return 'Yesterday';
    }

    return d.toLocaleDateString([], {
        day: '2-digit',
        month: 'short',
    });
};

const firstWords = (text?: string, count = 3) => {
    if (!text) return '';

    const words = text.trim().split(/\s+/);

    if (words.length <= count) {
        return text;
    }

    return words.slice(0, count).join(' ') + '...';
};
</script>

<template>
    <div class="cursor-pointer border-b border-slate-100 transition-all duration-200"
        :class="active ? 'border-l-4 border-l-green-500 bg-green-50' : 'hover:bg-slate-50'">
        <div class="p-4">
            <div class="flex gap-3">
                <!-- Avatar -->

                <div class="relative shrink-0">
                    <div class="h-12 w-12 overflow-hidden rounded-full bg-slate-200">
                        <img v-if="conversation.profile_picture" :src="conversation.profile_picture"
                            class="h-full w-full object-cover" />

                        <div v-else class="flex h-full w-full items-center justify-center font-semibold text-slate-700">
                            {{ initials(conversation.social_name) }}
                        </div>
                    </div>

                    <span class="absolute right-0 bottom-0 h-3 w-3 rounded-full border-2 border-white"
                        :class="platformDot(conversation.platform)" />
                    <p v-if="conversation.typing" class="truncate text-sm text-green-600 italic">Typing...</p>

                    <p v-else class="truncate text-sm text-slate-500">
                        <!-- {{ firstWords(conversation.last_message) }} -->
                    </p>
                </div>

                <!-- Content -->

                <div class="min-w-0 flex-1">
                    <!-- Name -->

                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <h3 class="truncate font-semibold text-slate-800">
                                {{ conversation.social_name || conversation.phone }}
                            </h3>

                            <div class="mt-1 flex gap-2">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px]">
                                    {{ platformName(conversation.platform) }}
                                </span>
                            </div>
                        </div>

                        <span class="text-[11px] text-slate-500">
                            {{ formatDate(conversation.last_message_at) }}
                        </span>
                    </div>

                    <!-- Last Message -->

                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex min-w-0 items-center gap-1">
                            <PaperClipIcon v-if="conversation.last_message_type != 'text'"
                                class="h-4 w-4 shrink-0 text-slate-400" />

                            <template v-if="conversation.last_message_direction == 'outbound'">
                                <CheckIcon v-if="conversation.last_message_status == 'sent'"
                                    class="h-4 w-4 shrink-0 text-slate-400" />

                                <CheckIcon v-else-if="conversation.last_message_status == 'delivered'"
                                    class="h-4 w-4 shrink-0 text-slate-500" />

                                <CheckBadgeIcon v-else-if="conversation.last_message_status == 'read'"
                                    class="h-4 w-4 shrink-0 text-blue-500" />

                                <span class="shrink-0 text-xs font-medium text-slate-600"> You: </span>
                            </template>

                            <span v-if="conversation.typing" class="truncate text-sm text-green-600 italic"> Typing...
                            </span>

                            <span v-else class="truncate text-sm text-slate-500">
                                {{ firstWords(conversation.last_message) }}
                            </span>
                        </div>

                        <span v-if="conversation.unread_count > 0"
                            class="ml-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-green-500 px-2 text-[11px] font-bold text-white">
                            {{ conversation.unread_count }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
