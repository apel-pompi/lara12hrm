<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AgencyLayout from '@/layouts/settings/socialmediaLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, RefreshCcw, SquarePen, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface SocialMediaItem {
    id: number;
    platform: 'facebook' | 'whatsapp' | 'messenger';
    page_id?: string | null;
    whatsapp_business_account_id?: string;

    access_token?: string;
    verify_token?: string;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Social Media Setup', href: '/social-media-setup' }];

const props = defineProps<{
    socialMediaSetups: SocialMediaItem[];
}>();

// normalize to data-like object for table/pagination UI
const data = computed(() => ({ data: props.socialMediaSetups || [], links: [] }));

const showDialog = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null as number | null,
    platform: '',
    page_id: '',
    whatsapp_business_account_id: '',
    access_token: '',
    verify_token: '',
});

const isFacebookOrMessenger = computed(() => form.platform === 'facebook' || form.platform === 'messenger');
const isWhatsApp = computed(() => form.platform === 'whatsapp');

const resetFormState = () => {
    form.reset();
    form.clearErrors();
    form.id = null;
    form.platform = '';
    form.page_id = '';
    form.whatsapp_business_account_id = '';
    form.access_token = '';
    form.verify_token = '';
};

watch(
    () => form.platform,
    (platform) => {
        if (platform === 'facebook' || platform === 'messenger') {
            form.whatsapp_business_account_id = '';
            form.clearErrors('whatsapp_business_account_id');
        }

        if (platform === 'whatsapp') {
            form.page_id = '';
            form.clearErrors('page_id');
        }
    },
);

const showDialogCreate = () => {
    resetFormState();
    isEditMode.value = false;
    showDialog.value = true;
};

const openEdit = (item: SocialMediaItem) => {
    resetFormState();
    form.id = item.id;
    form.platform = item.platform;
    form.page_id = item.page_id;
    form.whatsapp_business_account_id = item.whatsapp_business_account_id ?? '';
    form.access_token = item.access_token ?? '';
    form.verify_token = item.verify_token ?? '';
    isEditMode.value = true;
    showDialog.value = true;
};

const submit = () => {
    if (form.platform === 'facebook' || form.platform === 'messenger') {
        form.whatsapp_business_account_id = '';
    }

    if (form.platform === 'whatsapp') {
        form.page_id = '';
    }

    if (form.id) {
        form.put(route('social-media-setup.update', form.id), {
            preserveState: true,
            onSuccess: () => {
                toast.success('Social media updated');
                resetFormState();
                showDialog.value = false;
                router.reload({ only: ['socialMediaSetups'], preserveScroll: true });
            },
            onError: (errors) => {
                const first = Object.values(errors)[0];
                toast('Validation Error', { description: first });
            },
        });
    } else {
        form.post(route('social-media-setup.store'), {
            preserveState: true,
            onSuccess: () => {
                toast.success('Social media created');
                resetFormState();
                showDialog.value = false;
                router.reload({ only: ['socialMediaSetups'], preserveScroll: true });
            },
            onError: (errors) => {
                const first = Object.values(errors)[0];
                toast('Validation Error', { description: first });
            },
        });
    }
};

const deleteForm = useForm({});
const onDelete = (id: number) => {
    if (!confirm('Are you sure you want to delete this item?')) return;
    deleteForm.delete(route('social-media-setup.destroy', id), {
        onSuccess: () => {
            toast.success('Deleted successfully');
        },
        onError: () => {
            toast.error('Failed to delete');
        },
    });
};

const refresh = () => router.get(route('social-media-setup.index'), {}, { replace: true });

const truncateToken = (token?: string) => {
    if (!token || token.length <= 20) return token || '-';
    const start = token.slice(0, 10);
    const end = token.slice(-10);
    return `${start}....${end}`;
};

const platformLabel = (platform: string) => {
    if (platform === 'facebook') return 'Facebook';
    if (platform === 'whatsapp') return 'WhatsApp';
    if (platform === 'messenger') return 'Messenger';
    return platform || '-';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Social Media Setup" />
        <AgencyLayout>
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border bg-gray-50 px-4 py-6 md:min-h-min">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <Button class="w-full bg-blue-600 text-white hover:bg-blue-700 sm:w-auto dark:bg-blue-500"
                            size="sm" @click="showDialogCreate">
                            <Plus class="mr-2 h-4 w-4" />
                            Create
                        </Button>

                        <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-700" variant="outline" size="sm"
                            @click="refresh">
                            <RefreshCcw class="mr-2 h-4 w-4" />
                            Refresh
                        </Button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Social Media setup</h2>
                        <p class="text-sm text-gray-500">Social Media settings</p>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Platform</TableHead>
                                <TableHead>Page ID</TableHead>
                                <TableHead>WhatsApp Business Account ID</TableHead>
                                <TableHead>Access Token</TableHead>
                                <TableHead>Verify Token</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(item, index) in data.data" :key="item.id ?? index">
                                <TableCell>{{ platformLabel(item.platform) }}</TableCell>
                                <TableCell>{{ item.page_id || '-' }}</TableCell>
                                <TableCell>{{ item.whatsapp_business_account_id || '-' }}</TableCell>
                                <TableCell>{{ truncateToken(item.access_token) }}</TableCell>
                                <TableCell>{{ truncateToken(item.verify_token) }}</TableCell>
                                <TableCell class="text-right">
                                    <Button size="sm" variant="outline" class="ml-2" @click="openEdit(item)">
                                        <SquarePen />
                                    </Button>
                                    <Button size="sm" variant="destructive" class="ml-2" @click="onDelete(item.id)">
                                        <Trash />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="mt-5 text-sm text-gray-600">Showing {{ data.data.length }} results</div>

                <Dialog v-model:open="showDialog">
                    <DialogContent class="max-w-lg rounded-2xl shadow-lg sm:max-w-xl md:max-w-2xl">
                        <DialogHeader class="border-b pb-3">
                            <DialogTitle class="text-lg font-semibold">{{ isEditMode ? 'Edit' : 'Create' }} Social Media
                            </DialogTitle>
                            <DialogDescription class="text-sm text-gray-500">
                                {{ isEditMode ? 'Update the social media settings.' : 'Fill the fields to create a new social media setting.' }}
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid grid-cols-1 gap-6 py-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="platform" class="font-medium">Platform<span
                                        class="text-red-500">*</span></Label>
                                <Select id="platform" v-model="form.platform">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Platform" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="facebook">Facebook</SelectItem>
                                        <SelectItem value="whatsapp">WhatsApp</SelectItem>
                                        <SelectItem value="messenger">Messenger</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.platform" class="text-sm text-red-600">{{ form.errors.platform }}
                                </p>
                            </div>

                            <div v-if="isFacebookOrMessenger" class="grid gap-2">
                                <Label for="page_id" class="font-medium">Page ID<span
                                        class="text-red-500">*</span></Label>
                                <Input id="page_id" v-model="form.page_id" class="w-full" />
                                <p v-if="form.errors.page_id" class="text-sm text-red-600">{{ form.errors.page_id }}</p>
                            </div>
                            <div v-if="isWhatsApp" class="grid gap-2">
                                <Label for="whatsapp_business_account_id" class="font-medium">WhatsApp Business Account
                                    ID</Label>
                                <Input id="whatsapp_business_account_id" v-model="form.whatsapp_business_account_id"
                                    class="w-full" />
                                <p v-if="form.errors.whatsapp_business_account_id" class="text-sm text-red-600">
                                    {{ form.errors.whatsapp_business_account_id }}
                                </p>
                            </div>

                            <div class="grid gap-2">
                                <Label for="verify_token" class="font-medium">Verify Token</Label>
                                <Input id="verify_token" v-model="form.verify_token" class="w-full" />
                                <p v-if="form.errors.verify_token" class="text-sm text-red-600">{{
                                    form.errors.verify_token }}</p>
                            </div>

                            <div class="grid gap-2 md:col-span-2">
                                <Label for="access_token" class="font-medium">Access Token</Label>
                                <Input id="access_token" v-model="form.access_token" class="w-full" />
                                <p v-if="form.errors.access_token" class="text-sm text-red-600">{{
                                    form.errors.access_token }}</p>
                            </div>
                        </div>

                        <DialogFooter class="flex justify-end space-x-2 border-t pt-4">
                            <DialogClose as-child>
                                <Button type="button" variant="secondary">Cancel</Button>
                            </DialogClose>
                            <Button :disabled="form.processing" @click="submit">{{ isEditMode ? 'Update' : 'Save'
                                }}</Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </AgencyLayout>
    </AppLayout>
</template>
