<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Create Account</h1>
            <p class="text-slate-500 mt-1">Join our medical platform today</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-1.5">
                <InputLabel for="name" value="Full Name" class="text-slate-700 font-semibold ml-1" />
                <TextInput id="name" type="text"
                    class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                    v-model="form.name" required autofocus placeholder="John Doe" autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="space-y-1.5">
                <InputLabel for="email" value="Email Address" class="text-slate-700 font-semibold ml-1" />
                <TextInput id="email" type="email"
                    class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                    v-model="form.email" required placeholder="name@example.com" autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-1.5">
                <InputLabel for="password" value="Password" class="text-slate-700 font-semibold ml-1" />
                <TextInput id="password" type="password"
                    class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                    v-model="form.password" required placeholder="••••••••" autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="space-y-1.5">
                <InputLabel for="password_confirmation" value="Confirm Password"
                    class="text-slate-700 font-semibold ml-1" />
                <TextInput id="password_confirmation" type="password"
                    class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all pl-4 py-3"
                    v-model="form.password_confirmation" required placeholder="••••••••" autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-4">
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
                    Create Account
                </PrimaryButton>
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-slate-500">
                    Already have an account?
                    <Link :href="route('login')"
                        class="font-bold text-indigo-600 hover:text-indigo-700 underline underline-offset-4 decoration-2">
                    Sign In
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>