<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import axios from 'axios';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import {
    AlertCircle,
    Check,
    CheckCheck,
    Clock,
    Download,
    ExternalLink,
    FileText,
    Image as ImageIcon,
    Loader2,
    MessageCircle,
    Mic,
    Pause,
    Paperclip,
    PhoneCall,
    Play,
    Reply,
    Send,
    Smile,
    X,
} from 'lucide-vue-next';
import { toast } from 'vue-sonner';

interface ReplyRef {
    id: number;
    message: string | null;
    message_type: string;
    direction: string;
}

interface WMessage {
    id: number;
    direction: 'incoming' | 'outgoing';
    status?: string;
    message_type: string;
    message: string | null;
    media_url: string | null;
    media_mime: string | null;
    media_size: number | null;
    media_name: string | null;
    reply_to: number | null;
    message_time: string;
    reply?: ReplyRef | null;
}

const props = defineProps<{
    student: {
        id: number;
        fname: string;
        lname: string;
        phone?: string;
        whatsapp_url?: string | null;
    };
    studentService?: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const messages = ref<WMessage[]>([]);
const newMessage = ref('');
const loading = ref(false);
const sending = ref(false);
const conversation = ref<{ id: number; phone: string; name: string; is_read: boolean; unread_count: number } | null>(null);
const unread = ref(0);
const typing = ref(false);
let typingTimer: number | null = null;
const scrollContainer = ref<HTMLElement | null>(null);
let pollTimer: number | null = null;
let echoListener: (() => void) | null = null;
let typingSendTimer: number | null = null;

// Reply / quote
const replyingTo = ref<WMessage | null>(null);

// Emoji picker
const showEmoji = ref(false);
const emojis = ['😀','😁','😂','🤣','😊','😍','😘','😎','🤔','😏','😢','😭','😡','👍','👎','🙏','👏','🙌','💪','🔥','✅','❌','⭐','💯','🎉','💕','👌','🤝','📌','✨'];

// Attach menu
const showAttach = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const attachType = ref<'image' | 'document' | 'file'>('file');

// Voice recording
const recording = ref(false);
const recordSeconds = ref(0);
let mediaRecorder: MediaRecorder | null = null;
let audioChunks: Blob[] = [];
let recordInterval: number | null = null;

// Audio playback tracking
const playingId = ref<number | null>(null);

const autoUrl = computed(() => {
    if (!props.student.phone) return null;
    const clean = props.student.phone.replace(/\D/g, '');
    return clean ? `https://wa.me/${clean}` : null;
});
const activeUrl = computed(() => props.student.whatsapp_url || autoUrl.value);
const hasPhone = computed(() => !!props.student.phone);
const canChat = computed(() => hasPhone.value && !!activeUrl.value);
const initials = computed(() => {
    const f = props.student.fname?.charAt(0)?.toUpperCase() || '';
    const l = props.student.lname?.charAt(0)?.toUpperCase() || '';
    return f + l || 'S';
});

/* ---------- Audio context for notification beep ---------- */
let audioCtx: AudioContext | null = null;
const ensureAudio = () => {
    if (!audioCtx) {
        const Ctx = window.AudioContext || (window as any).webkitAudioContext;
        if (Ctx) audioCtx = new Ctx();
    }
    return audioCtx;
};
const playBeep = () => {
    const ctx = ensureAudio();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = 'sine';
    osc.frequency.value = 880;
    gain.gain.setValueAtTime(0.0001, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.15, ctx.currentTime + 0.02);
    gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.25);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + 0.25);
};

/* ---------- Helpers ---------- */
const scrollToBottom = async () => {
    await nextTick();
    if (scrollContainer.value) {
        scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }
};

const formatTime = (iso: string) => {
    if (!iso) return '';
    return new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const formatSize = (bytes: number | null) => {
    if (!bytes) return '';
    const kb = bytes / 1024;
    return kb < 1024 ? `${kb.toFixed(0)} KB` : `${(kb / 1024).toFixed(1)} MB`;
};

const isImage = (m: WMessage) => m.message_type === 'image' && !!m.media_url;
const isAudio = (m: WMessage) => (m.message_type === 'audio' || m.message_type === 'voice') && !!m.media_url;
const isDoc = (m: WMessage) => m.media_url && m.message_type !== 'image' && m.message_type !== 'audio' && m.message_type !== 'voice';

const replySnippet = (m: WMessage) => {
    if (m.message) return m.message;
    if (isImage(m)) return '📷 Photo';
    if (isAudio(m)) return '🎤 Voice message';
    if (isDoc(m)) return `📄 ${m.media_name || 'Document'}`;
    return 'Attachment';
};

/* ---------- Data loading ---------- */
const loadMessages = async () => {
    if (!canChat.value) return;
    loading.value = true;
    try {
        const res = await axios.get(route('studentWhatsapp.messages', props.student.id));
        conversation.value = res.data.conversation;
        messages.value = res.data.messages || [];
        unread.value = res.data.unread ?? 0;
        await scrollToBottom();
    } catch (e) {
        console.error('Failed to load WhatsApp messages', e);
    } finally {
        loading.value = false;
    }
};

const sendMessage = async () => {
    const text = newMessage.value.trim();
    if ((!text && !replyingTo.value) || sending.value || !canChat.value) return;

    sending.value = true;
    try {
        const res = await axios.post(route('studentWhatsapp.send', props.student.id), {
            message: text,
            message_type: 'text',
            reply_to: replyingTo.value?.id ?? null,
        });
        if (res.data.success) {
            messages.value.push(res.data.message);
            newMessage.value = '';
            replyingTo.value = null;
            await scrollToBottom();
        }
    } catch (e: any) {
        toast.error('Error', { description: e.response?.data?.error || 'Failed to send message.' });
    } finally {
        sending.value = false;
        stopTyping();
    }
};

/* ---------- Media upload + send ---------- */
const triggerAttach = (type: 'image' | 'document' | 'file') => {
    attachType.value = type;
    showAttach.value = false;
    showEmoji.value = false;
    fileInput.value?.click();
};

const onFilePicked = async (e: Event) => {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    input.value = '';
    if (!file) return;

    const isImg = file.type.startsWith('image/');
    const isPdf = file.type === 'application/pdf';
    const isAud = file.type.startsWith('audio/');

    let messageType = attachType.value;
    if (isImg) messageType = 'image';
    else if (isPdf) messageType = 'document';
    else if (isAud) messageType = 'audio';
    else if (attachType.value === 'image') messageType = 'image';
    else if (attachType.value === 'document') messageType = 'document';
    else messageType = 'file';

    sending.value = true;
    try {
        const form = new FormData();
        form.append('file', file);
        const up = await axios.post(route('studentWhatsapp.upload', props.student.id), form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        const res = await axios.post(route('studentWhatsapp.send', props.student.id), {
            message: '',
            message_type: messageType,
            media_url: up.data.url,
            media_mime: up.data.mime,
            media_size: up.data.size,
            media_name: up.data.name,
            reply_to: replyingTo.value?.id ?? null,
        });
        if (res.data.success) {
            messages.value.push(res.data.message);
            replyingTo.value = null;
            await scrollToBottom();
        }
    } catch (err: any) {
        toast.error('Upload failed', { description: err.response?.data?.error || err.message });
    } finally {
        sending.value = false;
    }
};

/* ---------- Voice recording ---------- */
const startRecording = async () => {
    showAttach.value = false;
    showEmoji.value = false;
    if (!navigator.mediaDevices?.getUserMedia) {
        toast.error('Recording not supported in this browser.');
        return;
    }
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        mediaRecorder.ondataavailable = (ev) => audioChunks.push(ev.data);
        mediaRecorder.onstop = handleRecordingStop;
        mediaRecorder.start();
        recording.value = true;
        recordSeconds.value = 0;
        recordInterval = window.setInterval(() => (recordSeconds.value += 1), 1000);
    } catch (e) {
        toast.error('Mic access denied.');
    }
};

const stopRecording = () => {
    mediaRecorder?.stop();
    if (recordInterval) clearInterval(recordInterval);
};

const cancelRecording = () => {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.onstop = null;
        mediaRecorder.stop();
    }
    recording.value = false;
    if (recordInterval) clearInterval(recordInterval);
};

const handleRecordingStop = async () => {
    recording.value = false;
    const mimeType = mediaRecorder?.mimeType || 'audio/webm';
    const recExt = mimeType.includes('ogg') ? 'ogg' : 'webm';
    const blob = new Blob(audioChunks, { type: mimeType });
    const file = new File([blob], 'voice.' + recExt, { type: mimeType });
    sending.value = true;
    try {
        const form = new FormData();
        form.append('file', file);
        const up = await axios.post(route('studentWhatsapp.upload', props.student.id), form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        const res = await axios.post(route('studentWhatsapp.send', props.student.id), {
            message: '',
            message_type: 'voice',
            media_url: up.data.url,
            media_mime: up.data.mime,
            media_size: up.data.size,
            media_name: up.data.name,
            reply_to: replyingTo.value?.id ?? null,
        });
        if (res.data.success) {
            messages.value.push(res.data.message);
            replyingTo.value = null;
            await scrollToBottom();
        }
    } catch (err: any) {
        toast.error('Voice upload failed', { description: err.message });
    } finally {
        sending.value = false;
    }
};

/* ---------- Emoji ---------- */
const insertEmoji = (emoji: string) => {
    newMessage.value += emoji;
    showEmoji.value = false;
};

/* ---------- Typing indicator ---------- */
const notifyTyping = () => {
    if (typingTimer) return;
    axios.post(route('studentWhatsapp.typing', props.student.id), { typing: true }).catch(() => {});
    typingTimer = window.setTimeout(() => {
        typingTimer = null;
        stopTyping();
    }, 2000);
};

const stopTyping = () => {
    if (typingSendTimer) return;
    typingSendTimer = window.setTimeout(() => {
        typingSendTimer = null;
        axios.post(route('studentWhatsapp.typing', props.student.id), { typing: false }).catch(() => {});
    }, 400);
};

const onInput = () => {
    if (newMessage.value.trim()) notifyTyping();
};

/* ---------- Echo + polling ---------- */
const setupEcho = async () => {
    try {
        const { echo: echoInstance } = await import('@/echo');
        if (!echoInstance) return;
        const channelName = `whatsapp.student.${props.student.id}`;

        echoInstance.private(channelName)
            .listen('.WhatsAppMessageSent', (e: any) => {
                const m = e.message as WMessage;
                if (m && !messages.value.some((x) => x.id === m.id)) {
                    messages.value.push(m);
                    if (m.direction === 'incoming') {
                        playBeep();
                        unread.value += 1;
                    }
                    scrollToBottom();
                }
            })
            .listen('.WhatsAppTyping', (e: any) => {
                typing.value = !!e.typing;
                if (typing.value) {
                    if (typingHideTimer) clearTimeout(typingHideTimer);
                    typingHideTimer = window.setTimeout(() => (typing.value = false), 4000);
                }
            })
            .listen('.WhatsAppMessageStatus', (e: any) => {
                const target = messages.value.find((x) => (x as any).meta_message_id === e.meta_message_id);
                if (target) target.status = e.status;
            });

        echoListener = () => echoInstance.leave(channelName);
    } catch {
        // fallback to polling only
    }
};

let typingHideTimer: number | null = null;

onMounted(async () => {
    await loadMessages();
    if (canChat.value) {
        setupEcho();
        pollTimer = window.setInterval(() => {
            if (document.hidden) return;
            loadMessages();
        }, 4000);
    }
});

onBeforeUnmount(() => {
    if (pollTimer) clearInterval(pollTimer);
    if (echoListener) echoListener();
    if (typingHideTimer) clearTimeout(typingHideTimer);
    if (recording.value) cancelRecording();
});

watch(() => props.student.phone, loadMessages);
watch(() => props.student.whatsapp_url, loadMessages);
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService || []">
        <div class="flex h-[calc(100vh-200px)] flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="relative flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-md">
                        <PhoneCall class="h-5 w-5" />
                        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-400 ring-2 ring-white dark:ring-gray-900"></span>
                    </div>
                    <div>
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ props.student.fname }} {{ props.student.lname }}
                            <span v-if="unread > 0" class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white">
                                {{ unread }}
                            </span>
                        </h3>
                        <p class="text-xs" :class="typing ? 'text-emerald-500' : 'text-gray-500 dark:text-gray-400'">
                            <span v-if="typing">typing…</span>
                            <span v-else>{{ conversation ? conversation.phone : (props.student.phone || 'No phone') }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span v-if="conversation" class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" :class="conversation.is_read ? '' : 'animate-pulse'"></span>
                        {{ conversation.is_read ? 'Active' : 'Pending' }}
                    </span>
                    <a v-if="activeUrl" :href="activeUrl" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                        <ExternalLink class="h-3.5 w-3.5" /> Open WhatsApp
                    </a>
                </div>
            </div>

            <!-- Messages -->
            <div ref="scrollContainer" class="flex-1 space-y-4 overflow-y-auto bg-gray-50/50 p-4 dark:bg-gray-950/50">
                <div v-if="messages.length === 0 && !loading" class="flex h-full flex-col items-center justify-center text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-500/10">
                        <MessageCircle class="h-8 w-8 text-emerald-500" />
                    </div>
                    <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">Start a conversation</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Send a message to begin chatting with {{ props.student.fname }}.</p>
                </div>

                <template v-for="msg in messages" :key="msg.id">
                    <div class="flex items-end gap-2" :class="msg.direction === 'outgoing' ? 'justify-end' : 'justify-start'">
                        <!-- incoming avatar -->
                        <div v-if="msg.direction === 'incoming'" class="mb-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-xs font-bold text-white">
                            {{ initials }}
                        </div>

                        <div class="max-w-[78%] space-y-1">
                            <!-- quoted reply -->
                            <div v-if="msg.reply" class="mb-1 rounded-lg border-l-2 border-emerald-400 bg-gray-100 px-2.5 py-1 text-[11px] text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <span class="font-semibold">{{ msg.reply.direction === 'outgoing' ? 'You' : props.student.fname }}</span>
                                <p class="truncate">{{ replySnippet(msg.reply as any) }}</p>
                            </div>

                            <!-- image -->
                            <div v-if="isImage(msg)" class="overflow-hidden rounded-2xl" :class="msg.direction === 'outgoing' ? 'rounded-br-sm' : 'rounded-bl-sm'">
                                <a :href="msg.media_url" target="_blank" rel="noopener noreferrer">
                                    <img :src="msg.media_url" class="max-h-72 w-full object-cover" alt="image" />
                                </a>
                                <p v-if="msg.message" class="px-3 py-2 text-sm" :class="msg.direction === 'outgoing' ? 'text-white' : 'text-gray-900 dark:text-gray-100'">{{ msg.message }}</p>
                            </div>

                            <!-- audio / voice -->
                            <div v-else-if="isAudio(msg)" class="rounded-2xl px-4 py-3" :class="msg.direction === 'outgoing' ? 'rounded-br-sm bg-gradient-to-br from-emerald-500 to-teal-600 text-white' : 'rounded-bl-sm bg-white text-gray-900 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700'">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="playingId = playingId === msg.id ? null : msg.id" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 hover:bg-white/30">
                                        <Play v-if="playingId !== msg.id" class="h-4 w-4" />
                                        <Pause v-else class="h-4 w-4" />
                                    </button>
                                    <Mic class="h-4 w-4 opacity-70" />
                                    <span class="text-xs">Voice message</span>
                                    <audio v-if="playingId === msg.id" :src="msg.media_url" autoplay controls class="hidden" @ended="playingId = null" />
                                </div>
                                <p v-if="msg.message" class="mt-1 text-sm">{{ msg.message }}</p>
                            </div>

                            <!-- document / file -->
                            <a v-else-if="isDoc(msg)" :href="msg.media_url" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 rounded-2xl px-4 py-3" :class="msg.direction === 'outgoing' ? 'rounded-br-sm bg-gradient-to-br from-emerald-500 to-teal-600 text-white' : 'rounded-bl-sm bg-white text-gray-900 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700'">
                                <FileText class="h-8 w-8 shrink-0 opacity-80" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium">{{ msg.media_name || 'Document' }}</p>
                                    <p class="text-[11px] opacity-70">{{ formatSize(msg.media_size) }}</p>
                                </div>
                                <Download class="h-4 w-4 shrink-0 opacity-70" />
                            </a>

                            <!-- text -->
                            <div v-else class="rounded-2xl px-4 py-2.5 text-sm shadow-sm leading-relaxed" :class="msg.direction === 'outgoing' ? 'rounded-br-sm bg-gradient-to-br from-emerald-500 to-teal-600 text-white' : 'rounded-bl-sm bg-white text-gray-900 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700'">
                                <p class="whitespace-pre-wrap break-words">{{ msg.message }}</p>
                            </div>

                            <!-- meta row -->
                            <p class="flex items-center gap-1 px-1 text-[10px]" :class="msg.direction === 'outgoing' ? 'justify-end text-right text-gray-400' : 'justify-start text-left text-gray-400'">
                                {{ formatTime(msg.message_time) }}
                                <template v-if="msg.direction === 'outgoing'">
                                    <Check v-if="msg.status === 'sent'" class="h-3 w-3" />
                                    <CheckCheck v-else-if="msg.status === 'delivered'" class="h-3 w-3" />
                                    <CheckCheck v-else-if="msg.status === 'read'" class="h-3 w-3 text-emerald-500" />
                                    <Clock v-else-if="msg.status === 'failed'" class="h-3 w-3 text-red-500" />
                                    <span v-if="msg.status === 'failed'" class="text-red-500">Failed</span>
                                </template>
                                <button v-if="!isDoc(msg) && !isAudio(msg)" type="button" @click="replyingTo = msg" class="ml-1 opacity-60 transition-opacity hover:text-emerald-500 hover:opacity-100">
                                    <Reply class="h-3 w-3" />
                                </button>
                            </p>
                        </div>

                        <!-- outgoing avatar -->
                        <div v-if="msg.direction === 'outgoing'" class="mb-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                            ME
                        </div>
                    </div>
                </template>

                <!-- typing bubble -->
                <div v-if="typing" class="flex items-end gap-2 justify-start">
                    <div class="mb-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-xs font-bold text-white">{{ initials }}</div>
                    <div class="flex items-center gap-1 rounded-2xl rounded-bl-sm bg-white px-4 py-3 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.3s]"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.15s]"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400"></span>
                    </div>
                </div>

                <div v-if="loading" class="flex justify-center py-4">
                    <Loader2 class="h-5 w-5 animate-spin text-emerald-600" />
                </div>
            </div>

            <!-- Recording bar -->
            <div v-if="recording" class="flex items-center gap-3 border-t border-gray-200 bg-red-50 px-4 py-3 dark:border-gray-800 dark:bg-red-500/10">
                <span class="flex h-3 w-3 animate-pulse rounded-full bg-red-500"></span>
                <Mic class="h-4 w-4 text-red-500" />
                <span class="text-sm font-medium text-red-600">Recording {{ recordSeconds }}s</span>
                <div class="flex-1"></div>
                <Button type="button" variant="ghost" size="sm" @click="cancelRecording">Cancel</Button>
                <Button type="button" size="sm" class="bg-emerald-600 text-white hover:bg-emerald-700" @click="stopRecording">Send</Button>
            </div>

            <!-- Input area -->
            <div v-else class="border-t border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
                <!-- reply preview -->
                <div v-if="replyingTo" class="mb-2 flex items-center justify-between rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-800">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-emerald-600">Replying to {{ replyingTo.direction === 'outgoing' ? 'yourself' : props.student.fname }}</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ replySnippet(replyingTo) }}</p>
                    </div>
                    <button type="button" @click="replyingTo = null" class="text-gray-400 hover:text-gray-600"><X class="h-4 w-4" /></button>
                </div>

                <!-- emoji popover -->
                <div v-if="showEmoji" class="mb-2 grid max-h-40 grid-cols-8 gap-1 overflow-y-auto rounded-lg border border-gray-200 bg-white p-2 dark:border-gray-700 dark:bg-gray-900">
                    <button v-for="e in emojis" :key="e" type="button" @click="insertEmoji(e)" class="rounded p-1 text-lg hover:bg-gray-100 dark:hover:bg-gray-800">{{ e }}</button>
                </div>

                <form @submit.prevent="sendMessage" class="flex items-end gap-2">
                    <!-- toolbar -->
                    <div class="relative flex items-center gap-1">
                        <button type="button" @click="showEmoji = !showEmoji; showAttach = false" class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Smile class="h-5 w-5" />
                        </button>
                        <button type="button" @click="showAttach = !showAttach; showEmoji = false" class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Paperclip class="h-5 w-5" />
                        </button>
                        <!-- attach menu -->
                        <div v-if="showAttach" class="absolute bottom-12 left-0 z-10 w-44 rounded-xl border border-gray-200 bg-white p-1 shadow-lg dark:border-gray-700 dark:bg-gray-900">
                            <button type="button" @click="triggerAttach('image')" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                <ImageIcon class="h-4 w-4 text-emerald-500" /> Image
                            </button>
                            <button type="button" @click="triggerAttach('document')" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                <FileText class="h-4 w-4 text-red-500" /> PDF / Document
                            </button>
                            <button type="button" @click="triggerAttach('file')" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                <Paperclip class="h-4 w-4 text-blue-500" /> Any File
                            </button>
                        </div>
                        <button type="button" @click="startRecording" class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <Mic class="h-5 w-5" />
                        </button>
                        <input ref="fileInput" type="file" class="hidden" @change="onFilePicked" />
                    </div>

                    <textarea
                        v-model="newMessage"
                        rows="1"
                        :placeholder="!hasPhone ? 'No phone number set for this student' : !activeUrl ? 'WhatsApp not configured' : 'Type a message…'"
                        :disabled="!canChat || sending"
                        class="flex-1 rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500"
                        @input="onInput"
                        @keydown.enter.exact.prevent="sendMessage"
                    />

                    <Button type="submit" :disabled="!newMessage.trim() || sending || !canChat" class="h-11 w-11 shrink-0 rounded-full bg-emerald-600 p-0 text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:cursor-not-allowed disabled:opacity-50">
                        <Send v-if="!sending" class="h-4 w-4" />
                        <Loader2 v-else class="h-4 w-4 animate-spin" />
                    </Button>
                </form>
                <p v-if="!canChat" class="mt-2 flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                    <AlertCircle class="h-3.5 w-3.5" />
                    <span v-if="!hasPhone">This student has no phone number.</span>
                    <span v-else>WhatsApp is not configured for this student.</span>
                </p>
            </div>
        </div>
    </StudentLayout>
</template>
