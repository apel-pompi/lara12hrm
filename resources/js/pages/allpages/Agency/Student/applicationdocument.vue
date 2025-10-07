<script setup lang="ts">
import RecursiveFolder from '@/components/RecursiveFolder.vue';
import ImageUpload from '@/components/StudentImageUpload.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';
import axios from 'axios';
import { ChevronDown, CloudUpload, Ellipsis } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    student: any;
    application: any;
    folderNames: string;
}>();

console.log(props.folderNames);

const openDropdown = ref<number | null>(null);

const toggleDropdown = (index: number) => {
    openDropdown.value = openDropdown.value === index ? null : index;
};

const openIndex = ref<number | null>(null);

const toggleAccordion = (index: number) => {
    openIndex.value = openIndex.value === index ? null : index;
};

const showDialog = ref(false);
const selectedCheck = ref<any>(null);
const selectedFolder = ref<string | null>(null);
const selectedFile = ref<File | null>(null);
const fileToUpload = ref<File | null>(null);

const openDialog = (check: any) => {
    selectedCheck.value = check;
    showDialog.value = true;
};

// file input
const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        fileToUpload.value = target.files[0];
    }
};

// File Upload
const uploadFile = async () => {
    if (!selectedFolder.value) {
        alert('Please select a folder!');
        return;
    }
    if (!selectedFile.value) {
        alert('Please select a file!');
        return;
    }

    const formData = new FormData();
    formData.append('folder', selectedFolder.value);
    formData.append('file', selectedFile.value);

    try {
        await axios.post('/api/upload-file', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        toast('Success', {
            description: `Product fees create successfully`,
        });
        showDialog.value = false;
        selectedFile.value = null
        selectedCheck.value = null;
    } catch (err) {
        toast('Success', {
            description: `Server error` + err,
        });
    }
};
</script>

<template>
    <ApplicationLayout :student="props.student" :application="props.application">
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
                                    <CloudUpload @click="openDialog(check)" class="h-4 w-4 flex-shrink-0 text-green-500" />
                                    <!-- Document Name -->
                                    <span class="text-xs text-gray-700">
                                        {{ check.documenttype.docname }}
                                    </span>
                                </div>

                                <!-- Add New Checklist -->
                                <span class="mt-2 inline-block cursor-pointer text-xs font-medium text-blue-500 hover:text-blue-600 hover:underline">
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
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">FILENAME</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">RELATED STAGE</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">ADDED BY</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">ADDED ON</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="text-xs hover:bg-gray-50">
                                <td class="flex items-center gap-2 px-4 py-2">
                                    <img src="001.jpg" class="h-8 w-8 rounded border" alt="File" />
                                </td>
                                <td class="px-4 py-2">Document Collections</td>
                                <td class="flex items-center gap-2 px-4 py-2">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs text-white">MR</div>
                                    M Khalil Rahman
                                </td>
                                <td class="px-4 py-2">2025-09-29</td>
                                <td class="px-4 py-2 text-right">
                                    <div class="relative inline-block text-left">
                                        <!-- Dropdown Toggle Button -->
                                        <button @click="toggleDropdown(index)" class="text-gray-400 hover:text-gray-600">
                                            <Ellipsis></Ellipsis>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div v-if="openDropdown === index" class="absolute right-0 z-10 mt-2 w-32 rounded border bg-white shadow-lg">
                                            <ul>
                                                <li>
                                                    <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100">Download</button>
                                                </li>
                                                <li>
                                                    <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100">Edit</button>
                                                </li>
                                                <li>
                                                    <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100">Delete</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- অন্য রো একইভাবে -->
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

                    <!-- File Upload Section -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                        <div class="flex-shrink-0">
                            <ImageUpload @image="(file) => (form.photo = file)" :Image="currentImage" />
                        </div>
                        <div class="mt-2 text-xs text-gray-500 sm:mt-0">
                            <p>Recommended size: 256x256px</p>
                        </div>
                    </div>

                    <!-- Optional native file input -->
                    <input type="file" class="mt-2" @change="handleFileChange" />
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex flex-col items-center justify-between gap-2 sm:flex-row">
                    <div v-if="selectedCheck?.documenttype.docname" class="text-xs text-gray-500">
                        Document Type: {{ selectedCheck.documenttype.docname }}
                    </div>

                    <Button class="w-full sm:w-auto" @click="uploadFile"> Upload </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </ApplicationLayout>
</template>
