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
        title: 'Accounts Group Three',
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

export interface GroupThree {
    groupthree: string;
    description: string;
    active: number;
    group_one: { groupone: number; description: string };
    group_two: { grouptwo: number; description: string };
    user: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    groupInfo: { groupone: number; grouptwo: string; description: string; group_one: { groupone: number; description: string } };
    code: number;
    groupthree: Paginated<GroupThree>;
}>();

const data = props.groupthree;

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    groupone: '',
    grouptwo: '',
    groupthree: '',
    description: '',
    active: '0',
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    form.groupone = props.groupInfo.groupone;
    form.grouptwo = props.groupInfo.grouptwo;
    form.groupthree = props.code;
    isEditMode.value = false;
    showDialog.value = true;
};

const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/Groupthree/${id}/edit`);

        if (!res.ok) {
            toast.error('Server error while fetching group three details.');
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
    const action = isEditMode.value && form.id ? route('GroupThree.update', form.id) : route('GroupThree.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            setTimeout(() => {
                form.reset();
                showDialog.value = false;
                router.visit(route('accsetting.GroupThree',{
                    GroupOne: props.groupInfo.groupone,
                    GroupTwo: props.groupInfo.grouptwo,
                }), {
                    preserveScroll: true,
                    preserveState: false,
                });
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

const toggleStatus = (three: GroupThree) => {
    const newStatus = !Boolean(three.active); // boolean
    router.put(
        route('GroupThree.updateStatus', three.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                three.active = newStatus ? 1 : 0; // local update (number)
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
    if (!confirm('Are you sure you want to delete this group three?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/Groupthree/show/${id}`, {
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

const goToGroupTwo = () => {
    router.get(route('accsetting.GroupTwo', { GroupOne: props.groupInfo.groupone }));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Accounts Setting" />

        <AccountsLayout :breadcrumbs="breadcrumbs">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="goToGroupTwo"
                        ><CornerDownLeft></CornerDownLeft> Back Group Two
                    </Button>
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"
                        ><Plus></Plus> Group Three
                    </Button>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sl</TableHead>
                                <TableHead>Group One</TableHead>
                                <TableHead>Group Two</TableHead>
                                <TableHead>Group Three Code</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Created By</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody v-for="(three, index) in data.data ?? []" :key="index">
                            <TableRow>
                                <TableCell>{{ index + 1 }}</TableCell>
                                <TableCell>{{ three.group_one.description }}</TableCell>
                                <TableCell>{{ three.group_two.description }}</TableCell>
                                <TableCell>{{ three.groupthree }}</TableCell>
                                <TableCell>{{ three.description }}</TableCell>
                                <TableCell>{{ three.user.name }}</TableCell>
                                <TableCell>
                                    <Switch v-model="three.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(three)"> </Switch>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(three.id)"><SquarePen></SquarePen></Button>
                                    <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(three.id)"><Trash></Trash></Button>
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
                            {{ isEditMode ? 'Edit Group Two' : 'Create Group Two' }}
                        </DialogTitle>
                        <DialogDescription class="text-sm text-gray-500">
                            {{ isEditMode ? 'Modify the information and click Update.' : 'Fill out the form to add a new Group Two.' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Body -->
                    <div class="mt-6 space-y-5">
                        <!-- Group One -->
                        <div>
                            <Label for="groupone" class="text-sm font-medium">Group One<span class="text-red-500">*</span></Label>
                            <p>{{ props.groupInfo.group_one.description }}</p>
                        </div>
                        <!-- Group Two -->
                        <div>
                            <Label for="grouptwo" class="text-sm font-medium">Group Two<span class="text-red-500">*</span></Label>
                            <p>{{ props.groupInfo.description }}</p>
                        </div>
                        <!-- Code -->
                        <div>
                            <Label for="groupthree" class="text-sm font-medium">Code<span class="text-red-500">*</span></Label>
                            <Input type="text" id="groupthree" v-model="form.groupthree" class="mt-1 w-full" readonly disabled />
                            <p v-if="form.errors.groupthree" class="mt-1 text-sm text-red-600">
                                {{ form.errors.groupthree }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="description" class="text-sm font-medium">Description<span class="text-red-500">*</span></Label>
                            <Input type="text" id="description" placeholder="Enter Description" v-model="form.description" class="mt-1 w-full" />
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