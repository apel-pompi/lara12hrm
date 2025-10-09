<script setup lang="ts">
import RecursiveFolder from '@/components/RecursiveFolder.vue';
import ImageUpload from '@/components/StudentImageUpload.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ChevronDown, CloudDownload, CloudUpload, Ellipsis, Trash } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

export interface DocumentType {
    id: number;
    docname: string;
    adddate: string;
}

const props = defineProps<{
    student: any;
    application: any;
    folderNames: string;
    appDoc: any;
    documenttype: DocumentType[];
    totalNetAmount: number;
    total_payable: number;
    total_income: number;
}>();

const openDropdown = ref<number | null>(null);

const toggleDropdown = (index: number) => {
    openDropdown.value = openDropdown.value === index ? null : index;
};

const openIndex = ref<number | null>(null);

const toggleAccordion = (index: number) => {
    openIndex.value = openIndex.value === index ? null : index;
};

const showDialog = ref(false);
const showDialogChecklist = ref(false);
const selectedCheck = ref<any>(null);
const stageID = ref<any>(null);
const docID = ref<any>(null);
const selectedFolder = ref<string | null>(null);
const selectedFile = ref<File | null>(null);

const openDialog = (stage: any, check: any) => {
    selectedCheck.value = check;
    stageID.value = stage.id;
    docID.value = check.id;
    showDialog.value = true;
};

const openDialogChecklist = (stage: any) => {
    stageID.value = stage.id;
    showDialogChecklist.value = true;
};
// file input
const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
const maxSizeKB = 300;

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;
    const file = target.files[0];
    // File type check
    if (!allowedTypes.includes(file.type)) {
        toast('Error', { description: 'Only JPG, JPEG, PNG files are allowed.' });
        target.value = '';
        selectedFile.value = null;
        return;
    }
    // File size check
    const sizeKB = file.size / 1024;
    if (sizeKB > maxSizeKB) {
        toast('Error', { description: `File size must be less than ${maxSizeKB} KB.` });
        target.value = '';
        selectedFile.value = null;
        return;
    }
    selectedFile.value = file;
};

const form = useForm({
    folder: '',
    file: null,
    stage_id: '',
    doc_id: '',
});

const sumitForm = useForm({
    doctype_id: '',
    workstage_id: '',
    workflow_id: '',
});
const submit = () => {
    sumitForm.workstage_id = stageID.value;
    sumitForm.workflow_id = props.application.workflow.id;
    sumitForm.post(
        route('studentApplication.updateCheckList', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
        {
            forceFormData: true,
            onSuccess: (page: any) => {
                const message = page?.props?.flash?.message || page?.props?.message;
                if (message?.toLowerCase().includes('exists')) {
                    toast('Warning', { description: message });
                } else {
                    toast('Success', { description: message || 'Document check list update successfully!' });
                }
                setTimeout(() => {
                    showDialogChecklist.value = false;
                    selectedFile.value = null;
                    form.reset();
                    router.visit(route('studentApplication.documentApplication', props.student.id, props.application.id), {
                        only: ['student_applications'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                toast('Error', {
                    description: Object.values(errors).join(', ') || 'Validation error — check the form fields.',
                });
            },
        },
    );
};
// File Upload
const uploadFile = () => {
    form.folder = selectedFolder.value;
    form.file = selectedFile.value;
    form.stage_id = stageID.value;
    form.doc_id = docID.value;

    form.post(
        route('studentApplication.docAppStore', {
            student: props.student.id,
            studentApplication: props.application.id,
        }),
        {
            forceFormData: true,
            onSuccess: (page: any) => {
                const message = page?.props?.flash?.message || page?.props?.message;
                if (message?.toLowerCase().includes('exists')) {
                    toast('Warning', { description: message });
                } else {
                    toast('Success', { description: message || 'Document uploaded successfully!' });
                }
                setTimeout(() => {
                    showDialog.value = false;
                    selectedFile.value = null;
                    form.reset();
                    router.visit(route('studentApplication.documentApplication', props.student.id, props.application.id), {
                        only: ['student_applications'],
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
            },
            onError: (errors) => {
                toast('Error', {
                    description: Object.values(errors).join(', ') || 'Validation error — check the form fields.',
                });
            },
        },
    );
};

const deleteFile = (documentId: number) => {
    if (!confirm('Are you sure you want to delete this document?')) return;

    form.delete(
        route('studentApplication.docAppDelete', {
            student: props.student.id,
            studentApplication: props.application.id,
            document: documentId,
        }),
        {
            onSuccess: () => {
                toast('Success', {
                    description: `Document deleted successfully`,
                });
                router.visit(
                    route('studentApplication.documentApplication', {
                        student: props.student.id,
                        studentApplication: props.application.id,
                    }),
                    {
                        only: ['student_applications'],
                        preserveScroll: true,
                        preserveState: false,
                    },
                );
            },
            onError: () => {
                toast('Error', {
                    description: 'Failed to delete the document.',
                });
            },
        },
    );
};

const downloadFile = (documentId: number) => {
    const url = route('studentApplication.docAppDownload', {
        student: props.student.id,
        studentApplication: props.application.id,
        document: documentId,
    });
    window.open(url, '_blank');
};
</script>

<template>
    <ApplicationLayout
        :student="props.student"
        :application="props.application"
        :totalNetAmount="props.totalNetAmount"
        :total_payable="props.total_payable"
        :total_income="props.total_income"
    >
        <!-- Activity timeline -->
        <div class="space-y-4">
            <div class="flex flex-col gap-4 lg:flex-row">
                <!-- Left Sidebar -->
                <div class="w-full rounded-lg bg-white p-4 shadow lg:w-1/3">
                    <h2 class="mb-2 font-semibold">Document Checklist (1/1)</h2>
                    <p class="mb-4 text-sm text-gray-400">
                        The changes & addition of the checklist will only be affected to current application only.
                    </p>

                    <div class="space-y-2">
                        <div
                            v-for="(stage, index) in props.application.workflow.stages || []"
                            :key="stage.id"
                            class="rounded-lg border bg-white shadow-sm"
                        >
                            <!-- Accordion Header -->
                            <div
                                class="flex cursor-pointer items-center justify-between px-4 py-3 transition-colors duration-200 hover:bg-gray-50"
                                @click="toggleAccordion(index)"
                            >
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ stage.stagename }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <ChevronDown
                                        class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                        :class="{ 'rotate-180': openIndex === index }"
                                    />
                                    <span
                                        class="flex h-4 w-4 items-center justify-center rounded-lg bg-blue-500 text-[10px] font-semibold text-white"
                                    >
                                        {{ stage.document_checks.length }}
                                    </span>
                                </div>
                            </div>

                            <!-- Accordion Body -->
                            <div v-if="openIndex === index" class="border-t bg-gray-50 px-4 py-3">
                                <div v-for="check in stage.document_checks || []" :key="check.id" class="flex items-center gap-2 py-1">
                                    <!-- Check Icon -->
                                    <CloudUpload @click="openDialog(stage, check)" class="h-4 w-4 flex-shrink-0 text-green-500" />
                                    <!-- Document Name -->
                                    <span class="text-xs text-gray-700">
                                        {{ check.documenttype.docname }}
                                    </span>
                                </div>

                                <!-- Add New Checklist -->
                                <span
                                    @click="openDialogChecklist(stage)"
                                    class="mt-2 inline-block cursor-pointer text-xs font-medium text-blue-500 hover:text-blue-600 hover:underline"
                                >
                                    + ADD NEW CHECKLIST
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Documents Table -->
                <div class="w-full overflow-x-auto rounded-lg bg-white p-4 shadow lg:w-2/3">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">File</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">FileName</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">RELATED STAGE</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">ADDED BY</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">ADDED ON</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(document, index) in props.appDoc" :key="index" class="text-xs hover:bg-gray-50">
                                <td class="flex items-center gap-2 px-4 py-2">
                                    <img
                                        :src="`/storage/FileFolder/${document.student_id}/${document.workflow.name}/${document.partner.name}/${document.product.name}/${document.docname}`"
                                        class="h-8 w-8 rounded border"
                                        alt="File"
                                    />
                                </td>
                                <td class="px-4 py-2">{{ document.documentid.docname }}</td>
                                <td class="px-4 py-2">{{ document.stage.stagename }}</td>
                                <td class="flex items-center gap-2 px-4 py-2">
                                    {{ document.user.name }}
                                </td>
                                <td class="px-4 py-2">{{ new Date(document.created_at).toISOString().split('T')[0] }}</td>
                                <td class="px-4 py-2 text-right">
                                    <div class="relative inline-block text-left">
                                        <!-- Dropdown Toggle Button -->
                                        <button @click="toggleDropdown(index)" class="text-gray-400 hover:text-gray-600">
                                            <Ellipsis></Ellipsis>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div v-if="openDropdown === index" class="absolute right-0 z-10 mt-2 w-32 bg-white shadow-lg">
                                            <ul class="w-40 overflow-hidden border border-gray-200 bg-white shadow-md">
                                                <li>
                                                    <button
                                                        @click="downloadFile(document.id)"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 transition-colors duration-150 hover:bg-blue-50 hover:text-blue-600"
                                                    >
                                                        <CloudDownload class="h-4 w-4" />
                                                        <span>Download</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button
                                                        @click="deleteFile(document.id)"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 transition-colors duration-150 hover:bg-red-50 hover:text-red-600"
                                                    >
                                                        <Trash class="h-4 w-4" />
                                                        <span>Delete</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-h-[80vh] w-full max-w-lg overflow-auto rounded-lg p-6 shadow-lg sm:max-w-xl md:max-w-2xl lg:max-w-3xl">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle class="text-lg font-semibold">Select Folder & Upload</DialogTitle>
                </DialogHeader>

                <!-- Body -->
                <div class="mt-4 flex flex-col gap-4">
                    <!-- Folder Tree -->
                    <div class="max-h-[300px] overflow-auto rounded border bg-gray-50 p-3">
                        <RecursiveFolder :folders="[props.folderNames]" v-model:selectedFolder="selectedFolder" />
                    </div>

                    <!-- Selected Folder -->
                    <div v-if="selectedFolder" class="text-sm font-medium text-gray-700">
                        Selected Folder: <span class="text-blue-600">{{ selectedFolder }}</span>
                    </div>
                    <p v-if="form.errors.folder" class="mt-1 text-xs text-red-500">
                        {{ form.errors.folder }}
                    </p>
                    <!-- File Upload Section -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                        <div class="flex-shrink-0">
                            <ImageUpload @image="(file) => (selectedFile = file)" :Image="currentImage" />
                        </div>
                        <div class="mt-2 text-xs text-gray-500 sm:mt-0">
                            <p>Recommended size: 256x256px</p>
                        </div>
                    </div>

                    <!-- Optional native file input -->
                    <input type="file" class="mt-2" @change="handleFileChange" />
                    <p v-if="form.errors.file" class="mt-1 text-xs text-red-500">
                        {{ form.errors.file }}
                    </p>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex flex-col items-center justify-between gap-2 sm:flex-row">
                    <div v-if="selectedCheck?.documenttype.docname" class="text-xs text-gray-500">
                        Document Type: {{ selectedCheck.documenttype.docname }}
                    </div>
                    <Button class="w-full sm:w-auto" :disabled="form.processing" @click="uploadFile">
                        <template v-if="form.processing">Saving...</template>
                        <template v-else>Update</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <Dialog v-model:open="showDialogChecklist">
            <DialogContent class="max-w-[825px]">
                <!-- Header -->
                <DialogHeader>
                    <DialogDescription> Manage your document check list here. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">Select Document Type</Label>
                        <Select v-model="sumitForm.doctype_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select Document Type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="documentType in props.documenttype" :key="documentType.id" :value="documentType.id">
                                        {{ documentType.docname }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex flex-col items-center justify-between gap-2 sm:flex-row">
                    <Button class="w-full sm:w-auto" :disabled="sumitForm.processing" @click="submit">
                        <template v-if="sumitForm.processing">Saving...</template>
                        <template v-else>Update</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </ApplicationLayout>
</template>
