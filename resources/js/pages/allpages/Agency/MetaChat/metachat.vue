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
        <div class="overflow-hidden bg-slate-100">
            <div class="flex h-full">
                <!-- Left Sidebar -->

                <aside class="hidden w-64 border-r bg-white xl:flex">
                    <Sidebar />
                </aside>

                <!-- Inbox -->

                <section class="flex flex-1 overflow-hidden">
                    <!-- Conversation List -->

                    <div class="flex w-[380px] flex-col border-r bg-white">
                        <Toolbar />

                        <ConversationList v-model="currentConversation" :conversations="conversations" />
                    </div>

                    <!-- Chat -->

                    <div class="flex flex-1 flex-col bg-slate-50">
                        <template v-if="currentConversation">
                            <ChatHeader :conversation="currentConversation" :channels="channels" @switch="currentConversation = $event" />

                            <ChatMessages :messages="messages" />

                            <Composer :conversation="currentConversation" @sent="appendMessage" />
                        </template>

                        <template v-else>
                            <div class="flex flex-1 items-center justify-center">
                                <div class="text-center">
                                    <div class="mx-auto mb-6 flex h-28 w-28 items-center justify-center rounded-full bg-slate-200">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 text-slate-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h8M8 14h5M4 6h16v12H4z" />
                                        </svg>
                                    </div>

                                    <h2 class="text-2xl font-semibold text-slate-700">Unified Inbox</h2>

                                    <p class="mt-2 text-slate-500">Select a conversation to start chatting.</p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Profile -->

                    <aside class="hidden w-96 justify-center border-l bg-white 2xl:flex">
                        <ContactProfile :conversation="currentConversation" />
                    </aside>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
