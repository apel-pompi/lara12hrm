<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import { toast } from 'vue-sonner';

const props = defineProps<{
    show: boolean;
    activityId: number | null;
}>();

const emit = defineEmits<{
    close: [];
}>();

const activity = ref<any>(null);
const loading = ref(false);

const loadActivity = async () => {

    if (!props.activityId) {
        return;
    }

    try {

        loading.value = true;

        const response = await axios.get(
            `/follow-up-activities/${props.activityId}`
        );

        activity.value =
            response.data?.data ??
            response.data;

    } catch (error: any) {

        toast.error('Failed to load follow-up activity', error?.response?.data?.message);

    } finally {

        loading.value = false;

    }
};

watch(
    () => [
        props.show,
        props.activityId,
    ],
    ([show]) => {

        if (show) {
            loadActivity();
        }

    }
);

const formatDate = (date: string | null | undefined) => {
    if (!date) return '-';

    const parsed = new Date(date);

    if (isNaN(parsed.getTime())) {
        return date;
    }

    return parsed.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
</script>


<template>

    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 p-4">

        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl">

            <!-- Header -->

            <div class="flex items-center justify-between border-b px-5 py-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-900">
                        Follow-up Activity
                    </h2>

                    <p class="text-xs text-slate-500">
                        Activity #{{ activityId }}
                    </p>

                </div>

                <button type="button" @click="emit('close')"
                    class="rounded-lg px-3 py-2 text-slate-500 hover:bg-slate-100">
                    ✕
                </button>

            </div>


            <!-- Loading -->

            <div v-if="loading" class="flex justify-center py-12">

                <div class="h-7 w-7 animate-spin rounded-full border-2 border-slate-200 border-t-blue-600" />

            </div>


            <!-- Activity -->

            <div v-else-if="activity" class="space-y-5 p-5">

                <div>

                    <p class="text-xs font-medium text-slate-400">
                        Title
                    </p>

                    <p class="mt-1 font-semibold text-slate-900">
                        {{ activity.title }}
                    </p>

                </div>


                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <p class="text-xs text-slate-400">
                            Follow-up Type
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ activity.master?.name ?? '-' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-slate-400">
                            Status
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ activity.status?.name ?? activity.status ?? '-' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-slate-400">
                            Date
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ formatDate(activity.follow_up_date) }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-slate-400">
                            Time
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ activity.follow_up_time ?? '-' }}
                        </p>

                    </div>

                </div>


                <div v-if="activity.description">

                    <p class="text-xs text-slate-400">
                        Description
                    </p>

                    <p class="mt-1 whitespace-pre-wrap text-sm text-slate-700">
                        {{ activity.description }}
                    </p>

                </div>


                <div v-if="activity.remarks">

                    <p class="text-xs text-slate-400">
                        Remarks
                    </p>

                    <p class="mt-1 whitespace-pre-wrap text-sm text-slate-700">
                        {{ activity.remarks }}
                    </p>

                </div>

            </div>


            <!-- Footer -->

            <div class="flex justify-end border-t bg-slate-50 px-5 py-3">

                <button type="button" @click="emit('close')"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Close
                </button>

            </div>

        </div>

    </div>

</template>