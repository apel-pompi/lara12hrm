<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker  from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Plus, SquarePen } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    totalNetAmount: number;
    total_payable: number;
    total_income: number;
    created_month: string;
    created_day: string;
    created_year: string;
}>();

const startdate = ref<string | null>(null);

const enddate = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

const form = useForm({
    startdate: '',
    enddate: '',
});
watch(startdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.startdate = newDate.toISOString().split('T')[0];
    }
});
watch(enddate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.enddate = newDate.toISOString().split('T')[0];
    }
});
</script>

<template>
    <aside class="space-y-4">
        <!-- Applied Intake card -->
        <div class="border bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <div class="text-xs text-slate-400">Applied Intake</div>
                    <div class="flex items-center gap-2 text-sm">
                        <span>Select date:</span>
                        <VueDatePicker
                            v-model="enddate"
                            :max-date="maxDate"
                            :format="'yyyy-MM-dd'"
                            :enable-time-picker="false"
                            placeholder="Start Date"
                            auto-apply
                            class="w-36 rounded-md border border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>
                </div>
            </div>

            <!-- Date boxes -->
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Start Date Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition hover:shadow-md">
                    <div class="text-xs font-medium tracking-wide text-slate-500">START</div>
                    <div class="mt-1 text-lg font-semibold text-slate-800">
                        {{ created_month }}
                        <span class="block text-3xl leading-none text-blue-600">{{ created_day }}</span>
                        {{ created_year }}
                    </div>
                </div>

                <!-- End Date Card -->
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center transition hover:bg-slate-100">
                    <div class="text-xs font-medium tracking-wide text-slate-500">END</div>
                    <div class="mt-1 cursor-pointer text-lg font-semibold text-blue-500">+ ADD</div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-5">
                <Button variant="default" size="lg" class="w-full gap-2">
                    <Plus class="h-4 w-4" />
                    Setup Payment Schedule
                </Button>
            </div>
        </div>

        <!-- Product Fees card -->
        <div class="bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h5 class="text-sm font-semibold">Product Fees</h5>
                <Button variant="outline" size="sm"><SquarePen /></Button>
            </div>
            <div class="mt-3 space-y-2 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Total Fee</span><span class="font-medium">{{ props.totalNetAmount }}</span>
                </div>
                <div class="flex justify-between"><span>Discount</span><span class="text-red-500">0.00</span></div>
                <div class="flex justify-between">
                    <span class="font-semibold">Net Fee</span><span class="font-semibold">{{ props.totalNetAmount }}</span>
                </div>
            </div>
        </div>

        <!-- Sales Forecast -->
        <div class="bg-white p-4 shadow-sm">
            <h5 class="text-sm font-semibold">Sales Forecast <span class="ml-2 text-xs text-green-600">EUR</span></h5>
            <div class="mt-3 space-y-2 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Partner Revenue</span><span>{{ props.total_payable }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Company Revenue</span><span>{{ props.total_income }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Net Revenue</span><span>{{ props.totalNetAmount }}</span>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="rounded-lg border bg-white p-4 text-center shadow-sm">
            <Button variant="default" size="lg" class="w-full gap-2"> View Application Work Ratio </Button>
        </div>
    </aside>
</template>
