<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Company Information', href: '/company' }];

import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Briefcase, Building, Mail, MapPin, Phone } from 'lucide-vue-next';

import ImageUpload from '@/components/ImageUpload.vue';
import { toast } from 'vue-sonner';

export interface Company {
    id: number;
    companyname: string;
    address_one: string;
    address_two: string;
    company_phone: string;
    company_email: string;
    companylogo: any;
}

const props = defineProps<{
    company: Company;
}>();

const data = props.company;
const currentImage = data.companylogo ? `/storage/company/${data.companylogo}` : null;
const form = useForm({
    id: data.id,
    companyname: data.companyname,
    address_one: data.address_one,
    address_two: data.address_two,
    company_phone: data.company_phone,
    company_email: data.company_email,
    companylogo: data.companylogo,
});

const submit = () => {
    const action = route('company.update', form.id);

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('companyname', form.companyname);
    formData.append('address_one', form.address_one);
    formData.append('address_two', form.address_two);
    formData.append('company_phone', form.company_phone);
    formData.append('company_email', form.company_email);

    if (form.companylogo instanceof File) {
        formData.append('companylogo', form.companylogo);
    }

    router.post(action, formData, {
        onSuccess: () => {
            toast('Success', {
                description: 'Company updated successfully',
            });

            setTimeout(() => {
                form.reset();
                router.visit(route('company.index').toString(), {
                    only: ['company'],
                    preserveScroll: true,
                    preserveState: false,
                });
            }, 200);
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast('Validation Error', {
                description: firstError,
            });
        },
    });
};
</script>

<template>
    <Head title="Company Information" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl bg-gray-50 p-6">
            <div class="grid auto-rows-min gap-6 md:grid-cols-2">
                <!-- Company Information Card -->
                <Card class="border border-gray-200 shadow-sm">
                    <CardHeader class="border-b border-gray-200 pb-4">
                        <div class="flex items-center gap-3">
                            <Building class="h-6 w-6 text-blue-600" />
                            <div>
                                <CardTitle class="text-xl font-semibold text-gray-800">Company Information</CardTitle>
                                <CardDescription class="text-sm text-gray-500">Update your company details</CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="pt-4">
                        <div class="grid gap-5">
                            <!-- Company Name -->
                            <div class="space-y-1">
                                <Label for="companyname" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                    Company Name <span class="text-red-500">*</span>
                                </Label>
                                <div class="relative">
                                    <Briefcase class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <Input
                                        id="companyname"
                                        class="w-full rounded-lg border-gray-300 py-2.5 pr-3 pl-10 transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Acme Corporation"
                                        v-model="form.companyname"
                                        :disabled="form.processing"
                                    />
                                </div>
                                <span v-if="form.errors.companyname" class="text-xs text-red-600">{{ form.errors.companyname }}</span>
                            </div>
                            <!-- Address Line 1 -->
                            <div class="space-y-1">
                                <Label for="address_one" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                    Company Address (Line 1) <span class="text-red-500">*</span>
                                </Label>
                                <div class="relative">
                                    <MapPin class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <Input
                                        id="address_one"
                                        class="w-full rounded-lg border-gray-300 py-2.5 pr-3 pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="123 Business Ave"
                                        v-model="form.address_one"
                                        :disabled="form.processing"
                                    />
                                </div>
                                <span v-if="form.errors.address_one" class="text-xs text-red-600">{{ form.errors.address_one }}</span>
                            </div>
                            <!-- Address Line 2 -->
                            <div class="space-y-1">
                                <Label for="address_two" class="text-sm font-medium text-gray-700">
                                    Company Address (Line 1) <span class="text-red-500">*</span>
                                </Label>
                                <div class="relative">
                                    <MapPin class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <Input
                                        id="address_two"
                                        class="w-full rounded-lg border-gray-300 py-2.5 pr-3 pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="Suite 456"
                                        v-model="form.address_two"
                                        :disabled="form.processing"
                                    />
                                </div>
                                <span v-if="form.errors.address_two" class="text-xs text-red-600">{{ form.errors.address_two }}</span>
                            </div>
                            <!-- Phone Number -->
                            <div class="space-y-1">
                                <Label for="company_phone" class="text-sm font-medium text-gray-700">
                                    Company Phone <span class="text-red-500">*</span>
                                </Label>
                                <div class="relative">
                                    <Phone class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <Input
                                        id="company_phone"
                                        class="w-full rounded-lg border-gray-300 py-2.5 pr-3 pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="+1 (555) 123-4567"
                                        v-model="form.company_phone"
                                        :disabled="form.processing"
                                    />
                                </div>
                                <span v-if="form.errors.company_phone" class="text-xs text-red-600">{{ form.errors.company_phone }}</span>
                            </div>
                            <!-- Phone Number -->
                            <div class="space-y-1">
                                <Label for="company_email" class="text-sm font-medium text-gray-700">
                                    Company Email <span class="text-red-500">*</span>
                                </Label>
                                <div class="relative">
                                    <Mail class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <Input
                                        id="company_email"
                                        class="w-full rounded-lg border-gray-300 py-2.5 pr-3 pl-10 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        placeholder="info@acme.com"
                                        v-model="form.company_email"
                                        :disabled="form.processing"
                                    />
                                </div>
                                <span v-if="form.errors.company_email" class="text-xs text-red-600">{{ form.errors.company_email }}</span>
                            </div>
                            <div class="space-y-1">
                                <Label for="companylogo" class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                    Company Logo <span class="text-red-500">*</span>
                                </Label>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <ImageUpload
                                            @image="(file) => (form.companylogo = file)"
                                            :Image="data.companylogo"
                                            :disabled="form.processing"
                                        />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Recommended size: 256x256px</p>
                                        <p class="text-xs text-gray-500">Max file size: 2MB</p>
                                    </div>
                                    <span v-if="form.errors.companylogo" class="text-xs text-red-600">{{ form.errors.companylogo }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="flex justify-end border-t border-gray-200 pt-4">
                        <Button class="bg-blue-600 px-6 py-2 text-white hover:bg-blue-700" :disabled="form.processing" @click="submit">
                            <span v-if="form.processing">Saving...</span>
                            <span v-else>Save Changes</span>
                        </Button>
                    </CardFooter>
                </Card>
                <!-- Company Details Card -->
                <Card class="border border-gray-200 shadow-sm">
                    <CardHeader class="border-b border-gray-200 pb-4">
                        <div class="flex items-center gap-3">
                            <InfoCircle class="h-6 w-6 text-blue-600" />
                            <div>
                                <CardTitle class="text-xl font-semibold text-gray-800">Company Details</CardTitle>
                                <CardDescription class="text-sm text-gray-500">View your company information</CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="pt-4">
                        <div class="space-y-6">
                            <!-- Company Overview -->
                            <div class="rounded-lg bg-blue-50/50 p-4">
                                <h3 class="mb-2 text-sm font-medium text-blue-800">Company Overview</h3>
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <Building class="mt-0.5 h-4 w-4 text-blue-600" />
                                        <div>
                                            <p class="text-xs text-gray-500">Company Name</p>
                                            <p class="text-sm font-medium text-gray-800">{{ data.companyname }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Contact Information -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <h3 class="mb-2 text-sm font-medium text-gray-800">Contact Information</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-3">
                                            <MapPin class="mt-0.5 h-4 w-4 text-gray-600" />
                                            <div>
                                                <p class="text-xs text-gray-500">Address</p>
                                                <p class="text-sm font-medium text-gray-800">{{ data.address_one }}</p>
                                                <p class="text-sm font-medium text-gray-800">{{ data.address_two }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <Phone class="mt-0.5 h-4 w-4 text-gray-600" />
                                            <div>
                                                <p class="text-xs text-gray-500">Phone</p>
                                                <p class="text-sm font-medium text-gray-800">{{ data.company_phone }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <Mail class="mt-0.5 h-4 w-4 text-gray-600" />
                                            <div>
                                                <p class="text-xs text-gray-500">Email</p>
                                                <p class="text-sm font-medium text-gray-800">{{ data.company_email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Company Logo Preview -->
                                <div class="rounded-lg bg-gray-50 p-4">
                                    <h3 class="mb-3 text-sm font-medium text-gray-800">Company Logo</h3>
                                    <div class="flex items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white p-6">
                                        <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-gray-200">
                                            <img
                                                :src="currentImage || '/storage/company/default.png'"
                                                alt="preview"
                                                class="object-cover object-center"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
