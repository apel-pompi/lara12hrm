<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Eye, Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

import ImageUpload from '@/components/EmployeeImage.vue';

import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Switch from '@/components/ui/switch/Switch.vue';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from 'vue-sonner';

export interface Branch {
    id: number;
    branchname: string;
}

export interface Department {
    id: number;
    deptname: string;
}

export interface Designation {
    id: number;
    desname: string;
}

export interface PersonalInfo {
    id: number;
    empid: number;
    empname: string;
    joindate: string;
    branch_id: number;
    dept_id: number;
    des_id: number;
    dateofbirth: string;
    gender: number;
    present: string;
    permanent: string;
    phonepersonal: string;
    phoneoffice: string;
    email: string;
    blood: string;
    nidpass: string;
    photo: any;
    active: number;
    branch?: Branch;
    department?: Department;
    designation?: Designation;
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Personal Information', href: '/personalinfo' }];

const props = defineProps<{
    personalinfo: Paginated<PersonalInfo>;
    branch: Branch[];
    department: Department[];
    designation: Designation[];
    filters: { empid?: string; empname?: string; branch_id?: string; dept_id?: string; des_id?: string; blood?: string };
}>();

const data = props.personalinfo;

const currentImage = ref<string | null>(null);

const jdate = ref<string | null>(null);
const bdate = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

interface FormErrors {
    empid: string;
    empname: string;
    joindate: string;
    branch_id: string;
    dept_id: string;
    des_id: string;
    dateofbirth: string;
    gender: string;
    present: string;
    permanent: string;
    phonepersonal: string;
    phoneoffice: string;
    email: string;
    blood: string;
    nidpass: string;
    photo: any;
    active: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    empid: '',
    empname: '',
    joindate: '',
    branch_id: '',
    dept_id: '',
    des_id: '',
    dateofbirth: '',
    gender: '',
    present: '',
    permanent: '',
    phonepersonal: '',
    phoneoffice: '',
    email: '',
    blood: '',
    nidpass: '',
    photo: null as File | null,
    active: '',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

watch(jdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.joindate = newDate.toISOString().split('T')[0];
    }
});

watch(bdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.dateofbirth = newDate.toISOString().split('T')[0];
    }
});

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/personalinfo/${id}`);
        if (!res.ok) {
            toast.error('Server error while fetching personal info details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.id = data.id;
        currentImage.value = data.photo ? `/storage/employee/${data.photo}` : '/storage/employee/default.png';
        isEditMode.value = false;
        showDialog.value = false;
        showDialogOpen.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
        toast.error('Network error occurred. Please try again.');
    }
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/personalinfo/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching personal info details.');
            return;
        }

        const data = await res.json();

        Object.assign(form, data.data);

        form.id = data.data.id;
        currentImage.value = data.data.photo ? `${data.data.photo}` : '/storage/employee/default.png';

        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    if (isEditMode.value && form.id) {
        const action = route('personalinfo.update', form.id);

        const formData = new FormData();

        Object.entries(form).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        });

        if (form.photo instanceof File) {
            formData.append('photo', form.photo);
        }
        formData.append('_method', 'PUT');

        router.post(action, formData, {
            forceFormData: true,
            onSuccess: () => {
                toast('Success', {
                    description: 'Personal information updated successfully',
                });

                setTimeout(() => {
                    form.reset();
                    router.visit(route('personalinfo.index').toString(), {
                        only: ['personalinfos'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', {
                    description: firstError,
                });
            },
        });
    } else {
        form.post(route('personalinfo.store'), {
            onSuccess: () => {
                toast('Success', {
                    description: 'Personal information store successfully',
                });

                setTimeout(() => {
                    form.reset();
                    router.visit(route('personalinfo.index').toString(), {
                        only: ['personalinfos'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast('Validation Error', {
                    description: firstError,
                });
            },
        });
    }
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this personal info?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/personalinfo/show/${id}`, {
        onSuccess: () => {
            toast.success('Personal Info deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

// Switch toggle handler
const toggleStatus = (personalinfos: PersonalInfo) => {
    const newStatus = !Boolean(personalinfos.active); // boolean
    router.put(
        route('personalinfo.updateStatus', personalinfos.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                personalinfos.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Personal Info status update');
            },
        },
    );
};


// Combobox states
const selectedPersonID = ref(null); // empid
const selectedPerson = ref(null); // empname
const selectedBranch = ref(null); // branchname
const selectedDepartment = ref(null); // branchname
const selectedDesignation = ref(null); // branchname
const selectedBlood = ref(null); // branchname

const queryID = ref("");
const queryName = ref("");
const queryBranch = ref("");
const queryDepartment = ref("");
const queryDesignation = ref("");
const queryBlood = ref("");

// Filtered lists

const filteredPeopleID = computed(() =>
  queryID.value === ""
    ? data.data
    : data.data.filter((person) =>
        person.empid)
);
const filteredPeople = computed(() =>
  queryName.value === ""
    ? data.data
    : data.data.filter((person) =>
        person.empname.toLowerCase().includes(queryName.value.toLowerCase())
      )
);

const filteredBranch = computed(() =>
  queryBranch.value === ""
    ? props.branch
    : props.branch.filter((branches) =>
        branches.branchname.toLowerCase().includes(queryBranch.value.toLowerCase())
      )
);

const filteredDepartment = computed(() =>
  queryDepartment.value === ""
    ? props.department
    : props.department.filter((departments) =>
        departments.deptname.toLowerCase().includes(queryDepartment.value.toLowerCase())
      )
);

const filteredDesignation = computed(() =>
  queryDesignation.value === ""
    ? props.designation
    : props.designation.filter((designations) =>
        designations.desname.toLowerCase().includes(queryDesignation.value.toLowerCase())
      )
);

const filteredBlood = computed(() =>
  queryBlood.value === ""
    ? data.data
    : data.data.filter((bloods) =>
        bloods.blood.toLowerCase().includes(queryBlood.value.toLowerCase())
      )
);

const search = () => {
    const params: Record<string, any> = {};
    if (selectedPersonID.value) {
        params.empid = selectedPersonID.value.empid;
    }
    if (selectedPerson.value) params.empname = selectedPerson.value.empname;
    
    if (selectedBranch.value) params.branch_id = selectedBranch.value.id;
    if (selectedDepartment.value) params.dept_id = selectedDepartment.value.id;
    if (selectedDesignation.value) params.des_id = selectedDesignation.value.id;
    if (selectedBlood.value) params.blood = selectedBlood.value.blood;


    router.get(route('personalinfo.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const refresh = () => {
    router.get(route('personalinfo.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};


</script>

<template>
    <Head title="Personal Information" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Personal Info </Button>
                <!-- Search start -->
                <div class="grid gap-2">
                    <Combobox v-model="selectedPersonID">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select empid..."
                                    :display-value="(person) => person?.empid"
                                    @input="query = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredPeopleID.length === 0 && query !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="person in filteredPeopleID"
                                    :key="person.id"
                                    :value="person"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ person.empid }}
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
                <div class="grid gap-2">
                    <Combobox v-model="selectedPerson">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select employee..."
                                    :display-value="(person) => person?.empname"
                                    @input="query = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredPeople.length === 0 && query !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="person in filteredPeople"
                                    :key="person.id"
                                    :value="person"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ person.empname }}
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
                <div class="grid gap-2">
                    <Combobox v-model="selectedBranch">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Branch..."
                                    :display-value="(branches) => branches?.branchname"
                                    @input="queryBranch  = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredBranch.length === 0 && queryBranch !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="branches in filteredBranch"
                                    :key="branches.id"
                                    :value="branches"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ branches.branchname }}
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
                <div class="grid gap-2">
                    <Combobox v-model="selectedDepartment">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Department..."
                                    :display-value="(departments) => departments?.deptname"
                                    @input="queryDepartment  = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredDepartment.length === 0 && queryDepartment !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="departments in filteredDepartment"
                                    :key="departments.id"
                                    :value="departments"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ departments.deptname }}
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
                <div class="grid gap-2">
                    <Combobox v-model="selectedDesignation">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Designation..."
                                    :display-value="(designations) => designations?.desname"
                                    @input="queryDesignation  = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredDesignation.length === 0 && queryDesignation !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="designations in filteredDesignation"
                                    :key="designations.id"
                                    :value="designations"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ designations.desname }}
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
                <div class="grid gap-2">
                    <Combobox v-model="selectedBlood">
                        <div class="relative w-48">
                            <!-- Input -->
                            <div class="relative w-full">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Blood..."
                                    :display-value="(bloods) => bloods?.blood"
                                    @input="queryBlood  = $event.target.value"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                            </div>

                            <!-- Options -->
                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                            >
                                <div v-if="filteredBlood.length === 0 && queryBlood !== ''" class="cursor-default px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="bloods in filteredBlood"
                                    :key="bloods.blood"
                                    :value="bloods"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ bloods.blood }}
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
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                </div>
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                </div>
                <!-- Search end -->
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Employee ID</TableHead>
                            <TableHead>Employee Name</TableHead>
                            <TableHead>Branch</TableHead>
                            <TableHead>Department </TableHead>
                            <TableHead>Designation</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Blood</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(personal, index) in data.data" :key="personal.id">
                            <TableCell>{{ personal.empid }}</TableCell>
                            <TableCell>{{ personal.empname }}</TableCell>
                            <TableCell>{{ personal.branch?.branchname }}</TableCell>
                            <TableCell>{{ personal.department?.deptname }}</TableCell>
                            <TableCell>{{ personal.designation?.desname }}</TableCell>
                            <TableCell>{{ personal.phonepersonal }}</TableCell>
                            <TableCell>{{ personal.blood }}</TableCell>
                            <TableCell>
                                <Switch v-model="personal.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(personal)"> </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(personal.id)"><Eye></Eye></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(personal.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(personal.id)"><Trash></Trash></Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</div>
                <div class="space-x-2">
                    <Button
                        v-for="(link, index) in data.links"
                        :key="index"
                        :disabled="!link.url"
                        variant="outline"
                        size="sm"
                        :class="[link.active ? 'hover:outline' : '', !link.url ? 'cursor-not-allowed opacity-50' : '']"
                        @click="goToPage(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </Button>
                </div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="h-[auto] w-full max-w-[95vw] overflow-y-auto sm:max-w-[900px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Personal Info' : 'Create Personal Info' }}</DialogTitle>
                    <DialogDescription>Fill in the employee details below. Click save when you're done.</DialogDescription>
                </DialogHeader>

                <!-- Responsive grid layout -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="empid">Employee ID</Label>
                            <Input id="empid" placeholder="Enter Employee ID" v-model="form.empid" autofocus />
                            <span v-if="errors?.empid" class="text-sm text-red-600">{{ errors.empid }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="empname">Employee Name</Label>
                            <Input id="empname" placeholder="Enter Employee Name" v-model="form.empname" autofocus />
                            <span v-if="errors?.empname" class="text-sm text-red-600">{{ errors.empname }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="joindate">Joining Date</Label>
                            <VueDatePicker
                                v-model="jdate"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="Joning date"
                                auto-apply
                            />
                            <span v-if="errors?.joindate" class="text-sm text-red-600">{{ errors.joindate }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="dateofbirth">Date of Birth</Label>
                            <VueDatePicker
                                v-model="bdate"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="Date of birth"
                                auto-apply
                            />
                            <span v-if="errors?.dateofbirth" class="text-sm text-red-600">{{ errors.dateofbirth }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="phonepersonal">Personal Phone No</Label>
                            <Input id="phonepersonal" placeholder="Enter personal phone no" v-model="form.phonepersonal" autofocus />
                            <span v-if="errors?.phonepersonal" class="text-sm text-red-600">{{ errors.phonepersonal }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="phoneoffice">Official Phone No</Label>
                            <Input id="phoneoffice" placeholder="Enter official phone no" v-model="form.phoneoffice" autofocus />
                            <span v-if="errors?.phoneoffice" class="text-sm text-red-600">{{ errors.phoneoffice }}</span>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="branch_id">Branch Name</Label>
                            <Select v-model="form.branch_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Branch" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="b in branch" :key="b.id" :value="b.id">{{ b.branchname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.branch_id" class="text-sm text-red-600">{{ errors.branch_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="dept_id">Department Name</Label>
                            <Select v-model="form.dept_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Department" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="d in department" :key="d.id" :value="d.id">{{ d.deptname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.dept_id" class="text-sm text-red-600">{{ errors.dept_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="des_id">Designation</Label>
                            <Select v-model="form.des_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Designation" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="g in designation" :key="g.id" :value="g.id">{{ g.desname }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.des_id" class="text-sm text-red-600">{{ errors.des_id }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input id="email" placeholder="Enter email" v-model="form.email" autofocus />
                            <span v-if="errors?.email" class="text-sm text-red-600">{{ errors.email }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="photo" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                Employee Photo <span class="text-red-500">*</span>
                            </Label>
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <ImageUpload @image="(file) => (form.photo = file)" :Image="currentImage" :disabled="form.processing" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Recommended size: 256x256px</p>
                                    <p class="text-xs text-gray-500">Max size: 2MB</p>
                                </div>
                                <span v-if="form.errors.photo" class="text-xs text-red-600">{{ form.errors.photo }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="permanent">Permanent Address</Label>
                            <Input id="permanent" placeholder="Enter permanent address" v-model="form.permanent" />
                            <span v-if="errors?.permanent" class="text-sm text-red-600">{{ errors.permanent }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="present">Present Address</Label>
                            <Input id="present" placeholder="Enter present address" v-model="form.present" />
                            <span v-if="errors?.present" class="text-sm text-red-600">{{ errors.present }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="nidpass">NID / Passport No</Label>
                            <Input id="nidpass" placeholder="Enter NID or Passport No" v-model="form.nidpass" />
                            <span v-if="errors?.nidpass" class="text-sm text-red-600">{{ errors.nidpass }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="blood">Blood Group</Label>
                            <Select v-model="form.blood">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Blood" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="O Positive">O Positive</SelectItem>
                                        <SelectItem value="O Negative">O Negative</SelectItem>

                                        <SelectItem value="A Positive">A Positive</SelectItem>
                                        <SelectItem value="A Negative">A Negative</SelectItem>

                                        <SelectItem value="B Positive">B Positive</SelectItem>
                                        <SelectItem value="B Negative">B Negative</SelectItem>

                                        <SelectItem value="AB Positive">AB Positive</SelectItem>
                                        <SelectItem value="AB Negative">AB Negative</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.blood" class="text-sm text-red-600">{{ errors.blood }}</span>
                        </div>
                        <div class="space-y-2">
                            <Label for="gender">Gender</Label>
                            <Select v-model="form.gender">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Gender" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="1">Man</SelectItem>
                                        <SelectItem value="2">Woman</SelectItem>
                                        <SelectItem value="3">Other's</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.gender" class="text-sm text-red-600">{{ errors.gender }}</span>
                        </div>
                        <input type="hidden" v-model="form.active" class="form-radio text-primary-600" />

                        <div class="pt-4">
                            <Button :disabled="form.processing" @click="submit" class="w-full">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                            </Button>
                        </div>
                    </div>
                </div>

                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="w-full sm:w-auto">Close</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
            <DialogContent class="h-[auto] w-full max-w-[95vw] overflow-y-auto sm:max-w-[900px]">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show employee details</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this employee. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Employee ID" htmlFor="empid">
                                <Input id="empid" v-model="form.empid" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Employee Name" htmlFor="empname">
                                <Input id="empname" v-model="form.empname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Joining Date" htmlFor="joindate">
                                <Input id="joindate" v-model="form.joindate" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Date of Birth" htmlFor="dateofbirth">
                                <Input id="dateofbirth" v-model="form.dateofbirth" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Personal Phone No" htmlFor="phonepersonal">
                                <Input id="phonepersonal" v-model="form.phonepersonal" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Official Phone No" htmlFor="phoneoffice">
                                <Input id="phoneoffice" v-model="form.phoneoffice" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                    </div>
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Branch Name" htmlFor="branch_id">
                                <Input id="branch_id" v-model="form.branch.branchname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Department Name" htmlFor="dept_id">
                                <Input id="dept_id" v-model="form.department.deptname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Designation" htmlFor="des_id">
                                <Input id="des_id" v-model="form.designation.desname" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Email" htmlFor="email">
                                <Input id="email" v-model="form.email" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Email" htmlFor="email">
                                <Input id="email" v-model="form.email" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-2">
                            <h3 class="mb-3 text-sm font-medium text-gray-800">Employee Photo</h3>
                            <div class="flex items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white p-2">
                                <div class="flex items-center justify-center overflow-hidden rounded-full bg-gray-200">
                                    <img
                                        :src="currentImage || '/storage/employee/default.png'"
                                        alt="preview"
                                        class="h-35 w-35 object-cover object-center"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <FormGroup label="Permanent Address" htmlFor="permanent">
                                <Input id="permanent" v-model="form.permanent" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Present Address" htmlFor="present">
                                <Input id="present" v-model="form.present" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="NID or Passport No" htmlFor="nidpass">
                                <Input id="nidpass" v-model="form.nidpass" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Blood Group" htmlFor="blood">
                                <Input id="blood" v-model="form.blood" :disabled="!isEditMode" />
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Gender" htmlFor="gender">
                                <select id="gender" v-model="form.gender" :disabled="!isEditMode">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>
                            </FormGroup>
                        </div>
                        <div class="space-y-2">
                            <FormGroup label="Status" htmlFor="active">
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center space-x-2">
                                        <span
                                            class="inline-block rounded-full px-3 py-1 text-sm font-medium"
                                            :class="form.active == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        >
                                            {{ form.active == '1' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </FormGroup>
                        </div>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="showDialogOpen = false"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
