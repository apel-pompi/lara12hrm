<script setup lang="ts">

import { Folder, FolderOpen } from 'lucide-vue-next'
import { ref, defineEmits, defineProps } from 'vue'

defineProps<{ folders: { name: string; children: any[] }[] }>()

const emit = defineEmits<{ 'update:selected': (folderName: string) => void }>()

const openFolders = ref(new Set<string>())
const selectedFolder = ref<string>('')

const toggle = (folderName: string) => {
  if (openFolders.value.has(folderName)) {
    openFolders.value.delete(folderName)
  } else {
    openFolders.value.add(folderName)
  }
}

const select = (folder: { name: string; children: any[] }) => {
  selectedFolder.value = folder.name
  emit('update:selected', folder.name)
  // auto toggle if folder has children
  if (folder.children.length) toggle(folder.name)
}
</script>

<template>
    <div>
    <!-- Selected folder display -->
    <div class="mb-2 p-2 border rounded bg-gray-100">
      Selected: <span class="font-semibold">{{ selectedFolder || 'None' }}</span>
    </div>

    <!-- Folder tree -->
    <ul class="pl-4">
      <li v-for="folder in folders" :key="folder.name">
        <div
          @click="select(folder)"
          class="cursor-pointer flex items-center gap-1 select-none hover:bg-gray-100 p-1 rounded"
        >
          <!-- Icon -->
          <component
            :is="folder.children.length ? (openFolders.has(folder.name) ? FolderOpen : Folder) : Folder"
            class="w-4 h-4"
          />
          {{ folder.name }}
        </div>

        <!-- Recursive call -->
        <RecursiveFolder
          v-if="folder.children.length && openFolders.has(folder.name)"
          :folders="folder.children"
          @update:selected="selectedFolder = $event"
        />
      </li>
    </ul>
  </div>
</template>
