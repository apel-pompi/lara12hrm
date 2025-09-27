<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import ProductLayout from '@/pages/allpages/Agency/Product/productlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { SquarePen } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    product: { id: number; status: string };
    academic: { id: number; name: string };
    requirement: { id: number; scoretype: string; score: string }[];
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

const showDialog = ref(false);
const isEditAcademic = ref(false);
const editingId = ref<number | null>(null);
// Combobox states
const selecteName = ref(null); // name
const queryName = ref('');

// Filtered lists
const filteredName = computed(() => (queryName.value === '' ? props.academic : props.academic.filter((n) => n.name)));

const form = useForm({
    id: null as number | null,
    product_id: '',
    degree_id: '',
    scoretype: '',
    score: '',
});

const onCreateAcademic = () => {
    editingId.value = null;
    selecteName.value = null;
    scoreType.value = 'percentage';
    academicScore.value = null;

    showDialog.value = true;
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

    showDialog.value = true;
};

const submit = () => {
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

            showDialog.value = false;
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
                    <button class="rounded-md bg-blue-500 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-600">Edit</button>
                </div>
                <!-- Table Header -->
                <div class="grid grid-cols-5 border-b pb-2 text-xs font-medium text-gray-500">
                    <p class="text-center">LISTENING</p>
                    <p class="text-center">READING</p>
                    <p class="text-center">WRITING</p>
                    <p class="text-center">SPEAKING</p>
                    <p class="text-center">OVERALL</p>
                </div>

                <!-- TOEFL Row -->
                <div class="grid grid-cols-5 items-center border-b py-3 text-sm text-gray-700">
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <div class="flex justify-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-400 text-xs text-white">-</span>
                    </div>
                </div>

                <!-- IELTS Row -->
                <div class="grid grid-cols-5 items-center border-b py-3 text-sm text-gray-700">
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <div class="flex justify-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-400 text-xs text-white">-</span>
                    </div>
                </div>

                <!-- PTE Row -->
                <div class="grid grid-cols-5 items-center py-3 text-sm text-gray-700">
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <p class="text-center">-</p>
                    <div class="flex justify-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-400 text-xs text-white">-</span>
                    </div>
                </div>
            </div>

            <!-- Other Test Scores -->
            <div class="rounded-xl bg-white p-4 shadow sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Other Test Scores</h2>
                    <button class="rounded-md bg-blue-500 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-600">Edit</button>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div class="flex flex-col items-center">
                        <p class="text-xs font-medium text-gray-600">SAT I</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-400 text-sm text-white">-</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <p class="text-xs font-medium text-gray-600">SAT II</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-400 text-sm text-white">-</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <p class="text-xs font-medium text-gray-600">GRE</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-400 text-sm text-white">-</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <p class="text-xs font-medium text-gray-600">GMAT</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-400 text-sm text-white">-</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Academic Dialog -->
        <Dialog v-model:open="showDialog">
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
                        <p v-if="form.errors.degree" class="text-xs text-red-600">
                            {{ form.errors.degree }}
                        </p>
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
                    <Button :disabled="!scoreType || academicScore === null" @click="submit" variant="default"> Update </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </ProductLayout>
</template>
