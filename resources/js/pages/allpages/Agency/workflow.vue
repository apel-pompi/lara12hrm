<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Plus, SquarePen } from 'lucide-vue-next';
import draggable from 'vuedraggable';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Switch from '@/components/ui/switch/Switch.vue';
import { toast } from 'vue-sonner';

export interface Workflow {
    id: number;
    name: string;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Workflows', href: '/workflow' }];

const props = defineProps<{
    workflow: Workflow[];
}>();

const data = props.workflow;
console.log(data)
const items = ref<{ id: number; name: string }[]>([]);

const newItem = ref('');

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push({
            id: items.value.length + 1, // Auto increment id
            name: newItem.value,
        });
        newItem.value = ''; // input reset
    }
};
const removeItem = (id: number) => {
    items.value = items.value.filter((item) => item.id !== id);
};

interface FormErrors {
    name?: string;
    active?: string;
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    stagename: '',
    stage: '',
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
        const res = await fetch(`/department/${id}/edit`);

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
    const reordered = items.value.map((item, index) => ({
        id: index + 1,
        name: item.name,
    }));
    form.stagename = reordered.map((item) => item.name).join(',');
    form.stage = reordered.map((item) => item.id).join(',');

    const action = isEditMode.value && form.id ? route('workflow.update', form.id) : route('workflow.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `Worksflows ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                items.value = [];
                form.reset();
                router.visit(route('workflow.index'), {
                    only: ['workflows'],
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

const toggleStatus = (workflow: Workflow) => {
    const newStatus = !Boolean(workflow.active); // boolean
    router.put(
        route('workflow.updateStatus', workflow.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                workflow.active = newStatus ? 1 : 0; // local update (number)
                toast.success('General Services  status update');
            },
        },
    );
};

</script>

<template>
    <Head title="Workflows" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center gap-2 py-4">
                <Button variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create Workflows </Button>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>General Services </TableHead>
                            <TableHead>Total Partners</TableHead>
                            <TableHead>Added Person</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(workflow, index) in data" :key="workflow.id ?? index">
                            <TableCell>{{ workflow.name }}</TableCell>
                            <TableCell></TableCell>
                            <TableCell>{{ workflow.user.name }}</TableCell>
                            <TableCell>
                                <Switch v-model="workflow.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(workflow)"> </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(workflow.id)"><SquarePen></SquarePen></Button>
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
                    <DialogTitle>{{ isEditMode ? 'Edit Workflows' : 'Create Workflows' }}</DialogTitle>
                    <DialogDescription> Make changes to your workflows here. Click save when you're done. </DialogDescription>
                </DialogHeader>
                <div class="grid gap-5">
                    <div class="grid gap-y-3">
                        <div class="grid gap-2">
                            <Label for="name">Workflow Name</Label>
                            <Input class="max-w-sm" placeholder="Enter Workflow Name" id="name" v-model="form.name" autofocus />
                            <span v-if="errors?.name" class="text-sm text-red-600">{{ errors.name }}</span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="stagename">Workflow Stage</Label>

                            <draggable v-model="items" item-key="id" class="space-y-2">
                                <template #item="{ element, index }">
                                    <div class="flex items-center justify-between rounded border bg-gray-50 p-2">
                                        <span>{{ index + 1 }}. {{ element.name }}</span>
                                        <Button size="sm" variant="destructive" @click="removeItem(element.id)"> Delete </Button>
                                    </div>
                                </template>
                            </draggable>
                            <div v-if="items.length === 0" class="text-gray-400 italic">No items yet. Please add one.</div>
                            <div class="mx-auto mt-6 max-w-md">
                                <div class="flex gap-2">
                                    <Input v-model="newItem" placeholder="Enter stage name..." />
                                    <Button @click="addItem" variant="outline">Add</Button>
                                </div>
                            </div>

                            <span v-if="errors?.stagename" class="text-sm text-red-600">{{ errors.stagename }}</span>
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
