<script setup lang="ts">
import ImageUpload from '@/components/StudentImageUpload.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { CornerDownLeft, Save } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

export interface Student {
    id: number;
    student_id: string;
    fname: string;
    lname: string;
    dateofbirth: string;
    gender: number;
    email: string;
    phone: string;
    contactpre: string;
    preaddcountry: number;
    preaddstate: number;
    preaddcity: number;
    intakedate: string;
    pascountry: string;
    pasnocountry: string;
    visatype: string;
    visaexdate: string;
    paddress: string;
    pvisades: string;
    descountry_id: number;
    stage_id: number;
    metting_note: string;
    passportno: string;
    assain_user: number;
    source_id: number;
    photo: string;
    user_id: number;
    status: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Create', href: '/student/create' }];

const props = defineProps<{
    student: Student[];
    countries: {
        id: number;
        name: string;
        phonecode: string;
        iso3: string;
        currency: string;
        currency_symbol: string;
    }[];
    studentstage: {
        id: number;
        name: string;
    }[];
    users: {
        id: number;
        name: string;
    }[];
    source: {
        id: number;
        name: string;
    }[];
}>();

interface FormErrors {
    fname?: string;
    lname?: string;
    descountry_id?: number;
    stage_id?: number;
    metting_note?: string;
    passportno?: string;
    assain_user?: number;
    source_id?: number;
}

// ------------------- COUNTRY / STATE / CITY -------------------

const selectedCountry = ref<any>(null);
const selectedState = ref<any>(null);
const selectedCity = ref<any>(null);

const queryCountry = ref('');
const queryState = ref('');
const queryCity = ref('');

const states = ref<{ id: number; name: string }[]>([]);
const cities = ref<{ id: number; name: string }[]>([]);

const fetchStates = async () => {
    if (!selectedCountry.value) return;
    const res = await fetch(`/countries/${selectedCountry.value.id}/states`);
    states.value = await res.json();
};

const fetchCities = async () => {
    if (!selectedState.value) return;
    const res = await fetch(`/states/${selectedState.value.id}/cities`);
    cities.value = await res.json();
};

watch(selectedCountry, async () => {
    selectedState.value = null;
    selectedCity.value = null;
    states.value = [];
    cities.value = [];
    queryState.value = '';
    queryCity.value = '';
    await fetchStates();
});

watch(selectedState, async () => {
    selectedCity.value = null;
    cities.value = [];
    queryCity.value = '';
    await fetchCities();
});

const filteredCountries = computed(() =>
    queryCountry.value ? props.countries.filter((c) => c.name.toLowerCase().includes(queryCountry.value.toLowerCase())) : props.countries,
);

const filteredStates = computed(() =>
    queryState.value ? states.value.filter((s) => s.name.toLowerCase().includes(queryState.value.toLowerCase())) : states.value,
);

const filteredCities = computed(() =>
    queryCity.value ? cities.value.filter((c) => c.name.toLowerCase().includes(queryCity.value.toLowerCase())) : cities.value,
);
// Country of Passport
const selectedpCountry = ref<any>(null);
const querypCountry = ref('');
const filteredpCountry = computed(() =>
    querypCountry.value === '' ? props.countries : props.countries.filter((c) => c.name.toLowerCase().includes(querypCountry.value.toLowerCase())),
);
// preferred destination
const selectedPreDes = ref<any>(null);
const queryPreDes = ref('');
const filteredPreDes = computed(() =>
    queryPreDes.value === '' ? props.countries : props.countries.filter((c) => c.name.toLowerCase().includes(queryPreDes.value.toLowerCase())),
);
// Stage
const selectedStuStage = ref<any>(null);
const queryStuStage = ref('');
const filteredStuStage = computed(() =>
    queryStuStage.value === ''
        ? props.studentstage
        : props.studentstage.filter((c) => c.name.toLowerCase().includes(queryStuStage.value.toLowerCase())),
);
// Assignee name
const selectedUser = ref<any>(null);
const queryUser = ref('');
const filteredUser = computed(() =>
    queryUser.value === '' ? props.users : props.users.filter((c) => c.name.toLowerCase().includes(queryUser.value.toLowerCase())),
);
// Source
const selectedSource = ref<any>(null);
const querySource = ref('');
const filteredSource = computed(() =>
    querySource.value === '' ? props.source : props.source.filter((c) => c.name.toLowerCase().includes(querySource.value.toLowerCase())),
);
const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    student_id: '',
    fname: '',
    lname: '',
    dateofbirth: '',
    gender: '',
    email: '',
    phone: '',
    contactpre: '',
    preaddcountry: '',
    preaddstate: '',
    preaddcity: '',
    intakedate: '',
    pascountry: '',
    pasnocountry: '',
    visatype: '',
    visaexdate: '',
    paddress: '',
    pvisades: '',
    descountry_id: '',
    stage_id: '',
    metting_note: '',
    passportno: '',
    assain_user: '',
    source_id: '',
    photo: '',
    user_id: '',
    status: true,
});

const dob = ref<string | null>(null);
const visaex = ref<string | null>(null);
const intake = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

watch(dob, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.dateofbirth = newDate.toISOString().split('T')[0];
    }
});

watch(visaex, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.visaexdate = newDate.toISOString().split('T')[0];
    }
});

watch(intake, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.intakedate = newDate.toISOString().split('T')[0];
    }
});
const submit = () => {
    form.preaddcountry = selectedCountry.value?.id ?? null;
    form.preaddstate = selectedState.value?.id ?? null;
    form.preaddcity = selectedCity.value?.id ?? null;
    form.pascountry = selectedpCountry.value?.id ?? null;
    form.descountry_id = selectedPreDes.value?.id ?? null;
    form.stage_id = selectedStuStage.value?.id ?? null;
    form.assain_user = selectedUser.value?.id ?? null;
    form.source_id = selectedSource.value?.id ?? null;
    form.post(route('student.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: `Student created successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('student.index'), {
                    only: ['students'],
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

const goToManageStudent = () => {
    router.visit('/student');
};
</script>

<template>
    <Head title="Create Student" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="goToManageStudent"><CornerDownLeft></CornerDownLeft> Manage Student </Button>
                </div>
                <div class="space-x-2"></div>
            </div>
            <div class="mx-auto mb-15 max-w-6xl space-y-8 rounded-lg border border-gray-800 bg-white p-6 shadow">
                <p class="text-sm text-gray-500"><span class="text-red-500">*</span> fields are mandatory.</p>

                <!-- Personal Details  -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Personal Details</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Profile Upload -->

                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <ImageUpload @image="(file) => (form.photo = file)" :Image="currentImage" />
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Recommended size: 256x256px</p>
                                <p class="text-xs text-gray-500">Max size: 300Kb</p>
                            </div>
                        </div>

                        <!-- Fields -->
                        <div class="grid grid-cols-1 gap-6 md:col-span-2 md:grid-cols-2">
                            <div>
                                <Label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></Label>
                                <Input
                                    v-model="form.fname"
                                    type="text"
                                    placeholder="enter first name"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                />
                                <span v-if="form.errors.fname" class="text-sm text-red-600">{{ form.errors.fname }}</span>
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></Label>
                                <Input
                                    v-model="form.lname"
                                    placeholder="enter last name"
                                    type="text"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                />
                                <span v-if="form.errors.lname" class="text-sm text-red-600">{{ form.errors.lname }}</span>
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Date Of Birth</Label>
                                <VueDatePicker
                                    v-model="dob"
                                    :max-date="maxDate"
                                    :format="'yyyy-MM-dd'"
                                    :enable-time-picker="false"
                                    placeholder="Date Of Birth"
                                    auto-apply
                                />
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Gender</Label>
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
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ADDRESS -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Contact Details</h2>
                    <div class="flex flex-col gap-4 md:flex-row md:space-x-4">
                        <!-- Email -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">Email</Label>
                            <Input
                                v-model="form.email"
                                placeholder="enter email address"
                                type="email"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <!-- Phone -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">Phone</Label>
                            <Input
                                v-model="form.phone"
                                placeholder="enter phone number"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <!-- Contact Preference -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">Contact Preference</Label>
                            <RadioGroup class="flex flex-row space-x-6" v-model="form.contactpre">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem value="0" id="contact-email" />
                                    <Label for="contact-email">Email</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem value="1" id="contact-phone" />
                                    <Label for="contact-phone">Phone</Label>
                                </div>
                            </RadioGroup>
                        </div>
                    </div>
                </section>
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Address</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Country</Label>
                            <Combobox v-model="selectedCountry">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Country"
                                        @input="queryCountry = $event.target.value"
                                        :display-value="(c) => (c ? c.name : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredCountries.length === 0 && queryCountry !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="country in filteredCountries"
                                            :key="country.id"
                                            :value="country"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ country.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">State</Label>
                            <Combobox v-model="selectedState" :disabled="!selectedCountry">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select State"
                                        @input="queryState = $event.target.value"
                                        :display-value="(s) => s?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredStates.length === 0 && queryState !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="state in filteredStates"
                                            :key="state.id"
                                            :value="state"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ state.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>

                        <div>
                            <Label class="block text-sm font-medium text-gray-700">City</Label>
                            <Combobox v-model="selectedCity" :disabled="!selectedState">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select City"
                                        @input="queryCity = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredCities.length === 0 && queryCity !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="city in filteredCities"
                                            :key="city.id"
                                            :value="city"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ city.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Permanent Address</Label>
                            <Textarea v-model="form.paddress" id="paddress" placeholder="enter permanent address" />
                        </div>
                    </div>
                </section>
                <!-- Visa Information   -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Visa Information</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Preferred Intake</Label>
                            <VueDatePicker
                                    v-model="intake"
                                    :max-date="maxDate"
                                    :format="'yyyy-MM-dd'"
                                    :enable-time-picker="false"
                                    placeholder="Preferred Intake Date"
                                    auto-apply
                                />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Country of Passport</Label>
                            <Combobox v-model="selectedpCountry">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Country of Passport"
                                        @input="querypCountry = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredpCountry.length === 0 && querypCountry !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="pCountry in filteredpCountry"
                                            :key="pCountry.id"
                                            :value="pCountry"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ pCountry.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>

                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Passport Number</Label>
                            <Input
                                v-model="form.pasnocountry"
                                placeholder="enter passport number"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Visa Type</Label>
                            <Input
                                v-model="form.visatype"
                                placeholder="enter visa type"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Visa Expiry Date</Label>
                            <VueDatePicker
                                v-model="visaex"
                                :max-date="maxDate"
                                :format="'yyyy-MM-dd'"
                                :enable-time-picker="false"
                                placeholder="Visa Expiry Date"
                                auto-apply
                            />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Previous Visas & Destination</Label>
                            <Input
                                v-model="form.pvisades"
                                placeholder="Previous Visas & Destination"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </section>
                <!-- Identification -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Identification</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <Label class="block text-sm font-medium text-gray-700"
                                >What is your preferred destination?<span class="text-red-500">*</span></Label
                            >
                            <Combobox v-model="selectedPreDes">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="preferred destination"
                                        @input="queryPreDes = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredPreDes.length === 0 && queryPreDes !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="PreDes in filteredPreDes"
                                            :key="PreDes.id"
                                            :value="PreDes"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ PreDes.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <span v-if="form.errors.descountry_id" class="text-sm text-red-600">{{ form.errors.descountry_id }}</span>
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Stage<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selectedStuStage">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Stage"
                                        @input="querypStuStage = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredStuStage.length === 0 && queryStuStage !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="PreDes in filteredStuStage"
                                            :key="PreDes.id"
                                            :value="PreDes"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ PreDes.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <span v-if="form.errors.stage_id" class="text-sm text-red-600">{{ form.errors.stage_id }}</span>
                        </div>

                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Meeting Notes</Label>
                            <Input
                                v-model="form.metting_note"
                                placeholder="enter metting notes"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Passport</Label>
                            <Input
                                v-model="form.passportno"
                                placeholder="enter passport number"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </section>
                <!-- Internal-->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">Internal</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Assignee<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selectedUser">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select assignee parson"
                                        @input="queryUser = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredUser.length === 0 && queryUser !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="user in filteredUser"
                                            :key="user.id"
                                            :value="user"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ user.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <span v-if="form.errors.assain_user" class="text-sm text-red-600">{{ form.errors.assain_user }}</span>
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Choose a source <span class="text-red-500">*</span></Label>
                            <Combobox v-model="selectedSource">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select source"
                                        @input="querySource = $event.target.value"
                                        :display-value="(c) => c?.name ?? ''"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredSource.length === 0 && querySource !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="source in filteredSource"
                                            :key="source.id"
                                            :value="source"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ source.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <span v-if="form.errors.source_id" class="text-sm text-red-600">{{ form.errors.source_id }}</span>
                        </div>
                    </div>
                </section>
                <section class="border-t pt-6">
                    <div class="flex justify-end">
                        <Button
                            class="flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-white shadow-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-400"
                            @click="submit"
                        >
                            <Save class="h-5 w-5" />
                            Save
                        </Button>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
