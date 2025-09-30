<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import ApplicationLayout from '@/pages/allpages/Agency/Student/applicationLayout.vue';
import { ChevronDown, ChevronRight, CloudUpload, Ellipsis, Folder } from 'lucide-vue-next';

import { ref } from 'vue';
import { toast } from 'vue-sonner';

import axios from 'axios';

const props = defineProps<{
    student: any;
    application: any;
}>();

const openDropdown = ref<number | null>(null);

const toggleDropdown = (index: number) => {
    openDropdown.value = openDropdown.value === index ? null : index;
};

const openIndex = ref<number | null>(null);

const toggleAccordion = (index: number) => {
    openIndex.value = openIndex.value === index ? null : index;
};

interface Folder {
    id: string;
    name: string;
    children?: Folder[];
    isOpen?: boolean;
    loaded?: boolean;
}

const showDialog = ref(false);
const folders = ref<Folder[]>([]);
const selectedFolder = ref<string | null>(null);
const file = ref<File | null>(null);

const selectedCheck = ref<any>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files || target.files.length === 0) return;
    file.value = target.files[0];
};
const openDialog = async (check: any) => {
    showDialog.value = true;
    selectedCheck.value = check;
    const res = await axios.get('/gdrive/folders'); // top-level folder, parent_id=null
    folders.value = res.data.map((f: any) => ({
        ...f,
        isOpen: false,
        loaded: false,
        children: [],
    }));
};

const toggleFolder = async (folder: Folder) => {
    folder.isOpen = !folder.isOpen;

    if (folder.isOpen && !folder.loaded) {
        const res = await axios.get('/gdrive/folders', { params: { parent_id: folder.id } });
        folder.children = res.data.map((f: any) => ({
            ...f,
            isOpen: false,
            loaded: false,
            children: [],
        }));
        folder.loaded = true;
    }
};

// Folder select
const selectFolder = (id: string) => {
    selectedFolder.value = id;
};

// File Upload
const uploadFile = async () => {
    if (!file.value || !selectedFolder.value) return;

    const formData = new FormData();
    formData.append('student_id', props.student.id);
    formData.append('applcation_id', props.application.id);
    formData.append('check_id', selectedCheck.value.id);
    formData.append('docname', selectedCheck.value.documenttype.docname);
    formData.append('photo', file.value);
    formData.append('folder_id', selectedFolder.value);
    try {
        await axios.post('/gdrive/upload', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        toast('Success', {
            description: `Product fees create successfully`,
        });
        showDialog.value = false;
        file.value = null;
        selectedCheck.value = null;
    } catch (err) {
        toast('Success', {
            description: `Server error` + err,
        });
    }
};
console.log(props.application.id)
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
            <DialogContent class="max-h-[80vh] w-full overflow-auto sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Select Folder & Upload</DialogTitle>
                </DialogHeader>

                <div class="mt-4">
                    <ul class="space-y-1">
                        <template v-for="folder in folders" :key="folder.id">
                            <li>
                                <div
                                    class="flex cursor-pointer items-center justify-between rounded px-2 py-1 hover:bg-gray-100"
                                    @click="
                                        toggleFolder(folder);
                                        selectFolder(folder.id);
                                    "
                                >
                                    <div class="flex items-center gap-1">
                                        <Folder class="h-4 w-4 text-gray-500" />
                                        <span>{{ folder.name }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span v-if="selectedFolder === folder.id" class="text-xs font-medium text-blue-600">Selected</span>
                                        <span v-if="!folder.loaded || (folder.children && folder.children.length > 0)">
                                            <component :is="folder.isOpen ? ChevronDown : ChevronRight" class="h-3 w-3 text-gray-400" />
                                        </span>
                                    </div>
                                </div>

                                <!-- Lazy Loaded Children -->
                                <ul v-if="folder.isOpen" class="ml-4 space-y-1">
                                    <template v-for="child in folder.children" :key="child.id">
                                        <li>
                                            <div
                                                class="flex cursor-pointer items-center justify-between rounded px-2 py-1 hover:bg-gray-100"
                                                @click.stop="
                                                    toggleFolder(child);
                                                    selectFolder(child.id);
                                                "
                                            >
                                                <div class="flex items-center gap-1">
                                                    <Folder class="h-4 w-4 text-gray-400" />
                                                    <span>{{ child.name }}</span>
                                                </div>
                                                <span v-if="selectedFolder === child.id" class="text-xs font-medium text-blue-600">Selected</span>
                                                <span v-if="!child.loaded || (child.children && child.children.length > 0)">
                                                    <component :is="child.isOpen ? ChevronDown : ChevronRight" class="h-3 w-3 text-gray-400" />
                                                </span>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="mt-4 flex flex-col gap-2">
                    <input type="file" @change="handleFileChange" />
                    <DialogFooter>
                        <Button class="mt-2 w-full" @click="uploadFile" :disabled="!file || !selectedFolder"> Upload </Button>
                    </DialogFooter>
                    <div v-if="file" class="mt-2 text-sm text-gray-700">Selected: {{ file.name }}</div>
                    <div class="mt-1 text-xs text-gray-500">Doc: {{ selectedCheck?.documenttype.docname }}</div>
                </div>
            </DialogContent>
        </Dialog>
    </ApplicationLayout>
</template>
