<script setup lang="ts">
import { ArrowPathIcon, FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref, watch } from 'vue';

const props = defineProps<{
    filters?: {
        search?: string;
        platform?: string;
        sort?: string;
        formdate?: string;
        todate?: string;
    };
    total?: number;
}>();

const emit = defineEmits<{
    (e: 'filter', filters: { search: string; platform: string; sort: string; formdate?: string | null; todate?: string | null }): void;
    (e: 'refresh'): void;
}>();

const search = ref(props.filters?.search ?? '');
const platform = ref(props.filters?.platform ?? 'all');
const sort = ref(props.filters?.sort ?? 'latest');
const formdate = ref<any>(props.filters?.formdate ?? null);
const todate = ref<any>(props.filters?.todate ?? null);

const formatDate = (val: any) => {
    if (!val) return null;
    if (val instanceof Date && !isNaN(val.getTime())) {
        const year = val.getFullYear();
        const month = String(val.getMonth() + 1).padStart(2, '0');
        const day = String(val.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    if (typeof val === 'string') return val;
    return null;
};

let timeout: number;
watch(search, () => {
    clearTimeout(timeout);
    timeout = window.setTimeout(() => {
        emitFilter();
    }, 400);
});

watch([formdate, todate], () => {
    emitFilter();
});

const emitFilter = () => {
    emit('filter', {
        search: search.value,
        platform: platform.value,
        sort: sort.value,
        formdate: formatDate(formdate.value),
        todate: formatDate(todate.value),
    });
};

const refresh = () => {
    search.value = '';
    formdate.value = null;
    todate.value = null;
    platform.value = 'all';
    sort.value = 'latest';
    emitFilter();
    emit('refresh');
};
</script>

<template>
    <div class="border-b bg-white">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4">
            <div>
                <h2 class="text-center text-lg font-semibold text-slate-500">Conversations</h2>
                <p class="text-center text-sm text-slate-500">Manage all social conversations</p>
            </div>

            <button @click="refresh" class="rounded-xl border p-2 hover:bg-slate-100" title="Refresh">
                <ArrowPathIcon class="h-5 w-5" />
            </button>
        </div>

        <!-- Search -->
        <div class="px-6 pb-4">
            <div class="relative">
                <MagnifyingGlassIcon class="absolute top-3 left-3 h-5 w-5 text-slate-400" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by name, phone or message..."
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pr-4 pl-10 focus:border-blue-500 focus:outline-none"
                />
            </div>
        </div>

        <!-- Filters -->
        <div class="space-y-3 px-6 pb-5">
            <div class="grid grid-cols-2 gap-3">
                <!-- Platform -->
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> Platform </label>
                    <select v-model="platform" @change="emitFilter" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="all">All Platforms</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="messenger">Messenger</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> Sort </label>
                    <select v-model="sort" @change="emitFilter" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="unread">Unread First</option>
                    </select>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> From Date </label>
                    <VueDatePicker v-model="formdate" :format="'yyyy-MM-dd'" :enable-time-picker="false" placeholder="From Date" auto-apply />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase"> To Date </label>
                    <VueDatePicker v-model="todate" :format="'yyyy-MM-dd'" :enable-time-picker="false" placeholder="To Date" auto-apply />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between border-t bg-slate-50 px-6 py-3">
            <div class="text-sm text-slate-500">
                Showing <span class="font-semibold text-slate-700"> {{ total ?? 0 }} </span> conversations
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <FunnelIcon class="h-4 w-4" />
                Filters Active
            </div>
        </div>
    </div>
</template>
