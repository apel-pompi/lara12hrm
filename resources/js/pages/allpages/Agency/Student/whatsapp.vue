<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Check, Edit3, ExternalLink, Info, Link2, Loader2, PhoneCall } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: {
        id: number;
        fname: string;
        lname: string;
        email?: string;
        phone?: string;
        whatsapp_url?: string | null;
    };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const showEditForm = ref(!props.student.whatsapp_url);

const form = useForm({
    whatsapp_url: props.student.whatsapp_url || '',
});

// Auto-generate wa.me link from phone if no custom URL is set
const autoUrl = computed(() => {
    if (!props.student.phone) return null;
    const clean = props.student.phone.replace(/\D/g, '');
    return clean ? `https://wa.me/${clean}` : null;
});

const activeUrl = computed(() => props.student.whatsapp_url || autoUrl.value);

const submitUrl = () => {
    form.post(route('studentWhatsapp.updateUrl', { student: props.student.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Saved!', { description: 'WhatsApp URL has been updated.' });
            if (form.whatsapp_url) showEditForm.value = false;
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
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">
                        <PhoneCall class="h-3.5 w-3.5" />
                    </span>
                    WhatsApp Chat
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Chat directly with <strong>{{ props.student.fname }} {{ props.student.lname }}</strong> via WhatsApp.
                </p>
            </div>

            <!-- Active Link Card -->
            <div
                v-if="activeUrl && !showEditForm"
                class="relative overflow-hidden rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50/50 via-white to-teal-50/30 p-6 shadow-sm dark:border-gray-800 dark:from-gray-900/80 dark:to-gray-950"
            >
                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-emerald-400/10 blur-2xl"></div>
                <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-teal-500/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/20">
                            <PhoneCall class="h-5 w-5" />
                        </div>
                        <div class="space-y-1.5">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">WhatsApp Ready</p>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                                <span v-if="props.student.whatsapp_url">Using your custom URL for <strong>{{ props.student.fname }}</strong>.</span>
                                <span v-else>Auto-generated from phone: <strong>{{ props.student.phone }}</strong></span>
                            </p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                                <span class="h-1.5 w-1.5 animate-ping rounded-full bg-emerald-500"></span>
                                {{ props.student.whatsapp_url ? 'Custom URL Active' : 'Auto-Generated' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-3">
                        <Button variant="outline" size="sm" @click="showEditForm = true" class="gap-1.5 border-gray-300 dark:border-gray-700">
                            <Edit3 class="h-3.5 w-3.5" />
                            {{ props.student.whatsapp_url ? 'Change URL' : 'Override URL' }}
                        </Button>
                        <a
                            :href="activeUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-9 items-center gap-1.5 rounded-md bg-gradient-to-r from-emerald-500 to-teal-600 px-4 text-sm font-medium text-white shadow-sm transition-all hover:from-emerald-600 hover:to-teal-700 hover:shadow-md hover:shadow-emerald-500/20"
                        >
                            Open WhatsApp <ExternalLink class="h-3.5 w-3.5" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- No phone & no custom URL -->
            <div
                v-if="!activeUrl && !showEditForm"
                class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50/30 p-8 text-center dark:border-gray-700 dark:bg-gray-900/40"
            >
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-500 dark:bg-emerald-500/10">
                    <PhoneCall class="h-6 w-6" />
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No WhatsApp link available</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    No phone number is set for this student. Please configure a custom WhatsApp link.
                </p>
                <Button size="sm" class="mt-4 bg-emerald-600 text-white hover:bg-emerald-700" @click="showEditForm = true">
                    Configure Link
                </Button>
            </div>

            <!-- Edit / Configure Form -->
            <div v-if="showEditForm" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                    <Link2 class="h-4 w-4 text-emerald-500" />
                    {{ props.student.whatsapp_url ? 'Update WhatsApp Link' : 'Set Custom WhatsApp Link' }}
                </h3>

                <form @submit.prevent="submitUrl" class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="whatsapp_url" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                            Custom WhatsApp Link <span class="text-gray-400">(optional if phone is set)</span>
                        </label>
                        <input
                            id="whatsapp_url"
                            v-model="form.whatsapp_url"
                            type="url"
                            placeholder="https://wa.me/8801700000000"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500"
                        />
                        <p v-if="form.errors.whatsapp_url" class="text-xs text-red-600">{{ form.errors.whatsapp_url }}</p>
                        <p class="flex items-start gap-1.5 text-[11px] leading-relaxed text-gray-500 dark:text-gray-400">
                            <Info class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-400" />
                            Leave blank to use the auto-generated link from phone
                            <strong>({{ props.student.phone || 'not set' }})</strong>. You can paste any
                            <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">wa.me/</code> link, group invite, or pre-filled message URL.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                        <Button v-if="activeUrl" type="button" variant="ghost" size="sm" @click="showEditForm = false">Cancel</Button>
                        <Button type="submit" size="sm" :disabled="form.processing" class="gap-1.5 bg-emerald-600 font-medium text-white hover:bg-emerald-700">
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
                    <Info class="h-4 w-4 text-emerald-500" /> How WhatsApp Integration Works
                </h4>
                <ul class="list-disc space-y-1.5 pl-5 text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    <li>The system auto-cleans the student's phone number (removes spaces, dashes, country prefixes) to build a <code class="rounded bg-gray-100 px-1 dark:bg-gray-800">wa.me/</code> click-to-chat link instantly.</li>
                    <li>You can override this with any custom WhatsApp link — e.g. a group invite, a pre-filled message template, or a specific business number.</li>
                    <li>Clicking <em>Open WhatsApp</em> opens WhatsApp Web or the WhatsApp Desktop/Mobile app in a new tab to begin the conversation.</li>
                </ul>
            </div>
        </div>
    </StudentLayout>
</template>
