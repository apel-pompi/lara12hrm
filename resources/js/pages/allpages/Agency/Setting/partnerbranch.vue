<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref,watch,computed } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Plus, Loader2, SquarePen, Trash } from 'lucide-vue-next';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { toast } from 'vue-sonner';


const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Branch', href: '/partnerbranch' }];

export interface PartnerBranch {
    id: number;
    branch_name: string;
    branch_email: string;
    active:number
}

const props = defineProps<{
    partnerbranch:{
        id:number;branch_name:string;branch_email:string;active:number
        citys:{
            name:string
        };
        states:{
            name:string;
            countries: {
                id: number;
                name: string;
                phonecode: string;
                iso3: string;
                currency: string;
                currency_symbol: string;
            }[];
        };
        user:{
            name:string
        }
    }[];
    countries: {
        id: number;
        name: string;
        phonecode: string;
        iso3: string;
        currency: string;
        currency_symbol: string;
    }[];
}>();
console.log(props.partnerbranch)
interface FormErrors {
    branch_name?: string;
    branch_email?: string;
    branch_state_id?: number;
    branch_city_id?: number;
    branch_phoneno?: string;
    branch_phonecode?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    branch_name: '',
    branch_email: '',
    branch_state_id: null,
    branch_city_id: null,
    branch_phoneno: null,
    branch_phonecode: null,
});



const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

// ------------------- Branch Country & State-------------------
//selected
const selectedBranchCountry = ref<any>(null);
const selectedBranchState = ref<any>(null);
const selectedBranchCity = ref<any>(null);
//query
const queryBranchCountry = ref('');
const queryBranchState = ref('');
const queryBranchCity = ref('');

const statesBranch = ref<{ id: number; name: string }[]>([]);
const citiesBranch = ref<{ id: number; name: string }[]>([]);

//fetch state
const fetchBranchStates = async () => {
    if (!selectedBranchCountry.value) return;
    const res = await fetch(`/countries/${selectedBranchCountry.value.id}/states`);
    statesBranch.value = await res.json();
};

const fetchBranchCities = async () => {
    if (!selectedBranchState.value) return;
    const res = await fetch(`/states/${selectedBranchState.value.id}/cities`);
    citiesBranch.value = await res.json();
};

watch(selectedBranchCountry, async () => {
    selectedBranchState.value = null;
    selectedBranchCity.value = null;
    statesBranch.value = [];
    citiesBranch.value = [];
    queryBranchState.value = '';
    queryBranchCity.value = '';
    await fetchBranchStates();
});

watch(selectedBranchState, async () => {
    selectedBranchCity.value = null;
    citiesBranch.value = [];
    queryBranchCity.value = '';
    await fetchBranchCities();
});

//filter
const filteredBranchCountries = computed(() =>
    queryBranchCountry.value ? props.countries.filter((c) => c.name.toLowerCase().includes(queryBranchCountry.value.toLowerCase())) : props.countries,
);

const filteredBranchStates = computed(() =>
    selectedBranchState.value
        ? statesBranch.value.filter((s) => s.name.toLowerCase().includes(selectedBranchState.value.toLowerCase()))
        : statesBranch.value,
);

const filteredBranchCities = computed(() =>
    queryBranchCity.value ? citiesBranch.value.filter((c) => c.name.toLowerCase().includes(queryBranchCity.value.toLowerCase())) : citiesBranch.value,
);

const selectedBranchPhone = ref<any>(null);
const queryBranchPhone = ref('');
const filteredBranchPhone = computed(() =>
    queryBranchPhone.value === ''
        ? props.countries
        : props.countries.filter((c) => c.name.toLowerCase().includes(queryBranchPhone.value.toLowerCase())),
);

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/documenttype/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching document type details.');
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

    const action = isEditMode.value && form.id ? route('partnerbranch.update', form.id) : route('partnerbranch.store');
    const method = isEditMode.value ? 'put' : 'post';
    form.branch_state_id = selectedBranchState.value?.id ?? null;
    form.branch_city_id = selectedBranchCity.value?.id ?? null;
    form.branch_phonecode = selectedBranchPhone.value?.phonecode ?? null;
    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Partner Branch ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('partnerbranch.index'), {
                    only: ['partner_branches'],
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

const toggleStatus = (partnerbranch: PartnerBranch) => {
    const newStatus = partnerbranch.active; // boolean
    router.put(
        route('partnerbranch.updateStatus', partnerbranch.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                partnerbranch.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Partner Branch  status update');
            },
        },
    );
};

</script>

<template>
    <Head title="Partner Branch" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Partner Branch</Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Branch Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Country Name</TableHead>
                            <TableHead>State Name</TableHead>
                            <TableHead>City Name</TableHead>
                            <TableHead>Total Usage</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(partner, index) in props.partnerbranch" :key="partner.id ?? index">
                            <TableCell>{{ partner.branch_name }}</TableCell>
                            <TableCell>{{ partner.branch_email }}</TableCell>
                            <TableCell>{{ partner.states.country.name }}</TableCell>
                            <TableCell>{{ partner.states.name }}</TableCell>
                            <TableCell>{{ partner.citys.name }}</TableCell>
                            <TableCell></TableCell>
                            <TableCell>
                                <Switch v-model="partner.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(partner)"> </Switch>
                            </TableCell>
                            <TableCell>{{ partner.user.name }}</TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(partner.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(partner.id)"><Trash></Trash></Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm"></div>
                <div class="space-x-2"></div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="w-full max-w-3xl rounded-2xl shadow-lg">
                <DialogHeader class="border-b pb-3">
                    <DialogTitle class="text-xl font-semibold">Add Partner Branch</DialogTitle>
                    <DialogDescription class="text-sm text-gray-500"> Fill out the form and click save. </DialogDescription>
                </DialogHeader>

                <!-- Form -->
                <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Branch Name -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">Branch Name <span class="text-red-500">*</span> </Label>
                        <Input v-model="form.branch_name" type="text" class="w-full" />
                        <span v-if="form.errors.branch_name" class="text-sm text-red-600">{{ form.errors.branch_name }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">Email <span class="text-red-500">*</span></Label>
                        <Input v-model="form.branch_email" type="email" class="w-full" />
                        <span v-if="form.errors.branch_email" class="text-sm text-red-600">{{ form.errors.branch_email }}</span>
                    </div>

                    <!-- Country -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">Country</Label>
                        <Combobox v-model="selectedBranchCountry">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Country"
                                    @input="queryBranchCountry = $event.target.value"
                                    :display-value="(c) => (c ? c.name : '')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredBranchCountries.length === 0 && queryBranchCountry !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="country in filteredBranchCountries"
                                        :key="country.id"
                                        :value="country"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ country.name }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <span v-if="form.errors.branch_country_id" class="text-sm text-red-600">{{ form.errors.branch_country_id }}</span>
                    </div>

                    <!-- State -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">State</Label>
                        <Combobox v-model="selectedBranchState" :disabled="!selectedBranchCountry">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select State"
                                    @input="queryBranchState = $event.target.value"
                                    :display-value="(s) => s?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredBranchStates.length === 0 && queryBranchState !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="state in filteredBranchStates"
                                        :key="state.id"
                                        :value="state"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ state.name }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <span v-if="form.errors.branch_state_id" class="text-sm text-red-600">{{ form.errors.branch_state_id }}</span>
                    </div>

                    <!-- City -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">City</Label>
                        <Combobox v-model="selectedBranchCity" :disabled="!selectedBranchState">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Select Country"
                                    @input="queryBranchCity = $event.target.value"
                                    :display-value="(c) => c?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <div
                                        v-if="filteredBranchCities.length === 0 && queryBranchCity !== ''"
                                        class="cursor-default px-4 py-2 text-gray-500 select-none"
                                    >
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="city in filteredBranchCities"
                                        :key="city.id"
                                        :value="city"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ city.name }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <span v-if="form.errors.branch_city_id" class="text-sm text-red-600">{{ form.errors.branch_city_id }}</span>
                    </div>

                    <!-- Phone -->
                    <div>
                        <Label class="block text-sm font-medium text-gray-700 pb-2">Phone Code</Label>
                        <div class="mt-1 flex">
                            <!-- Country Code -->
                            <div class="relative w-28">
                                <Combobox v-model="selectedBranchPhone">
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded-l-lg border border-gray-300 bg-white py-2 pr-8 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                            placeholder="+Code"
                                            @input="queryBranchPhone = $event.target.value"
                                            :display-value="(c) => (c ? c.phonecode : '')"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-4 w-4 text-gray-400" />
                                        </ComboboxButton>
                                        <ComboboxOptions
                                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                        >
                                            <div
                                                v-if="filteredBranchPhone.length === 0 && queryBranchPhone !== ''"
                                                class="cursor-default px-4 py-2 text-gray-500 select-none"
                                            >
                                                Nothing found.
                                            </div>
                                            <ComboboxOption
                                                v-for="phone in filteredBranchPhone"
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
                            <input v-model="form.branch_phoneno"
                                type="tel"
                                class="w-full rounded-r-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="Enter phone number"
                            />
                        </div>
                        <span v-if="form.errors.branch_phonecode" class="text-sm text-red-600">{{ form.errors.branch_phonecode }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-5 flex justify-end gap-3 border-t pt-3">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>
                    <Button
                        :disabled="form.processing"
                        @click="submit"
                        class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-70"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <span>{{ form.processing ? 'Saving...' : 'Submit' }}</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
