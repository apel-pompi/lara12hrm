<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import StudentLayout from '@/pages/allpages/Agency/Student/studentlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Link, router, useForm } from '@inertiajs/vue3';
import { Eye, Plus, SquarePen, Trash } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Badge } from '@/components/ui/badge';

const props = defineProps<{
    student: { id: number;status:string };
    workflow: { id: number; name: string };
    studentApplication: { id: number }[];
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
    stage: null as string | null,
    status: null as string | null,
    saleprice: null as number | null,
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

const onShow = (id: number) => {
    router.visit(`/student/activities/${props.student.id}/application/${id}/activities`);
};

const onEdit = async (id: number) => {
    try {
        const url = route('studentApplication.edit', {
            student: props.student.id,
            studentApplication: id,
        });
        const res = await fetch(url);
        if (!res.ok) {
            toast.error('Server error while fetching application details.');
            return;
        }
        const data = await res.json();
 
        Object.assign(form, data.data);
        form.id = data.data.id;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action =
        isEditMode.value && form.id
            ? route('studentApplication.update', {
                  student: props.student.id,
                  studentApplication: form.id,
              })
            : route('studentApplication.store', { student: props.student.id });

    const method = isEditMode.value ? 'put' : 'post';
    form.workflow_id = selectedWorkflow.value.id;
    form.partner_branch_id = selectedPartner.value.partnerbranchid;
    form.product_id = selectedProduct.value.id;
    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Student Application ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('studentApplication.index', props.student.id), {
                    only: ['student_applications'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200); // Delay for 200ms
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', {
                description: firstError,
            });
        },
    });
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this Student Application?')) return;

    if (deleteForm.processing) return;

    const url = route('studentApplication.destroy', {
        student: props.student.id,
        studentApplication: id,
    });

    deleteForm.delete(url, {
        onSuccess: () => {
            toast.success('Student Application deleted successfully');
        },
        onError: () => {
            toast.error('Something went wrong while deleting!');
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <StudentLayout :student="props.student" :studentService="studentService">
        <div class="space-y-4">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">Student Application</div>
                <div class="space-x-2">
                    <!-- <Button size="sm" @click="showDailogCreate"><Plus></Plus> Add</Button> -->
                </div>
            </div>
            <div class="flex items-start gap-3 rounded-xl">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Workflow</TableHead>
                            <TableHead>Current Stage</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Sale Forcast</TableHead>
                            <TableHead>Started</TableHead>
                            <TableHead>Last Update</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(stud, index) in props.studentApplication" :key="stud.id ?? index">
                            <TableCell>
                                <Link :href="route('studentApplication.appActivities', [props.student.id, stud.id])" class="flex items-center space-x-2">
                                    {{ stud.product.name }}<br />
                                    {{ stud.partner_branch.partner.name }} ({{ stud.partner_branch.branch_name }})
                                </Link>
                            </TableCell>
                            <TableCell>{{ stud.workflow.name }}</TableCell>
                            <TableCell></TableCell>
                            <TableCell>
                                <Badge
                                    v-if="stud.status"
                                    size="sm"
                                    variant="outline"
                                    :class="
                                        stud.status === 'In Progress' ? 'text-green-600' : stud.status === 'Archived' ? 'text-red-600' : 'text-blue-600'
                                    "
                                >
                                    {{ stud.status ?? '' }}
                                </Badge>
                            </TableCell>
                            <TableCell></TableCell>
                            <TableCell>{{ new Date(stud.created_at).toISOString().split('T')[0] }}</TableCell>
                            <TableCell>{{ new Date(stud.updated_at).toISOString().split('T')[0] }}</TableCell>
                            <TableCell>{{ stud.user.name }}</TableCell>
                            <TableCell>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(stud.id)"><Eye></Eye></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(stud.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(stud.id)"><Trash></Trash></Button
                            ></TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
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
        </div>
    </StudentLayout>
</template>
