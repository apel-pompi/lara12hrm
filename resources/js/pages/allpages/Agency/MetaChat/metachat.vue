<script setup lang="ts">
import echo from '@/echo';
import AppLayout from '@/layouts/AppLayout.vue';
import ChatHeader from '@/pages/allpages/Agency/MetaChat/ChatComponents/ChatHeader.vue';
import ChatMessages from '@/pages/allpages/Agency/MetaChat/ChatComponents/ChatMessages.vue';
import Composer from '@/pages/allpages/Agency/MetaChat/ChatComponents/Composer.vue';
import ContactProfile from '@/pages/allpages/Agency/MetaChat/ChatComponents/ContactProfile.vue';
import ConversationList from '@/pages/allpages/Agency/MetaChat/ChatComponents/ConversationList.vue';
import Sidebar from '@/pages/allpages/Agency/MetaChat/ChatComponents/Sidebar.vue';
import Toolbar from '@/pages/allpages/Agency/MetaChat/ChatComponents/Toolbar.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Unified Inbox', href: '/metachat' }];

const loading = ref(false);

const props = defineProps({
    conversations: Array,
    counts: {
        type: Object,
        default: () => ({}),
    },
});

const currentConversation = ref(null);
const channels = ref([]);
const messages = ref([]);

let currentChannel = null;
let typingChannel = null;

const appendMessage = (message) => {
    if (messages.value.some((m) => m.id === message.id)) {
        return;
    }

    messages.value.push(message);

    currentConversation.value.last_message = message.message;

    currentConversation.value.last_message_at = message.created_at;
};

watch(
    currentConversation,
    async (conversation) => {
        if (currentChannel) {
            echo.leave(currentChannel);
        }

        if (typingChannel) {
            echo.leave(typingChannel);
        }

        if (!conversation?.id) {
            messages.value = [];
            return;
        }

        const { data } = await axios.get(`/conversations/${conversation.id}/messages`);

        messages.value = data.data;

        currentChannel = `conversation.${conversation.id}`;

        echo.channel(currentChannel)

            .listen('.message.received', (e) => {
                messages.value.push(e);
                currentConversation.value.last_message = e.message;
                currentConversation.value.typing = false;
            })
            .listen('.message.status.updated', (e) => {
                const msg = messages.value.find((m) => m.id === e.id);

                if (!msg) {
                    return;
                }

                msg.status = e.status;

                msg.read_at = e.read_at;

                msg.delivered_at = e.delivered_at;
            });

        typingChannel = `private-conversation.${conversation.id}`;

        echo.private(`conversation.${conversation.id}`)

            .listenForWhisper('typing', (e) => {
                conversation.typing = e.typing;
            });
    },
    {
        immediate: true,
    },
);

watch(currentConversation, (conversation) => {
    if (typingChannel) {
        echo.leave(typingChannel);
    }

    if (!conversation?.id) {
        return;
    }

    typingChannel = `private-conversation.${conversation.id}`;

    echo.private(`conversation.${conversation.id}`).listenForWhisper('typing', (e) => {
        conversation.typing = e.typing;
    });
});

watch(currentConversation, async (conversation) => {
    if (!conversation) return;

    const { data } = await axios.get(`/conversations/${conversation.id}/channels`);

    channels.value = data.data;
});

const filters = ref({
    search: '',
    platform: 'all',
    sort: 'latest',
});
const totalConversations = ref(0);
const conversationListRef = ref(null);

// Mobile: 'list' | 'chat'
const mobileView = ref<'list' | 'chat'>('list');

const handleFilter = (newFilters: any) => {
    filters.value = { ...newFilters };
};

const refreshConversations = () => {
    conversationListRef.value?.loadConversations();
};

const selectConversation = (conversation: any) => {
    currentConversation.value = conversation;
    mobileView.value = 'chat';
};

const backToList = () => {
    mobileView.value = 'list';
};

const stopListening = () => {
    if (!currentConversation.value) return;

    echo.leave(`conversation.${currentConversation.value.id}`);
};

const startListening = (conversationId: number) => {
    echo.channel(`conversation.${conversationId}`)

        .listen('.message.received', (e) => {
            if (messages.value.some((m) => m.id === e.id)) {
                return;
            }

            messages.value.push(e);
        });
};
</script>

<template>
    <Head title="Meta Chat Box" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="h-[calc(100vh-6.5rem)] w-full overflow-hidden bg-slate-100">
            <div class="flex h-full overflow-hidden">
                <!-- Left Sidebar: xl+ only -->
                <aside class="hidden h-full w-64 shrink-0 overflow-hidden border-r bg-white xl:flex">
                    <Sidebar class="h-full w-full" :counts="props.counts" />
                </aside>

                <!-- Inbox Section -->
                <section class="flex h-full min-w-0 flex-1 overflow-hidden">
                    <!-- Conversation List Panel -->
                    <!-- Mobile: full width when mobileView='list', hidden when 'chat' -->
                    <!-- md+: always visible at fixed width, flex-1 on mobile if in list view -->
                    <div
                        class="flex h-full flex-col overflow-hidden border-r bg-white transition-all duration-200"
                        :class="{
                            'w-full': mobileView === 'list',
                            hidden: mobileView === 'chat',
                            'md:flex md:w-[340px] md:shrink-0': true,
                        }"
                    >
                        <Toolbar
                            :filters="filters"
                            :total="totalConversations"
                            @filter="handleFilter"
                            @refresh="refreshConversations"
                            class="shrink-0"
                        />

                        <ConversationList
                            ref="conversationListRef"
                            v-model="currentConversation"
                            :filters="filters"
                            @update:total="totalConversations = $event"
                            @update:modelValue="selectConversation"
                            class="min-h-0 flex-1 overflow-hidden"
                        />
                    </div>

                    <!-- Chat Panel -->
                    <!-- Mobile: full width when mobileView='chat', hidden when 'list' -->
                    <!-- md+: always flex-1 -->
                    <div
                        class="flex h-full min-w-0 flex-col overflow-hidden bg-slate-50 transition-all duration-200"
                        :class="{
                            'flex w-full': mobileView === 'chat',
                            hidden: mobileView === 'list',
                            'md:flex md:flex-1': true,
                        }"
                    >
                        <template v-if="currentConversation">
                            <!-- Mobile back button in chat header area -->
                            <div class="flex shrink-0 items-center gap-2 border-b bg-white px-4 py-2 md:hidden">
                                <button
                                    @click="backToList"
                                    class="flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-50"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Back
                                </button>
                                <span class="text-sm font-semibold text-slate-700">{{ currentConversation.social_name }}</span>
                            </div>

                            <ChatHeader
                                :conversation="currentConversation"
                                :channels="channels"
                                @switch="currentConversation = $event"
                                class="shrink-0"
                            />

                            <ChatMessages :messages="messages" class="min-h-0 flex-1 overflow-y-auto" />

                            <Composer :conversation="currentConversation" @sent="appendMessage" class="shrink-0" />
                        </template>

                        <template v-else>
                            <div class="flex flex-1 items-center justify-center">
                                <div class="px-4 text-center">
                                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-200 sm:h-28 sm:w-28">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-slate-400 sm:h-12 sm:w-12"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h8M8 14h5M4 6h16v12H4z" />
                                        </svg>
                                    </div>

                                    <h2 class="text-xl font-semibold text-slate-700 sm:text-2xl">Unified Inbox</h2>

                                    <p class="mt-2 text-sm text-slate-500 sm:text-base">Select a conversation to start chatting.</p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Contact Profile: 2xl+ only -->
                    <aside class="hidden h-full w-80 shrink-0 flex-col overflow-hidden border-l bg-white xl:w-96 2xl:flex">
                        <ContactProfile :conversation="currentConversation" class="h-full w-full" />
                    </aside>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
