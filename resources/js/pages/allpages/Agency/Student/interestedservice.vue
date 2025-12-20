<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router, useForm } from '@inertiajs/vue3';
import VueDatePicker  from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import { CircleDot, MoreVertical, Plus } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: { id: number; status: string };
    workflow: Array<{ id: number; name: string }>;
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

interface FormErrors {
    workflow_id?: string;
    partner_branch_id?: string;
    product_id: string;
}
const form = useForm({
    id: null as number | null,
    student_id: props.student.id,
    workflow_id: null as number | null,
    partner_branch_id: null as number | null,
    product_id: null as number | null,
    startdate: null as string | null,
    enddate: null as string | null,
    status: null as number | null,
    user_id: null as number | null,
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

// Combobox states
const selectedWorkflow = ref<{ id: number; name: string } | null>(null);
const queryWorkflow = ref('');

const filteredWorkflow = computed(() =>
    queryWorkflow.value === '' ? props.workflow : props.workflow.filter((c) => c.name.toLowerCase().includes(queryWorkflow.value.toLowerCase())),
);

const partner = ref<{ partnerid: number; partnername: string; partnerbranchid: number; partnerbranch: string }[]>([]);
const fetchPartner = async () => {
    if (!selectedWorkflow.value) return;

    const res = await fetch(
        route('studentApplication.partner', {
            student: props.student.id,
            partner: selectedWorkflow.value.id,
        }),
    );

    partner.value = await res.json();
};

watch(selectedWorkflow, async () => {
    await fetchPartner();
});

const selectedPartner = ref<{ partnerid: number; partnername: string; partnerbranchid: number; partnerbranch: string } | null>(null);
const queryPartner = ref('');

const filteredPartner = computed(() =>
    queryPartner.value === '' ? partner.value : partner.value.filter((c) => c.partnername.toLowerCase().includes(queryPartner.value.toLowerCase())),
);

const product = ref<{ id: number; name: string }[]>([]);

const fetchProduct = async () => {
    if (!selectedPartner.value) return;
    const res = await fetch(
        route('studentApplication.product', {
            student: props.student.id,
            partner: selectedPartner.value.partnerid,
            product: selectedPartner.value.partnerbranchid,
        }),
    );

    product.value = await res.json();
};

watch(selectedPartner, async () => {
    await fetchProduct();
});

const selectedProduct = ref<{ id: number; name: string } | null>(null);
const queryProduct = ref('');

const filteredProduct = computed(() =>
    queryProduct.value === '' ? product.value : product.value.filter((c) => c.name.toLowerCase().includes(queryProduct.value.toLowerCase())),
);

const fdate = ref<string | null>(null);
const tdate = ref<string | null>(null);

watch(fdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.startdate = newDate.toISOString().split('T')[0];
    }
});

watch(tdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.enddate = newDate.toISOString().split('T')[0];
    }
});

const submit = () => {
    const action = route('studentInService.store', { student: props.student.id });

    form.workflow_id = selectedWorkflow.value.id;
    form.partner_branch_id = selectedPartner.value.partnerbranchid;
    form.product_id = selectedProduct.value.id;

    form.post(action, {
        onSuccess: () => {
            toast('Success', {
                description: 'Student Interested Service created successfully',
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('studentInService.index', props.student.id), {
                    only: ['student_in_services'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Error', {
                description: firstError,
            });
        },
    });
};

const createdForm = useForm({
    studentInService: '',
});

const handleCreateApplication = async (itemId: number) => {
    if (!confirm('Are you sure you want to create this Student Application?')) return;
    createdForm.studentInService = itemId;
    createdForm.post(route('studentInService.create', [props.student.id, itemId]), {
        onSuccess: async () => {
            setTimeout(() => {
                form.reset();
            }, 200);
        },

        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', { description: firstError });
        },
    });
};

const handleDelete = async (itemId: number) => {
    if (!confirm('Are you sure you want to delete this Student Interested Service?')) return;

    try {
        const url = route('studentInService.destroy', {
            student: props.student.id,
            studentInService: itemId,
        });

        const response = await axios.delete(url);
        toast.success(response.data.message ?? 'Deleted successfully');
        router.visit(window.location.href, {
            preserveScroll: true,
            preserveState: false,
            method: 'get',
        });
    } catch (error: any) {
        toast.error(error.response?.data?.message ?? 'Failed to delete!');
    }
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <div class="flex items-center justify-between py-4">
                <h2 class="text-sm font-medium">Student Interested Service</h2>
                <Button size="sm" @click="showDailogCreate"> <Plus class="mr-2 h-4 w-4" /> Add </Button>
            </div>
            <!-- Card Grid -->
            <div class="grid gap-4 sm:grid-cols-2">
                <Card
                    v-for="(inservice, index) in props.studentService"
                    :key="index"
                    class="border"
                    :class="inservice.status == 'converted' ? 'border-green-300' : 'border-gray-500'"
                >
                    <CardHeader class="flex items-start justify-between">
                        <div>
                            <CardTitle class="text-base font-semibold">{{ inservice.workflow.name }}</CardTitle>
                            <CardDescription class="text-sm text-gray-600">{{ inservice.product.name }}</CardDescription>
                            <p class="mt-1 text-xs text-gray-500">{{ inservice.partner_branch.partner.name }}</p>
                            <p class="text-xs text-gray-500">{{ inservice.partner_branch.branch_name }}</p>
                        </div>
                        <span
                            class="flex items-center gap-1 rounded-full px-2 py-1 text-sm font-medium"
                            :class="inservice.status == 'converted' ? 'text-green-600' : 'text-gray-400'"
                        >
                            <CircleDot :class="['h-4 w-4', inservice.status === 'converted' ? 'text-green-600' : 'text-gray-400']" />{{
                                inservice.status
                            }}</span
                        >
                    </CardHeader>

                    <CardContent class="grid gap-3">
                        <div class="grid grid-cols-2 text-sm">
                            <div>
                                <p class="text-gray-500">Product Fees</p>
                                <p class="font-semibold text-blue-600" v-if="inservice.productfees?.netamount">
                                    {{ inservice.productfees.netamount }}
                                </p>
                                <p class="text-gray-500 italic" v-else>Fees not assigned</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Sales Forecast</p>
                                <p class="font-semibold text-blue-600" v-if="inservice.productfees?.netamount">
                                    {{ inservice.productfees.netamount }}
                                </p>
                                <p class="text-gray-500 italic" v-else>Forecast not assigned</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 text-xs text-gray-600">
                            <p>
                                Expected Start Date: <span class="font-medium">{{ inservice.startdate }}</span>
                            </p>
                            <p>
                                Expected Win Date: <span class="font-medium">{{ inservice.enddate }}</span>
                            </p>
                        </div>
                    </CardContent>

                    <CardFooter class="flex items-center justify-between border-t pt-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                {{ (inservice.user.name?.charAt(0) ?? '').toUpperCase() }}
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-800">{{ inservice.user.name }}</p>
                                <p class="text-[10px] text-gray-500">{{ new Date(inservice.created_at).toISOString().split('T')[0] }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Dropdown Menu -->
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost" size="icon">
                                        <MoreVertical class="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>

                                <DropdownMenuContent align="end" class="w-40">
                                    <DropdownMenuItem @click="handleCreateApplication(inservice.id)"> Create Application</DropdownMenuItem>
                                    <DropdownMenuItem @click="handleDelete(inservice.id)" class="text-red-600"> Delete </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-sm rounded-2xl shadow-lg sm:max-w-md md:max-w-lg lg:max-w-xl">
                <DialogHeader class="border-b pb-3">
                    <DialogTitle class="text-lg font-semibold">
                        {{ isEditMode ? 'Edit student application' : 'Create student application' }}
                    </DialogTitle>
                    <DialogDescription class="text-sm text-gray-500">
                        {{
                            isEditMode
                                ? 'Update the student application details and click save.'
                                : 'Fill in the details below to create a new student application.'
                        }}
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="workflow_id">Select workflow</Label>
                            <Combobox v-model="selectedWorkflow">
                                <div class="relative">
                                    <!-- Input -->
                                    <div class="relative w-full">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="Select workflow..."
                                            :display-value="(n) => n?.name"
                                            @input="queryWorkflow = $event.target.value"
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
                                            v-if="filteredWorkflow.length === 0 && queryWorkflow !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="n in filteredWorkflow"
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
                            <span v-if="form.errors.workflow_id" class="text-sm text-red-600">{{ form.errors.workflow_id }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="partner_branch_id">Select Partner & Branch</Label>
                            <Combobox v-model="selectedPartner">
                                <div class="relative">
                                    <!-- Input -->
                                    <div class="relative w-full">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="Select partner..."
                                            :display-value="(n) => (n ? `${n.partnername} [ ${n.partnerbranch} ]` : '')"
                                            @input="queryPartner = $event.target.value"
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
                                            v-if="filteredPartner.length === 0 && queryPartner !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="n in filteredPartner"
                                            :key="n.partnerbranchid"
                                            :value="n"
                                            class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                            v-slot="{ selected }"
                                        >
                                            <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                {{ n.partnername }} [ {{ n.partnerbranch }} ]
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
                            <span v-if="form.errors.partner_branch_id" class="text-sm text-red-600">{{ form.errors.partner_branch_id }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="product_id">Select Product</Label>
                            <Combobox v-model="selectedProduct">
                                <div class="relative">
                                    <!-- Input -->
                                    <div class="relative w-full">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="Select product..."
                                            :display-value="(n) => n?.name"
                                            @input="queryProduct = $event.target.value"
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
                                            v-if="filteredProduct.length === 0 && queryProduct !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="n in filteredProduct"
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
                            <span v-if="errors?.product_id" class="text-sm text-red-600">{{ errors.product_id }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Start Date -->
                            <div class="flex flex-col">
                                <Label for="product_id">Expected Start Date</Label>
                                <VueDatePicker
                                    v-model="fdate"
                                    :format="'yyyy-MM-dd'"
                                    :enable-time-picker="false"
                                    placeholder="Expected Start Date"
                                    auto-apply
                                />
                            </div>

                            <!-- Win Date -->
                            <div class="flex flex-col">
                                <Label for="product_id">Expected Win Date</Label>
                                <VueDatePicker
                                    v-model="tdate"
                                    :format="'yyyy-MM-dd'"
                                    :enable-time-picker="false"
                                    placeholder="Expected Win Date"
                                    auto-apply
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <DialogFooter class="flex justify-end space-x-2 border-t pt-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button :disabled="form.processing" @click="submit">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </StudentLayout>
</template>
