<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { CornerDownLeft, Plus, SquarePen } from 'lucide-vue-next';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { toast } from 'vue-sonner';

export interface DocumentType {
    id: number;
    docname: string;
    adddate: string;
    totaluse: number;
    user_id: number;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Documetn Type', href: '/documenttype' }];

const props = defineProps<{
    documenttype: DocumentType[];
}>();

const data = props.documenttype;


interface FormErrors {
    docname?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    docname: '',
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

    const action = isEditMode.value && form.id ? route('documenttype.update', form.id) : route('documenttype.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Worksflows Document Type ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('documenttype.index'), {
                    only: ['w_document_types'],
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

const toggleStatus = (documenttype: DocumentType) => {
    const newStatus = !Boolean(documenttype.active); // boolean
    router.put(
        route('documenttype.updateStatus', documenttype.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                documenttype.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Document Type  status update');
            },
        },
    );
};

const goToWorkflow = () => {
    router.visit('/workflow');
};
</script>

<template>
    <Head title="Document Type" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToWorkflow"><CornerDownLeft></CornerDownLeft>Back Workflows </Button>
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Document Type </Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Document Type</TableHead>
                            <TableHead>Added Date</TableHead>
                            <TableHead>Total Usage</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(documenttype, index) in data" :key="documenttype.id ?? index">
                            <TableCell>{{ documenttype.docname }}</TableCell>
                            <TableCell>{{ documenttype.adddate }}</TableCell>
                            <TableCell>{{ documenttype.totaluse }}</TableCell>
                            <TableCell>
                                <Switch v-model="documenttype.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(documenttype)"> </Switch>
                            </TableCell>
                            <TableCell>{{ documenttype.user.name }}</TableCell>
                            
                            <TableCell class="text-right">
                                
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(documenttype.id)"><SquarePen></SquarePen></Button>
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
                    <DialogTitle>{{ isEditMode ? 'Edit Document Type' : 'Create Document Type' }}</DialogTitle>
                    <DialogDescription> Make changes to your workflows Document Type here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="docname">Workflow Document Type</Label>
                            <Input class="max-w-sm" placeholder="Enter Workflow Document Type" id="docname" v-model="form.docname" autofocus />
                            <span v-if="errors?.docname" class="text-sm text-red-600">{{ errors.docname }}</span>
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
