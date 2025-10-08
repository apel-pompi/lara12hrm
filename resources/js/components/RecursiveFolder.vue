<script setup lang="ts">
import { Folder, FolderOpen } from 'lucide-vue-next';
import { defineEmits, defineProps, ref } from 'vue';

const props = defineProps<{
    folders: any[];
    selectedFolder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:selectedFolder', value: string): void;
}>();

const openFolders = ref<Set<string>>(new Set());
const selectedFolder = ref(props.selectedFolder || '');

// toggle open folder
const toggleFolder = (folderName: string) => {
    if (openFolders.value.has(folderName)) openFolders.value.delete(folderName);
    else openFolders.value.add(folderName);
};

// select folder
const selectFolder = (folder: { name: string; children: any[] }) => {
    selectedFolder.value = folder.name;
    emit('update:selectedFolder', folder.name);
    if (folder.children?.length) toggleFolder(folder.name);
};
</script>

<template>
    <ul class="pl-4">
        <li v-for="folder in folders" :key="folder.name">
            <div
                @click.stop="selectFolder(folder)"
                class="flex cursor-pointer items-center gap-1 rounded px-1 py-0.5 hover:bg-gray-100"
                :class="{ 'bg-blue-50 font-medium text-blue-700': selectedFolder === folder.name }"
            >
                <component :is="folder.children?.length ? (openFolders.has(folder.name) ? FolderOpen : Folder) : Folder" class="h-4 w-4" />
                {{ folder.name }}
            </div>

            <!-- Recursive rendering -->
            <RecursiveFolder
                v-if="folder.children?.length && openFolders.has(folder.name)"
                :folders="folder.children"
                v-model:selectedFolder="props.selectedFolder"
                @update:selectedFolder="emit('update:selectedFolder', $event)"
            />
        </li>
    </ul>
</template>
