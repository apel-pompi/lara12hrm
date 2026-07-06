<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Check, Edit3, ExternalLink, Info, Link2, Loader2, MessageSquare } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: {
        id: number;
        fname: string;
        lname: string;
        email?: string;
        phone?: string;
        inbox_url?: string | null;
    };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const showEditForm = ref(!props.student.inbox_url);

const form = useForm({
    inbox_url: props.student.inbox_url || '',
});

const submitUrl = () => {
    form.post(route('studentChat.updateUrl', { student: props.student.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Saved!', { description: 'Facebook Messenger inbox URL has been updated.' });
            if (form.inbox_url) showEditForm.value = false;
        },
        onError: (errors) => {
            toast.error('Error', { description: Object.values(errors)[0] as string });
        },
    });
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-1 border-b border-gray-200 pb-4 dark:border-gray-800">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-white">
                        <MessageSquare class="h-3.5 w-3.5" />
                    </span>
                    Facebook Messenger Chat
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Chat directly with <strong>{{ props.student.fname }} {{ props.student.lname }}</strong> via Facebook Messenger.
                </p>
            </div>

            <!-- Active Link Card -->
            <div
                v-if="props.student.inbox_url && !showEditForm"
                class="relative overflow-hidden rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/50 via-white to-indigo-50/30 p-6 shadow-sm dark:border-gray-800 dark:from-gray-900/80 dark:to-gray-950"
            >
                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-blue-400/10 blur-2xl"></div>
                <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-indigo-500/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/20">
                            <MessageSquare class="h-5 w-5" />
                        </div>
                        <div class="space-y-1.5">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Messenger Inbox Connected</p>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                                Ready to chat with <strong>{{ props.student.fname }}</strong> on Facebook Messenger.
                            </p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                                <span class="h-1.5 w-1.5 animate-ping rounded-full bg-emerald-500"></span>
                                Link Active
                            </span>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-3">
                        <Button variant="outline" size="sm" @click="showEditForm = true" class="gap-1.5 border-gray-300 dark:border-gray-700">
                            <Edit3 class="h-3.5 w-3.5" /> Edit URL
                        </Button>
                        <a
                            :href="props.student.inbox_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-9 items-center gap-1.5 rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 text-sm font-medium text-white shadow-sm transition-all hover:from-blue-700 hover:to-indigo-700 hover:shadow-md hover:shadow-blue-500/20"
                        >
                            Open Messenger <ExternalLink class="h-3.5 w-3.5" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- No link yet — show placeholder card -->
            <div
                v-if="!props.student.inbox_url && !showEditForm"
                class="rounded-xl border border-dashed border-blue-200 bg-blue-50/30 p-8 text-center dark:border-gray-700 dark:bg-gray-900/40"
            >
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-500 dark:bg-blue-500/10">
                    <MessageSquare class="h-6 w-6" />
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No Messenger link configured yet</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Facebook Lead Ads auto-populate this. You can also set it manually below.
                </p>
                <Button size="sm" class="mt-4 bg-blue-600 text-white hover:bg-blue-700" @click="showEditForm = true">
                    Configure Link
                </Button>
            </div>

            <!-- Edit / Configure Form -->
            <div v-if="showEditForm" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                    <Link2 class="h-4 w-4 text-indigo-500" />
                    {{ props.student.inbox_url ? 'Update Messenger Link' : 'Set Messenger Link' }}
                </h3>

                <form @submit.prevent="submitUrl" class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="inbox_url" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                            Facebook Messenger / Business Inbox URL
                        </label>
                        <input
                            id="inbox_url"
                            v-model="form.inbox_url"
                            type="url"
                            placeholder="https://business.facebook.com/latest/inbox/messenger?asset_id=...&selected_item_id=..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500"
                        />
                        <p v-if="form.errors.inbox_url" class="text-xs text-red-600">{{ form.errors.inbox_url }}</p>
                        <p class="flex items-start gap-1.5 text-[11px] leading-relaxed text-gray-500 dark:text-gray-400">
                            <Info class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-400" />
                            When a Facebook Lead Ad fires, this URL is generated automatically using the Page ID and Lead ID. You may also paste any direct `m.me/` or Business Suite inbox link.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                        <Button v-if="props.student.inbox_url" type="button" variant="ghost" size="sm" @click="showEditForm = false">Cancel</Button>
                        <Button type="submit" size="sm" :disabled="form.processing" class="gap-1.5 bg-blue-600 font-medium text-white hover:bg-blue-700">
                            <Loader2 v-if="form.processing" class="h-3.5 w-3.5 animate-spin" />
                            <Check v-else class="h-3.5 w-3.5" />
                            {{ form.processing ? 'Saving...' : 'Save Link' }}
                        </Button>
                    </div>
                </form>
            </div>

            <!-- Info Panel -->
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800/80 dark:bg-gray-900/50">
                <h4 class="mb-2 flex items-center gap-1.5 text-xs font-semibold text-gray-800 dark:text-gray-200">
                    <Info class="h-4 w-4 text-blue-500" /> How Facebook Chat Integration Works
                </h4>
                <ul class="list-disc space-y-1.5 pl-5 text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    <li>When a lead submits your Facebook Lead Ad form, the system uses the <strong>Page ID</strong> + <strong>Lead ID</strong> to automatically construct a direct Business Suite inbox link.</li>
                    <li>Clicking <em>Open Messenger</em> launches your Facebook Business Suite inbox in a new tab — pointing directly to that lead's conversation thread.</li>
                    <li>Because Facebook blocks iframe embedding for security reasons, chats open natively in a new browser tab or the Messenger app.</li>
                </ul>
            </div>
        </div>
    </StudentLayout>
</template>
