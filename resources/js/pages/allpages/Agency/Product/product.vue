<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Plus, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

export interface Product {
    id: number;
    name: number;
    partner_id: number;
    partner_type_id: number;
    revinue_type: number;
    duration: string;
    intak_month: string;
    description: string;
    note: string;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Product', href: '/product' }];

const props = defineProps<{
    product: Product[];
    partner: { id: number; name: string };
    productType: { id: number; producttypename: string }[];
    months: { id: number; month: string }[];
}>();

const data = props.product;

interface FormErrors {
    name?: string;
    partner_id?: number;
    product_type_id?: number;
    revinue_type?: number;
    duration?: string;
    intak_month?: [];
    description?: string;
    note?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

// ------------------- Partner & Product-------------------
//selected
const selectedPartner = ref<any>(null);
const selectedProduct = ref<any>(null);
//query
const queryPartner = ref('');
const queryProduct = ref('');
//filter
const filteredPartner = computed(() =>
    queryPartner.value ? props.partner.filter((c) => c.name.toLowerCase().includes(queryPartner.value.toLowerCase())) : props.partner,
);

const filteredProduct = computed(() =>
    queryProduct.value
        ? props.productType.filter((c) => c.producttypename.toLowerCase().includes(queryProduct.value.toLowerCase()))
        : props.productType,
);

//Intake Month
const selectedIntake = ref<{ id: number; month: string }[]>([]);
const queryIntake = ref('');

const filteredIntake = computed(() =>
    queryIntake.value === '' ? props.months : props.months.filter((p) => p.month.toLowerCase().includes(queryIntake.value.toLowerCase())),
);

const isSelectedIntake = (p: { id: number; month: string }) => selectedIntake.value.some((pb) => pb.id === p.id);

const toggleIntake = (p: { id: number; month: string }) => {
    const idx = selectedIntake.value.findIndex((pb) => pb.id === p.id);
    if (idx === -1) selectedIntake.value.push(p);
    else selectedIntake.value.splice(idx, 1);
};

const removeIntake = (p: { id: number; month: string }) => {
    selectedIntake.value = selectedIntake.value.filter((pb) => pb.id !== p.id);
};

const form = useForm({
    name: '',
    partner_id: '',
    product_type_id: '',
    revinue_type: '',
    duration: '',
    intak_month: [], // multiple selection (from tags)
    description: '',
    note: '',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const submit = () => {
    form.partner_id = selectedPartner.value ? selectedPartner.value.id : '';
    form.product_type_id = selectedProduct.value ? selectedProduct.value.id : '';
    form.intak_month = selectedIntake.value.map((m) => m.month);

    form.post(route('product.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success', {
                description: `Product created successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('product.index'), {
                    only: ['products'],
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
const toggleStatus = (product: Product, checked: boolean) => {
    router.put(
        route('product.updateStatus', product.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                product.active = checked ? 1 : 0;
                toast.success('Partner  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this product info?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/product/show/${id}`, {
        onSuccess: () => {
            toast.success('Product Info deleted successfully');
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
    <Head title="Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Product </Button>
                </div>
                <div class="space-x-2"></div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Product Name</TableHead>
                            <TableHead>Product Type</TableHead>
                            <TableHead>Associated Partner</TableHead>
                            <TableHead>Enrolled</TableHead>
                            <TableHead>Intake Month</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(product, index) in data" :key="product.id ?? index">
                            <TableCell>
                                <Link
                                    :href="route('productActivities.application', product.id)"
                                    method="get"
                                    class="flex items-center space-x-2 dark:text-white"
                                    >{{ product.name }}</Link
                                >
                            </TableCell>
                            <TableCell>{{ product.productype.producttypename }}</TableCell>
                            <TableCell>{{ product.partner.name }}</TableCell>

                            <TableCell></TableCell>
                            <Badge class="m-0.5 p-1" variant="outline" v-for="(b, idx) in product.intak_month.split(',')" :key="idx">
                                {{ b }}
                            </Badge>
                            <TableCell>
                                <Switch :model-value="Boolean(product.active)" @update:model-value="(checked) => toggleStatus(product, checked)">
                                </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(product.id)"><Trash></Trash></Button>
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
            <DialogContent class="max-h-[100vh] w-full max-w-4xl overflow-y-auto rounded-2xl border shadow-xl">
                <DialogHeader class="sticky top-2 z-10 border-b bg-white pb-5">
                    <DialogTitle class="text-2xl font-bold text-gray-900">Add Product</DialogTitle>
                    <DialogDescription class="text-sm text-gray-500"> Fill out the form below to add a new product. </DialogDescription>
                </DialogHeader>

                <!-- Form -->
                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <Label for="name" class="block pb-2 text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></Label>
                        <Input id="name" v-model="form.name" placeholder="Enter product name" />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- Select Partner -->
                    <div>
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Select Partner <span class="text-red-500">*</span></Label>
                        <!-- Combobox -->
                        <Combobox v-model="selectedPartner">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-lg border-gray-300 bg-white py-2 pr-10 pl-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Search partner..."
                                    @input="queryPartner = $event.target.value"
                                    :display-value="(c) => (c ? c.name : '')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredPartner.length === 0 && queryPartner !== ''" class="px-4 py-2 text-gray-500">
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="partner in filteredPartner"
                                        :key="partner.id"
                                        :value="partner"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ partner.name }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <p v-if="form.errors.partner_id" class="mt-1 text-sm text-red-600">{{ form.errors.partner_id }}</p>
                    </div>

                    <!-- Product Type -->
                    <div>
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Product Type <span class="text-red-500">*</span></Label>
                        <!-- Combobox -->
                        <Combobox v-model="selectedProduct">
                            <div class="relative">
                                <ComboboxInput
                                    class="w-full rounded-lg border-gray-300 bg-white py-2 pr-10 pl-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Search product..."
                                    @input="queryProduct = $event.target.value"
                                    :display-value="(c) => (c ? c.producttypename : '')"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>
                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredProduct.length === 0 && queryProduct !== ''" class="px-4 py-2 text-gray-500">
                                        Nothing found.
                                    </div>
                                    <ComboboxOption
                                        v-for="product in filteredProduct"
                                        :key="product.id"
                                        :value="product"
                                        class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                    >
                                        {{ product.producttypename }}
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <p v-if="form.errors.product_type_id" class="mt-1 text-sm text-red-600">{{ form.errors.product_type_id }}</p>
                    </div>

                    <!-- Revenue Type -->
                    <div>
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Revenue Type<span class="text-red-500">*</span></Label>
                        <RadioGroup v-model="form.revinue_type" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem value="0" id="revenue-client" />
                                <Label for="revenue-client">Revenue From Client</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem value="1" id="revenue-partner" />
                                <Label for="revenue-partner">Commission From Partner</Label>
                            </div>
                        </RadioGroup>
                        <p v-if="form.errors.revinue_type" class="mt-1 text-sm text-red-600">{{ form.errors.revinue_type }}</p>
                    </div>

                    <!-- Duration -->
                    <div>
                        <Label>Duration<span class="text-red-500">*</span></Label>
                        <Input type="text" v-model="form.duration" placeholder="e.g. 1 year 2 months 6 weeks" />
                        <p v-if="form.errors.duration" class="mt-1 text-sm text-red-600">{{ form.errors.duration }}</p>
                    </div>
                    <!-- Intake Month -->
                    <div>
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Intake Month<span class="text-red-500">*</span></Label>
                        <div class="relative w-full">
                            <!-- Tags -->
                            <div class="flex min-h-[40px] cursor-text flex-wrap gap-1 rounded-md border p-1" @click="$refs.inputMonth.focus()">
                                <span
                                    v-for="p in selectedIntake"
                                    :key="p.id"
                                    class="flex items-center rounded-full bg-indigo-100 px-2 py-1 text-sm text-indigo-800"
                                >
                                    {{ p.month }}
                                    <button type="button" class="ml-1" @click.prevent="removeIntake(p)">×</button>
                                </span>

                                <!-- Input -->
                                <input
                                    ref="inputMonth"
                                    type="text"
                                    v-model="queryIntake"
                                    class="flex-1 border-none p-1 text-sm outline-none"
                                    placeholder="Type to search..."
                                />
                            </div>

                            <!-- Dropdown Options -->
                            <div
                                v-if="queryIntake !== '' && filteredIntake.length > 0"
                                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg"
                            >
                                <div
                                    v-for="p in filteredIntake"
                                    :key="p.id"
                                    class="flex cursor-pointer items-center justify-between px-4 py-2 hover:bg-indigo-600 hover:text-white"
                                    @click.prevent="
                                        toggleIntake(p);
                                        queryIntake = '';
                                    "
                                >
                                    <span>{{ p.month }}</span>
                                    <CheckIcon v-if="isSelectedIntake(p)" class="h-5 w-5 text-indigo-600" />
                                </div>
                            </div>

                            <!-- Nothing found -->
                            <div
                                v-if="queryIntake !== '' && filteredIntake.length === 0"
                                class="absolute z-10 mt-1 w-full rounded-md border bg-white px-4 py-2 text-sm text-gray-500"
                            >
                                Nothing found.
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div>
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Description</Label>
                        <Textarea v-model="form.description" placeholder="Type your description here..." rows="3" />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <!-- Note -->
                    <div class="md:col-span-2">
                        <Label class="block pb-2 text-sm font-medium text-gray-700">Note</Label>
                        <Textarea v-model="form.note" placeholder="Type your note here..." rows="3" />
                        <p v-if="form.errors.note" class="mt-1 text-sm text-red-600">{{ form.errors.note }}</p>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="sticky bottom-0 z-10 mt-6 flex justify-end gap-3 border-t bg-white pt-4">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" class="rounded-lg">Close</Button>
                    </DialogClose>
                    <Button
                        :disabled="form.processing"
                        @click="submit"
                        class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700 disabled:opacity-70"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <span>{{ form.processing ? 'Saving...' : 'Submit' }}</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
