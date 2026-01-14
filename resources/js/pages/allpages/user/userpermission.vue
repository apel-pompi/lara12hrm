<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import UserLayout from '@/layouts/settings/userLayout.vue';
import { Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

import { computed, ref } from 'vue';

import Badge from '@/components/ui/badge/Badge.vue';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, RefreshCcw, Search, Trash, Undo2, Eye } from 'lucide-vue-next';

import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'User Permission', href: '/userpermission' }];

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

export interface Users {
    id: number;
    name: string;
    username: string;
    email: string;
    roles: { name: string }[];
}

const props = defineProps<{
    users: Paginated<Users>;
    filters: { name?: string };
    alluser:{id:number;name:string;username:string;email:string}[];
    roles: any[];
}>();

const data = props.users;

const showPassword = ref<boolean>(false);

interface FormErrors {
    name?: string;
    username?: string;
    email: string;
    password: string;
    password_confirmation: string;
    permission: [];
}

const showDialog = ref(false);
const isEditMode = ref(false);
const errors = ref<FormErrors>();

const form = useForm({
    id: null as number | null,
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    permissions: [],
});

const showDailogCreate = () => {
    form.reset();
    form.id = null;
    isEditMode.value = false;
    showDialog.value = true;
};


const onEdit = async (id: number) => {
    try {
        const res = await fetch(`/userpermission/${id}/edit`);
        if (!res.ok) {
            toast.error('Server error while fetching userpermission details.');
            return;
        }
        const data = await res.json();

        Object.assign(form, {
            id: data.users.id,
            name: data.users.name,
            username: data.users.username,
            email: data.users.email,
            password: '',
            password_confirmation: '',
            permissions: data.users.roles, // assuming roles = array of role IDs
        });

        isEditMode.value = true;
        showDialog.value = true;
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const submit = () => {
    const action = isEditMode.value && form.id ? route('userpermission.update', form.id) : route('userpermission.store');
    const method = isEditMode.value ? 'put' : 'post';

    form[method](action, {
        onSuccess: () => {
            toast('Success', {
                description: `User Permission ${isEditMode.value ? 'updated' : 'created'} successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('userpermission.index'), {
                    only: ['userpermission'],
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
const deleteForm = useForm({});

const onDelete = async (id: number) => {
    if (!confirm('Are you sure you want to banned this User?')) return;

    if (deleteForm.processing) return;

    deleteForm.delete(`/userpermission/show/${id}`, {
        onSuccess: () => {
            toast.success('User banned successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};

const onReturn = async (id: number) => {
    if (!confirm('Are you sure you want to active this User?')) return;

    if (deleteForm.processing) return;

    deleteForm.post(`/userpermission/active/${id}`, {
        onSuccess: () => {
            toast.success('User active successfully');
        },
        onError: () => {
            toast.success('Somethings wrong !');
        },
        preserveScroll: true,
        preserveState: false,
    });
};


const selectedName = ref(null);
const queryName = ref('');
const filteredName = computed(() => {
    if (queryName.value === '') return props.alluser;

    return props.alluser.filter((n) => n.name && n.name.toLowerCase().includes(queryName.value.toLowerCase()));
});

const selectedUserName = ref(null);
const queryUserName = ref('');
const filteredUserName= computed(() => {
    if (queryUserName.value === '') return props.alluser;

    return props.alluser.filter((n) => n.username && n.username.toLowerCase().includes(queryUserName.value.toLowerCase()));
});

const selectedEmail = ref(null);
const queryEmail = ref('');
const filteredEmail= computed(() => {
    if (queryEmail.value === '') return props.alluser;

    return props.alluser.filter((n) => n.email && n.email.toLowerCase().includes(queryEmail.value.toLowerCase()));
});

const search = () => {
    const params: Record<string, any> = {};

    if (selectedName.value) params.name = selectedName.value.name;
    if (selectedUserName.value) params.username = selectedUserName.value.username;
    if (selectedEmail.value) params.email = selectedEmail.value.email;

    router.get(route('userpermission.index'), params, {
        preserveState: false,
        preserveScroll: true,
    });
};


const refresh = () => {
    router.get(route('userpermission.index'), {}, { replace: true });
};

const perPage = ref(10);

const changePerPage = () => {
    router.get(route('userpermission.index'), { per_page: perPage.value }, { preserveState: false, replace: true });
};
const goToPage = (url: string | null) => {
    if (url) {
        router.get(url, {}, { preserveState: false, replace: true });
    }
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="User Permission" />
        <UserLayout>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-screen flex-1 border px-4 md:min-h-min">
                <div class="flex items-center gap-2 py-4">
                    <Button class="dark:bg-black dark:text-white dark:hover:bg-gray-600" variant="outline" size="sm" @click="showDailogCreate"><Plus></Plus> Create </Button>
                </div>
                <div class="flex flex-wrap items-center gap-4 py-4">
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedName">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select Name"
                                    @input="queryName = $event.target.value"
                                    :display-value="(c) => c?.name ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredName.length === 0 && queryName !== ''" class="text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredName"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.name }}
                                        </span>
                                        <span
                                            v-if="selected"
                                            class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedUserName">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select User Name"
                                    @input="queryUserName = $event.target.value"
                                    :display-value="(c) => c?.username ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredUserName.length === 0 && queryUserName !== ''" class="text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredUserName"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.username }}
                                        </span>
                                        <span
                                            v-if="selected"
                                            class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-auto">
                        <Combobox v-model="selectedEmail">
                            <div class="relative w-full md:w-48">
                                <ComboboxInput
                                    class="w-full rounded-md border px-3 py-2 text-sm"
                                    placeholder="Select Email"
                                    @input="queryEmail = $event.target.value"
                                    :display-value="(c) => c?.email ?? ''"
                                />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
                                </ComboboxButton>

                                <ComboboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 text-sm shadow-lg"
                                >
                                    <div v-if="filteredEmail.length === 0 && queryEmail !== ''" class="text-gray-500 select-none">
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="one in filteredEmail"
                                        :key="one.id"
                                        :value="one"
                                        class="ui-active:bg-indigo-600 ui-active:text-white ui-selected:font-medium relative cursor-pointer py-2 pl-10 select-none"
                                        v-slot="{ selected }"
                                    >
                                        <span :class="['block truncate', selected ? 'font-medium' : 'font-normal']">
                                            {{ one.email }}
                                        </span>
                                        <span
                                            v-if="selected"
                                            class="ui-active:text-white absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                        >
                                            <CheckIcon class="h-5 w-5" />
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                    </div>
                    <div class="w-full sm:w-auto">
                        <Button variant="outline" size="sm" @click="search"><Search></Search> Search </Button>
                    </div>
                    <div class="w-full sm:w-auto">
                        <Button variant="outline" size="sm" @click="refresh"><RefreshCcw></RefreshCcw> Refresh </Button>
                    </div>
                </div>
                <div class="overflow-hidden rounded-xl border shadow-sm">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>User Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Roles</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-center">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(trn, index) in data.data" :key="trn.id ?? index">
                                <TableCell>{{ trn.name }}</TableCell>
                                <TableCell>{{ trn.username }}</TableCell>
                                <TableCell>{{ trn.email }}</TableCell>
                                <TableCell>
                                    <div v-for="(role, index) in trn.roles" :key="index">
                                        <Badge variant="default" size="sm" class="flex flex-wrap gap-2">{{ role.name }}</Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <span v-if="trn.banned_at"><Badge size="sm" class="flex flex-wrap gap-2 bg-red-500">Banned</Badge></span>
                                    <span v-else><Badge variant="outline" size="sm" class="flex flex-wrap gap-2">Active</Badge></span>
                                </TableCell>

                                <TableCell class="text-right flex justify-center gap-2">
                                    
                                    <div class="group relative">
                                        <Button @click="onEdit(trn.id)" class="cursor-pointer" variant="outline" size="sm">
                                            <Eye />
                                        </Button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Edit User
                                        </span>
                                    </div>
                                    <div class="group relative" v-if="trn.banned_at">
                                        <Button @click="onReturn(trn.id)" class="cursor-pointer bg-red-500" size="sm">
                                            <Undo2 class="text-white"/>
                                        </Button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Active User
                                        </span>
                                    </div>
                                    <div class="group relative" v-else>
                                        <Button @click="onDelete(trn.id)" class="cursor-pointer" variant="outline" size="sm">
                                            <Trash />
                                        </Button>
                                        <span
                                            class="absolute -top-6 left-1/2 -translate-x-1/2 rounded bg-gray-800 px-2 py-1 text-xs text-white opacity-0 transition group-hover:opacity-100"
                                        >
                                            Banned User
                                        </span>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="flex flex-col items-center justify-between space-y-3 py-4 md:flex-row md:space-y-0">
                    <div class="text-muted-foreground flex flex-1 items-center space-x-2 text-sm">
                        <label for="per-page" class="text-gray-600">Show:</label>
                        <select v-model="perPage" @change="changePerPage" class="rounded border px-2 py-1 text-sm">
                            <option v-for="size in [5, 10, 25, 50, 100, 200]" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span>Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results</span>
                    </div>
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
            <!-- Enhanced User Dialog Component -->
            <Dialog v-model:open="showDialog">
                <DialogContent class="max-w-237.5 overflow-hidden rounded-2xl p-0 shadow-xl">
                    <!-- Header with gradient background -->
                    <DialogHeader class="border-b px-6 pt-6 pb-4">
                        <DialogTitle class="text-2xl font-semibold">
                            {{ isEditMode ? 'Edit User' : 'Create New User' }}
                        </DialogTitle>
                        <DialogDescription class="mt-1 flex items-center text-gray-500">
                            {{ isEditMode ? 'Update user information' : 'Fill in the details to create a new user account' }}
                        </DialogDescription>
                    </DialogHeader>

                    <!-- Form Container -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <!-- Left Column - Personal Info -->
                            <div class="space-y-5">
                                <h3 class="border-b pb-2 text-lg font-medium text-gray-700">Personal Information</h3>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <Label for="name" class="text-sm font-medium text-gray-700">Full Name</Label>
                                        <Input
                                            id="name"
                                            placeholder="John Doe"
                                            v-model="form.name"
                                            class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        />
                                        <p v-if="errors?.name" class="mt-1 flex items-center text-sm text-red-500">
                                            <AlertCircle class="mr-1 h-4 w-4" /> {{ errors.name }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="username" class="text-sm font-medium text-gray-700">Username</Label>
                                        <Input
                                            id="username"
                                            placeholder="johndoe"
                                            v-model="form.username"
                                            class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        />
                                        <p v-if="errors?.username" class="mt-1 flex items-center text-sm text-red-500">
                                            <AlertCircle class="mr-1 h-4 w-4" /> {{ errors.username }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="email" class="text-sm font-medium text-gray-700">Email Address</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            placeholder="john@example.com"
                                            v-model="form.email"
                                            class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        />
                                        <p v-if="errors?.email" class="mt-1 flex items-center text-sm text-red-500">
                                            <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Security & Permissions -->
                            <div class="space-y-5">
                                <h3 class="border-b pb-2 text-lg font-medium text-gray-700">Security & Permissions</h3>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <Label for="password" class="text-sm font-medium text-gray-700">
                                            {{ isEditMode ? 'New Password' : 'Password' }}
                                        </Label>
                                        <div class="relative">
                                            <Input
                                                id="password"
                                                :type="showPassword ? 'text' : 'password'"
                                                placeholder="••••••••"
                                                v-model="form.password"
                                                class="pr-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                            />
                                            <button
                                                type="button"
                                                class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                                @click="showPassword = !showPassword"
                                            >
                                                <IconEye v-if="!showPassword" class="h-5 w-5" />
                                                <IconEyeOff v-else class="h-5 w-5" />
                                            </button>
                                        </div>
                                        <p v-if="errors?.password" class="mt-1 flex items-center text-sm text-red-500">
                                            <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.password }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="confirm_password" class="text-sm font-medium text-gray-700"> Confirm Password </Label>
                                        <Input
                                            id="password_confirmation"
                                            :type="showPassword ? 'text' : 'password'"
                                            placeholder="••••••••"
                                            v-model="form.password_confirmation"
                                            class="focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        />
                                        <p v-if="errors?.password_confirmation" class="mt-1 flex items-center text-sm text-red-500">
                                            <CircleAlertIcon class="mr-1 h-4 w-4" /> {{ errors.password_confirmation }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="role" class="text-sm font-medium text-gray-700">User Role</Label>
                                        <select
                                            id="roles"
                                            v-model="form.permissions"
                                            multiple
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option v-for="role in props.roles" :key="role.id" :value="Number(role.id)">{{ role.name }}</option>
                                        </select>
                                        <p v-if="errors?.permission" class="mt-1 flex items-center text-sm text-red-500">
                                            <IconAlertCircle class="mr-1 h-4 w-4" /> {{ errors.permission }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer with Actions -->
                        <DialogFooter class="mt-8 border-t pt-6">
                            <DialogClose as-child>
                                <Button type="button" variant="outline" class="px-6"> Cancel </Button>
                            </DialogClose>
                            <Button
                                type="submit"
                                class="bg-indigo-600 px-6 transition-colors hover:bg-indigo-700"
                                :disabled="form.processing"
                                @click="submit"
                            >
                                <template v-if="form.processing">
                                    <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                    Processing...
                                </template>
                                <template v-else>
                                    {{ isEditMode ? 'Update User' : 'Create User' }}
                                </template>
                            </Button>
                        </DialogFooter>
                    </div>
                </DialogContent>
            </Dialog>
        </UserLayout>
    </AppLayout>
</template>
