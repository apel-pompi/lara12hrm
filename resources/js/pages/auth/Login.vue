<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase>
        <Head title="Log in" />
        <div
            class="flex min-h-[80vh] items-center justify-center dark:from-gray-900 dark:via-gray-800 dark:to-gray-900"
        >
            <Card class="w-full max-w-md rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <CardHeader class="pb-0">
                    <CardTitle class="text-center text-2xl font-bold text-gray-800 dark:text-gray-100"> Log in to your account </CardTitle>
                    <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">Enter your credentials below to access your dashboard</p>
                    <div v-if="status" class="mt-3 text-center text-sm font-medium text-green-600">
                        {{ status }}
                    </div>
                </CardHeader>
                <CardContent class="p-6">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label for="email">Email or Username</Label>
                            <Input
                                id="email"
                                type="login"
                                required
                                autofocus
                                :tabindex="1"
                                v-model="form.login"
                                placeholder="username or email"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.login" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <Label for="password">Password</Label>
                                <TextLink
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm text-indigo-600 hover:underline dark:text-indigo-400"
                                    :tabindex="5"
                                >
                                    Forgot password?
                                </TextLink>
                            </div>
                            <Input
                                id="password"
                                type="password"
                                required
                                :tabindex="2"
                                autocomplete="current-password"
                                v-model="form.password"
                                placeholder="Enter your password"
                                class="mt-1"
                            />
                            <InputError :message="form.errors.password" />
                        </div>
                        <div class="flex items-center justify-between">
                            <Label for="remember" class="flex items-center space-x-2">
                                <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
                                <span class="text-sm text-gray-600 dark:text-gray-300">Remember me</span>
                            </Label>
                        </div>
                        <Button
                            type="submit"
                            class="mt-4 w-full rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring focus:ring-indigo-300 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                            :tabindex="4"
                            :disabled="form.processing"
                        >
                            <LoaderCircle v-if="form.processing" class="mr-2 inline-block h-4 w-4 animate-spin" />
                            Log in
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </div>
        
    </AuthBase>
</template>
