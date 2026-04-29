<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CircleAlert,
    CircleCheck,
    Download,
    FileSpreadsheet,
    ListChecks,
    Loader2,
    Table,
    Trash2,
    Upload,
    UploadCloud,
    X,
} from 'lucide-vue-next';
import { ref } from 'vue';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Import', href: '/imports' }];

const form = useForm({
    excel_file: null,
});

const selectedFile = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        form.excel_file = file;
    }
};

const removeFile = () => {
    selectedFile.value = null;
    form.excel_file = null;
    // Reset file input
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = '';
    }
};

const submit = () => {
    if (selectedFile.value) {
        form.post(route('imports.import'), {
            forceFormData: true,
            onSuccess: () => {
                selectedFile.value = null;
                form.excel_file = null;
            },
        });
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const downloadTemplate = () => {
    window.location.href = route('imports.downloadTemplate');
};
</script>

<template>
    <Head title="Student Upload" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-9002 relative flex-1 border bg-gray-50 bg-[radial-gradient(circle_at_top_left,_rgba(129,140,248,0.20),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(45,212,191,0.18),_transparent_30%),linear-gradient(135deg,_rgba(248,250,252,0.96),_rgba(238,242,255,0.95)_45%,_rgba(250,245,255,0.94))] p-4 py-6 dark:border-gray-800/80 dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(20,184,166,0.14),_transparent_30%),linear-gradient(135deg,_rgba(15,23,42,0.96),_rgba(30,41,59,0.96)_45%,_rgba(49,46,129,0.82))]"
        >
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-4 text-center">
                    <div class="inline-flex items-center gap-3 rounded-full bg-white/80 px-6 py-3 shadow-sm backdrop-blur-sm dark:bg-gray-800/80">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                            <FileSpreadsheet class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Excel Data Import</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload your Excel file to import student data</p>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
                    <!-- Alert Messages -->
                    <div v-if="$page.props.flash.success" class="border-l-4 border-green-500 bg-green-50 p-4 dark:bg-green-900/20">
                        <div class="flex items-start">
                            <CircleCheck class="mt-0.5 mr-3 h-5 w-5 flex-shrink-0 text-green-600" />
                            <div class="flex-1">
                                <p class="font-medium text-green-800 dark:text-green-200">Success!</p>
                                <p class="mt-1 text-sm text-green-700 dark:text-green-300">{{ $page.props.flash.success }}</p>
                            </div>
                            <button @click="$page.props.flash.success = null" class="text-green-600 hover:text-green-800">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <div v-if="$page.props.flash.error" class="border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900/20">
                        <div class="flex items-start">
                            <CircleAlert class="mt-0.5 mr-3 h-5 w-5 flex-shrink-0 text-red-600" />
                            <div class="flex-1">
                                <p class="font-medium text-red-800 dark:text-red-200">Error!</p>
                                <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $page.props.flash.error }}</p>
                            </div>
                            <button @click="$page.props.flash.error = null" class="text-red-600 hover:text-red-800">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="p-4 sm:p-6">
                        <!-- Upload Card -->
                        <div class="group relative">
                            <div
                                class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 transition-opacity duration-300 group-hover:opacity-5"
                            ></div>

                            <div
                                class="relative rounded-xl border-2 border-dashed border-gray-300 p-2 text-center transition-all duration-300 hover:border-blue-400 dark:border-gray-600 hover:dark:border-blue-400"
                            >
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                    <UploadCloud class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                </div>

                                <div class="mb-4">
                                    <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">Upload Excel File</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Drag and drop your file here or click to browse</p>
                                </div>

                                <!-- File Input -->
                                <div class="mb-4">
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        @change="handleFileChange"
                                        accept=".xlsx,.xls,.csv"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    />
                                    <button
                                        type="button"
                                        @click="$refs.fileInput.click()"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                                    >
                                        <Upload class="h-4 w-4" />
                                        Browse Files
                                    </button>
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400">Supported formats: .xlsx • Max size: 1MB</p>
                            </div>
                        </div>

                        <!-- Selected File Info -->
                        <div v-if="selectedFile" class="animate-fade-in mt-4">
                            <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                                            <FileSpreadsheet class="h-5 w-5 text-green-600 dark:text-green-400" />
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ selectedFile.name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ formatFileSize(selectedFile.size) }}
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeFile"
                                        class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    @click="downloadTemplate"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                                >
                                    <Download class="h-4 w-4" />
                                    Download Template
                                </button>
                            </div>

                            <div class="flex gap-3">
                                <Link
                                    :href="route('dashboard')"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                                >
                                    <ArrowLeft class="h-4 w-4" />
                                    Back to Dashboard
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="form.processing || !selectedFile"
                                    @click="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white transition-all hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-blue-600"
                                >
                                    <template v-if="form.processing">
                                        <Loader2 class="h-4 w-4 animate-spin" />
                                        Importing...
                                    </template>
                                    <template v-else>
                                        <Upload class="h-4 w-4" />
                                        Import File
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Section -->
                <div class="mt-4 grid gap-6 md:grid-cols-2">
                    <!-- Requirements Card -->
                    <div class="rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                <ListChecks class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Requirements</h3>
                        </div>
                        <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-start gap-2">
                                <CircleCheck class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" />
                                <span>File must be in .xlsx format</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <CircleCheck class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" />
                                <span>Maximum file size: 1MB</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <CircleCheck class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" />
                                <span>First row must contain column headers</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <CircleCheck class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" />
                                <span>Phone numbers must be unique</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Columns Card -->
                    <div class="rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                                <Table class="h-4 w-4 text-purple-600 dark:text-purple-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Required Columns</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">first_name</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">last_name</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">gender</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">phone</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">destination_country</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">source</span>
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-700">
                                <span class="font-medium text-gray-700 dark:text-gray-300">counsilor_name</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
