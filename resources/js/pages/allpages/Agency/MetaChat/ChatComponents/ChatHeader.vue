<script setup lang="ts">
import {
    ArrowTopRightOnSquareIcon,
    ClipboardDocumentListIcon,
    ClockIcon,
    EnvelopeIcon,
    GlobeAltIcon,
    PencilSquareIcon,
    PhoneIcon,
    UserCircleIcon,
    UserIcon,
    UserPlusIcon,
} from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    conversation: Object,

    channels: Array,
});

const emit = defineEmits(['switch']);

const lastSeen = (date?: string) => {
    if (!date) return 'Offline';

    const d = new Date(date);

    const diff = Math.floor((Date.now() - d.getTime()) / 60000);

    if (diff < 1) return 'Online';

    if (diff < 60) return diff + ' min ago';

    if (diff < 1440) return Math.floor(diff / 60) + ' hr ago';

    return d.toLocaleDateString();
};

const OpenStudent = () => {
    console.log(props.conversation);

    const studentId = props.conversation?.student_id;

    if (!studentId) {
        console.error('Student ID not found');
        return;
    }

    router.visit(route('studentActivities.index', { student: studentId }));
};
</script>

<template>
    <div class="border-b bg-white">
        <div class="px-6 py-5">
            <div class="flex justify-between">
                <div class="flex gap-4">
                    <div>
                        <img v-if="conversation.profile_picture" :src="conversation.profile_picture" class="h-16 w-16 rounded-full object-cover" />

                        <div v-else class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-200">
                            <UserCircleIcon class="h-10 w-10" />
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold">
                            {{ conversation.social_name }}
                        </h2>

                        <div class="mt-2 flex gap-2">
                            <button
                                v-for="item in channels"
                                :key="item.id"
                                @click="$emit('switch', item)"
                                class="rounded-full px-3 py-1 text-xs"
                                :class="item.id == conversation.id ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-slate-200'"
                            >
                                {{ item.platform }}
                            </button>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <PhoneIcon class="h-4 w-4" />

                                {{ conversation.phone }}
                            </div>

                            <div class="flex items-center gap-2">
                                <EnvelopeIcon class="h-4 w-4" />

                                {{ conversation.email }}
                            </div>

                            <div class="flex items-center gap-2">
                                <GlobeAltIcon class="h-4 w-4" />

                                {{ conversation.country ?? 'Unknown' }}
                            </div>

                            <div class="flex items-center gap-2">
                                <UserIcon class="h-4 w-4" />

                                {{ conversation.assigned_to ?? 'Unassigned' }}
                            </div>

                            <div class="flex items-center gap-2">
                                <ClockIcon class="h-4 w-4" />

                                {{ lastSeen(conversation.last_seen_at) }}
                            </div>
                        </div>

                        <div v-if="conversation.tags?.length" class="mt-4 flex flex-wrap gap-2">
                            <span v-for="tag in conversation.tags" :key="tag" class="rounded-full bg-blue-100 px-2 py-1 text-xs">
                                {{ tag }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-2">
                    <button @click="OpenStudent" class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                        <ArrowTopRightOnSquareIcon class="mr-1 inline h-4" />

                        Student
                    </button>

                    <button class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                        <ClipboardDocumentListIcon class="mr-1 inline h-4" />

                        Timeline
                    </button>

                    <button class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                        <PencilSquareIcon class="mr-1 inline h-4" />

                        Notes
                    </button>

                    <button class="rounded-xl border px-3 py-2 hover:bg-slate-50">
                        <UserPlusIcon class="mr-1 inline h-4" />

                        Assign
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
