<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';

import { Badge } from '@/components/ui/badge';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, Trash } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

export interface Partner {
    id: number;
    name: string;
    brn: string;
    email: string;
    fax: string;
    website: string;
    photo: string;
    active: number;
}

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Partner', href: '/partner' }];

const props = defineProps<{
    pertners: Partner[];
}>();

const data = props.pertners;

const toggleStatus = (partner: Partner) => {
    const newStatus = !Boolean(partner.active); // boolean
    router.put(
        route('partner.updateStatus', partner.id),
        { active: newStatus ? 1 : 0 }, // server expects number
        {
            preserveState: true,
            onSuccess: () => {
                partner.active = newStatus ? 1 : 0; // local update (number)
                toast.success('Partner  status update');
            },
        },
    );
};

const goToPartnerCreate = () => {
    router.visit('/partner/create');
};

const colors = [
    'bg-blue-500',
    'bg-green-500',
    'bg-purple-500',
    'bg-purple-700',
    'bg-pink-500',
    'bg-indigo-500',
    'bg-teal-500',
    'bg-yellow-400',
    'bg-yellow-700',
];

function getAvatarColor(name: string) {
    if (!name) return colors[0];
    const index = name.charCodeAt(0) % colors.length;
    return colors[index];
}
</script>

<template>
    <Head title="Partner" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 border px-4 md:min-h-min">
            <div class="flex items-center justify-end space-x-2 py-4">
                <div class="flex-1 text-sm">
                    <Button variant="outline" size="sm" @click="goToPartnerCreate"><Plus></Plus> Create Partner </Button>
                </div>
                <div class="space-x-2"></div>
            </div>
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Workflow</TableHead>
                            <TableHead>Partner Type</TableHead>
                            <TableHead>Country</TableHead>
                            <TableHead>State</TableHead>
                            <TableHead>City</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(partner, index) in data" :key="partner.id ?? index">
                            <TableCell>
                                <Link :href="route('PartnerActivities.application', partner.id)" method="get" class="flex items-center space-x-2">
                                    <template v-if="partner.photo">
                                        <img
                                            :src="`/storage/partner/${partner.photo}`"
                                            alt="Profile"
                                            class="h-10 w-10 rounded-full object-cover shadow-md"
                                        />
                                    </template>
                                    <template v-else>
                                        <span
                                            :class="[
                                                'flex h-10 w-10 items-center justify-center rounded-full text-lg font-semibold text-white shadow-md',
                                                getAvatarColor(partner.name),
                                            ]"
                                        >
                                            {{ (partner.name?.charAt(0) ?? '').toUpperCase() }}
                                        </span>
                                    </template>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ partner.name }}</span>
                                </Link>
                            </TableCell>
                            <TableCell>
                                <Badge class="m-0.5 p-1" variant="outline" v-for="(wf, idx) in partner.workflow_names" :key="idx">
                                    {{ wf }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ partner.partnertype.partnertypename }}</TableCell>
                            <TableCell>{{ partner.state.country?.name }}</TableCell>
                            <TableCell>{{ partner.state.name }}</TableCell>
                            <TableCell>{{ partner.city?.name }}</TableCell>
                            <TableCell>
                                <Switch v-model="partner.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(partner)"> </Switch>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(partner.id)"><Trash></Trash></Button>
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
    </AppLayout>
</template>
