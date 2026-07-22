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
import { toast } from 'vue-sonner';

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
    const studentId = props.conversation?.student_id;

    if (!studentId) {
        toast.error('Student is Not Found');
        return;
    }

    router.visit(route('studentActivities.index', { student: studentId }));
};
</script>

<template>
    <div class="shrink-0 border-b bg-white">
        <div class="px-3 py-3 sm:px-6 sm:py-5">
            <div class="flex items-start justify-between gap-2">
                <!-- Left: Avatar + Info -->
                <div class="flex min-w-0 gap-3">
                    <div class="shrink-0">
                        <img
                            v-if="conversation.profile_picture"
                            :src="conversation.profile_picture"
                            class="h-10 w-10 rounded-full object-cover sm:h-14 sm:w-14"
                        />
                        <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 sm:h-14 sm:w-14">
                            <UserCircleIcon class="h-6 w-6 sm:h-9 sm:w-9" />
                        </div>
                    </div>

                    <div class="min-w-0">
                        <h2 class="truncate text-base font-bold sm:text-xl">
                            {{ conversation.social_name }}
                        </h2>

                        <!-- Platform channel tabs -->
                        <div class="mt-1 flex flex-wrap gap-1">
                            <button
                                v-for="item in channels"
                                :key="item.id"
                                @click="$emit('switch', item)"
                                class="rounded-full px-2 py-0.5 text-xs"
                                :class="item.id == conversation.id ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-slate-200'"
                            >
                                {{ item.platform }}
                            </button>
                        </div>

                        <!-- Contact details: hidden on xs, grid on sm+ -->
                        <div class="mt-2 hidden grid-cols-2 gap-x-6 gap-y-1 text-sm text-slate-600 sm:grid">
                            <div class="flex items-center gap-2">
                                <PhoneIcon class="h-4 w-4 shrink-0" />
                                <span class="truncate">{{ conversation.phone }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <EnvelopeIcon class="h-4 w-4 shrink-0" />
                                <span class="truncate">{{ conversation.email }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <GlobeAltIcon class="h-4 w-4 shrink-0" />
                                <span class="truncate">{{ conversation.country ?? 'Unknown' }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <UserIcon class="h-4 w-4 shrink-0" />
                                <span class="truncate">{{ conversation.assigned_to ?? 'Unassigned' }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <ClockIcon class="h-4 w-4 shrink-0" />
                                <span>{{ lastSeen(conversation.last_seen_at) }}</span>
                            </div>
                        </div>

                        <div v-if="conversation.tags?.length" class="mt-2 flex flex-wrap gap-1">
                            <span v-for="tag in conversation.tags" :key="tag" class="rounded-full bg-blue-100 px-2 py-0.5 text-xs">
                                {{ tag }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Action buttons -->
                <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                    <!-- Mobile: icon only -->
                    <button @click="OpenStudent" class="rounded-xl border p-2 hover:bg-slate-50" title="Open Student">
                        <ArrowTopRightOnSquareIcon class="h-4 w-4" />
                        <span class="ml-1 hidden text-sm sm:inline">Student</span>
                    </button>

                    <button class="rounded-xl border p-2 hover:bg-slate-50" title="Timeline">
                        <ClipboardDocumentListIcon class="h-4 w-4" />
                        <span class="ml-1 hidden text-sm sm:inline">Timeline</span>
                    </button>

                    <button class="rounded-xl border p-2 hover:bg-slate-50" title="Notes">
                        <PencilSquareIcon class="h-4 w-4" />
                        <span class="ml-1 hidden text-sm sm:inline">Notes</span>
                    </button>

                    <button class="rounded-xl border p-2 hover:bg-slate-50" title="Assign">
                        <UserPlusIcon class="h-4 w-4" />
                        <span class="ml-1 hidden text-sm sm:inline">Assign</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
