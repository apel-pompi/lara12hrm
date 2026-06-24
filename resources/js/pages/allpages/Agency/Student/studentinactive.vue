<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { AlertCircle, ArrowRightLeft, CheckCheck, CheckSquare, Clock3, RefreshCcw, Send, Search, Users } from 'lucide-vue-next';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import axios from 'axios';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

export interface Student {
    id: number;
    photo: string;
    student_id: string;
    fname: string;
    lname: string;
    gender: number;
    phone: string;
    assain_user: number;
    source_id: number;
    stage_id: number;
    created_at: string;
    last_activity_at: string | null;
    status: number;
    assainuser?: { id: number; name: string };
    source?: { id: number; name: string };
    stage?: { id: number; name: string };
}

export interface UserOption {
    id: number;
    name: string;
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student', href: '/student' }];

const props = defineProps<{
    student: Paginated<Student>;
    period: '1month' | '3month' | '6month';
    users: UserOption[];
    filters: { name?: string; phone?: string; user?: number; created_at?: string };
    countAll: number;
    countLead: number;
    countPending: number;
    countProspect: number;
    countonBoard: number;
    countArchive: number;
    countInactive1Month: number;
    countInactive3Month: number;
    countInactive6Month: number;
    showInactiveTabs: boolean;
}>();

// ─── Period label ───────────────────────────────────────
const periodLabel = computed(() => {
    if (props.period === '1month') return '1 Month';
    if (props.period === '3month') return '3 Months';
    return '6 Months';
});

// ─── Colors / helpers ───────────────────────────────────
const colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-purple-700', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-yellow-400', 'bg-yellow-700'];

function getAvatarColor(name: string) {
    if (!name) return colors[0];
    return colors[name.charCodeAt(0) % colors.length];
}

const formatDate = (dateString: string | null) => {
    if (!dateString) return 'No activity';
    const date = new Date(dateString);
    return date.toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true });
};

const daysSince = (dateString: string | null): string => {
    if (!dateString) return '∞';
    const diff = Math.floor((Date.now() - new Date(dateString).getTime()) / 86400000);
    return `${diff} days`;
};

const getLastActivityOrEntryDate = (student: Student): string => {
    if (student.last_activity_at && !student.last_activity_at.includes('1900')) {
        return formatDate(student.last_activity_at);
    }
    return formatDate(student.created_at);
};

const getEffectiveLastActivityDate = (student: Student): string | null => {
    if (student.last_activity_at && !student.last_activity_at.includes('1900')) {
        return student.last_activity_at;
    }
    return student.created_at;
};

// ─── Tabs ───────────────────────────────────────────────
const page = usePage();
const active = ref('inactive1month');
const tabRefs = ref<HTMLElement[]>([]);

const tabs = computed(() => {
    const baseTabs = [
        { key: 'all',      label: 'All',     count: props.countAll },
        { key: 'pending',  label: 'Pending', count: props.countPending },
        { key: 'lead',     label: 'Lead',    count: props.countLead },
        { key: 'prospect', label: 'Prospect',count: props.countProspect },
        { key: 'onboard',  label: 'OnBoard', count: props.countonBoard },
        { key: 'archive',  label: 'Archive', count: props.countArchive },
    ];

    if (!props.showInactiveTabs) {
        return baseTabs;
    }

    return baseTabs.concat([
        { key: 'inactive1month', label: '1 Month+', count: props.countInactive1Month },
        { key: 'inactive3month', label: '3 Months+', count: props.countInactive3Month },
        { key: 'inactive6month', label: '6 Months+', count: props.countInactive6Month },
    ]);
});

const routes: Record<string, string> = {
    all:            'student.index',
    pending:        'student.pending',
    lead:           'student.lead',
    prospect:       'student.prospect',
    onboard:        'student.onBoard',
    archive:        'student.archive',
    inactive1month: 'student.inactive1month',
    inactive3month: 'student.inactive3month',
    inactive6month: 'student.inactive6month',
};

const indicatorStyle = ref({});

const updateIndicator = () => {
    const index = tabs.value.findIndex((t) => t.key === active.value);
    const el = tabRefs.value[index];
    if (!el) return;
    indicatorStyle.value = { width: el.offsetWidth + 'px', transform: `translateX(${el.offsetLeft}px)` };
};

const setActive = async (tab: string) => {
    active.value = tab;
    await nextTick();
    updateIndicator();
    router.get(route(routes[tab]), {}, { replace: true });
};

const setActiveFromUrl = () => {
    const url = page.url;
    if (url.includes('inactive/6month'))      active.value = 'inactive6month';
    else if (url.includes('inactive/3month')) active.value = 'inactive3month';
    else if (url.includes('inactive/1month')) active.value = 'inactive1month';
    else if (url.includes('pending'))         active.value = 'pending';
    else if (url.includes('lead'))            active.value = 'lead';
    else if (url.includes('prospect'))        active.value = 'prospect';
    else if (url.includes('onBoard'))         active.value = 'onboard';
    else if (url.includes('archive'))         active.value = 'archive';
    else                                      active.value = 'all';
};

onMounted(async () => {
    setActiveFromUrl();
    await nextTick();
    updateIndicator();
    window.addEventListener('resize', updateIndicator);
});

watch(active, async () => { await nextTick(); updateIndicator(); });

// ─── Pagination ─────────────────────────────────────────
const perPage = ref(10);

watch(perPage, (value) => {
    router.get(route(routes[active.value]), { per_page: value }, { preserveState: false, preserveScroll: true, replace: true });
});

const goToPage = (url: string | null) => {
    if (url) router.get(url, {}, { preserveState: false, replace: true });
};

const refresh = () => {
    router.get(route(routes[active.value]), {}, { replace: true });
};

// ─── Checkbox selection ──────────────────────────────────
const selectedIds = ref<number[]>([]);
const allChecked = computed(() => props.student.data.length > 0 && selectedIds.value.length === props.student.data.length);

const toggleAll = () => {
    if (allChecked.value) {
        selectedIds.value = [];
    } else {
        selectedIds.value = props.student.data.map((s) => s.id);
    }
};

const toggleOne = (id: number) => {
    const idx = selectedIds.value.indexOf(id);
    if (idx >= 0) selectedIds.value.splice(idx, 1);
    else selectedIds.value.push(id);
};

// ─── Transfer Form ───────────────────────────────────────
const transferForm = useForm({
    to_user_id:   null as number | null,
    student_ids:  [] as number[],
    transfer_all: false,
    period:       props.period,
});

const selectedStudent = ref<Student | null>(null);
const selectedPhone = ref<Student | null>(null);
const selectedAssain = ref<UserOption | null>(null);
const selectedDate = ref<string | null>(null);

const nameResults = ref<Student[]>([]);
const phoneResults = ref<Student[]>([]);
const assainResults = ref<UserOption[]>([]);
const dateResults = ref<string[]>([]);

const displayStudent = (s: Student | null) => (s ? `${s.fname} ${s.lname}` : '');
const displayPhone = (s: Student | null) => s?.phone ?? '';
const displayAssain = (s: UserOption | null) => s?.name ?? '';
const displayDate = (d: string | null) => d ?? '';

const onNameInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    searchStudents('name', value);
};

const onPhoneInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    searchStudents('phone', value);
};

const onAssainInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    searchStudents('assain', value);
};

const onDateInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value;
    searchStudents('date', value);
};

let timer: ReturnType<typeof setTimeout> | null = null;

const fetchStudents = async (type: 'name' | 'phone' | 'assain' | 'date', query: string = '') => {
    try {
        const res = await axios.get<Student[]>('/student/SearchInactive', {
            params: { type, q: query, period: props.period },
        });

        switch (type) {
            case 'name':
                nameResults.value = res.data;
                break;
            case 'phone':
                phoneResults.value = res.data;
                break;
            case 'assain':
                const map = new Map<number, UserOption>();
                res.data.forEach((s) => {
                    if (s.assainuser && !map.has(s.assainuser.id)) {
                        map.set(s.assainuser.id, s.assainuser as UserOption);
                    }
                });
                assainResults.value = Array.from(map.values());
                break;
            case 'date':
                const dates = new Map<string, string>();
                res.data.forEach((s) => {
                    if (!s.created_at) return;
                    const date = s.created_at.slice(0, 10);
                    if (!dates.has(date)) {
                        dates.set(date, date);
                    }
                });
                dateResults.value = Array.from(dates.keys()).sort((a, b) => b.localeCompare(a));
                break;
        }
    } catch (e) {
        console.error(e);
    }
};

const searchStudents = (type: 'name' | 'phone' | 'assain' | 'date', query: string) => {
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        fetchStudents(type, query);
    }, 250);
};

const showAllStudents = (type: 'name' | 'phone' | 'assain' | 'date') => {
    fetchStudents(type, '');
};

const search = () => {
    const params: Record<string, any> = {};
    if (selectedStudent.value) {
        params.name = `${selectedStudent.value.fname} ${selectedStudent.value.lname}`.trim();
    }
    if (selectedPhone.value) params.phone = selectedPhone.value.phone;
    if (selectedAssain.value) params.user = selectedAssain.value.id;
    if (selectedDate.value) params.created_at = selectedDate.value;

    router.get(route(routes[active.value]), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const transferMode = ref<'selected' | 'all'>('selected');
const transferSuccess = ref('');
const transferError   = ref('');

const submitTransfer = () => {
    transferSuccess.value = '';
    transferError.value   = '';

    if (!transferForm.to_user_id) {
        transferError.value = 'Please select a user.';
        return;
    }

    if (transferMode.value === 'all') {
        transferForm.transfer_all = true;
        transferForm.student_ids  = [];
    } else {
        if (selectedIds.value.length === 0) {
            transferError.value = 'Please select at least one lead.';
            return;
        }
        transferForm.transfer_all = false;
        transferForm.student_ids  = [...selectedIds.value];
    }

    transferForm.period = props.period;

    transferForm.post(route('student.transferInactiveLeads'), {
        preserveScroll: true,
        onSuccess: () => {
            selectedIds.value   = [];
            transferForm.reset('to_user_id');
            transferSuccess.value = 'Lead(s) transferred successfully.';
            setTimeout(() => refresh(), 1000);
        },
        onError: () => {
            transferError.value = 'Failed to transfer leads.';
        },
    });
};
</script>

<template>
    <Head title="Inactive Leads" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <!-- ── Tab Bar ── -->
            <div class="flex justify-center">
                <div class="no-scrollbar relative mb-4 flex overflow-x-auto rounded-full bg-gray-100/80 p-1 dark:bg-gray-800/80">
                    <div
                        class="absolute top-1 bottom-1 rounded-full bg-white shadow transition-all duration-300 ease-out dark:bg-gray-900"
                        :style="indicatorStyle"
                    ></div>
                    <button
                        v-for="(tab, index) in tabs"
                        :key="tab.key"
                        @click="setActive(tab.key)"
                        :ref="(el) => (tabRefs[index] = el as HTMLElement)"
                        class="relative z-10 cursor-pointer px-4 py-1.5 text-sm font-medium whitespace-nowrap transition-all duration-200"
                        :class="[
                            active === tab.key ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 hover:text-indigo-500 dark:text-gray-300',
                            tab.key.startsWith('inactive') ? 'text-indigo-600' : '',
                        ]"
                    >
                        {{ tab.label }}
                        <span class="ml-1 text-xs opacity-70">({{ tab.count }})</span>
                    </button>
                </div>
            </div>

            <!-- ── Search Panel ── -->
            <div class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900">
                <div class="grid w-full gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6">
                    <Combobox v-model="selectedStudent" as="div" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Search student..."
                                :display-value="(s: any) => displayStudent(s)"
                                @input="onNameInput"
                                @focus="() => showAllStudents('name')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('name')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="nameResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in nameResults" :key="s.id" :value="s" class="option">
                                    {{ s.fname }} {{ s.lname }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <Combobox v-model="selectedPhone" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Search phone..."
                                :display-value="(s: any) => displayPhone(s)"
                                @input="onPhoneInput"
                                @focus="() => showAllStudents('phone')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('phone')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="phoneResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in phoneResults" :key="s.id" :value="s" class="option">
                                    {{ s.phone }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <Combobox v-model="selectedAssain" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Assign user..."
                                :display-value="(s: any) => displayAssain(s)"
                                @input="onAssainInput"
                                @focus="() => showAllStudents('assain')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('assain')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="assainResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="s in assainResults" :key="s.id" :value="s" class="option">
                                    {{ s.name }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <Combobox v-model="selectedDate" class="w-full">
                        <div class="relative w-full">
                            <ComboboxInput
                                class="input"
                                placeholder="Entry date..."
                                :display-value="(d: any) => displayDate(d)"
                                @input="onDateInput"
                                @focus="() => showAllStudents('date')"
                            />
                            <ComboboxButton class="icon-btn" @click="() => showAllStudents('date')">
                                <ChevronUpDownIcon class="icon" />
                            </ComboboxButton>

                            <ComboboxOptions class="dropdown">
                                <div v-if="dateResults.length === 0" class="empty">Searching...</div>
                                <ComboboxOption v-for="d in dateResults" :key="d" :value="d" class="option">
                                    {{ d }}
                                </ComboboxOption>
                            </ComboboxOptions>
                        </div>
                    </Combobox>

                    <div class="flex flex-col items-stretch justify-end gap-2 sm:flex-row">
                        <Button class="btn-primary w-full cursor-pointer sm:w-auto" @click="search">
                            <Search class="mr-1 h-4 w-4" /> Search
                        </Button>
                        <Button class="w-full cursor-pointer sm:w-auto" @click="refresh">
                            <RefreshCcw class="mr-1 h-4 w-4" /> Refresh
                        </Button>
                    </div>
                </div>
            </div>

            <!-- ── Lead Transfer Panel ── -->
            <div class="mb-6 flex flex-col items-center justify-center gap-3 rounded-md border border-gray-300 bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center dark:border-gray-700 dark:bg-gray-900">
                <!-- Header -->
                <div class="flex items-center gap-3 border-b border-orange-100 bg-gradient-to-r from-indigo-500 to-indigo-500 px-6 py-3 dark:border-orange-900/50 dark:from-indigo-900 dark:to-indigo-900">
                    <ArrowRightLeft class="h-5 w-5 text-white" />
                    <h3 class="font-semibold text-white">
                        Lead Transfer — {{ periodLabel }}+ Inactive
                        <span class="ml-2 rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ student.total }} leads</span>
                    </h3>
                </div>

                <!-- Body -->
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-end sm:flex-wrap">
                    <!-- Transfer Mode -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Transfer Mode</label>
                        <div class="flex gap-2">
                            <button
                                @click="transferMode = 'selected'"
                                :class="[
                                    'flex items-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition',
                                    transferMode === 'selected'
                                        ? 'border-orange-400 bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-orange-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                ]"
                            >
                                <CheckSquare class="h-4 w-4" />
                                Selected ({{ selectedIds.length }})
                            </button>
                            <button
                                @click="transferMode = 'all'"
                                :class="[
                                    'flex items-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition',
                                    transferMode === 'all'
                                        ? 'border-orange-400 bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300'
                                        : 'border-gray-200 bg-white text-gray-600 hover:border-orange-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                ]"
                            >
                                <CheckCheck class="h-4 w-4" />
                                All ({{ student.total }})
                            </button>
                        </div>
                    </div>

                    <!-- Target User -->
                    <div class="flex flex-col gap-1 min-w-48">
                        <label class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Transfer to →</label>
                        <Select v-model="transferForm.to_user_id">
                            <SelectTrigger class="h-10 border-gray-200 bg-white text-sm dark:border-gray-700 dark:bg-gray-800">
                                <SelectValue placeholder="Select a user..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="u in users" :key="u.id" :value="u.id">
                                    {{ u.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Submit -->
                    <Button
                        @click="submitTransfer"
                        :disabled="transferForm.processing"
                        class="flex h-10 cursor-pointer items-center gap-2 bg-indigo-500 text-white hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500"
                    >
                        <Send class="h-4 w-4" />
                        {{ transferForm.processing ? 'Processing...' : 'Transfer' }}
                    </Button>

                    <Button @click="refresh" variant="outline" class="h-10 cursor-pointer">
                        <RefreshCcw class="mr-1 h-4 w-4" />
                        Refresh
                    </Button>
                </div>

                <!-- Alerts -->
                <div v-if="transferSuccess" class="mx-5 mb-4 flex items-center gap-2 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-300">
                    <CheckCheck class="h-4 w-4" />
                    {{ transferSuccess }}
                </div>
                <div v-if="transferError" class="mx-5 mb-4 flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">
                    <AlertCircle class="h-4 w-4" />
                    {{ transferError }}
                </div>
            </div>

            <!-- ── Table ── -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-center justify-between border-b px-6 py-4 dark:border-gray-700">
                    <div>
                        <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-gray-100">
                            <Clock3 class="h-5 w-5 text-orange-500" />
                            {{ periodLabel }}+ No Activity
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">These leads have had no activity for more than {{ periodLabel }}.</p>
                    </div>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-semibold text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                        {{ student.total }} leads
                    </span>
                </div>

                <Table>
                    <TableHeader>
                        <TableRow class="bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800">
                            <TableHead class="w-10 px-4">
                                <input type="checkbox" :checked="allChecked" @change="toggleAll" class="h-4 w-4 rounded border-gray-300 text-orange-500" />
                            </TableHead>
                            <TableHead class="px-4 font-semibold">Student Name</TableHead>
                            <TableHead class="px-4 font-semibold">Phone</TableHead>
                            <TableHead class="px-4 font-semibold">Source</TableHead>
                            <TableHead class="px-4 font-semibold">Assigned To</TableHead>
                            <TableHead class="px-4 font-semibold">Entry Date</TableHead>
                            <TableHead class="px-4 font-semibold text-orange-600">Last Activity</TableHead>
                            <TableHead class="px-4 font-semibold text-orange-600">Inactive Duration</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(stud, index) in student.data"
                            :key="stud.id ?? index"
                            :class="selectedIds.includes(stud.id) ? 'bg-orange-50 dark:bg-orange-900/10' : ''"
                            class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                        >
                            <TableCell class="px-4">
                                <input
                                    type="checkbox"
                                    :checked="selectedIds.includes(stud.id)"
                                    @change="toggleOne(stud.id)"
                                    class="h-4 w-4 rounded border-gray-300 text-orange-500"
                                />
                            </TableCell>
                            <TableCell class="px-4">
                                <Link :href="route('studentActivities.index', stud.id)" class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <img v-if="stud.photo" :src="`/storage/student/${stud.photo}`" class="h-9 w-9 rounded-full object-cover" />
                                        <div
                                            v-else
                                            :class="['flex h-9 w-9 items-center justify-center rounded-full font-semibold text-white text-sm', getAvatarColor(stud.fname)]"
                                        >
                                            {{ stud.fname?.charAt(0) }}{{ stud.lname?.charAt(0) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ stud.fname }} {{ stud.lname }}</div>
                                        <div v-if="stud.stage?.name" class="text-xs text-gray-400">{{ stud.stage.name }}</div>
                                    </div>
                                </Link>
                            </TableCell>
                            <TableCell class="px-4 text-gray-600 dark:text-gray-300">{{ stud.phone }}</TableCell>
                            <TableCell class="px-4 text-gray-500 dark:text-gray-400">{{ stud.source?.name ?? '—' }}</TableCell>
                            <TableCell class="px-4">
                                <div class="flex items-center gap-1.5">
                                    <Users class="h-3.5 w-3.5 text-gray-400" />
                                    <span class="text-gray-700 dark:text-gray-300">{{ stud.assainuser?.name ?? '—' }}</span>
                                </div>
                            </TableCell>
                            <TableCell class="px-4 text-xs text-gray-500 dark:text-gray-400">{{ formatDate(stud.created_at) }}</TableCell>
                            <TableCell class="px-4">
                                <span class="rounded bg-orange-50 px-2 py-0.5 text-xs text-orange-700 dark:bg-orange-900/20 dark:text-orange-300">
                                    {{ getLastActivityOrEntryDate(stud) }}
                                </span>
                            </TableCell>
                            <TableCell class="px-4">
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/20 dark:text-red-300">
                                    {{ daysSince(getEffectiveLastActivityDate(stud)) }}
                                </span>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="student.data.length === 0">
                            <TableCell colspan="8" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                <Clock3 class="mx-auto mb-2 h-8 w-8 opacity-30" />
                                <p>No inactive leads found for this period.</p>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- ── Pagination ── -->
            <div class="mt-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        <span>Show</span>
                        <Select v-model="perPage">
                            <SelectTrigger class="h-8 w-20 text-sm">
                                <SelectValue placeholder="10" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</SelectItem>
                            </SelectContent>
                        </Select>
                        <span>entries</span>
                    </div>
                    <div>
                        Showing
                        <span class="font-medium text-gray-900 dark:text-white">{{ student.from }}</span>
                        to
                        <span class="font-medium text-gray-900 dark:text-white">{{ student.to }}</span>
                        of
                        <span class="font-medium text-gray-900 dark:text-white">{{ student.total }}</span>
                        results
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-2 md:justify-end">
                    <button
                        v-for="(link, index) in student.links"
                        :key="index"
                        @click="goToPage(link.url)"
                        :disabled="!link.url"
                        v-html="link.label"
                        :class="[
                            'rounded-md border px-3 py-1.5 text-sm transition',
                            link.active
                                ? 'border-orange-500 bg-orange-500 text-white shadow'
                                : 'bg-white text-gray-600 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                            !link.url ? 'cursor-not-allowed opacity-50' : '',
                        ]"
                    ></button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
