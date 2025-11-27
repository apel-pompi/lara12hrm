<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsLayout from '@/layouts/settings/accountLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chart OF Accounts',
        href: '/accountssetting',
    },
];

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

export interface ChartOfAccount {
    accounttype: string;
    accountcode: string;
    description: string;
    accountusage: string;
    analyticalcode: string;
    groupone: { groupone: number; description: string };
    grouptwo: { grouptwo: number; description: string };
    groupthree: { groupthree: string; description: string };
    active: number;
    user: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    chartofaccount: Paginated<ChartOfAccount>;
    groupone:{groupone:number;description:string}
}>();

const data = props.chartofaccount;

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    accounttype: '',
    accountcode: '',
    accountusage: '',
    analyticalcode: '',
    description: '',
    groupone: null as number | null,
    grouptwo: null as number | null,
    groupthree: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const grouptwoOptions = ref([]);

const fetchTwo = async () => {
    if (!form.groupone) return; // groupone code
    const res = await fetch(`/chartOfAccount/getGroupTwo/${form.groupone}`);
    const data = await res.json();
    grouptwoOptions.value = data.data; 
};

watch(() => form.groupone, () => {
    fetchTwo();
});

const groupthreeOptions = ref([]);

const fetchThree = async () => {
    if (!form.groupone || !form.grouptwo) return; // ensure both selected

    const res = await fetch(`/chartOfAccount/getGroupThree/${form.groupone}/${form.grouptwo}`);
    const data = await res.json();
    groupthreeOptions.value = data.data;
};

watch(() => form.grouptwo, () => {
    form.groupthree = '';
    fetchThree();
});

const generateAccountCode = async () => {
    if (!form.groupthree) return;

    const res = await fetch(`/chartOfAccount/generateAccountCode/${form.groupthree}`);
    const data = await res.json();
    form.accountcode = data.accountcode;
};

watch(() => form.groupthree, () => {
    form.accountcode = ''; // reset
    generateAccountCode();
});

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/chartOfAccount/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching chart of accounts details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.data);
        form.id = data.data.id;
        // Fetch group three options
        await fetchThree();

        form.groupthree = data.data.groupthree;
        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('chartOfAccount.update', form.id) : route('chartOfAccount.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('chartOfAccount.index'), {
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

const toggleStatus = (one: ChartOfAccount) => {
    const newStatus = !Boolean(one.active); // boolean
    router.put(
        route('chartOfAccount.updateStatus', one.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                one.active = newStatus ? 1 : 0; // local update (number)
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
    if (!confirm('Are you sure you want to delete this chart of accounts?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/chartOfAccount/show/${id}`, {
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

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Accounts Setting" />

        <AccountsLayout :breadcrumbs="breadcrumbs">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Create
                    </Button>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sl</TableHead>
                                <TableHead>Group One</TableHead>
                                <TableHead>Group Two</TableHead>
                                <TableHead>Group Three</TableHead>
                                <TableHead>Account Code</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Account Usage</TableHead>
                                <TableHead>Analytic Type</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody v-for="(chart, index) in data.data ?? []" :key="index">
                            <TableRow>
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell>{{ chart.group_one.description }}</TableCell>
                                <TableCell>{{ chart.group_two.description }}</TableCell>
                                <TableCell>{{ chart.group_three.description }}</TableCell>
                                <TableCell>{{ chart.accountcode }}</TableCell>
                                <TableCell>{{ chart.description }}</TableCell>
                                <TableCell>{{ chart.accountusage }}</TableCell>
                                <TableCell>{{ chart.analyticalcode }}</TableCell>
                                <TableCell>{{ chart.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="chart.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(chart)"> </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(chart.id)"><SquarePen></SquarePen></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(chart.id)"><Trash></Trash></Button>
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
                <DialogContent class="max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                    <!-- Header -->
                    <DialogHeader class="mb-4 border-b pb-3">
                        <DialogTitle class="text-2xl font-semibold tracking-wide">
                            {{ isEditMode ? 'Edit Chart of Accounts' : 'Create Chart of Accounts' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new Chart of Accounts.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- First Group -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">First Group<span class="text-red-500">*</span></Label>
                            <Select v-model="form.groupone">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group One" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="one in props.groupone" :key="one.id" :value="one.groupone">
                                            {{ one.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.groupone" class="mt-1 text-sm text-red-600">{{ form.errors.groupone }}</p>
                        </div>

                        <!-- Second Group -->
                        <div>
                            <Label for="grouptwo" class="text-sm font-medium">Second Group<span class="text-red-500">*</span></Label>
                             <Select v-model="form.grouptwo">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group Two" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="two in grouptwoOptions" :key="two.id" :value="two.grouptwo">
                                            {{ two.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.grouptwo" class="mt-1 text-sm text-red-600">{{ form.errors.grouptwo }}</p>
                        </div>

                        <!-- Third Group -->
                        <div>
                            <Label for="groupthree" class="text-sm font-medium">Third Group<span class="text-red-500">*</span></Label>
                            <Select v-model="form.groupthree">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Group Three" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="three in groupthreeOptions" :key="three.id" :value="three.groupthree">
                                            {{ three.description }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.groupthree" class="mt-1 text-sm text-red-600">{{ form.errors.groupthree }}</p>
                        </div>

                        <!-- Account Code -->
                        <div>
                            <Label for="accountcode" class="text-sm font-medium">Account Code<span class="text-red-500">*</span></Label>
                            <Input type="text" id="accountcode" v-model="form.accountcode" class="mt-1 w-full" readonly  disabled/>
                            <p v-if="form.errors.accountcode" class="mt-1 text-sm text-red-600">{{ form.errors.accountcode }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="description" class="text-sm font-medium">Description<span class="text-red-500">*</span></Label>
                            <Input type="text" id="description" v-model="form.description" placeholder="Enter Description" class="mt-1 w-full" />
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                        </div>

                        <!-- Account Type -->
                        <div>
                            <Label for="accounttype" class="text-sm font-medium">Account Type<span class="text-red-500">*</span></Label>
                            <Select v-model="form.accounttype">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Account Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Asset">Asset</SelectItem>
                                        <SelectItem value="Liability">Liability</SelectItem>
                                        <SelectItem value="Income">Income</SelectItem>
                                        <SelectItem value="Expenses">Expenses</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.accounttype" class="mt-1 text-sm text-red-600">{{ form.errors.accounttype }}</p>
                        </div>

                        <!-- Account Usage -->
                        <div>
                            <Label for="accountusage" class="text-sm font-medium">Account Usage<span class="text-red-500">*</span></Label>
                            <Select v-model="form.accountusage">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Account Usage" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Ledger">Ledger</SelectItem>
                                        <SelectItem value="Liability">Liability</SelectItem>
                                        <SelectItem value="AP">AP [Account Payable]</SelectItem>
                                        <SelectItem value="AR">AR [Account Receivable]</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.accountusage" class="mt-1 text-sm text-red-600">{{ form.errors.accountusage }}</p>
                        </div>

                        <!-- Analytical Code -->
                        <div>
                            <Label for="analyticalcode" class="text-sm font-medium">Analytical Type</Label>
                            <Select v-model="form.analyticalcode">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Analytical Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="Cash">Cash</SelectItem>
                                        <SelectItem value="Non-Cash">Non-Cash</SelectItem>
                                        <SelectItem value="Cheque">Cheque</SelectItem>
                                        <SelectItem value="Bankers Draft">Bankers Draft</SelectItem>
                                        <SelectItem value="Pay Order">Pay Order</SelectItem>
                                        <SelectItem value="Letter of Credit">Letter of Credit</SelectItem>
                                        <SelectItem value="Wire Transfer">Wire Transfer</SelectItem>
                                        <SelectItem value="Others">Others</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.analyticalcode" class="mt-1 text-sm text-red-600">{{ form.errors.analyticalcode }}</p>
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
