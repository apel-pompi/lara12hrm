<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link, router, useForm } from '@inertiajs/vue3';
import axios from 'axios';

import { CornerDownLeft } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import StudentSidebar from './StudentSidebar.vue';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Student Activity', href: '/student' }];

export interface Student {
    id: number;
    name: string;
}

const props = defineProps<{
    student: { id: number; status: number; fname: string; lname: string };
    studentService: Array<{ id: number; startdate: string; enddate: string; status: string }>;
}>();

const lead = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentInService.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentDocument.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentNotes.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentTasks.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentEducations.index', props.student.id),
    },
    {
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
];

const prospect = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentInService.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentDocument.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentNotes.index', props.student.id),
    },
    {
        title: 'Quotations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentAccounts.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentTasks.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentEducations.index', props.student.id),
    },
    {
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
];
const onBoard = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentInService.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentDocument.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentNotes.index', props.student.id),
    },
    {
        title: 'Quoations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentAccounts.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentTasks.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentEducations.index', props.student.id),
    },
    {
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
];

const archive = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Applications',
        href: route('studentApplication.index', props.student.id),
    },
    {
        title: 'Interested Service',
        href: route('studentInService.index', props.student.id),
    },
    {
        title: 'Documents',
        href: route('studentDocument.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Notes & Terms',
        href: route('studentNotes.index', props.student.id),
    },
    {
        title: 'Quoations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Accounts',
        href: route('studentAccounts.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Tasks',
        href: route('studentTasks.index', props.student.id),
    },
    {
        title: 'Educations',
        href: route('studentEducations.index', props.student.id),
    },
];

const getStatusText = (status: number) => {
    switch (status) {
        case 1:
            return { id: '1', text: 'Lead', color: 'bg-green-500 text-white' };
        case 2:
            return { id: '2', text: 'Prospect', color: 'bg-yellow-500 text-black' };
        case 3:
            return { id: '3', text: 'onBoard', color: 'bg-blue-500 text-white' };
        default:
            return { id: '4', text: 'Achieved', color: 'bg-gray-500 text-white' };
    }
};

const sidebarNavItems = computed<NavItem[]>(() => {
    if (!props.student) return [];

    if (props.student.status === 1) {
        return lead;
    } else if (props.student.status === 2) {
        return prospect;
    } else if (props.student.status === 3) {
        return onBoard;
    } else {
        return archive;
    }
});

const goToStudent = () => {
    router.visit('/student');
};

const updateRate = (status: number) => {
    router.put(
        route('studentActivities.updateRate', props.student.id),
        {
            student_id: props.student.id,
            status,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Partner  status update');
            },
        },
    );
};

const updateArchive = (studentId: number, status: number) => {
    if (!confirm(status === 4 ? 'Archive this student?' : 'Restore this student?')) return
    router.put(
        route('studentActivities.updateArchive', props.student.id),
        {
            student_id: studentId,
            status,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Partner  status update');
            },
        },
    );
};
const form = useForm({
    user_id: '',
});

const showDialog = ref(false);
const users = ref<{ id: number; name: string }[]>([]);
const showDailogCreate = async () => {
    try {
        const res = await axios.get('/users/list');
        users.value = res.data;
    } catch (e) {
        console.error('Failed to load users', e);
    }

    showDialog.value = true;
};

const updateAssignee = () => {
    form.post(route('studentActivities.updateAssignee', { student: props.student.id }), {
        onSuccess: () => {
            toast('Success', {
                description: `User assignee successfully`,
            });
            setTimeout(() => {
                showDialog.value = false;
                form.reset();
                router.visit(route('studentActivities.index', [props.student.id]), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            showDialog.value = false;
            form.reset();
        },
        onError: () => {
            toast('Validation Error', {
                description: 'User assignee error',
            });
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border bg-gray-100 px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 pt-1 pl-4">
                <div class="flex-1 text-sm">
                    <Heading title="Student Actvities" description="Manage your student activities and account settings" />
                </div>
                <div class="space-x-2">
                    <Button variant="outline" size="sm" @click="goToStudent"><CornerDownLeft></CornerDownLeft> Back</Button>
                </div>
            </div>
            <div class="flex flex-col gap-6 pb-12 lg:flex-row">
                <!-- LEFT SIDEBAR -->
                <StudentSidebar :student="student" :getStatusText="getStatusText" :updateArchive="updateArchive" :updateRate="updateRate" :showDailogCreate="showDailogCreate" :studentService="studentService" />

                <!-- MAIN CONTENT -->
                <main class="flex flex-1 flex-col gap-6">
                    <!-- Tabs -->
                    <nav class="text-md flex flex-wrap gap-4 border-b bg-white p-6 font-medium">
                        <div class="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 border bg-gray-100 p-3">
                            <Button v-for="item in sidebarNavItems" :key="item.href" variant="ghost" as-child>
                                <Link :href="item.href">
                                    {{ item.title }}
                                </Link>
                            </Button>
                        </div>
                    </nav>

                    <section class="bg-white p-4 shadow dark:bg-gray-900">
                        <slot />
                    </section>
                </main>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-[825px]">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle> User Assignee </DialogTitle>
                    <DialogDescription> update user assignee. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">Select Assignee User</Label>
                        <Select v-model="form.user_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select user" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="form.processing" @click="updateAssignee">
                        <template v-if="form.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
