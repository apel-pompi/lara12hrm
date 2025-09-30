<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import ProductLayout from '@/pages/allpages/Agency/Product/productlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import axios from 'axios';
import { SquarePen } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    product: { id: number; status: string };
    academic: { id: number; name: string };
    requirement: { id: number; scoretype: string; score: string }[];
    requirementEnglish: Array<{
        id: number;
        name: string;
        listening: number | null;
        reading: number | null;
        writing: number | null;
        speaking: number | null;
        overall: number | null;
    }>;
    requirementOthers:Array<{
        id:number;
        name:string;
        scores:number | null;
    }>
}>();

const scoreType = ref(''); // percentage, gpa, cgpa
const academicScore = ref<number | null>(null);

// Dynamic placeholder

const scorePlaceholder = computed(() => {
    if (scoreType.value === 'percentage') return 'Enter percentage';
    if (scoreType.value === 'gpa') return 'Enter GPA';
    if (scoreType.value === 'cgpa') return 'Enter CGPA';
    return 'Select type first';
});

const showDialogAcademic = ref(false);
const editingId = ref<number | null>(null);

// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.academic : props.academic.filter((n) => n.name)));

const onCreateAcademic = () => {
    editingId.value = null;
    selecteName.value = null;
    scoreType.value = 'percentage';
    academicScore.value = null;

    showDialogAcademic.value = true;
};

const onEditAcademic = async (id: number) => {
    if (!props.requirement || props.requirement.length === 0) return;
    const req = props.requirement.find((r) => r.id === id);
    if (!req) return;

    editingId.value = req.id;

    // Prefill values
    selecteName.value = props.academic?.find((d) => d.id === req.degree_id) || null;
    scoreType.value = req.scoretype;
    academicScore.value = req.score;

    showDialogAcademic.value = true;
};

const submitAcademic = () => {
    const params: Record<string, any> = {};
    if (selecteName.value) params.id = selecteName.value.id;

    const payload = {
        product_id: props.product.id,
        degree_id: params.id,
        scoretype: scoreType.value,
        score: academicScore.value,
        id: editingId.value ?? null,
    };

    axios
        .post(`/product/activities/${props.product.id}/requirement`, payload)
        .then((res) => {
            toast('Success', {
                description: res.data.message,
            });

            showDialogAcademic.value = false;
            editingId.value = null;
            academicScore.value = null;
            selecteName.value = null;
            scoreType.value = 'percentage';

            // Table update
            if (!props.requirement) props.requirement = [];
            const index = props.requirement.findIndex((r) => r.id === res.data.requirement.id);
            if (index > -1) {
                // Update existing
                props.requirement[index] = res.data.requirement;
            } else {
                // Add new
                props.requirement.push(res.data.requirement);
            }
        })
        .catch((err) => {
            toast('Error', {
                description: 'Failed to save requirement.' + err,
            });
        });
};

const requirementEnglish = ref([...props.requirementEnglish]);
const showDialogEnglish = ref(false);
const editingIdEng = ref<number | null>(null);

watch(
    () => props.requirementEnglish,
    (newVal) => {
        requirementEnglish.value = [...newVal];
    },
    { deep: true },
);

const testTypes = ref([
    { name: 'TOEFL', listening: null, reading: null, writing: null, speaking: null, overall: null },
    { name: 'IELTS', listening: null, reading: null, writing: null, speaking: null, overall: null },
    { name: 'PTE', listening: null, reading: null, writing: null, speaking: null, overall: null },
]);

const openEnglishDialog = () => {
    testTypes.value.forEach((test) => {
        test.listening = null;
        test.reading = null;
        test.writing = null;
        test.speaking = null;
        test.overall = null;
    });

    if (props.requirementEnglish.length > 0) {
        props.requirementEnglish.forEach((req) => {
            const match = testTypes.value.find((t) => t.name === req.name);
            if (match) {
                match.listening = req.listening;
                match.reading = req.reading;
                match.writing = req.writing;
                match.speaking = req.speaking;
                match.overall = req.overall;
            }
        });
    }

    showDialogEnglish.value = true;
};

const submitEnglish = () => {
    const payload = {
        product_id: props.product.id,
        testTypes: testTypes.value,
    };

    axios
        .post(`/product/activities/${props.product.id}/requirementEng`, payload)
        .then((res) => {
            toast('Success', { description: res.data.message });

            showDialogEnglish.value = false;
            editingIdEng.value = null;

            requirementEnglish.value = res.data.requirementEnglish;
        })
        .catch((err) => {
            toast('Error', { description: 'Failed to save requirement.' + err.message });
        });
};

const requirementOthers = ref([...props.requirementOthers]);
const showDialogOthers = ref(false);
const editingIdOthers = ref<number | null>(null);
const othersTypes = ref([
    { name: 'SAT I', scores: null },
    { name: 'SAT II', scores: null },
    { name: 'GRE', scores: null },
    { name: 'GMAT', scores: null },
]);
const openOthersDialog = () => {
    othersTypes.value.forEach((test) => {
        test.scores = null;
    });

    if (props.requirementOthers.length > 0) {
        props.requirementOthers.forEach((req) => {
            const match = othersTypes.value.find((t) => t.name === req.name);
            if (match) {
                match.scores = req.scores;
            }
        });
    }

    showDialogOthers.value = true;
};
const submitOthers = () => {
    const payload = {
        product_id: props.product.id,
        othersTypes: othersTypes.value,
    };

    axios
        .post(`/product/activities/${props.product.id}/requirementOthers`, payload)
        .then((res) => {
            toast('Success', { description: res.data.message });

            showDialogOthers.value = false;
            editingIdOthers.value = null;

            requirementOthers.value = res.data.requirementOthers;
        })
        .catch((err) => {
            toast('Error', { description: 'Failed to save requirement.' + err.message });
        });
};

watch(
    () => props.requirementOthers,
    (newVal) => {
        requirementOthers.value = [...newVal];
    },
    { deep: true },
);
</script>

<template>
    <ProductLayout :product="props.product">
        <div class="space-y-6">
            <!-- Academic Requirements -->
            <div class="rounded-xl bg-white p-4 shadow sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Academic Requirements</h2>
                    <Button
                        size="sm"
                        varient="default"
                        @click="props.requirement[0]?.id ? onEditAcademic(props.requirement[0].id) : onCreateAcademic()"
                    >
                        <SquarePen></SquarePen>Edit</Button
                    >
                </div>
                <div v-for="item in props.requirement" :key="item.id" class="grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="font-semibold text-gray-700">Degree Level</p>

                        <p class="text-gray-600">{{ item.degree?.name || '-' }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Academic Score</p>
                        <p class="text-gray-600">{{ item.scoretype }} ( {{ item.score }} )</p>
                    </div>
                </div>
            </div>

            <!-- English Test Scores -->
            <div class="rounded-xl bg-white p-4 shadow sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">English Test Scores</h2>
                    <Button size="sm" variant="default" @click="openEnglishDialog">
                        <SquarePen /> {{ props.requirementEnglish.length > 0 ? 'Edit' : 'Create' }}
                    </Button>
                </div>
                <!-- Table Header -->
                <div class="grid grid-cols-6 border-b pb-2 text-xs font-medium text-gray-500">
                    <p class="border-b-0 text-center"></p>
                    <p class="text-center">LISTENING</p>
                    <p class="text-center">READING</p>
                    <p class="text-center">WRITING</p>
                    <p class="text-center">SPEAKING</p>
                    <p class="text-center">OVERALL</p>
                </div>

                <!-- Row -->
                <div v-for="re in requirementEnglish" :key="re.id" class="grid grid-cols-6 items-center py-3 text-sm text-gray-700">
                    <p class="text-center">{{ re.name ?? '-' }}</p>
                    <p class="text-center">{{ re.listening ?? '-' }}</p>
                    <p class="text-center">{{ re.reading ?? '-' }}</p>
                    <p class="text-center">{{ re.writing ?? '-' }}</p>
                    <p class="text-center">{{ re.speaking ?? '-' }}</p>
                    <div class="flex justify-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-400 text-xs text-white">{{
                            re.overall ?? '-'
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- Other Test Scores -->
            <div class="rounded-xl bg-white p-4 shadow sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Other Test Scores</h2>
                    <Button size="sm" variant="default" @click="openOthersDialog">
                        <SquarePen /> {{ props.requirementOthers.length > 0 ? 'Edit' : 'Create' }}
                    </Button>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div v-for="others in requirementOthers" :key="others.id" class="flex flex-col items-center">
                        <p class="text-xs font-medium text-gray-600">{{ others.name ?? '-' }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-400 text-sm text-white">{{ others.scores ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Academic Dialog -->
        <Dialog v-model:open="showDialogAcademic">
            <DialogContent class="max-w-md rounded-lg bg-white shadow-xl">
                <!-- Header -->
                <DialogHeader class="border-b border-gray-200 px-6 py-4">
                    <DialogTitle class="text-lg font-semibold text-gray-800"> Academic Requirements </DialogTitle>
                </DialogHeader>

                <!-- Body -->
                <div class="space-y-5 px-6 py-5">
                    <!-- Degree Level -->
                    <div class="space-y-1">
                        <Label for="degree" class="text-sm font-medium text-gray-700"> Degree Level <span class="text-red-500">*</span> </Label>
                        <Combobox v-model="selecteName">
                            <div class="relative">
                                <!-- Input -->
                                <div class="relative w-full">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select name..."
                                        :display-value="(n) => n?.name"
                                        @input="queryName = $event.target.value"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                </div>

                                <!-- Options -->
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredName.length === 0 && queryName !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="n in filteredName"
                                        :key="n.id"
                                        :value="n"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ n.name }}
                                        </span>
                                        <span
                                            v-if="selected"
                                            class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                    </div>

                    <!-- Academic Score -->
                    <div class="space-y-2">
                        <Label class="block text-sm font-medium text-gray-700"> Academic Score </Label>
                        <div class="flex items-center gap-6">
                            <Label class="flex items-center gap-2">
                                <input type="radio" value="percentage" v-model="scoreType" class="h-4 w-4 accent-blue-500" />
                                <span class="text-sm text-gray-700">Percentage</span>
                            </Label>

                            <Label class="flex items-center gap-2">
                                <input type="radio" value="gpa" v-model="scoreType" class="h-4 w-4 accent-blue-500" />
                                <span class="text-sm text-gray-700">GPA</span>
                            </Label>

                            <Label class="flex items-center gap-2">
                                <input type="radio" value="cgpa" v-model="scoreType" class="h-4 w-4 accent-blue-500" />
                                <span class="text-sm text-gray-700">CGPA</span>
                            </Label>

                            <input
                                v-model.number="academicScore"
                                type="number"
                                :placeholder="scorePlaceholder"
                                :disabled="!scoreType"
                                class="focus:ring-opacity-50 w-28 rounded-md border border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                            />
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="flex justify-end space-x-3 border-t border-gray-200 px-6 py-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary"> Cancel </Button>
                    </DialogClose>
                    <Button :disabled="!scoreType || academicScore === null" @click="submitAcademic" variant="default"> Update </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <!-- English Test Scores -->
        <Dialog v-model:open="showDialogEnglish">
            <DialogContent class="max-w-md rounded-lg bg-white shadow-xl">
                <!-- Header -->
                <DialogHeader class="border-b border-gray-200 px-6 py-4">
                    <DialogTitle class="text-base font-semibold text-gray-800">English Test Scores</DialogTitle>
                </DialogHeader>

                <!-- Body -->
                <div class="overflow-x-auto text-sm">
                    <table class="w-full text-left">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="p-2"></th>
                                <th class="p-2">Listening</th>
                                <th class="p-2">Reading</th>
                                <th class="p-2">Writing</th>
                                <th class="p-2">Speaking</th>
                                <th class="p-2 font-semibold text-green-600">Overall Scores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- TOEFL Row -->
                            <tr v-for="(test, index) in testTypes" :key="index">
                                <td class="py-2 pr-6 text-sm font-medium">{{ test.name }}</td>
                                <td class="p-2">
                                    <input type="number" v-model="test.listening" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                                <td class="p-2">
                                    <input type="number" v-model="test.reading" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                                <td class="p-2">
                                    <input type="number" v-model="test.writing" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                                <td class="p-2">
                                    <input type="number" v-model="test.speaking" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                                <td class="p-2">
                                    <input type="number" v-model="test.overall" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <DialogFooter class="flex justify-end space-x-3 border-t border-gray-200 px-6 py-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button @click="submitEnglish" variant="default"> Update </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <!-- Others Test Scores -->
        <Dialog v-model:open="showDialogOthers">
            <DialogContent class="max-w-md rounded-lg bg-white shadow-xl">
                <!-- Header -->
                <DialogHeader class="border-b border-gray-200 px-6 py-4">
                    <DialogTitle class="text-base font-semibold text-gray-800">Others Test Scores</DialogTitle>
                </DialogHeader>

                <!-- Body -->
                <div class="overflow-x-auto text-sm">
                    <table class="w-full text-left">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="p-2"></th>
                                <th class="p-2">Scores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- TOEFL Row -->
                            <tr v-for="(test, index) in othersTypes" :key="index">
                                <td class="py-2 pr-6 text-sm font-medium">{{ test.name }}</td>
                                <td class="p-2">
                                    <input type="number" v-model="test.scores" class="w-full rounded border px-2 py-1 text-sm" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <DialogFooter class="flex justify-end space-x-3 border-t border-gray-200 px-6 py-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button @click="submitOthers" variant="default"> Update </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </ProductLayout>
</template>
