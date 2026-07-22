<script setup lang="ts">
import { computed } from 'vue';

import { ChatBubbleLeftRightIcon, ClockIcon, FlagIcon, InboxIcon, UserCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    counts: {
        type: Object,
        default: () => ({
            smartViews: {},
            channels: {},
            status: {},
            total: 0,
            unread: 0,
            waiting: 0,
        }),
    },
});

const smartViews = computed(() => [
    {
        name: 'All Conversations',
        icon: InboxIcon,
        key: 'all',
        count: props.counts?.smartViews?.all || 0,
        active: true,
    },
    {
        name: 'New Leads',
        icon: UserCircleIcon,
        key: 'new_leads',
        count: props.counts?.smartViews?.new_leads || 0,
    },
    {
        name: 'My Leads',
        icon: UserCircleIcon,
        key: 'my_leads',
        count: props.counts?.smartViews?.my_leads || 0,
    },
    {
        name: 'Waiting Reply',
        icon: ClockIcon,
        key: 'waiting',
        count: props.counts?.smartViews?.waiting || 0,
    },
    {
        name: 'Unread',
        icon: ChatBubbleLeftRightIcon,
        key: 'unread',
        count: props.counts?.smartViews?.unread || 0,
    },
    {
        name: 'High Priority',
        icon: FlagIcon,
        key: 'priority',
        count: props.counts?.smartViews?.priority || 0,
    },
]);

const channels = computed(() => [
    {
        name: 'WhatsApp',
        key: 'whatsapp',
        color: 'bg-green-500',
        count: props.counts?.channels?.whatsapp || 0,
    },
    {
        name: 'Messenger',
        key: 'messenger',
        color: 'bg-blue-600',
        count: props.counts?.channels?.messenger || 0,
    },
    {
        name: 'Instagram',
        key: 'instagram',
        color: 'bg-pink-500',
        count: props.counts?.channels?.instagram || 0,
    },
]);

const status = computed(() => [
    {
        name: 'Pending',
        key: 'pending',
        color: 'bg-slate-500',
        count: props.counts?.status?.pending || 0,
    },
    {
        name: 'Lead',
        key: 'lead',
        color: 'bg-yellow-500',
        count: props.counts?.status?.lead || 0,
    },
    {
        name: 'Prospect',
        key: 'prospect',
        color: 'bg-green-500',
        count: props.counts?.status?.prospect || 0,
    },
    {
        name: 'OnBoard',
        key: 'onboard',
        color: 'bg-sky-500',
        count: props.counts?.status?.onboard || 0,
    },
    {
        name: 'Archived',
        key: 'archive',
        color: 'bg-red-500',
        count: props.counts?.status?.archive || 0,
    },
]);
</script>

<template>
    <div class="flex h-full flex-col bg-white">
        <!-- Header -->

        <div class="border-b px-6 py-5">
            <h2 class="text-center text-xl font-bold text-slate-500">Unified Inbox</h2>

            <p class="mt-1 text-sm text-slate-500">Manage all social conversations</p>
        </div>

        <div class="flex-1 overflow-y-auto">
            <!-- Smart Views -->

            <div class="px-3 py-5">
                <p class="mb-3 px-3 text-xs font-bold text-slate-400 uppercase">Smart Views</p>

                <button
                    v-for="item in smartViews"
                    :key="item.key"
                    class="mb-1 flex w-full items-center justify-between rounded-xl px-3 py-3 transition"
                    :class="item.active ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-slate-100'"
                >
                    <div class="flex items-center gap-3">
                        <component :is="item.icon" class="h-5 w-5" />

                        <span>
                            {{ item.name }}
                        </span>
                    </div>

                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" :class="item.active ? 'bg-white/20' : 'bg-slate-200 text-slate-700'">
                        {{ item.count }}
                    </span>
                </button>
            </div>

            <!-- Channels -->

            <div class="border-t px-3 py-5">
                <p class="mb-3 px-3 text-xs font-bold text-slate-400 uppercase">Channels</p>

                <button
                    v-for="channel in channels"
                    :key="channel.key"
                    class="mb-1 flex w-full items-center justify-between rounded-xl px-3 py-3 hover:bg-slate-100"
                >
                    <div class="flex items-center gap-3">
                        <span class="h-3 w-3 rounded-full" :class="channel.color" />

                        {{ channel.name }}
                    </div>

                    <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs">
                        {{ channel.count }}
                    </span>
                </button>
            </div>

            <!-- Status -->

            <div class="border-t px-3 py-5">
                <p class="mb-3 px-3 text-xs font-bold text-slate-400 uppercase">Status</p>

                <button
                    v-for="item in status"
                    :key="item.name"
                    class="mb-1 flex w-full items-center justify-between rounded-xl px-3 py-3 hover:bg-slate-100"
                >
                    <div class="flex items-center gap-3">
                        <span class="h-3 w-3 rounded-full" :class="item.color" />

                        {{ item.name }}
                    </div>

                    <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs">
                        {{ item.count }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Footer -->

        <div class="border-t p-5">
            <div class="rounded-2xl bg-slate-100 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500"> Total Conversations </span>

                    <span class="font-bold"> {{ props.counts?.total || 0 }} </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-slate-500"> Unread </span>

                    <span class="font-bold text-red-500"> {{ props.counts?.smartViews?.unread || 0 }} </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-slate-500"> Waiting Reply </span>

                    <span class="font-bold text-orange-500"> {{ props.counts?.smartViews?.waiting || 0 }} </span>
                </div>
            </div>
        </div>
    </div>
</template>
