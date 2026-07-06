<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type NavItem } from '@/types';

import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';
import { AlertCircle, CornerDownLeft, Loader2, Phone, Save } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
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

const pending = [
    {
        title: 'Activities',
        href: route('studentActivities.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
    {
        title: 'Facebook Chat',
        href: route('studentChat.index', props.student.id),
    },
    {
        title: 'WhatsApp Chat',
        href: route('studentWhatsapp.index', props.student.id),
    },
];
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
        title: 'Quotations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
    },
    {
        title: 'Conversations',
        href: route('studentConversations.index', props.student.id),
    },
    {
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
    {
        title: 'Facebook Chat',
        href: route('studentChat.index', props.student.id),
    },
    {
        title: 'WhatsApp Chat',
        href: route('studentWhatsapp.index', props.student.id),
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
        title: 'Quotations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
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
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
    {
        title: 'Facebook Chat',
        href: route('studentChat.index', props.student.id),
    },
    {
        title: 'WhatsApp Chat',
        href: route('studentWhatsapp.index', props.student.id),
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
        title: 'Quoations',
        href: route('studentQuotations.index', props.student.id),
    },
    {
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
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
        title: 'Check-in Logs',
        href: route('studentCheckin.index', props.student.id),
    },
    {
        title: 'Facebook Chat',
        href: route('studentChat.index', props.student.id),
    },
    {
        title: 'WhatsApp Chat',
        href: route('studentWhatsapp.index', props.student.id),
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
        title: 'Appointements',
        href: route('studentAppointements.index', props.student.id),
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
        title: 'Educations',
        href: route('studentEducations.index', props.student.id),
    },
    {
        title: 'Facebook Chat',
        href: route('studentChat.index', props.student.id),
    },
    {
        title: 'WhatsApp Chat',
        href: route('studentWhatsapp.index', props.student.id),
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
        case 4:
            return { id: '4', text: 'Achieved', color: 'bg-gray-500 text-white' };
        default:
            return { id: null, text: 'Pending', color: 'bg-red-800 text-white' };
    }
};

const sidebarNavItems = computed<NavItem[]>(() => {
    if (!props.student) return [];

    if (props.student.status === null) {
        return pending;
    } else if (props.student.status === 1) {
        return lead;
    } else if (props.student.status === 2) {
        return prospect;
    } else if (props.student.status === 3) {
        return onBoard;
    } else {
        return archive;
    }
});

const page = usePage();
const currentUrl = computed(() => page.url);

const isActiveTab = (href: string) => {
    const clean = href.split('?')[0];
    return currentUrl.value.startsWith(clean);
};

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

const showTransfer = ref(false);
const Transferform = useForm({
    details: '',
});
const updateTransfer = (studentId: number) => {
    if (confirm('Transfer this student?')) showTransfer.value = true;
};
const submitTransfer = () => {
    if (!Transferform.details) {
        toast('error', {
            description: 'Please write student transfer details before submitting.',
        });
        return;
    }
    Transferform.post(
        route('approval.studentTransfer', {
            student: props.student.id,
        }),
        {
            onSuccess: () => {
                const flash = usePage().props.flash;

                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
                if (flash?.error) {
                    toast('error', {
                        description: flash.error,
                    });
                    return;
                }

                setTimeout(() => {
                    showDialog.value = false;
                    assaignform.reset();
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
                    description: 'Student transfer request send error',
                });
            },
        },
    );
};

const showArchive = ref(false);
const archiveform = useForm({
    details: '',
});
const updateArchive = (studentId: number, status: number) => {
    if (!confirm(status === 4 ? 'Archive this student?' : 'Restore this student?')) return;
    showArchive.value = true;
};

const submitArchive = () => {
    if (!archiveform.details) {
        toast('error', {
            description: 'Please write archive details before submitting.',
        });
        return;
    }
    archiveform.post(
        route('approval.studentArchive', {
            student: props.student.id,
        }),
        {
            onSuccess: () => {
                const flash = usePage().props.flash;

                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
                if (flash?.error) {
                    toast('error', {
                        description: flash.error,
                    });
                    return;
                }

                setTimeout(() => {
                    showDialog.value = false;
                    assaignform.reset();
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
                    description: 'Student archive request send error',
                });
            },
        },
    );
};

const assaignform = useForm({
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
    assaignform.post(route('studentActivities.updateAssignee', { student: props.student.id }), {
        onSuccess: () => {
            const flash = usePage().props.flash;

            if (flash?.success) {
                toast('success', {
                    description: flash.success,
                });
            }
            if (flash?.error) {
                toast('error', {
                    description: flash.error,
                });
                return;
            }
            setTimeout(() => {
                showDialog.value = false;
                assaignform.reset();
                router.visit(route('studentActivities.index', [props.student.id]), {
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
            showDialog.value = false;
            form.reset();
        },
        onError: () => {
            const flash = usePage().props.flash;
            toast('error', {
                description: flash.error,
            });
        },
    });
};

const showonBoard = ref(false);
const onBoardform = useForm({
    details: '',
});
const updateonBoard = (studentId: number) => {
    if (confirm('onBoard this student?')) showonBoard.value = true;
};
const submitonBoard = () => {
    if (!onBoardform.details) {
        toast('error', {
            description: 'Please write student onBoard details before submitting.',
        });
        return;
    }
    onBoardform.post(
        route('approval.studentOnBoard', {
            student: props.student.id,
        }),
        {
            onSuccess: () => {
                const flash = usePage().props.flash;

                if (flash?.success) {
                    toast('success', {
                        description: flash.success,
                    });
                }
                if (flash?.error) {
                    toast('error', {
                        description: flash.error,
                    });
                    return;
                }

                setTimeout(() => {
                    showonBoard.value = false;
                    onBoardform.reset();
                    router.visit(route('studentActivities.index', [props.student.id]), {
                        preserveScroll: true,
                        preserveState: false,
                    });
                }, 200);
                showonBoard.value = false;
                onBoardform.reset();
            },
            onError: () => {
                toast('Validation Error', {
                    description: 'Student onBoard request send error',
                });
            },
        },
    );
};

export interface Student {
    id: number;
    student_id: string;
    fname: string;
    lname: string;
    dateofbirth: string;
    gender: number;
    email: string;
    phone: string;
    ename: string;
    ephone: string;
}

const form = useForm({
    student_id: '',
    fname: '',
    lname: '',
    dateofbirth: '',
    gender: '',
    email: '',
    phone: '',
    ename: '',
    ephone: '',
    contactpre: '',
});

const dob = ref<string | null>(null);
const maxDate = today(getLocalTimeZone());

watch(dob, (newDate) => {
    if (newDate instanceof Date && !isNaN(newDate.getTime())) {
        form.dateofbirth = newDate.toISOString().split('T')[0];
    }
});

const editDialog = ref(false);

const editDialogCreate = async () => {
    await fetchStudent();
    editDialog.value = true;
};

const fetchStudent = async () => {
    try {
        const url = route('student.edit', {
            student: props.student.id,
        });
        const res = await fetch(url);
        const data = await res.json();
        if (data.success) {
            form.fname = data.student.fname;
            form.lname = data.student.lname;
            form.gender = String(data.student.gender);
            form.email = data.student.email;
            form.phone = data.student.phone;
            form.ename = data.student.ename;
            form.ephone = data.student.ephone;
            form.contactpre = data.student.contactpre;
            if (data.student.dateofbirth) {
                dob.value = new Date(data.student.dateofbirth);
            }
        }
    } catch (error) {
        console.error('Error loading data:', error);
    }
};

const updateStudent = () => {
    form.put(
        route('student.update', {
            student: props.student.id,
        }),

        {
            preserveState: true,
            onSuccess: () => {
                const flash = usePage().props.flash;
                if (flash.message) {
                    toast('Success', {
                        description: flash.message,
                    });
                }
                editDialog.value = false;
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                toast.error(firstError as string);
            },
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="relative min-h-screen flex-1 bg-gray-50 dark:bg-gray-950">
            <!-- Page Header -->
            <div class="border-b border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-900 dark:text-gray-100">Student Activities</h1>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Manage student activities and account settings</p>
                    </div>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="goToStudent"
                        class="flex items-center gap-1.5 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        <CornerDownLeft class="h-3.5 w-3.5" />
                        Back to Students
                    </Button>
                </div>
            </div>

            <div class="flex flex-col gap-4 p-4 lg:flex-row lg:items-start">
                <!-- LEFT SIDEBAR -->
                <StudentSidebar
                    :student="student"
                    :getStatusText="getStatusText"
                    :updateArchive="updateArchive"
                    :updateRate="updateRate"
                    :editStudent="editDialogCreate"
                    :showDailogCreate="showDailogCreate"
                    :updateTransfer="updateTransfer"
                    :updateonBoard="updateonBoard"
                    :studentService="studentService"
                />

                <!-- MAIN CONTENT -->
                <main class="flex min-w-0 flex-1 flex-col gap-4">
                    <!-- Navigation Tabs -->
                    <nav class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex flex-wrap gap-1 p-2">
                            <Link
                                v-for="item in sidebarNavItems"
                                :key="item.href"
                                :href="item.href"
                                class="relative rounded-lg px-3.5 py-2 text-sm font-medium transition-all duration-150"
                                :class="{
                                    'bg-indigo-600 text-white shadow-sm': isActiveTab(item.href),
                                    'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200':
                                        !isActiveTab(item.href),
                                }"
                            >
                                {{ item.title }}
                            </Link>
                        </div>
                    </nav>

                    <!-- Content Card -->
                    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <slot />
                    </section>
                </main>
            </div>
        </div>
        <Dialog v-model:open="showDialog">
            <DialogContent class="max-w-206.25">
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
                        <Select v-model="assaignform.user_id">
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
                    <Button :disabled="assaignform.processing" @click="updateAssignee">
                        <template v-if="assaignform.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="showTransfer">
            <DialogContent class="max-w-206.25">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle> Lead Transfer Request </DialogTitle>
                    <DialogDescription> Submit transfer request. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">why doing you transfer this student ? <span class="text-red-500">*</span></Label>
                        <Textarea v-model="Transferform.details" id="paddress" placeholder="Please write details" />
                        <span v-if="Transferform.errors.details" class="text-sm text-red-600">{{ Transferform.errors.details }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="Transferform.processing" @click="submitTransfer">
                        <template v-if="Transferform.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="editDialog">
            <DialogContent class="max-h-[90vh] max-w-206.25 overflow-y-auto bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
                <!-- Header -->
                <DialogHeader class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <DialogTitle class="text-xl font-bold text-gray-900 dark:text-white">Student Information Update</DialogTitle>
                    <DialogDescription class="text-gray-600 dark:text-gray-400"
                        >Update student details and click save when you're done.</DialogDescription
                    >
                </DialogHeader>

                <!-- Main Form Content -->
                <div class="space-y-8 py-4">
                    <!-- Personal Details -->
                    <section class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-6 flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-white">
                            <User class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            Personal Details
                        </h2>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                            <!-- Personal Info Fields -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:col-span-3">
                                <div class="space-y-2">
                                    <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        First Name <span class="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        v-model="form.fname"
                                        type="text"
                                        placeholder="Enter first name"
                                        class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                    />
                                    <span v-if="form.errors.fname" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.fname }}</span>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Last Name <span class="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        v-model="form.lname"
                                        placeholder="Enter last name"
                                        type="text"
                                        class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                    />
                                    <span v-if="form.errors.lname" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.lname }}</span>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Date Of Birth <span class="text-red-500">*</span>
                                    </Label>
                                    <VueDatePicker
                                        v-model="dob"
                                        :max-date="maxDate"
                                        :format="'yyyy-MM-dd'"
                                        :enable-time-picker="false"
                                        placeholder="Select date of birth"
                                        auto-apply
                                        class="w-full"
                                        :dark="isDarkMode"
                                    />
                                    <span v-if="form.errors.dateofbirth" class="text-sm text-red-600 dark:text-red-400">{{
                                        form.errors.dateofbirth
                                    }}</span>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Gender <span class="text-red-500">*</span>
                                    </Label>
                                    <Select v-model="form.gender">
                                        <SelectTrigger
                                            class="w-full border-gray-300 bg-white text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        >
                                            <SelectValue placeholder="Select Gender" />
                                        </SelectTrigger>
                                        <SelectContent class="border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-700">
                                            <SelectGroup>
                                                <SelectItem value="1" class="text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600"
                                                    >Male</SelectItem
                                                >
                                                <SelectItem value="2" class="text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600"
                                                    >Female</SelectItem
                                                >
                                                <SelectItem value="3" class="text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-600"
                                                    >Other</SelectItem
                                                >
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <span v-if="form.errors.gender" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.gender }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Contact Details -->
                    <section class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-6 flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-white">
                            <Phone class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            Contact Details
                        </h2>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <div class="space-y-2">
                                <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</Label>
                                <Input
                                    v-model="form.email"
                                    placeholder="Enter email address"
                                    type="email"
                                    class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Phone Number <span class="text-red-500">*</span>
                                </Label>
                                <Input
                                    v-model="form.phone"
                                    placeholder="Enter phone number"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                                <span v-if="form.errors.phone" class="text-sm text-red-600 dark:text-red-400">{{ form.errors.phone }}</span>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact Preference</Label>
                                <RadioGroup class="flex flex-col space-y-3" v-model="form.contactpre">
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem value="0" id="contact-email" class="text-indigo-600 dark:text-indigo-400" />
                                        <Label for="contact-email" class="text-sm text-gray-700 dark:text-gray-300">Email</Label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem value="1" id="contact-phone" class="text-indigo-600 dark:text-indigo-400" />
                                        <Label for="contact-phone" class="text-sm text-gray-700 dark:text-gray-300">Phone</Label>
                                    </div>
                                </RadioGroup>
                            </div>
                        </div>
                    </section>

                    <!-- Emergency Contact -->
                    <section class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-6 flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-white">
                            <AlertCircle class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            Emergency Contact Details
                        </h2>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Name</Label>
                                <Input
                                    v-model="form.ename"
                                    placeholder="Enter emergency contact name"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label class="text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Phone Number</Label>
                                <Input
                                    v-model="form.ephone"
                                    placeholder="Enter emergency phone number"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <div class="flex w-full flex-col-reverse gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <DialogClose as-child>
                            <Button
                                type="button"
                                variant="outline"
                                class="w-full border-gray-300 text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                            >
                                Cancel
                            </Button>
                        </DialogClose>

                        <Button
                            :disabled="form.processing"
                            @click="updateStudent"
                            class="w-full bg-indigo-600 text-white hover:bg-indigo-700 sm:w-auto dark:bg-indigo-700 dark:hover:bg-indigo-600"
                        >
                            <template v-if="form.processing">
                                <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                Saving Changes...
                            </template>
                            <template v-else>
                                <Save class="mr-2 h-4 w-4" />
                                Update
                            </template>
                        </Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="showonBoard">
            <DialogContent class="max-w-206.25">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle> Student onBoard Request </DialogTitle>
                    <DialogDescription> Submit onBoard request. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">why doing you onBoard this student ? <span class="text-red-500">*</span></Label>
                        <Textarea v-model="onBoardform.details" id="paddress" placeholder="Please write details" />
                        <span v-if="onBoardform.errors.details" class="text-sm text-red-600">{{ onBoardform.errors.details }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="onBoardform.processing" @click="submitonBoard">
                        <template v-if="onBoardform.processing">
                            Saving...
                            <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                        </template>
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="showArchive">
            <DialogContent class="max-w-206.25">
                <!-- Header -->
                <DialogHeader>
                    <DialogTitle> Archive Request </DialogTitle>
                    <DialogDescription> Submit archive request. Click save when you're done. </DialogDescription>
                </DialogHeader>

                <!-- Body -->
                <div class="grid gap-6">
                    <!-- Select Document Type -->
                    <div class="grid gap-2">
                        <Label for="doctype">why doing you archive this student ? <span class="text-red-500">*</span></Label>
                        <Textarea v-model="archiveform.details" id="paddress" placeholder="Please write details" />
                        <span v-if="archiveform.errors.details" class="text-sm text-red-600">{{ archiveform.errors.details }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <DialogFooter class="mt-6 flex items-center justify-between">
                    <!-- Close Left -->
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Close</Button>
                    </DialogClose>

                    <!-- Submit Right -->
                    <Button :disabled="archiveform.processing" @click="submitArchive">
                        <template v-if="archiveform.processing">
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
