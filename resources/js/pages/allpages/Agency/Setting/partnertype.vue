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
import { CornerDownLeft, Plus, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import TableCell from '@/components/ui/table/TableCell.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';

export interface PartnerType {
    id: number;
    partnertypename: string;
    mastercaterory_id: string;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner Type Setup', href: '/patnersetup' }];

const props = defineProps<{
    partnertype: PartnerType[];
    mastersetup: {id:number,catname:string}[]
}>();

const data = props.partnertype;

interface FormErrors {
    partnertypename?: string;
    mastercaterory_id?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    partnertypename: '',
    mastercaterory_id: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};


const submit = () => {
    const action = isEditMode.value && form.id ? route('general.patnersetup.update', form.id) : route('general.patnersetup');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Partner Type ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('general.patnersetup'), {
                    only: ['partner_type_setups'],
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

const toggleStatus = (partnertype: PartnerType) => {
    const newStatus = !Boolean(partnertype.active); // boolean
    router.put(
        route('general.patnersetupUpdateStatus', partnertype.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                partnertype.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Partner Type  status update');
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this partner type setup?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/general/patnersetup/show/${id}`, {
        onSuccess: () => {
            toast.success('Partner Type deleted successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToMasterCategory = () => {
    router.visit('/general');
};

</script>

<template>
    <Head title="Partner Type Setup" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToMasterCategory"><CornerDownLeft></CornerDownLeft>Back Master Category </Button>
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Partner Type </Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Partner Type Name</TableHead>
                            <TableHead>Master Category</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(patnersetup, index) in data" :key="patnersetup.id ?? index">
                            <TableCell>{{ patnersetup.partnertypename }}</TableCell>
                            <TableCell>{{ patnersetup.mastercategory.catname }}</TableCell>
                            <TableCell>{{ patnersetup.user.name }}</TableCell>
                            <TableCell>
                                <Switch v-model="patnersetup.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(patnersetup)"> </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(patnersetup.id)"><Trash></Trash></Button>
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
                    <DialogTitle>{{ isEditMode ? 'Edit Partner Type' : 'Create Partner Type' }}</DialogTitle>
                    <DialogDescription> Make changes to your partner type here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="partnertypename">Master Category</Label>
                            <Select v-model="form.mastercaterory_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Master Category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="master in props.mastersetup" :key="master.id" :value="master.id">
                                            {{ master.catname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="errors?.mastercaterory_id" class="text-sm text-red-600">{{ errors.mastercaterory_id }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="partnertypename">Partner Type Name</Label>
                            <Input class="max-w-sm" placeholder="Enter Partner Type Name" id="catname" v-model="form.partnertypename" autofocus />
                            <span v-if="errors?.partnertypename" class="text-sm text-red-600">{{ errors.partnertypename }}</span>
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
