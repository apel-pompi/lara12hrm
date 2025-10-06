<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import ProductLayout from '@/pages/allpages/Agency/Product/productlayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { router, useForm } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';


const props = defineProps<{
    product: { id: number; status: string };
    country: { id: number; name: string; currency: string; currency_symbol: string };
    instype: { id: number; name: string };
    feestype: { id: number; name: string };
    feesDt:[]
}>();

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

// Selected Country
const selectedCountry = ref<{ id: number; name: string; currency: string; currency_symbol: string }[]>([]);
const queryCountry = ref('');

const filteredCountry = computed(() =>
    queryCountry.value === '' ? props.country : props.country.filter((p) => p.name.toLowerCase().includes(queryCountry.value.toLowerCase())),
);

const isSelectedCountry = (p: { id: number; name: string }) => selectedCountry.value.some((pb) => pb.id === p.id);

const toggleCountry = (p: { id: number; name: string }) => {
    const idx = selectedCountry.value.findIndex((pb) => pb.id === p.id);
    if (idx === -1) selectedCountry.value.push(p);
    else selectedCountry.value.splice(idx, 1);
};
const removeCountry = (p: { id: number; name: string }) => {
    selectedCountry.value = selectedCountry.value.filter((pb) => pb.id !== p.id);
};

//selected installment type and fees type
const selectedInsType = ref<any>(null);

//query
const queryInsType = ref('');

//filter
const filteredInsType = computed(() =>
    queryInsType.value ? props.instype.filter((c) => c.name.toLowerCase().includes(queryInsType.value.toLowerCase())) : props.instype,
);

const filteredFeesType = (query: string) => {
    if (!query) return props.feestype;
    return props.feestype.filter((c) => c.name.toLowerCase().includes(query.toLowerCase()));
};

interface FeeRow {
    fees: any | null;
    query: string;
    ins_amount: number;
    insqty: number;
    pay_type: string;
}

const form = useForm({
    id: null as number | null,
    country_id: [],
    ins_id: null as number | null,
    name: '',
    netamount: '',
    rows: [{ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '' } as FeeRow],
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const rows = ref<FeeRow[]>([{ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '' }]);

const addRow = () => {
    rows.value.push({ fees: null, query: '', ins_amount: 0, insqty: 1, pay_type: '' });
};

const removeRow = (index: number) => {
    rows.value.splice(index, 1);
};

const submit = () => {
    form.country_id = selectedCountry.value.map((p) => p.id);
    form.ins_id = selectedInsType.value ? selectedInsType.value.id : null;
    const rowsForSubmit = rows.value.map((r) => ({
        fees_id: r.fees?.id ?? null,
        ins_amount: r.ins_amount,
        insqty: r.insqty,
        pay_type: r.pay_type,
        totalfees: r.ins_amount * r.insqty,
    }));

    form.transform((data) => ({
        ...data,
        country_id: selectedCountry.value.map((p) => p.id),
        ins_id: selectedInsType.value ? selectedInsType.value.id : null,
        rows: rowsForSubmit,
        netamount: rowsForSubmit.reduce((sum, r) => sum + r.totalfees, 0),
    }));

    form.post(route('productActivities.storefess', props.product.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: `Product fees create successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('productActivities.fees', props.product.id), {
                    only: ['products'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', { description: firstError });
        },
    });
};

const deleteForm = useForm({});

const onDelete = async (id: number, productId: number) => {
    if (!confirm('Are you sure you want to delete this product fees?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/product/activities/${productId}/fees/show/${id}`, {
        onSuccess: () => {
            toast.success('Product fees deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

</script>

<template>
    <ProductLayout :product="props.product">
        <div class="space-y-4">
            <div>
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus>Add</Button>
                <div class="mx-auto max-w-4xl space-y-6 p-6">
                    <!-- Default Fee Card -->
                    <div v-for="fee in props.feesDt" :key="fee.id" class="rounded-lg border p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-blue-500">{{ fee.name }}</h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    Valid For <br>
                                    <Badge variant="outline" size="sm" v-for="country in fee.country_names" :key="country">{{ country }}</Badge>
                                </p>
                                <p class="mt-1 text-sm text-gray-500">Installment Type <span class="font-medium">{{ fee.installment.name }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Fee Breakdown</p>
                                <div v-for="detailFees in fee.details" :key="detailFees" class="mt-1">
                                    <p class="text-sm text-gray-700">{{ detailFees.fees.name }}</p>
                                    <p class="text-sm">{{ detailFees.insqty }} {{ fee.installment.name }} @ $ {{ detailFees.amount }}</p>
                                    <p class="mt-2 text-sm font-semibold">{{ detailFees.totalamount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <!-- Buttons on the left -->
                            <div class="space-x-2">
                                <Button variant="outline" size="sm"><SquarePen></SquarePen>Edit</Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(fee.id,props.product.id)"><Trash></Trash></Button>
                            </div>
                            <!-- Total Fees on the right -->
                            <div class="text-lg font-bold text-blue-500">Total Fees $ {{ fee.netamount }}</div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <!-- Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="flex max-h-[90vh] w-full max-w-5xl flex-col overflow-y-auto sm:max-w-3xl md:max-w-4xl lg:max-w-5xl">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Fee Option' : 'Add New Fee Option' }}</DialogTitle>
                    <DialogDescription> Make changes to your fee option here. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <div class="space-y-6">
                    <!-- First Row -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="w-full">
                            <Label for="name">Fee Option Name<span class="text-red-600">*</span></Label>
                            <Input v-model="form.name" class="w-full" placeholder="Enter fee option name" />
                        </div>

                        <div class="w-full">
                            <Label for="country_id">Country of Residency<span class="text-red-600">*</span></Label>
                            <div class="relative w-full">
                                <div
                                    class="flex min-h-[40px] cursor-text flex-wrap items-center gap-1 rounded-lg border px-2 py-1 shadow-sm"
                                    @click="$refs.inputCountry.focus()"
                                >
                                    <span
                                        v-for="p in selectedCountry"
                                        :key="p.id"
                                        class="flex items-center rounded-full bg-indigo-100 px-2 py-1 text-sm text-indigo-800"
                                    >
                                        {{ p.name }}
                                        <button type="button" class="ml-1" @click.prevent="removeCountry(p)">×</button>
                                    </span>

                                    <input
                                        ref="inputCountry"
                                        type="text"
                                        v-model="queryCountry"
                                        class="flex-1 border-none bg-transparent p-1 text-sm outline-none"
                                        placeholder="Search country"
                                    />
                                </div>

                                <div
                                    v-if="queryCountry !== '' && filteredCountry.length > 0"
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white shadow-lg"
                                >
                                    <div
                                        v-for="p in filteredCountry"
                                        :key="p.id"
                                        class="flex cursor-pointer items-center justify-between px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                        @click.prevent="
                                            toggleCountry(p);
                                            queryCountry = '';
                                        "
                                    >
                                        <span>{{ p.name }}</span>
                                        <CheckIcon v-if="isSelectedCountry(p)" class="h-5 w-5 text-indigo-600" />
                                    </div>
                                </div>

                                <div
                                    v-if="queryCountry !== '' && filteredCountry.length === 0"
                                    class="absolute z-10 mt-1 w-full rounded-lg border bg-white px-4 py-2 text-sm text-gray-500"
                                >
                                    Nothing found.
                                </div>
                            </div>
                        </div>

                        <div class="w-full">
                            <Label for="ins_type">Installment Type<span class="text-red-600">*</span></Label>
                            <Combobox v-model="selectedInsType">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-lg border-gray-300 bg-white py-2 pr-10 pl-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Search ins"
                                        @input="queryInsType = $event.target.value"
                                        :display-value="(c) => (c ? c.name : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white py-1 text-sm shadow-lg"
                                    >
                                        <div v-if="filteredInsType.length === 0 && queryInsType !== ''" class="px-4 py-2 text-gray-500">
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="partner in filteredInsType"
                                            :key="partner.id"
                                            :value="partner"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ partner.name }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div v-for="(row, index) in rows" :key="index" class="mb-4 rounded-xl border bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                            <div>
                                <Label>Fee Type<span class="text-red-600">*</span></Label>
                                <Combobox v-model="row.fees">
                                    <div class="relative">
                                        <ComboboxInput
                                            class="w-full rounded-lg border-gray-300 bg-white py-2 pr-10 pl-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Search fees"
                                            @input="row.query = $event.target.value"
                                            :display-value="(c) => (c ? c.name : '')"
                                        />
                                        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                        </ComboboxButton>
                                        <ComboboxOptions
                                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white py-1 text-sm shadow-lg"
                                        >
                                            <div v-if="filteredFeesType(row.query).length === 0 && row.query !== ''" class="px-4 py-2 text-gray-500">
                                                Nothing found.
                                            </div>
                                            <ComboboxOption
                                                v-for="fees in filteredFeesType(row.query)"
                                                :key="fees.id"
                                                :value="fees"
                                                class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                            >
                                                {{ fees.name }}
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </div>
                                </Combobox>
                            </div>
                            <div>
                                <Label>Amount<span class="text-red-600">*</span></Label>
                                <Input v-model.number="row.ins_amount" class="w-full" type="number" placeholder="0.00" />
                            </div>
                            <div>
                                <Label>Qty<span class="text-red-600">*</span></Label>
                                <Input v-model.number="row.insqty" class="w-full" type="number" placeholder="0" />
                            </div>
                            <div>
                                <Label>Total Fee</Label>
                                <Input
                                    v-model.number="form.totalfees"
                                    :value="(row.ins_amount * row.insqty).toFixed(2)"
                                    class="w-full"
                                    type="number"
                                    readonly
                                />
                            </div>
                            <div>
                                <Label>Income/Payable</Label>
                                <Select v-model="row.pay_type">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Choose income or payable" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="income">Income</SelectItem>
                                            <SelectItem value="payable">Payable</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <!-- Delete Button -->
                        <div class="mt-2 flex justify-end">
                            <Button variant="default" size="sm" @click="removeRow(index)" v-if="rows.length > 1"><Trash></Trash></Button>
                        </div>
                    </div>
                    <!-- Add Fee Button & Net Total -->
                    <div class="flex items-center justify-between border-t pt-4">
                        <Button variant="outline" @click="addRow"><Plus></Plus> Fee</Button>
                        <span class="text-lg font-semibold">
                            Net Total:
                            <span class="text-blue-600">{{ rows.reduce((sum, r) => sum + r.ins_amount * r.insqty, 0).toFixed(2) }}</span>
                        </span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Cancel</Button>
                    </DialogClose>
                    <Button :disabled="form.processing" @click="submit" class="bg-blue-600 hover:bg-blue-700">
                        {{ isEditMode ? 'Update' : 'Save' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </ProductLayout>
</template>
