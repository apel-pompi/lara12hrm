<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
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
console.log(data)

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
        const res = await fetch(`/workflow/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching department details.');
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
                router.visit(route('documentlist.index',data.id), {
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


const goToWorkflow = () => {
    router.visit('/workflow');
};
</script>

<template>
    <Head title="Workflows" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="goToWorkflow"><CornerDownLeft></CornerDownLeft>Back Workflows </Button>
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Document Check List </Button>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <!-- Title -->
                <h2 class="mb-4 text-xl font-semibold text-gray-800">Edit Document Checklist</h2>

                <!-- Selected Workflow -->
                <div class="mb-6">
                    <p class="text-sm text-gray-500">Selected Workflow</p>
                    <h3 class="text-lg font-bold text-indigo-600">{{ data.name }}</h3>
                </div>

                <hr class="my-4" />

                <!-- Checklist Info -->
                <div class="mb-6">
                    <p class="font-medium text-gray-700">Document Checklist</p>
                    <p class="text-sm text-gray-500">Create your document checklist based on your selected workflow stages</p>
                </div>

                <div class="space-y-6">
                    <!-- Stage with checklist items -->

                    <div v-for="(stage, index) in data.stages" :key="stage.id ?? index" class="mb-6">
                        <p class="mb-2 font-semibold text-gray-900">{{ stage.stagename }}</p>

                        <div class="space-y-2">
                            <!-- Checklist Item -->
                            <div v-for="doc in stage.document_checks" :key="doc.id ?? index" class="flex items-center justify-between rounded-md border p-2">
                                <div class="flex items-center gap-2">
                                    <Check class="h-4 w-4 text-gray-500" />
                                    <span>{{ doc.documenttype.docname }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span class="text-sm">All Partners</span>
                                    <Trash class="h-4 w-4 cursor-pointer hover:text-red-600" />
                                    <SquarePen class="h-4 w-4 cursor-pointer hover:text-indigo-600" />
                                </div>
                            </div>

                            

                            <!-- Add new checklist -->
                            <Label
                                @click="showDailogCreate(stage.id)"
                                class="font-sm flex cursor-pointer items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800"
                                ><Plus></Plus> Add New Checklist</Label
                            >
                        </div>
                    </div>
                </div>
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
                    <DialogTitle>{{ isEditMode ? 'Edit Document List' : 'New Document Check' }}</DialogTitle>
                    <DialogDescription> Make changes to your document check list here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="name">Select Document List</Label>
                            <Select v-model="form.doctype_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Document Types" />
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

                        <div class="grid gap-2">
                            <Button :disabled="form.processing" @click="submit">
                                <template v-if="form.processing">Saving...</template>
                                <template v-else>{{ isEditMode ? 'Update' : 'Create' }}</template>
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
