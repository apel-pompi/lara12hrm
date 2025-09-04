<script setup lang="ts">
import ImageUpload from '@/components/PartnerImage.vue';
import { Button } from '@/components/ui/button';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm } from '@inertiajs/vue3';
import { CornerDownLeft, Plus, Save } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Create', href: '/partner/create' }];

const props = defineProps<{
    master: { id: number; catname: string; parner_types: { id: number; partnertypename: string }[] }[];
    workflow: { id: number; name: string }[];
    countries: {
        id: number;
        name: string;
        phonecode: string;
        iso3: string;
        currency: string;
        currency_symbol: string;
    }[];
}>();

const currentImage = ref<string | null>(null);
// ------------------- MASTER / PARTNER TYPE -------------------
const selectedMaster = ref<{ id: number; catname: string; parner_types: any[] } | null>(null);
const selectedPartnerType = ref<{ id: number; partnertypename: string } | null>(null);
const queryMaster = ref('');
const queryPartner = ref('');

const filteredMasters = computed(() =>
    queryMaster.value === '' ? props.master : props.master.filter((m) => m.catname.toLowerCase().includes(queryMaster.value.toLowerCase())),
);

const filteredPartnerTypes = computed(() =>
    selectedMaster.value
        ? queryPartner.value === ''
            ? selectedMaster.value.parner_types
            : selectedMaster.value.parner_types.filter((pt) => pt.partnertypename.toLowerCase().includes(queryPartner.value.toLowerCase()))
        : [],
);

// ------------------- WORKFLOW MULTISELECT -------------------

const selectedWorkflows = ref<{ id: number; name: string }[]>([]);
const queryWorkflow = ref('');

const filteredWorkflow = computed(() =>
    queryWorkflow.value === '' ? props.workflow : props.workflow.filter((w) => w.name.toLowerCase().includes(queryWorkflow.value.toLowerCase())),
);

const isSelectedWorkflow = (w: { id: number; name: string }) => selectedWorkflows.value.some((sw) => sw.id === w.id);
const toggleWorkflow = (w: { id: number; name: string }) => {
    const idx = selectedWorkflows.value.findIndex((sw) => sw.id === w.id);
    if (idx === -1) selectedWorkflows.value.push(w);
    else selectedWorkflows.value.splice(idx, 1);
};
const removeWorkflow = (w: { id: number; name: string }) => {
    selectedWorkflows.value = selectedWorkflows.value.filter((sw) => sw.id !== w.id);
};

// ------------------- CURRENCY -------------------
const selectedCurrency = ref<any>(null);
const queryCurrency = ref('');
const filteredCurrency = computed(() =>
    queryCurrency.value === '' ? props.countries : props.countries.filter((c) => c.name.toLowerCase().includes(queryCurrency.value.toLowerCase())),
);

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

// ------------------- Phone -------------------
const selectedPhone = ref<any>(null);
const queryPhone = ref('');
const filteredPhone = computed(() =>
    queryPhone.value === '' ? props.countries : props.countries.filter((c) => c.name.toLowerCase().includes(queryPhone.value.toLowerCase())),
);

// ------------------- NAVIGATION -------------------
const goToPartner = () => router.visit('/partner');

// ------------------- SAVE / SUBMIT -------------------

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    name: '',
    brn: '',
    email: '',
    fax: '',
    website:'',
    phoneno: '',
    master_cat_id: null as number | null,
    partner_type_id: null as number | null,
    currency: null as number | null,
    country_id: null as number | null,
    state_id: null as number | null,
    city_id: null as number | null,
    workflow_id: [] as number[],
    phone_code: null as number | null,
    photo: null as File | null,
});

const showDailogCreate = () => {
    form.reset();
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    form.master_cat_id = selectedMaster.value?.id ?? null;
    form.partner_type_id = selectedPartnerType.value?.id ?? null;
    form.currency = selectedCurrency.value?.id ?? null;
    form.country_id = selectedCountry.value?.id ?? null;
    form.state_id = selectedState.value?.id ?? null;
    form.city_id = selectedCity.value?.id ?? null;
    form.phone_code = selectedPhone.value?.phonecode ?? null;
    form.workflow_id = selectedWorkflows.value.map((w) => w.id);

    form.post(route('partner.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Partner created successfully');
        },
        onError: () => {
            toast.error('Failed to save partner');
        },
    });
};
</script>

<template>
    <Head title="Partners Create" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="goToPartner"><CornerDownLeft></CornerDownLeft> Manage Partner </Button>
                </div>
            </div>

            <div class="mx-auto mb-15 max-w-6xl space-y-8 rounded-lg border border-gray-800 bg-white p-6 shadow">
                <p class="text-sm text-gray-500">* fields are mandatory.</p>

                <!-- PRIMARY INFORMATION -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">PRIMARY INFORMATION</h2>
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
                                <Label class="block text-sm font-medium text-gray-700">Master Category <span class="text-red-500">*</span></Label>
                                <Combobox v-model="selectedMaster">
                                    <div class="relative">
                                        <!-- Input -->
                                        <div class="relative w-full">
                                            <ComboboxInput
                                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                                placeholder="Select Master"
                                                :display-value="(m) => m?.catname"
                                                @input="queryMaster = $event.target.value"
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
                                                v-if="filteredMasters.length === 0 && query !== ''"
                                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                                            >
                                                Nothing found.
                                            </div>

                                            <ComboboxOption
                                                v-for="m in filteredMasters"
                                                :key="m.id"
                                                :value="m"
                                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                                v-slot="{ selected }"
                                            >
                                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                    {{ m.catname }}
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

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Partner Type <span class="text-red-500">*</span></Label>
                                <Combobox v-model="selectedPartnerType" :disabled="!selectedMaster">
                                    <div class="relative">
                                        <!-- Input -->
                                        <div class="relative w-full">
                                            <ComboboxInput
                                                class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                                placeholder="Select Partner"
                                                :display-value="(pt) => pt?.partnertypename"
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
                                                v-if="filteredPartnerTypes.length === 0 && query !== ''"
                                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                                            >
                                                Nothing found.
                                            </div>

                                            <ComboboxOption
                                                v-for="pt in filteredPartnerTypes"
                                                :key="pt.id"
                                                :value="pt"
                                                class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                                v-slot="{ selected }"
                                            >
                                                <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                                    {{ pt.partnertypename }}
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

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Partner Name <span class="text-red-500">*</span></Label>
                                <Input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                />
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Business Registration Number</Label>
                                <Input
                                    v-model="form.brn"
                                    type="text"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                />
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Service Workflow <span class="text-red-500">*</span></Label>
                                <div class="relative w-full">
                                    <!-- Tags -->
                                    <div
                                        class="flex min-h-[40px] cursor-text flex-wrap gap-1 rounded-md border p-1"
                                        @click="$refs.inputWorkflow.focus()"
                                    >
                                        <span
                                            v-for="w in selectedWorkflows"
                                            :key="w.id"
                                            class="flex items-center rounded-full bg-indigo-100 px-2 py-1 text-sm text-indigo-800"
                                        >
                                            {{ w.name }}
                                            <button type="button" class="ml-1" @click.prevent="removeWorkflow(w)">×</button>
                                        </span>

                                        <!-- Input -->
                                        <input
                                            ref="inputWorkflow"
                                            type="text"
                                            v-model="queryWorkflow"
                                            class="flex-1 border-none p-1 text-sm outline-none"
                                            placeholder="Type to search..."
                                        />
                                    </div>

                                    <!-- Dropdown Options -->
                                    <div
                                        v-if="queryWorkflow !== '' && filteredWorkflow.length > 0"
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg"
                                    >
                                        <div
                                            v-for="w in filteredWorkflow"
                                            :key="w.id"
                                            class="flex cursor-pointer items-center justify-between px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                            @click.prevent="
                                                toggleWorkflow(w);
                                                queryWorkflow = '';
                                            "
                                        >
                                            <span>{{ w.name }}</span>
                                            <CheckIcon v-if="isSelectedWorkflow(w)" class="h-5 w-5 text-indigo-600" />
                                        </div>
                                    </div>

                                    <!-- Nothing found -->
                                    <div
                                        v-if="queryWorkflow !== '' && filteredWorkflow.length === 0"
                                        class="absolute z-10 mt-1 w-full rounded-md border bg-white px-4 py-2 text-sm text-gray-500"
                                    >
                                        Nothing found.
                                    </div>
                                </div>
                            </div>

                            <div>
                                <Label class="block text-sm font-medium text-gray-700">Currency <span class="text-red-500">*</span></Label>
                                <Combobox v-model="selectedCurrency">
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="Select Currency"
                                            @input="queryCurrency = $event.target.value"
                                            :display-value="(c) => (c ? `[ ${c.currency} ${c.currency_symbol} ] ${c.name}` : '')"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>
                                        <ComboboxOptions
                                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                        >
                                            <div
                                                v-if="filteredCurrency.length === 0 && queryCurrency !== ''"
                                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                                            >
                                                Nothing found.
                                            </div>
                                            <ComboboxOption
                                                v-for="currency in filteredCurrency"
                                                :key="currency.id"
                                                :value="currency"
                                                class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                            >
                                                {{ `[ ${currency.currency} ${currency.currency_symbol} ] ${currency.name}` }}
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </div>
                                </Combobox>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ADDRESS -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">ADDRESS</h2>
                    <div class="flex flex-col gap-4 md:flex-row md:space-x-4">
                        <!-- Country -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></Label>
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

                        <!-- State -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></Label>
                            <Combobox v-model="selectedState" :disabled="!selectedCountry">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Country"
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

                        <!-- City -->
                        <div class="w-full md:w-1/3">
                            <Label class="block text-sm font-medium text-gray-700">City</Label>
                            <Combobox v-model="selectedCity" :disabled="!selectedState">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Country"
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
                    </div>
                </section>

                <!-- CONTACT DETAILS -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">CONTACT DETAILS</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <Label class="block text-sm font-medium text-gray-700"> Phone Number </Label>
                            <div class="mt-1 flex">
                                <!-- Country Code -->
                                <div class="relative w-28">
                                    <Combobox v-model="selectedPhone">
                                        <div class="relative">
                                            <ComboboxInput
                                                class="w-full rounded-l-lg border border-gray-300 bg-white py-2 pr-8 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                                placeholder="+Code"
                                                @input="queryPhone = $event.target.value"
                                                :display-value="(c) => (c ? c.phonecode : '')"
                                            />
                                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                <ChevronUpDownIcon class="h-4 w-4 text-gray-400" />
                                            </ComboboxButton>
                                            <ComboboxOptions
                                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                            >
                                                <div
                                                    v-if="filteredPhone.length === 0 && queryPhone !== ''"
                                                    class="cursor-default px-4 py-2 text-gray-500 select-none"
                                                >
                                                    Nothing found.
                                                </div>
                                                <ComboboxOption
                                                    v-for="phone in filteredPhone"
                                                    :key="phone.id"
                                                    :value="phone"
                                                    class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                                >
                                                    {{ `${phone.phonecode} ${phone.iso3}` }}
                                                </ComboboxOption>
                                            </ComboboxOptions>
                                        </div>
                                    </Combobox>
                                </div>

                                <!-- Phone Number -->
                                <input
                                    v-model="form.phoneno"
                                    type="tel"
                                    class="w-full rounded-r-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Enter phone number"
                                />
                            </div>
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></Label>
                            <Input
                                v-model="form.email"
                                type="email"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Fax</Label>
                            <Input
                                v-model="form.fax"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <Label class="block text-sm font-medium text-gray-700">Website</Label>
                            <Input
                                v-model="form.website"
                                type="text"
                                class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </section>

                <!-- Brnach DETAILS -->
                <section>
                    <h2 class="text-md mb-4 border-b pb-2 font-semibold text-gray-700">BRANCH</h2>

                    <!-- responsive grid for inputs -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Name -->
                        <div>
                            <!-- <Label class="block text-sm font-medium text-gray-700"> Name <span class="text-red-500">*</span> </Label>
                            <Input type="text" class="mt-1 w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-500" /> -->
                        </div>
                    </div>

                    <!-- actions row -->
                    <div class="mt-6 flex items-center justify-between">
                        <!-- left side -->
                        <Button
                            variant="outline"
                            size="sm"
                            @click="showDailogCreate"
                            class="flex items-center gap-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50"
                        >
                            <Plus class="h-4 w-4" /> Add Branch
                        </Button>

                        <!-- right side -->
                        <Button
                            class="flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
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
