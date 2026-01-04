<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import { RefreshCcw, Search, ShieldCheck } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Supplier Payable', href: '/suppliersPayble' }];

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

export interface SupplierPayable {
    suppliername: string;
    branch: string;
    contact_person: string;
    payable: number;
}

const props = defineProps<{
    payables: Paginated<SupplierPayable>;
    branch: { id: number; branchname: string }[];
    
}>();

const data = props.payables;

const vdate = ref<string | null>(null);

const maxDate = today(getLocalTimeZone());

const selectedBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => {
    if (queryBranch.value === '') return props.branch;

    return props.branch.filter((n) => n.branchname && n.branchname.toLowerCase().includes(queryBranch.value.toLowerCase()));
});


const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    vouchernumber: '',
    voucherdate: '',
    referance: '',
    branch_id: '',
    notes: '',
    subcode: '',
    invAmt: '',
});

watch(vdate, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.voucherdate = newDate.toISOString().split('T')[0];
    }
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    showDialog.value = true;
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('suppliersInvoice.update', form.id) : route('suppliersInvoice.store');
    const method = isEditMode.value ? 'put' : 'post';

    if (!form.referance) {
        toast('Validation Error', {
            description: 'Please write referance',
        });
        return;
    }


    if (!form.invAmt) {
        toast('Validation Error', {
            description: 'Please input invoice amount',
        });
        return;
    }

    

    if (!form.notes) {
        toast('Validation Error', {
            description: 'Please write notes',
        });
        return;
    }


    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('suppliersInvoice.index'), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            form.reset();
            showDialog.value = false;
        },

        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const flash = usePage().props.flash;
            if (flash?.error) {
                toast('error', {
                    description: flash.error + firstError,
                });
            }
        },
    });
};



const selectedSupplier = ref(null);
const querySupplier = ref('');
const filteredSupplier = computed(() => {
    if (querySupplier.value === '') return data.data;

    return data.data.filter((n) => n.suppliername && n.suppliername.toLowerCase().includes(querySupplier.value.toLowerCase()));
});

const selectedPerson = ref(null);
const queryPerson = ref('');
const filteredPerson = computed(() => {
    if (queryPerson.value === '') return data.data;

    return data.data.filter((n) => n.contact_person && n.contact_person.toLowerCase().includes(queryPerson.value.toLowerCase()));
});

const search = () => {
    const params: Record<string, any> = {};
    if (selectedBranch.value) params.branch = selectedBranch.value.id;
    if (selectedSupplier.value) params.suppliercode = selectedSupplier.value.suppliercode;
    if (selectedPerson.value) params.contact_person = selectedPerson.value.contact_person;
    console.log('Search Params:', params);
    router.get(route('suppliersPayble.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const refresh = () => {
    router.get(route('suppliersPayble.index'), {}, { replace: true });
};
</script>

<template>
    <Head title="Supplier Payable" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border px-4 md:min-h-min">
            
            <div class="flex flex-wrap items-center gap-4 py-4">
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedBranch">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Branch"
                                @input="queryBranch = $event.target.value"
                                :display-value="(c) => c?.branchname ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredBranch.length === 0 && queryBranch !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredBranch"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.branchname }}
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
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedSupplier">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Supplier"
                                @input="querySupplier = $event.target.value"
                                :display-value="(c) => c?.suppliername ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredSupplier.length === 0 && querySupplier !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredSupplier"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.suppliername }}
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
                <div class="w-full sm:w-1/2 lg:w-auto">
                    <Combobox v-model="selectedPerson">
                        <div class="relative w-full md:w-48">
                            <ComboboxInput
                                class="w-full rounded-md border px-3 py-2 text-sm"
                                placeholder="Select Person"
                                @input="queryPerson = $event.target.value"
                                :display-value="(c) => c?.contact_person ?? ''"
                            />
                            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                            </ComboboxButton>

                            <ComboboxOptions
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                            >
                                <div v-if="filteredPerson.length === 0 && queryPerson !== ''" class="px-4 py-2 text-gray-500 select-none">
                                    Nothing found.
                                </div>

                                <ComboboxOption
                                    v-for="one in filteredPerson"
                                    :key="one.id"
                                    :value="one"
                                    class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pr-4 pl-10 select-none"
                                    v-slot="{ selected }"
                                >
                                    <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                        {{ one.contact_person }}
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
                <div class="w-full sm:w-1/2 lg:w-auto">
                    
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                    
                </div>
                <div class="w-full sm:w-1/2 lg:w-auto">
                   
                </div>
                <div class="w-full sm:w-auto">
                    <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                </div>
                <div class="w-full sm:w-auto">
                    <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Sl</TableHead>
                            <TableHead>Branch</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Contact Person</TableHead>
                            <TableHead>Payable Amount</TableHead>
                            <TableHead class="text-center">Create Payment</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="(supplier, index) in data.data" :key="index">
                        <TableRow>
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ supplier?.branch }}</TableCell>
                            <TableCell>{{ supplier?.suppliername }}</TableCell>
                            <TableCell>{{ supplier?.contact_person }}</TableCell>
                            <TableCell>{{ supplier?.payable }}</TableCell>

                            <TableCell class="text-center">
                                <Button
                                    size="sm"
                                    variant="outline"
                                    class="cursor-pointer"
                                    @click="router.visit(route('supplierPayment.create', { subcode: supplier.subcode }))"
                                >
                                    <ShieldCheck class="text-red-500 mr-1 h-4 w-4" />
                                </Button>                                
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
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                <!-- Header -->
                <DialogHeader class="space-y-1 border-b pb-4">
                    <DialogTitle class="text-xl font-semibold tracking-wide">
                        {{ isEditMode ? 'Edit Supplier Invoice' : 'Create Supplier Invoice' }}
                    </DialogTitle>
                    <DialogDescription class="text-sm text-gray-500">
                        {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new Supplier Invoice.' }}
                    </DialogDescription>
                </DialogHeader>
                <!-- Body -->
                <!-- Referance -->
                <div>
                    <Label for="referance" class="text-sm font-medium">Referance <span class="text-red-500">*</span></Label>
                    <Textarea v-model="form.referance" class="mt-1 w-full" placeholder="write your voucher referance"></Textarea>
                    <p v-if="form.errors.referance" class="mt-1 text-sm text-red-600">{{ form.errors.referance }}</p>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Branch -->
                    <div>
                        <Label for="branch_id" class="text-sm font-medium">Select Branch<span class="text-red-500">*</span></Label>
                       

                        <p v-if="form.errors.branch_id" class="mt-1 text-sm text-red-600">{{ form.errors.branch_id }}</p>
                    </div>

                    <!-- Voucher Date -->
                    <div>
                        <Label for="voucherdate" class="text-sm font-medium">Select Date<span class="text-red-500">*</span></Label>
                        <VueDatePicker
                            v-model="vdate"
                            :max-date="maxDate"
                            :format="'yyyy-MM-dd'"
                            :enable-time-picker="false"
                            placeholder="Select Date"
                            auto-apply
                        />
                        <p v-if="form.errors.voucherdate" class="mt-1 text-sm text-red-600">{{ form.errors.voucherdate }}</p>
                    </div>
                    <!-- Debit Account -->
                    <div>
                        <Label for="subcode" class="text-sm font-medium">Supplier Name<span class="text-red-500">*</span></Label>
                       

                        <p v-if="form.errors.subcode" class="mt-1 text-sm text-red-600">{{ form.errors.subcode }}</p>
                    </div>
                    <!-- Invoice Amount -->
                    <div>
                        <Label for="invAmt" class="text-sm font-medium">Invoice Amount<span class="text-red-500">*</span></Label>
                        <Input type="text" id="invAmt" v-model="form.invAmt" placeholder="Enter Invoice Amount" class="mt-1 w-full" />
                        <p v-if="form.errors.invAmt" class="mt-1 text-sm text-red-600">{{ form.errors.invAmt }}</p>
                    </div>
                    
                </div>

                <!-- Notes -->
                <div>
                    <Label for="notes" class="text-sm font-medium">Notes <span class="text-red-500">*</span></Label>
                    <Textarea v-model="form.notes" class="mt-1 w-full" placeholder="write voucher notes"></Textarea>
                    <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-8 flex justify-end gap-3 border-t pt-4">
                    <DialogClose as-child>
                        <Button variant="secondary">Cancel</Button>
                    </DialogClose>

                    <Button :disabled="form.processing" @click="submit">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
