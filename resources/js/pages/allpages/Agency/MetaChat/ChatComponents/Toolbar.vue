<script setup lang="ts">
import { ArrowPathIcon, FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps<{
    filters: {
        search?: string;
    };
}>();
const search = ref(props.filters?.search ?? '');

const platform = ref('all');

const sort = ref('latest');
// Search after user stops typing
let timeout: number;
watch(search, (value) => {
    clearTimeout(timeout);

    timeout = window.setTimeout(() => {
        router.get(
            route('metachat.index'),
            {
                search: value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 500);
});

const refresh = () => {
    router.get(route('metachat.index'));
};
</script>

<template>
    <div class="border-b bg-white">
        <!-- Header -->

        <div class="flex items-center justify-between px-6 py-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Conversations</h2>

                <p class="text-sm text-slate-500">Manage all social conversations</p>
            </div>

            <button @click="refresh" class="rounded-xl border p-2 hover:bg-slate-100">
                <ArrowPathIcon class="h-5 w-5" />
            </button>
        </div>

        <!-- Search -->

        <div class="px-6 pb-4">
            <div class="relative">
                <MagnifyingGlassIcon class="absolute top-3 left-3 h-5 w-5 text-slate-400" />

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by name, phone or message..."
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pr-4 pl-10 focus:border-blue-500 focus:outline-none"
                />
            </div>
        </div>

        <!-- Filters -->

        <div class="grid grid-cols-2 gap-3 px-6 pb-5">
            <!-- Platform -->

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> Platform </label>

                <select v-model="platform" @change="emit('filter')" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2">
                    <option value="all">All Platforms</option>

                    <option value="whatsapp">WhatsApp</option>

                    <option value="messenger">Messenger</option>

                    <option value="instagram">Instagram</option>
                </select>
            </div>

            <!-- Sort -->

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> Sort </label>

                <select v-model="sort" @change="emit('filter')" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2">
                    <option value="latest">Latest</option>

                    <option value="oldest">Oldest</option>

                    <option value="unread">Unread First</option>
                </select>
            </div>
        </div>

        <!-- Footer -->

        <div class="flex items-center justify-between border-t bg-slate-50 px-6 py-3">
            <div class="text-sm text-slate-500">
                Showing

                <span class="font-semibold text-slate-700"> 0 </span>

                conversations
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <FunnelIcon class="h-4 w-4" />

                Filters Active
            </div>
        </div>
    </div>
</template>
