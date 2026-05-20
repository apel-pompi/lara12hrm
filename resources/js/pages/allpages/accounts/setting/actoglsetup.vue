<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsLayout from '@/layouts/settings/accountLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'AC to GL Setup',
        href: '/actoglsetup',
    },
];

export interface ACtoGLSetup {
    id: number;
    type: string;
    code: string;
    cracc: string;
    accdisc: string;
    dracc: string;
    props: string;
    percent: string;
    acctax: string;
    branch: { id: number; branchname: string };
    active: number;
    user: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    actogl: Array<ACtoGLSetup>;
    supplier: Array<{ id: number; subcode: string; name: string }>;
    branch: Array<{ id: number; branchname: string }>;
    draccountcode: Array<{ accountcode: string; description: string }>;
    craccountcode: Array<{ accountcode: string; description: string; group_four?: { description: string } }>;
}>();

const data = props.actogl;

const selecteBranch = ref(null);
const queryBranch = ref('');
const filteredBranch = computed(() => (queryBranch.value === '' ? props.branch : props.branch.filter((n) => n.branchname)));

const selecteCrAcc = ref<{ accountcode: string; description: string } | null>(null);
const queryCrAcc = ref('');

const filteredCrAcc = computed(() => {
    if (!queryCrAcc.value) return props.craccountcode;

    return props.craccountcode.filter((acc) => {
        const desc = form.type === 'Student Advance' && acc.group_four ? acc.group_four.description : acc.description;
        return desc?.toLowerCase().includes(queryCrAcc.value.toLowerCase());
    });
});

const selecteDrAcc = ref<{ accountcode: string; description: string } | null>(null);
const queryDrAcc = ref('');

const filteredDrAcc = computed(() => {
    if (!queryDrAcc.value) return props.draccountcode;

    return props.draccountcode.filter((acc) => acc.description?.toLowerCase().includes(queryDrAcc.value.toLowerCase()));
});

const selecteTaxAcc = ref<{ accountcode: string; description: string } | null>(null);
const queryTaxAcc = ref('');
const filteredTaxAcc = computed(() => {
    if (!queryTaxAcc.value) return props.craccountcode;

    return props.craccountcode.filter((acc) => acc.description?.toLowerCase().includes(queryTaxAcc.value.toLowerCase()));
});

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    type: '',
    code: '',
    accdisc: '',
    cracc: '',
    dracc: '',
    props: '',
    percent: null as number | null,
    acctax: '',
    branch_id: null as number | null,
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const { data } = await axios.get(`/actoglsetup/${id}/edit`);

        Object.assign(form, data.data);
        form.id = data.data.id;
        selecteBranch.value = props.branch.find((b) => b.id === data.data.branch_id) ?? null;

        selecteCrAcc.value = props.craccountcode.find((a) => a.accountcode === data.data.cracc) ?? null;

        selecteDrAcc.value = props.draccountcode.find((a) => a.accountcode === data.data.dracc) ?? null;

        selecteTaxAcc.value = props.craccountcode.find((a) => a.accountcode === data.data.acctax) ?? null;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

watch(
    () => form.type,
    (newType) => {
        if (newType === 'Student Advance') {
            form.accdisc = null;
            form.code = null;
            form.acctax = null;
            form.percent = null;
        }
    },
);

const submit = () => {
    const action = isEditMode.value && form.id ? route('actoglsetup.update', form.id) : route('actoglsetup.store');
    const method = isEditMode.value ? 'put' : 'post';

    if (!selecteBranch.value?.id) {
        toast('Validation Error', {
            description: 'Please select a branch',
        });
        return;
    }
    if (!form.type) {
        toast('Validation Error', {
            description: 'Please select a type',
        });
        return;
    }
    if (form.type !== 'Student Advance') {
        if (!form.accdisc) {
            toast('Validation Error', {
                description: 'Please select an account name',
            });
            return;
        }

        if (!form.code) {
            toast('Validation Error', {
                description: 'Please select a code',
            });
            return;
        }
    }

    form.cracc = selecteCrAcc.value?.accountcode ?? null;
    form.dracc = selecteDrAcc.value?.accountcode ?? null;
    form.acctax = selecteTaxAcc.value?.accountcode ?? null;
    form.branch_id = selecteBranch.value.id;

    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('actoglsetup.index'), {
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

const toggleStatus = (one: ACtoGLSetup, checked: boolean) => {
    router.put(
        route('actoglsetup.updateStatus', one.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                one.active = checked ? 1 : 0;
                const flash = usePage().props.flash;
                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this code pharms?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/actoglsetup/show/${id}`, {
        onSuccess: () => {
            const flash = usePage().props.flash;
            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
        },
        onError: () => {
            if (flash?.success) {
                toast('error', {
                    description: flash.success,
                });
            }
        },
        preserveScroll: true,
        preserveState: false,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="AC to GL Setup" />

        <AccountsLayout :breadcrumbs="breadcrumbs">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Toolbar -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Create
                    </Button>
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">AC to GL setup List</h2>
                        <p class="text-sm text-gray-500">Manage all AC to GL setup from here.</p>
                    </div>
                    <Table class="w-full text-sm">
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-100">
                                <TableHead>Sl</TableHead>
                                <TableHead>Branch Name</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Code</TableHead>
                                <TableHead>Account Code</TableHead>
                                <TableHead>Credit Account</TableHead>
                                <TableHead>Debit Account</TableHead>
                                <TableHead>Props</TableHead>
                                <TableHead>Percent</TableHead>
                                <TableHead>Tax Acccount</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(chart, index) in data ?? []" :key="index" class="hover:bg-muted/50">
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell>{{ chart.branch.branchname }}</TableCell>
                                <TableCell>{{ chart.type }}</TableCell>
                                <TableCell>{{ chart.code }}</TableCell>
                                <TableCell>{{ chart.accdisc }}</TableCell>
                                <TableCell>{{ chart.type === 'Student Advance' && chart.craccount?.group_four ? chart.craccount.group_four.description : (chart.craccount?.description ?? '') }}</TableCell>
                                <TableCell>{{ chart.draccount?.description ?? '' }}</TableCell>
                                <TableCell>{{ chart.props }}</TableCell>
                                <TableCell>{{ chart.percent }}</TableCell>
                                <TableCell>{{ chart.taxaccount?.description ?? '' }}</TableCell>
                                <TableCell>
                                    <Switch :model-value="Boolean(chart.active)" @update:model-value="(checked) => toggleStatus(chart, checked)">
                                    </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button size="sm" variant="outline" @click="onEdit(chart.id)"
                                        ><SquarePen class="h-4 w-4 text-indigo-600"
                                    /></Button>
                                    <Button size="sm" variant="outline" @click="onDelete(chart.id)"><Trash class="h-4 w-4 text-indigo-600" /></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                    <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                        <label for="per-page" class="text-gray-600"></label>
                    </div>
                    <div class="space-x-2"></div>
                </div>
            </div>

            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                    <!-- Header -->
                    <DialogHeader class="mb-4 border-b pb-3">
                        <DialogTitle class="text-2xl font-semibold tracking-wide">
                            {{ isEditMode ? 'Edit AC to GL Setup' : 'Create AC to GL Setup' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new AC to GL Setup.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- First Group -->
                        <div>
                            <Label for="type" class="text-sm font-medium">Branch<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selecteBranch">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Branch"
                                        @input="queryBranch = $event.target.value"
                                        :display-value="(c) => (c ? c.branchname : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredBranch.length === 0 && queryBranch !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="branch in filteredBranch"
                                            :key="branch.id"
                                            :value="branch"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ branch.branchname }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.branch_id" class="mt-1 text-sm text-red-600">{{ form.errors.branch_id }}</p>
                        </div>

                        <div>
                            <Label for="type" class="text-sm font-medium">Type<span class="text-red-500">*</span></Label>
                            <Select v-model="form.type">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Student Advance">Student</SelectItem>
                                        <SelectItem value="Supplier">Supplier</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                        </div>
                        <div v-if="form.type !== 'Student Advance'">
                            <Label for="accdisc" class="text-sm font-medium">Account Name<span class="text-red-500">*</span></Label>
                            <Select v-model="form.accdisc">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Account Name" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup v-for="supplier in props.supplier" :key="supplier.id">
                                        <SelectItem :value="supplier.subcode">{{ supplier.name }}</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.accdisc" class="mt-1 text-sm text-red-600">{{ form.errors.accdisc }}</p>
                        </div>
                        <!-- Second Group -->
                        <div v-if="form.type !== 'Student Advance'">
                            <Label for="code" class="text-sm font-medium">Code<span class="text-red-500">*</span></Label>
                            <Select v-model="form.code">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Code" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Wholesale">Wholesale</SelectItem>
                                        <SelectItem value="International">International</SelectItem>
                                        <SelectItem value="Domestic">Domestic</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">{{ form.errors.code }}</p>
                        </div>
                        <div>
                            <Label for="dracc" class="text-sm font-medium">Debit Account<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selecteDrAcc">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Account"
                                        @input="queryDrAcc = $event.target.value"
                                        :display-value="(c) => (c ? c.description : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredDrAcc.length === 0 && queryDrAcc !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="dracc in filteredDrAcc"
                                            :key="dracc.accountcode"
                                            :value="dracc"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ dracc.description }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.dracc" class="mt-1 text-sm text-red-600">{{ form.errors.dracc }}</p>
                        </div>
                        <!-- Third Group -->
                        <div>
                            <Label for="cracc" class="text-sm font-medium">Credit Account<span class="text-red-500">*</span></Label>
                            <Combobox v-model="selecteCrAcc">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Account"
                                        @input="queryCrAcc = $event.target.value"
                                        :display-value="(c) => (c ? (form.type === 'Student Advance' && c.group_four ? c.group_four.description : c.description) : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredCrAcc.length === 0 && queryCrAcc !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="cracc in filteredCrAcc"
                                            :key="cracc.accountcode"
                                            :value="cracc"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ form.type === 'Student Advance' && cracc.group_four ? cracc.group_four.description : cracc.description }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.cracc" class="mt-1 text-sm text-red-600">{{ form.errors.cracc }}</p>
                        </div>

                        <!-- Account Code -->
                        <div v-if="form.type !== 'Student Advance'">
                            <Label for="props" class="text-sm font-medium">Addition Type</Label>
                            <Select v-model="form.props">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Addition Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Addition">Addition</SelectItem>
                                        <SelectItem value="Deduction">Deduction</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.props" class="mt-1 text-sm text-red-600">{{ form.errors.props }}</p>
                        </div>

                        <!-- Description -->
                        <div v-if="form.type !== 'Student Advance'">
                            <Label for="percent" class="text-sm font-medium">Percent</Label>
                            <Input type="text" id="percent" v-model="form.percent" placeholder="Enter Percent" class="mt-1 w-full" />
                            <p v-if="form.errors.percent" class="mt-1 text-sm text-red-600">{{ form.errors.percent }}</p>
                        </div>

                        <!-- Account Type -->
                        <div v-if="form.type !== 'Student Advance'">
                            <Label for="acctax" class="text-sm font-medium">Tax Account</Label>
                            <Combobox v-model="selecteTaxAcc">
                                <div class="relative">
                                    <ComboboxInput
                                        class="w-full rounded-md border border-gray-300 bg-white py-2 pr-10 pl-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                        placeholder="Select Account"
                                        @input="queryTaxAcc = $event.target.value"
                                        :display-value="(c) => (c ? c.description : '')"
                                    />
                                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                    </ComboboxButton>
                                    <ComboboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-gray-700 dark:bg-gray-900"
                                    >
                                        <div
                                            v-if="filteredTaxAcc.length === 0 && queryTaxAcc !== ''"
                                            class="cursor-default px-4 py-2 text-gray-500 select-none"
                                        >
                                            Nothing found.
                                        </div>
                                        <ComboboxOption
                                            v-for="tax in filteredTaxAcc"
                                            :key="tax.accountcode"
                                            :value="tax"
                                            class="cursor-pointer px-3 py-2 hover:bg-indigo-600 hover:text-white"
                                        >
                                            {{ tax.description }}
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </div>
                            </Combobox>
                            <p v-if="form.errors.acctax" class="mt-1 text-sm text-red-600">{{ form.errors.acctax }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="mt-6 flex justify-end gap-3 border-t pt-4">
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
        </AccountsLayout>
    </AppLayout>
</template>
