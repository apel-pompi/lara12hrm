<script setup lang="ts">
import { ArrowTopRightOnSquareIcon, EnvelopeIcon, GlobeAltIcon, PhoneIcon, UserCircleIcon } from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
const props = defineProps({
    conversation: Object,
});

const initials = (name) => {
    if (!name) return '?';

    return name
        .split(' ')
        .map((i) => i.charAt(0))
        .join('')
        .substring(0, 2)
        .toUpperCase();
};

const formatDate = (date: string) => {
    const d = new Date(date);

    const today = new Date();

    if (d.toDateString() === today.toDateString()) {
        return d.toLocaleTimeString('en-BD', {
            hour: '2-digit',

            minute: '2-digit',

            hour12: true,
        });
    }

    return d.toLocaleDateString('en-BD', {
        day: '2-digit',

        month: 'short',
    });
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
    <div class="flex h-full flex-col bg-white overflow-y-auto">
        <template v-if="conversation">
            <!-- Profile -->

            <div class="border-b p-6 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-200 text-3xl font-bold text-slate-700">
                    {{ initials(conversation.social_name ?? conversation.phone) }}
                </div>

                <h2 class="mt-4 text-xl font-semibold text-slate-800">
                    {{ conversation.social_name ?? conversation.phone }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ conversation.phone }}
                </p>
            </div>

            <!-- Contact -->

            <div class="border-b p-6">
                <h3 class="mb-4 text-sm font-semibold tracking-wider text-slate-500 uppercase">Contact</h3>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <PhoneIcon class="h-5 w-5 text-slate-400" />

                        <span class="ml-3 text-sm">
                            {{ conversation.phone }}
                        </span>
                    </div>

                    <div class="flex items-center">
                        <EnvelopeIcon class="h-5 w-5 text-slate-400" />

                        <span class="ml-3 text-sm">
                            {{ conversation.email ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center">
                        <GlobeAltIcon class="h-5 w-5 text-slate-400" />

                        <span class="ml-3 text-sm capitalize">
                            {{ conversation.platform }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Student -->

            <div class="border-b p-6">
                <h3 class="mb-4 text-sm font-semibold tracking-wider text-slate-500 uppercase">Student</h3>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500"> Status </span>

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ conversation.student_status }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500"> Assigned </span>

                        <span class="text-sm font-medium">
                            {{ conversation.assigned_to ?? 'Unassigned' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500"> Workflow </span>

                        <span class="text-sm">
                            {{ conversation.workflow ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->

            <div class="border-b p-6">
                <h3 class="mb-4 text-sm font-semibold tracking-wider text-slate-500 uppercase">Timeline</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span> First Contact </span>

                        <span>
                            {{ conversation.created_at ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span> Last Message </span>

                        <span>
                            {{ formatDate(conversation.last_message_at) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span> Total Messages </span>

                        <span>
                            {{ conversation.total_messages ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->

            <div class="mt-auto space-y-3 p-6">
                <button
                    @click="OpenStudent"
                    class="flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-white hover:bg-blue-700"
                >
                    <ArrowTopRightOnSquareIcon class="mr-2 h-5 w-5" />

                    Open Student
                </button>
            </div>
        </template>

        <template v-else>
            <div class="flex flex-1 items-center justify-center">
                <div class="text-center">
                    <UserCircleIcon class="mx-auto h-16 w-16 text-slate-300" />

                    <p class="mt-4 text-sm text-slate-500">No Contact Selected</p>
                </div>
            </div>
        </template>
    </div>
</template>
