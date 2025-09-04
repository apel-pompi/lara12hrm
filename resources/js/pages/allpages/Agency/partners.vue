<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Plus,HousePlus} from 'lucide-vue-next';
import draggable from 'vuedraggable';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Switch from '@/components/ui/switch/Switch.vue';
import { toast } from 'vue-sonner';

export interface Partner {
    id: number;
    name: string;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Workflows', href: '/workflow' }];

const props = defineProps<{
    
    workflow: Partner[];
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
        const res = await fetch(`/workflow/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching department details.');
            return;
        }

        const data = await res.json();
        Object.assign(form, data.data);
        form.id = data.data.id;
        const stageNames = data.data.stagename.split(',');
        const stageIds = data.data.stage.split(',');
        items.value = stageNames.map((name: string, index: number) => ({
            id: Number(stageIds[index]) || index + 1,
            name: name,
        }));
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

const toggleStatus = (workflow: Partner) => {
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



const goToPartnerCreate = () => {
    router.visit('/partner/create');
};

</script>

<template>
    <Head title="Workflows" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="goToPartnerCreate"><Plus></Plus> Create Partner </Button>
                </div>
                <div class="space-x-2">

                </div>
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
                        
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="text-muted-foreground flex-1 text-sm"></div>
                <div class="space-x-2"></div>
            </div>
        </div>
        <!-- Dialog -->
        
    </AppLayout>
</template>
