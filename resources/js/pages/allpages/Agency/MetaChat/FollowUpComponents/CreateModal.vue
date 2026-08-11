<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot, } from '@headlessui/vue';
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import { toast } from 'vue-sonner';

interface Option {
    id: number;
    name: string;
}

const props = defineProps({

    show: {
        type: Boolean,
        default: false,
    },

    studentId: {
        type: Number,
        required: true,
    },

    masters: {
        type: Array as () => Option[],
        default: () => [],
    },

    statuses: {
        type: Array as () => Option[],
        default: () => [],
    },

    users: {
        type: Array as () => Option[],
        default: () => [],
    },

});

const emit = defineEmits([
    'close',
    'saved',
]);

const loading = ref(false);


const form = useForm({

    student_id: props.studentId,

    follow_up_master_id: null,

    follow_up_status_id: null,

    assigned_to: null,

    title: '',

    description: '',

    follow_up_date: '',

    follow_up_time: '',

    priority: 'Medium',

    remarks: '',

    status: 'Pending',

    is_auto: false,

    meta: {},

});



watch(
    () => props.studentId,
    (id) => {

        form.student_id = id;

    }
);

function resetForm() {

    form.reset();

    form.clearErrors();

    form.student_id = props.studentId;

    form.priority = 'Medium';

    form.status = 'Pending';

    form.is_auto = false;

}

function closeModal() {

    resetForm();

    emit('close');

}

async function submit() {

    loading.value = true;
    form.clearErrors();

    try {
        const response = await axios.post(
            route('follow-up-activities.store'),
            form.data()
        );

        toast.success(response.data?.message || 'Follow-up created successfully');

        loading.value = false;

        emit('saved');

        closeModal();

    } catch (error: any) {

        loading.value = false;

        if (error.response && error.response.status === 422) {
            const errors = error.response.data?.errors || {};
            const formattedErrors: Record<string, string> = {};
            for (const key in errors) {
                formattedErrors[key] = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
            }
            form.setError(formattedErrors);
            toast.error('Please fill in all required fields.');
        } else {
            toast.error(error.response?.data?.message || 'Failed to create follow-up activity.');
        }

    }

}

function handleKeyboard(e: KeyboardEvent) {

    if (!props.show) {
        return;
    }

    if (e.key === 'Escape') {

        closeModal();

    }

    if (e.ctrlKey && e.key === 'Enter') {

        submit();

    }

}

onMounted(() => {

    window.addEventListener(
        'keydown',
        handleKeyboard
    );

});

onUnmounted(() => {

    window.removeEventListener(
        'keydown',
        handleKeyboard
    );

});
</script>

<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" class="relative z-50" @close="closeModal">
            <!-- Overlay -->
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">

                <div class="flex min-h-full items-center justify-center p-4">

                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">

                        <DialogPanel
                            class="w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900">

                            <!-- Header -->

                            <div
                                class="border-b border-slate-200 bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white">
                                <DialogTitle class="text-xl font-semibold">
                                    Schedule Follow Up
                                </DialogTitle>

                                <p class="mt-1 text-sm text-blue-100">
                                    Create a new follow-up activity.
                                </p>
                            </div>

                            <!-- Body -->

                            <div class="space-y-6 p-6">

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                    <!-- Type -->

                                    <div>

                                        <label class="mb-1 block text-sm font-medium">

                                            Follow Up Type

                                        </label>

                                        <select v-model="form.follow_up_master_id"
                                            class="w-full rounded-lg border px-3 py-2">
                                            <option :value="null">
                                                Select Type
                                            </option>

                                            <option v-for="item in masters" :key="item.id" :value="item.id">
                                                {{ item.name }}
                                            </option>

                                        </select>

                                        <p v-if="form.errors.follow_up_master_id" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.follow_up_master_id }}
                                        </p>

                                    </div>

                                    <!-- Status -->

                                    <div>

                                        <label class="mb-1 block text-sm font-medium">

                                            Status

                                        </label>

                                        <select v-model="form.follow_up_status_id"
                                            class="w-full rounded-lg border px-3 py-2">

                                            <option :value="null">
                                                Select Status
                                            </option>

                                            <option v-for="item in statuses" :key="item.id" :value="item.id">
                                                {{ item.name }}
                                            </option>

                                        </select>

                                        <p v-if="form.errors.follow_up_status_id" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.follow_up_status_id }}
                                        </p>

                                    </div>

                                    <!-- Assigned -->

                                    <div>

                                        <label class="mb-1 block text-sm font-medium">

                                            Assign To

                                        </label>

                                        <select v-model="form.assigned_to" class="w-full rounded-lg border px-3 py-2"
                                            :class="{ 'border-red-500': form.errors.assigned_to }">

                                            <option :value="null">
                                                Select User
                                            </option>

                                            <option v-for="user in users" :key="user.id" :value="user.id">
                                                {{ user.name }}
                                            </option>

                                        </select>

                                        <p v-if="form.errors.assigned_to" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.assigned_to }}
                                        </p>

                                    </div>

                                    <!-- Priority -->

                                    <div>

                                        <label class="mb-2 block text-sm font-medium">

                                            Priority

                                        </label>

                                        <div class="flex gap-2">

                                            <button type="button" v-for="item in ['Low', 'Medium', 'High', 'Urgent']"
                                                :key="item" @click="form.priority = item"
                                                class="rounded-lg border px-3 py-2 text-sm transition" :class="form.priority === item
                                                    ? 'border-blue-600 bg-blue-600 text-white'
                                                    : 'bg-white hover:bg-slate-100 dark:bg-slate-800'">
                                                {{ item }}
                                            </button>

                                        </div>

                                    </div>

                                </div>

                                <!-- Title -->

                                <div>

                                    <label class="mb-1 block text-sm font-medium">

                                        Title

                                    </label>

                                    <input v-model="form.title" type="text" class="w-full rounded-lg border px-3 py-2"
                                        :class="{ 'border-red-500': form.errors.title }" placeholder="Follow Up Title">

                                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.title }}
                                    </p>

                                </div>

                                <!-- Description -->

                                <div>

                                    <label class="mb-1 block text-sm font-medium">

                                        Description

                                    </label>

                                    <textarea v-model="form.description" rows="4"
                                        class="w-full rounded-lg border px-3 py-2" />

                                </div>

                                <!-- Date Time -->

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                    <div>

                                        <label class="mb-1 block text-sm font-medium">

                                            Date

                                        </label>
                                        <VueDatePicker v-model="form.follow_up_date" :format="'yyyy-MM-dd'"
                                            model-type="format" :enable-time-picker="false" placeholder="Follow Up Date"
                                            auto-apply teleport="body" />

                                        <p v-if="form.errors.follow_up_date" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.follow_up_date }}
                                        </p>

                                    </div>

                                    <div>

                                        <label class="mb-1 block text-sm font-medium">

                                            Time

                                        </label>
                                        <VueDatePicker v-model="form.follow_up_time" :format="'HH:mm:ss'"
                                            model-type="format" time-picker placeholder="Follow Up Time" auto-apply
                                            teleport="body" />

                                        <p v-if="form.errors.follow_up_time" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.follow_up_time }}
                                        </p>

                                    </div>

                                </div>

                                <!-- Remarks -->

                                <div>

                                    <label class="mb-1 block text-sm font-medium">

                                        Remarks

                                    </label>

                                    <textarea v-model="form.remarks" rows="3"
                                        class="w-full rounded-lg border px-3 py-2" />

                                </div>

                            </div>
                            <!-- Footer -->

                            <div
                                class="flex flex-col items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800 md:flex-row">

                                <div class="text-xs text-slate-500 dark:text-slate-400">

                                    <span class="font-semibold">
                                        Shortcut:
                                    </span>

                                    Ctrl + Enter = Save

                                    &nbsp; | &nbsp;

                                    Esc = Close

                                </div>

                                <div class="flex gap-3">

                                    <button type="button" @click="closeModal"
                                        class="rounded-lg border border-slate-300 px-5 py-2 font-medium transition hover:bg-slate-100 dark:border-slate-600 dark:hover:bg-slate-700">
                                        Cancel
                                    </button>

                                    <button type="button" :disabled="loading || form.processing" @click="submit"
                                        class="flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">

                                        <svg v-if="loading || form.processing" class="h-4 w-4 animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" />

                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z" />
                                        </svg>

                                        <span>

                                            {{
                                                loading || form.processing
                                                    ? 'Saving...'
                                                    : 'Save Follow Up'
                                            }}

                                        </span>

                                    </button>

                                </div>

                            </div>

                        </DialogPanel>

                    </TransitionChild>

                </div>

            </div>

        </Dialog>

    </TransitionRoot>
</template>
