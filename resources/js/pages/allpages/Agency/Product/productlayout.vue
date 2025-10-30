<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Link, router, useForm } from '@inertiajs/vue3';
import { Archive, CornerDownLeft, Mail, MessageCircleMore, SquarePen,Undo2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Product Activity', href: '/product' }];

export interface Product {
    id: number;
    name: string;
}

const props = defineProps<{
    product: {
        id: number;
        name: string;
        intak_month: string;
        active:number;
        user: {
            name: string;
        };
    };
}>();

const sidebarNavItems = computed<NavItem[]>(() => {
    if (!props.product) return [];

    return [
        {
            title: 'Applications',
            href: route('productActivities.application', props.product.id),
        },
        {
            title: 'Documents',
            href: route('productActivities.documents', props.product.id),
        },
        {
            title: 'Fees',
            href: route('productActivities.fees', props.product.id),
        },
        {
            title: 'Requirements',
            href: route('productActivities.requirement', props.product.id),
        },
        {
            title: "other's Information",
            href: route('productActivities.others', props.product.id),
        },
        {
            title: 'Promotions',
            href: route('productActivities.promotions', props.product.id),
        },
    ];
});



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

const form = useForm({
    id: '',
    name: '',
    partner_id: '',
    product_type_id: '',
    revinue_type: '',
    duration: '',
    intak_month: [], // multiple selection (from tags)
    description: '',
    note: '',
});

// ------------------- Partner & Product-------------------
//selected
const selectedPartner = ref<any>(null);
const partners = ref<any[]>([]);

const selectedProduct = ref<any>(null);
const products = ref<any[]>([]);
//query
const queryPartner = ref('');
const queryProduct = ref('');
//filter
const filteredPartner = computed(() =>
    queryPartner.value ? partners.value.filter((c) => c.name.toLowerCase().includes(queryPartner.value.toLowerCase())) : partners.value,
);

const filteredProduct = computed(() =>
    queryProduct.value ? products.value.filter((c) => c.producttypename.toLowerCase().includes(queryProduct.value.toLowerCase())) : products.value,
);

//Intake Month
const intake = ref<any[]>([]);
const selectedIntake = ref<{ id: number; month: string }[]>([]);
const queryIntake = ref('');

const filteredIntake = computed(() =>
    queryIntake.value === '' ? intake : intake.value.filter((p) => p.month.toLowerCase().includes(queryIntake.value.toLowerCase())),
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

const showDialog = ref(false);

const onEdit = async (id: number) => {
    try {
        const url = route('product.edit', {
            product: id,
        });
        const res = await fetch(url);
        if (!res.ok) {
            toast.error('Server error while fetching application details.');
            return;
        }
        const data = await res.json();

        Object.assign(form, data.data);
        partners.value = data.partner;
        selectedPartner.value = data.partner.find((p: any) => p.id === data.data.partner_id);

        products.value = data.product;
        selectedProduct.value = data.product.find((p: any) => p.id === data.data.product_type_id);
        intake.value = data.months || [];

        const selected = intake.value.find((p: any) => p.id === data.data.months);
        selectedIntake.value = selected ? [selected] : [];
        if (selected) {
            queryIntake.value = selected.month;
        }
        form.id = data.data.id;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = async () => {
    // Start processing
    form.processing = true;

    // Validation
    if (form.revinue_type == null) {
        toast.error('Revenue type cannot be empty');
        form.processing = false;
        return;
    }

    if (selectedIntake.value.length === 0) {
        toast.error('Intake month cannot be empty');
        form.processing = false;
        return;
    }

    const payload = {
        id: form.id,
        name: form.name,
        partner_id: selectedPartner.value?.id,
        product_type_id: selectedProduct.value?.id,
        revinue_type: form.revinue_type,
        duration: form.duration,
        intake_months: selectedIntake.value.map((p) => p.month),
        description: form.description,
        note: form.note,
    };

    try {
        await router.put(route('product.update', { product: form.id }), payload, {
            onSuccess: () => {
                toast('success', { description: 'Product updated successfully!' });
                showDialog.value = false;
                form.reset();
            },
            onError: (errors) => {
                toast('error', { description: 'Something went wrong. ' + JSON.stringify(errors) });
            },
        });
    } catch (error) {
        console.error(error);
        toast.error('Server error occurred.');
    } finally {
        form.processing = false;
    }
};

const updateStatus = (active: number) => {
    router.put(
        route('product.updateStatus', props.product.id),
        {
            active,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Product status update');
            },
        },
    );
};
const goToPartner = () => {
    router.visit(route('PartnerActivities.product', props.product.partner.id));
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border bg-gray-100 px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 pt-4 pl-4">
                <div class="flex-1 text-sm dark:text-black">
                    <Heading title="Product Actvities" description="Manage your partner activities and account settings" />
                </div>
                <div class="space-x-2">
                    <Button class="dark:text-black" variant="outline" size="sm" @click="goToPartner"><CornerDownLeft></CornerDownLeft> Back</Button>
                </div>
            </div>
            <div class="flex flex-col gap-6 p-4 lg:flex-row">
                <!-- LEFT SIDEBAR -->
                <aside class="flex w-full flex-col gap-6 rounded-xl bg-white p-4 shadow lg:w-1/4 dark:bg-gray-900">
                    <!-- Profile -->
                    <div class="flex flex-col items-center border-b pb-5 text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 text-xl font-bold dark:bg-gray-700">
                            {{ (props.product.name?.charAt(0) ?? '').toUpperCase() }}
                        </div>
                        <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ props.product.name }}</h2>

                        <div class="mt-3 flex items-center justify-center gap-3 text-gray-400">
                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <MessageCircleMore />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Compose SMS
                                </span>
                            </div>

                            <div class="group relative">
                                <button class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <Mail />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Compose email
                                </span>
                            </div>

                            <div class="group relative">
                                <button @click="onEdit(props.product.id)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                    <SquarePen />
                                </button>
                                <span
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    Edit
                                </span>
                            </div>
                            <div>
                                <div v-if="props.product.active == 1">
                                    <div class="group relative">
                                        <!-- Archive Button -->
                                        <button @click="updateStatus(0)" class="cursor-pointer text-[8px] uppercase hover:text-gray-700">
                                            <Archive />
                                        </button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Archive
                                        </span>
                                    </div>
                                </div>

                                <div v-else>
                                    <div class="group relative">
                                    <!-- Restore Button -->
                                        <button
                                            @click="updateStatus(1)"
                                            class="cursor-pointer text-[10px] uppercase hover:text-gray-700"
                                        >
                                            <Undo2 />
                                        </button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Restore
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="border-b pb-5 text-sm">
                        <h4 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">General Information:</h4>
                        <p>
                            Intake: <span class="text-gray-500">{{ props.product.intak_month }}</span>
                        </p>


                        <p class="mt-2 text-gray-600">
                            Added From: <span class="font-medium">{{ props.product.user.name }}</span>
                        </p>
                        <p class="text-gray-600">
                            Internal Id: <span class="font-medium">{{ props.product.id }}</span>
                        </p>
                    </div>
                    <!-- Dialog -->
                    <Dialog v-model:open="showDialog">
                        <DialogContent class="max-h-[100vh] w-full max-w-4xl overflow-y-auto rounded-2xl border shadow-xl">
                            <DialogHeader class="sticky top-2 z-10 border-b bg-white pb-5">
                                <DialogTitle class="text-2xl font-bold text-gray-900">Edit Product</DialogTitle>
                                <DialogDescription class="text-sm text-gray-500"> Fill out the form below to add a edit product. </DialogDescription>
                            </DialogHeader>

                            <!-- Form -->
                            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Name -->
                                <div>
                                    <Label for="name" class="block pb-2 text-sm font-medium text-gray-700"
                                        >Name <span class="text-red-500">*</span></Label
                                    >
                                    <Input id="name" v-model="form.name" placeholder="Enter product name" />
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <!-- Select Partner -->
                                <div>
                                    <Label class="block pb-2 text-sm font-medium text-gray-700"
                                        >Select Partner <span class="text-red-500">*</span></Label
                                    >
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
                                    <Label class="block pb-2 text-sm font-medium text-gray-700"
                                        >Product Type <span class="text-red-500">*</span></Label
                                    >
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
                                    <Label class="block pb-2 text-sm font-medium text-gray-700">
                                        Revenue Type<span class="text-red-500">*</span>
                                    </Label>

                                    <RadioGroup v-model="form.revinue_type" class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <RadioGroupItem value="0" id="revenue-client" />
                                            <Label for="revenue-client">Revenue From Client</Label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <RadioGroupItem value="1" id="revenue-partner" />
                                            <Label for="revenue-partner">Commission From Partner</Label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <RadioGroupItem value="3" id="both-partner" />
                                            <Label for="both-partner">Both Commission</Label>
                                        </div>
                                    </RadioGroup>

                                    <p v-if="form.errors.revinue_type" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.revinue_type }}
                                    </p>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <Label>Duration<span class="text-red-500">*</span></Label>
                                    <Input type="text" v-model="form.duration" placeholder="e.g. 1 year 2 months 6 weeks" />
                                    <p v-if="form.errors.duration" class="mt-1 text-sm text-red-600">{{ form.errors.duration }}</p>
                                </div>
                                <!-- Intake Month -->
                                <div>
                                    <Label class="block pb-2 text-sm font-medium text-gray-700"
                                        >Intake Month<span class="text-red-500">*</span></Label
                                    >
                                    <div class="relative w-full">
                                        <!-- Tags -->
                                        <div
                                            class="flex min-h-[40px] cursor-text flex-wrap gap-1 rounded-md border p-1"
                                            @click="$refs.inputMonth.focus()"
                                        >
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
                </aside>

                <!-- MAIN CONTENT -->
                <main class="flex flex-1 flex-col gap-6">
                    <!-- Tabs -->
                    <nav class="text-md flex flex-wrap gap-4 border-b bg-white p-6 font-medium">
                        <div class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border bg-gray-100 p-3">
                            <Button class="p-4 m-1 dark:bg-black" v-for="item in sidebarNavItems" :key="item.href" variant="ghost" as-child>
                                <Link :href="item.href">
                                    {{ item.title }}
                                </Link>
                            </Button>
                        </div>
                    </nav>

                    <section class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
                        <slot />
                    </section>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
