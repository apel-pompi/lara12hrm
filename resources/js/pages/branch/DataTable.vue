<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { MoreHorizontal, Eye, Pencil, Trash } from 'lucide-vue-next'

const props = defineProps<{
  branch: any,
  onShow: (id: number) => void,
  onEdit: (id: number) => void,
  onDelete: (id: number) => void,
}>()


</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="w-8 h-8 p-0">
        <span class="sr-only">Open menu</span>
        <MoreHorizontal class="w-4 h-4" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent>
      <DropdownMenuLabel>Actions</DropdownMenuLabel>
      <DropdownMenuItem 
        @click="props.onShow(branch.id)"
        class="cursor-pointer hover:bg-muted hover:text-primary flex items-center">
        <Eye class="w-4 h-4 mr-2" /> Show
      </DropdownMenuItem>

      <DropdownMenuSeparator />

      <DropdownMenuItem 
        @click="props.onEdit(branch.id)"
        class="cursor-pointer hover:bg-muted hover:text-primary flex items-center">
        <Pencil class="w-4 h-4 mr-2" /> Edit
      </DropdownMenuItem>

      <DropdownMenuSeparator />

      <DropdownMenuItem 
        @click="props.onDelete(branch.id)"
        class="cursor-pointer text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center">
        <Trash class="w-4 h-4 mr-2" /> Delete
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>