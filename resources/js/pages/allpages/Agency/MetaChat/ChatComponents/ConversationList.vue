<script setup lang="ts">
import echo from '@/echo';
import ConversationItem from '@/pages/allpages/Agency/MetaChat/ChatComponents/ConversationItem.vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    filters?: {
        search?: string;
        platform?: string;
        sort?: string;
    };
}>();

const emit = defineEmits<{
    (e: 'update:total', total: number): void;
}>();

const model = defineModel();

const conversations = ref([]);
const loading = ref(false);
const currentPage = ref(1);
const lastPage = ref(1);
const totalConversations = ref(0);

echo.channel('conversations').listen('.conversation.updated', (e) => {
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

const loadConversations = async (page = 1) => {
    loading.value = true;

    try {
        const { data } = await axios.get('/conversations', {
            params: {
                page,
                platform: props.filters?.platform ?? 'all',
                sort: props.filters?.sort ?? 'latest',
                search: props.filters?.search ?? '',
                formdate: props.filters?.formdate ?? '',
                todate: props.filters?.todate ?? '',
            },
        });

        conversations.value = data.data ?? [];
        currentPage.value = data.meta?.current_page ?? 1;
        lastPage.value = data.meta?.last_page ?? 1;
        totalConversations.value = data.meta?.total ?? 0;
        emit('update:total', totalConversations.value);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

watch(
    () => props.filters,
    () => {
        loadConversations(1);
    },
    { deep: true },
);

const goToPage = (page: number) => {
    if (page >= 1 && page <= lastPage.value) {
        loadConversations(page);
    }
};

defineExpose({
    loadConversations,
});

onMounted(() => {
    loadConversations();
});

onBeforeUnmount(() => {
    echo.leave('conversations');
});
</script>

<template>
    <div class="flex flex-1 flex-col overflow-hidden">
        <div v-if="loading" class="p-5 text-center text-slate-400">Loading conversations...</div>

        <div v-else-if="conversations.length === 0" class="p-8 text-center text-slate-400">No conversations found.</div>

        <div v-else class="flex-1 overflow-y-auto">
            <ConversationItem
                v-for="conversation in conversations"
                :key="conversation.id"
                :conversation="conversation"
                :active="model?.id == conversation.id"
                @click="model = conversation"
            />
        </div>

        <!-- Pagination Controls -->
        <div v-if="lastPage > 1" class="flex shrink-0 items-center justify-between border-t bg-slate-50 px-4 py-3">
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage <= 1"
                class="flex items-center gap-1 rounded-lg border bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <ChevronLeftIcon class="h-4 w-4" />
                Previous
            </button>

            <span class="text-xs font-semibold text-slate-600"> Page {{ currentPage }} of {{ lastPage }} </span>

            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage >= lastPage"
                class="flex items-center gap-1 rounded-lg border bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Next
                <ChevronRightIcon class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>
