<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AccountsLayout from '@/layouts/settings/accountLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { CornerDownLeft, Plus, SquarePen, Trash } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts Group Four',
        href: '/accountssetting',
    },
];

export interface Paginated<T> {
    data: T[];
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

export interface GroupFour {
    id: number;
    code: string;
    description: string;
    active: number;
    user: {
        id: number;
        name: string;
    };
    group_one: { id: number; description: string };
    group_two: { id: number; description: string };
    group_three: { id: number; description: string };
}

const props = defineProps<{
    groupInfo: {
        id: number;
        groupone: number;
        code: string;
        description: string;
        group_one: {
            id: number;
            code: number;
            description: string;
        };
        group_two: {
            id: number;
            code: number;
            description: string;
        };
    };
    code: number;
    groupfour: Paginated<GroupFour>;
}>();

const data = props.groupfour;
console.log(props.groupInfo);
const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    groupone: null as number | null,
    grouptwo: null as number | null,
    groupthree: null as number | null,
    code: null as number | null,
    description: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    form.groupone = props.groupInfo.group_one.id;
    form.grouptwo = props.groupInfo.group_two.id;
    form.groupthree = props.groupInfo.id;
    form.code = props.code;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/Groupfour/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching group four details.');
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
    const action = isEditMode.value && form.id ? route('GroupFour.update', form.id) : route('GroupFour.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(
                    route('accsetting.GroupFour', {
                        GroupOne: props.groupInfo.group_one.id,
                        GroupTwo: props.groupInfo.group_two.id,
                        GroupThree: props.groupInfo.id,
                    }),
                    {
                        preserveScroll: true,
                        preserveState: false,
                    },
                );
            }, 200);
            form.reset();
            showDialog.value = false;
        },

        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const flash = usePage().props.flash;
            if (flash?.error) {
                toast('error', {
                    description: flash.error + firstError,
                });
            }
        },
    });
};

const toggleStatus = (four: GroupFour, checked: boolean) => {
    router.put(
        route('GroupFour.updateStatus', four.id),
        { active: checked ? 1 : 0 },
        {
            preserveState: true,
            onSuccess: () => {
                four.active = checked ? 1 : 0;
                const flash = usePage().props.flash;
                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
            },
        },
    );
};

const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this group four?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/Groupfour/show/${id}`, {
        onSuccess: () => {
            const flash = usePage().props.flash;
            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
        },
        onError: () => {
            if (flash?.success) {
                toast('error', {
                    description: flash.success,
                });
            }
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};

const goToGroupThree = () => {
    router.get(route('accsetting.GroupThree', { GroupOne: props.groupInfo.group_one.id, GroupTwo: props.groupInfo.group_two.id }));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Accounts Setting" />

        <AccountsLayout :breadcrumbs="breadcrumbs">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <!-- Header / Toolbar -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="goToGroupThree"
                        ><CornerDownLeft></CornerDownLeft> Back Group Three
                    </Button>
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Group Four
                    </Button>
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <!-- Title -->
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Accounts Group Four List</h2>
                        <p class="text-sm text-gray-500">Manage all Accounts Group Four from here.</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow class="bg-gray-100 hover:bg-gray-100">
                                <TableHead>Sl</TableHead>
                                <TableHead>Group Four Code</TableHead>
                                <TableHead>Group One</TableHead>
                                <TableHead>Group Two</TableHead>
                                <TableHead>Group Three</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody v-for="(four, index) in data.data ?? []" :key="index">
                            <TableRow>
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell>{{ four.code }}</TableCell>
                                <TableCell>{{ four.group_one.description }}</TableCell>
                                <TableCell>{{ four.group_two.description }}</TableCell>
                                <TableCell>{{ four.group_three.description }}</TableCell>
                                <TableCell>{{ four.description }}</TableCell>
                                <TableCell>{{ four.user.name }}</TableCell>
                                <TableCell>
                                    <Switch :model-value="Boolean(four.active)" @update:model-value="(checked) => toggleStatus(four, checked)">
                                    </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(four.id)"
                                        ><SquarePen class="h-4 w-4 text-indigo-600"
                                    /></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(four.id)"
                                        ><Trash class="h-4 w-4 text-red-600"
                                    /></Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex items-center justify-end space-x-2 py-4">
                    <div class="text-muted-foreground flex-1 text-sm">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</div>
                    <div class="space-x-2">
                        <Button
                            v-for="(link, index) in data.links"
                            :key="index"
                            :disabled="!link.url"
                            variant="outline"
                            size="sm"
                            :class="[link.active ? 'hover:outline' : '', !link.url ? 'cursor-not-allowed opacity-50' : '']"
                            @click="goToPage(link.url)"
                        >
                            <span v-html="link.label"></span>
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Dialog -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                    <!-- Header -->
                    <DialogHeader class="space-y-1 border-b pb-4">
                        <DialogTitle class="text-xl font-semibold tracking-wide">
                            {{ isEditMode ? 'Edit Group Four' : 'Create Group Four' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new Group Four.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="mt-6 space-y-5">
                        <!-- Group One -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">Group One<span class="text-red-500">*</span></Label>
                            <p>{{ props.groupInfo.group_one.description }}</p>
                        </div>
                        <!-- Group Tow -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">Group Two<span class="text-red-500">*</span></Label>
                            <p>{{ props.groupInfo.group_two.description }}</p>
                        </div>
                        <!-- Group Three -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">Group Three<span class="text-red-500">*</span></Label>
                            <p>{{ props.groupInfo.description }}</p>
                        </div>
                        <!-- Code -->
                        <div>
                            <Label for="code" class="text-sm font-medium">Code<span class="text-red-500">*</span></Label>
                            <Input type="text" id="code" v-model="form.code" class="mt-1 w-full" readonly disabled />
                            <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="description" class="text-sm font-medium">Description<span class="text-red-500">*</span></Label>
                            <Input
                                type="text"
                                id="description"
                                placeholder="Enter Description"
                                v-model="form.description"
                                class="mt-1 w-full uppercase"
                            />
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <DialogFooter class="mt-8 flex justify-end gap-3 border-t pt-4">
                        <DialogClose as-child>
                            <Button variant="secondary">Cancel</Button>
                        </DialogClose>

                        <Button :disabled="form.processing" @click="submit">
                            <template v-if="form.processing">Saving...</template>
                            <template v-else>{{ isEditMode ? 'Update' : 'Save' }}</template>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </AccountsLayout>
    </AppLayout>
</template>
