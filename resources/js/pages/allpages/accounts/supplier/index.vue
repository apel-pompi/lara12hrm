<script setup lang="ts">
import FormGroup from '@/components/FormGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Eye, Plus, RefreshCcw, Search, SquarePen, Trash } from 'lucide-vue-next';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

import Switch from '@/components/ui/switch/Switch.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { toast } from 'vue-sonner';

export interface Suppliers {
    id: number;
    subcode: string;
    name: string;
    subaddress: string;
    subcountry: string;
    substate: string;
    subcity: string;
    subzipcode: number;
    contact_person: string;
    subphone: string;
    subemail: string;
    user: { id: number; name: string };
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'All Supplier', href: '/suppliers' }];

const props = defineProps<{
    supplier: Paginated<Suppliers>;
}>();

const data = props.supplier;

interface FormErrors {
    subcode?: string;
    name?: string;
    subaddress?: string;
    subcountry?: string;
    substate?: string;
    subcity?: string;
    subzipcode?: number;
    contact_person?: string;
    subphone?: string;
    subemail?: string;
    user: { id: number; name: string };
}

const showDialog = ref(false);
const isEditMode = ref(false);
const showDialogOpen = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    subcode: '',
    name: '',
    subaddress: '',
    subcountry: '',
    substate: '',
    subcity: '',
    subzipcode: '',
    contact_person: '',
    subphone: '',
    subemail: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};

const onShow = async (id: number) => {
    try {
        const res = await fetch(`/suppliers/${id}`);
        if (!res.ok) {
            toast.error('Server error while fetching supplier details.');
            return;
        }
        const data = await res.json();
        Object.assign(form, data);
        form.id = data.id;
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
        const res = await fetch(`/suppliers/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching suppliers details.');
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
    const action = isEditMode.value && form.id ? route('suppliers.update', form.id) : route('suppliers.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Suppliers ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('suppliers.index'), {
                    only: ['suppliers'],
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
const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this supplier?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/suppliers/show/${id}`, {
        onSuccess: () => {
            toast.success('Supplier deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

// Switch toggle handler
const toggleStatus = (supplier: Suppliers) => {
    const newStatus = !Boolean(supplier.active); // boolean
    router.put(
        route('suppliers.updateStatus', supplier.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                supplier.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Supplier status update');
            },
        },
    );
};

// const searchForm = ref({
//     branch_id: props.filters.branch_id || '',
//     yearname: props.filters.yearname || '',
//     monthname: props.filters.monthname || '',
// });

// const search = () => {
//     const params: Record<string, any> = {};
//     if (searchForm.value.branch_id) params.branch_id = searchForm.value.branch_id;
//     if (searchForm.value.yearname) params.yearname = searchForm.value.yearname;
//     if (searchForm.value.monthname) params.monthname = searchForm.value.monthname;

//     router.get(route('suppliers.index'), params, {
//         preserveState: false,
//         preserveScroll: true,
//     });
// };

const refresh = () => {
    router.get(route('suppliers.index'), {}, { replace: true });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true });
    }
};
</script>

<template>
    <Head title="Supplier" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Supplier </Button>
                <!-- Search start -->
                <div class="grid gap-2"></div>
                <div class="grid gap-2"></div>
                <div class="grid gap-2"></div>
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                </div>
                <div class="grid gap-2">
                    <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                </div>
                <!-- Search start -->
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Supplier Code</TableHead>
                            <TableHead>Supplier Name</TableHead>
                            <TableHead>Address</TableHead>
                            <TableHead>Contact Person</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(supplier, index) in data.data" :key="supplier.id ?? index">
                            <TableCell>{{ supplier.subcode }}</TableCell>
                            <TableCell>{{ supplier.name }}</TableCell>
                            <TableCell>{{ supplier.subaddress }}</TableCell>
                            <TableCell>{{ supplier.contact_person }}</TableCell>
                            <TableCell>{{ supplier.subphone }}</TableCell>
                            <TableCell>{{ supplier.subemail }}</TableCell>
                            <TableCell>
                                <Switch v-model="supplier.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(supplier)"> </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onShow(supplier.id)"><Eye></Eye></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(supplier.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(supplier.id)"><Trash></Trash></Button>
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
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Supplier' : 'Create Supplier' }}</DialogTitle>
                    <DialogDescription> Make changes to your supplier here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid grid-cols-1 gap-6 py-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="name" class="font-medium">Supplier Name<span class="text-red-500">*</span></Label>
                        <Input id="name" v-model="form.name" class="w-full" placeholder="Enter Supplier Name" autofocus />
                        <p v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subaddress" class="font-medium">Supplier Address<span class="text-red-500">*</span></Label>
                        <Textarea id="subaddress" v-model="form.subaddress" class="w-full" placeholder="Enter Supplier Address" autofocus />
                        <p v-if="form.errors.subaddress" class="text-sm text-red-600">
                            {{ form.errors.subaddress }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subcountry" class="font-medium">Country Name</Label>
                        <Input id="subcountry" v-model="form.subcountry" class="w-full" placeholder="Supplier Country Name" autofocus />
                        <p v-if="form.errors.subcountry" class="text-sm text-red-600">
                            {{ form.errors.subcountry }}
                        </p>
                        <input type="hidden" value="0" v-model="form.active" class="form-radio text-primary-600" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="substate" class="font-medium">State Name</Label>
                        <Input id="substate" v-model="form.substate" class="w-full" placeholder="State Name" autofocus />
                        <p v-if="form.errors.substate" class="text-sm text-red-600">
                            {{ form.errors.substate }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subcity" class="font-medium">City Name</Label>
                        <Input id="subcity" v-model="form.subcity" class="w-full" placeholder="City Name" autofocus />
                        <p v-if="form.errors.subcity" class="text-sm text-red-600">
                            {{ form.errors.subcity }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subzipcode" class="font-medium">Zip Code</Label>
                        <Input id="subzipcode" v-model="form.subzipcode" class="w-full" placeholder="Enter Zip Code" autofocus />
                        <p v-if="form.errors.subzipcode" class="text-sm text-red-600">
                            {{ form.errors.subzipcode }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="contact_person" class="font-medium">Contact Person<span class="text-red-500">*</span></Label>
                        <Input id="contact_person" v-model="form.contact_person" class="w-full" placeholder="Enter Contact Person" autofocus />
                        <p v-if="form.errors.contact_person" class="text-sm text-red-600">
                            {{ form.errors.contact_person }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subphone" class="font-medium">Phone<span class="text-red-500">*</span></Label>
                        <Input id="subphone" v-model="form.subphone" class="w-full" placeholder="Enter Supplier Phone" autofocus />
                        <p v-if="form.errors.subphone" class="text-sm text-red-600">
                            {{ form.errors.subphone }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="subemail" class="font-medium">Email</Label>
                        <Input id="subemail" v-model="form.subemail" class="w-full" placeholder="Enter Supplier Email" autofocus />
                        <p v-if="form.errors.subemail" class="text-sm text-red-600">
                            {{ form.errors.subemail }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Show Dialog -->
        <Dialog v-model:open="showDialogOpen">
            <DialogContent class="max-w-4xl rounded-2xl p-6 shadow-xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-semibold">Show Supplier</DialogTitle>
                    <DialogDescription class="text-muted-foreground text-sm"> View the details of this Supplier. </DialogDescription>
                </DialogHeader>
                <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Supplier Code -->
                        <FormGroup label="Supplier Code" htmlFor="subcode">
                            <div v-if="!isEditMode" class="bg-muted rounded-md border px-3 py-2 text-sm">
                                {{ form.subcode }}
                            </div>
                        </FormGroup>
                        <!-- Supplier Name -->
                        <FormGroup label="Supplier Name" htmlFor="name">
                            <div v-if="!isEditMode" class="bg-muted rounded-md border px-3 py-2 text-sm">
                                {{ form.name }}
                            </div>
                        </FormGroup>
                        <!-- subaddress-->
                        <FormGroup label="Address" htmlFor="subaddress">
                            <Input id="subaddress" v-model="form.subaddress" :disabled="!isEditMode" />
                        </FormGroup>
                        <!-- subcountry-->
                        <FormGroup label="Country" htmlFor="subcountry">
                            <Input id="subaddress" v-model="form.subcountry" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="State" htmlFor="substate">
                            <Input id="substate" v-model="form.substate" :disabled="!isEditMode" />
                        </FormGroup>
                    </div>
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <FormGroup label="City" htmlFor="subcity">
                            <Input id="subcity" v-model="form.subcity" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Zip Code" htmlFor="subzipcode">
                            <Input id="subzipcode" v-model="form.subzipcode" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Contact Person" htmlFor="contact_person">
                            <Input id="contact_person" v-model="form.contact_person" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Phone" htmlFor="subphone">
                            <Input id="subphone" v-model="form.subphone" :disabled="!isEditMode" />
                        </FormGroup>
                        <FormGroup label="Email" htmlFor="subemail">
                            <Input id="subemail" v-model="form.subemail" :disabled="!isEditMode" />
                        </FormGroup>
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
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button variant="secondary">Cancel</Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
