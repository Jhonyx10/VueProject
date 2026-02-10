<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
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
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Welcome Back</h1>
            <p class="text-slate-500 mt-1">Enter your credentials to access your account</p>
        </div>

        <div v-if="status"
            class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl text-sm font-medium text-green-600 text-center">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-1.5">
                <InputLabel for="email" value="Email Address" class="text-slate-700 font-semibold ml-1" />
                <div class="relative">
                    <TextInput id="email" type="email"
                        class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                        v-model="form.email" required autofocus placeholder="doctor@example.com" autocomplete="username" />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center px-1">
                    <InputLabel for="password" value="Password" class="text-slate-700 font-semibold" />
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="text-xs font-bold text-indigo-600 hover:text-indigo-700 underline underline-offset-4">
                    Forgot?
                    </Link>
                </div>
                <TextInput id="password" type="password"
                    class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                    v-model="form.password" required placeholder="••••••••" autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer group">
                    <Checkbox name="remember" v-model:checked="form.remember"
                        class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="ms-2 text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Remember
                        me</span>
                </label>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full flex justify-center items-center py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }" :disabled="form.processing">
                    <span v-if="form.processing" class="mr-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                    Sign In
                </PrimaryButton>
            </div>

            <p class="text-center text-sm text-slate-500">
                Don't have an account?
                <Link :href="route('register')" class="font-bold text-indigo-600 hover:underline">Create Account</Link>
            </p>
        </form>
    </GuestLayout>
</template>