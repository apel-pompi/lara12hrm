<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { HousePlus, PackageSearch, Plus, SquarePen, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import TableCell from '@/components/ui/table/TableCell.vue';
import Switch from '@/components/ui/switch/Switch.vue';

export interface Master {
    id: number;
    catname: string;
    catadddate: string;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Master Setup', href: '/general' }];

const props = defineProps<{
    mastercategory: Master[];
}>();

const data = props.mastercategory;

interface FormErrors {
    catname?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    catname: '',
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
        const res = await fetch(`/general/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching master category details.');
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
    const action = isEditMode.value && form.id ? route('general.update', form.id) : route('general.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Master Category ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('general.index'), {
                    only: ['master_categories'],
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

const toggleStatus = (mastercategory: Master) => {
    const newStatus = !Boolean(mastercategory.active); // boolean
    router.put(
        route('general.updateStatus', mastercategory.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                mastercategory.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Master Category  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this master Category?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/general/show/${id}`, {
        onSuccess: () => {
            toast.success('Master Category deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToPartnerType = () => {
    router.visit('/general/patnersetup');
};
const goToProductType = () => {
    router.visit('/general/productsetup');
};
</script>

<template>
    <Head title="Master Setup" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Master Category </Button>
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" @click="goToPartnerType"><HousePlus></HousePlus> Partner Type </Button>
                    <Button variant="outline" size="sm" @click="goToProductType"><PackageSearch></PackageSearch> Product Type </Button>
                </div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Catergory Name</TableHead>
                            <TableHead>Added Date</TableHead>
                            <TableHead>Total Usage</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(master, index) in data" :key="master.id ?? index">
                            <TableCell>{{ master.catname }}</TableCell>
                            <TableCell>{{ master.catadddate }}</TableCell>
                            <TableCell></TableCell>
                            <TableCell>
                                <Switch v-model="master.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(master)"> </Switch>
                            </TableCell>
                            <TableCell>{{ master.user.name }}</TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(master.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(master.id)"><Trash></Trash></Button>
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
            <DialogContent class="max-w-[825px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditMode ? 'Edit Master Category' : 'Create Master Category' }}</DialogTitle>
                    <DialogDescription> Make changes to your master category here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="catname">Master Category Name</Label>
                            <Input class="max-w-sm" placeholder="Enter Master Category Name" id="catname" v-model="form.catname" autofocus />
                            <span v-if="errors?.catname" class="text-sm text-red-600">{{ errors.catname }}</span>
                        </div>

                        <div class="grid gap-2">
                            <Button :disabled="form.processing" @click="submit">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Submit' }}</template>
                            </Button>
                        </div>
                    </div>
                </div>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary"> Close </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
