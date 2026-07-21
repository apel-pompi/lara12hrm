<script setup lang="ts">
import echo from '@/echo';
import ConversationItem from '@/pages/allpages/Agency/MetaChat/ChatComponents/ConversationItem.vue';
import axios from 'axios';
import { onBeforeUnmount, onMounted, ref } from 'vue';
const props = defineProps({
    conversations: Array,
});

const model = defineModel();

const conversations = ref([]);
const loading = ref(false);
echo.channel('conversations')

    .listen('.conversation.updated', (e) => {
        const index = conversations.value.findIndex((x) => x.id === e.conversation.id);

        if (index === -1) {
            return;
        }

        conversations.value[index].last_message = e.conversation.last_message;

        conversations.value[index].last_message_at = e.conversation.last_message_at;

        conversations.value[index].unread_count = e.conversation.unread_count;

        const row = conversations.value.splice(index, 1)[0];

        conversations.value.unshift(row);
    });

const loadConversations = async () => {
    loading.value = true;

    try {
        const { data } = await axios.get('/conversations');

        conversations.value = data.data;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadConversations();
});

onBeforeUnmount(() => {
    echo.leave('conversations');
});
</script>

<template>
    <div v-if="loading" class="p-5 text-center">Loading...</div>

    <div v-else class="flex-1 overflow-y-auto">
        <ConversationItem
            v-for="conversation in conversations"
            :key="conversation.id"
            :conversation="conversation"
            :active="model?.id == conversation.id"
            @click="model = conversation"
        />
    </div>
</template>
