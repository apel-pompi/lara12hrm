<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/agencyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Check, CornerDownLeft, Plus, SquarePen, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Documetn Check List', href: '/documentlist' }];

export interface Stage {
    id: number;
    workflow_id: number;
    stagename: string;
}
export interface Workflow {
    id: number;
    name: string;
    stages: Stage[];
}
export interface DocumentType {
    id: number;
    docname: string;
    adddate: string;
}

export interface DocumentCheck {
    id: number;
    workflow_id: number;
    doctype_id: number;
    workstage_id: number;
    active: number;
    user_id: number;
    documenttype: DocumentType;
}

const props = defineProps<{
    documentcheck: DocumentCheck[];
    workflow: {
        id: number;
        name: string;
        stages: {
            id: number;
            stagename: string;
            document_checks: {
                id: number;
                documenttype: {
                    docname: string;
                };
            }[];
        }[];
    };
    documenttype: DocumentType[];
}>();

const data = props.workflow;

interface FormErrors {
    name?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    workflow_id: '',
    doctype_id: '',
    workstage_id: '',
    user_id: '',
    active: '0',
});

const showDailogCreate = (stageId: number) => {
    form.reset();
    form.workflow_id = data.id;
    form.workstage_id = stageId;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/documentlist/${id}/adddoctypeEdit`);

        if (res.status === 403) {
            const response = await res.json();
            toast.error(response.message);
            return;
        }
        const response = await res.json();

        Object.assign(form, response.data);

        form.id = response.data.id;
        form.doctype_id = response.data.id;

        // first relation row
        if (response.data.docusage.length > 0) {
            form.workflow_id = response.data.docusage[0].workflow_id;
            form.workstage_id = response.data.docusage[0].workstage_id;
        }

        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error(error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('documentlist.update', form.id) : route('documentlist.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Document List ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('documentlist.index', data.id), {
                    only: ['w_document_checks'],
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

const onDelete = async (id) => {
    if (!confirm('Are you sure you want to delete this document checklist?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/documentlist/${id}/adddoctypeDelete`, {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                toast.success(page.props.flash.success);
            }

            if (page.props.flash.error) {
                toast.error(page.props.flash.error);
            }
        },

        onError: () => {
            toast.error('Something went wrong!');
        },

        preserveScroll: true,
        preserveState: false,
    });
};

const goToWorkflow = () => {
    router.visit('/workflow');
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Workflows" />
        <AgencyLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Top Bar -->
                <div class="mb-6 flex flex-wrap items-center gap-3">
                    <Button class="shadow-sm dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm" @click="goToWorkflow">
                        <CornerDownLeft class="mr-2 h-4 w-4" />
                        Back Workflows
                    </Button>
                </div>

                <!-- Main Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                    <!-- Header -->
                    <div class="mb-6 flex flex-col gap-2 border-b pb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Edit Document Checklist</h2>
                            <p class="text-sm text-gray-500">Manage required documents for each workflow stage.</p>
                        </div>

                        <div class="rounded-xl bg-indigo-50 px-4 py-3 text-right">
                            <p class="text-xs font-medium text-gray-500">Selected Workflow</p>
                            <h3 class="text-lg font-bold text-indigo-600">
                                {{ data.name }}
                            </h3>
                        </div>
                    </div>

                    <!-- Stages -->
                    <div class="space-y-6">
                        <div
                            v-for="(stage, index) in data.stages"
                            :key="stage.id ?? index"
                            class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm"
                        >
                            <!-- Stage Header -->
                            <div class="mb-4 flex items-center justify-between border-b pb-3">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ stage.stagename }}
                                </h3>

                                <Label
                                    @click="showDailogCreate(stage.id)"
                                    class="flex cursor-pointer items-center gap-1 rounded-md bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700 transition hover:bg-indigo-200"
                                >
                                    <Plus class="h-4 w-4" />
                                    Add Checklist
                                </Label>
                            </div>

                            <!-- Checklist Items -->
                            <div class="space-y-3">
                                <div
                                    v-for="doc in stage.document_checks"
                                    :key="doc.id"
                                    class="group flex items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:shadow-md"
                                >
                                    <!-- Left -->
                                    <div class="flex items-center gap-3">
                                        <div class="rounded-full bg-green-100 p-2">
                                            <Check class="h-4 w-4 text-green-600" />
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ doc.documenttype.docname }}
                                            </p>
                                            <p class="text-xs text-gray-500">Required for this stage</p>
                                        </div>
                                    </div>

                                    <!-- Right -->
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600"> All Partners </span>

                                        <Button size="icon" variant="ghost" @click="onEdit(doc.documenttype.id)" class="hover:bg-indigo-50">
                                            <SquarePen class="h-4 w-4 text-indigo-600" />
                                        </Button>

                                        <Button size="icon" variant="ghost" @click="onDelete(doc.id)" class="hover:bg-red-50">
                                            <Trash class="h-4 w-4 text-red-500" />
                                        </Button>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div
                                    v-if="stage.document_checks.length === 0"
                                    class="rounded-xl border border-dashed border-gray-300 bg-white px-4 py-6 text-center text-sm text-gray-400"
                                >
                                    No checklist added yet.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-206.25">
                    <!-- Header -->
                    <DialogHeader>
                        <DialogTitle>
                            {{ isEditMode ? 'Edit Document List' : 'New Document Check' }}
                        </DialogTitle>
                        <DialogDescription> Manage your document check list here. Click save when you're done. </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="grid gap-6">
                        <!-- Select Document Type -->
                        <div class="grid gap-2">
                            <Label for="doctype">Select Document Type</Label>
                            <Select v-model="form.doctype_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Document Type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="document in props.documenttype" :key="document.id" :value="document.id">
                                            {{ document.docname }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="mt-6 flex items-center justify-between">
                        <!-- Close Left -->
                        <DialogClose as-child>
                            <Button type="button" variant="secondary">Close</Button>
                        </DialogClose>

                        <!-- Submit Right -->
                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Create' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AgencyLayout>
    </AppLayout>
</template>
