<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    doctor: {
        type: Object,
        default: () => ({})
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    firstName: props.doctor?.firstName ?? '',
    lastName: props.doctor?.lastName ?? '',
    age: props.doctor?.age ?? '',
    gender: props.doctor?.gender ?? '',
    address: props.doctor?.address ?? '',
    expertise: props.doctor?.expertise ?? '',
});

const submitForm = () => {
    if (props.doctor && props.doctor.id) {
        form.put(route('doctor.update', props.doctor.id), {
            onSuccess: () => closeModal?.(),
            preserveScroll: true,
        });
    } else {
        form.post(route('doctor.store'), {
            onSuccess: () => closeModal?.(),
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <section class="max-w-4xl">
        <header class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-1 bg-indigo-600 rounded-full"></div>
                <h2 class="text-xl font-bold text-slate-800">Profile Information</h2>
            </div>
            <p class="text-sm text-slate-500 ml-4">
                Manage your professional identity and account contact details.
            </p>
        </header>

        <form @submit.prevent="submitForm" class="space-y-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Personal Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <InputLabel for="firstName" value="First Name" class="text-slate-700 font-semibold" />
                        <TextInput id="firstName" type="text" v-model="form.firstName"
                            class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                            required autofocus />
                        <InputError :message="form.errors.firstName" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="lastName" value="Last Name" class="text-slate-700 font-semibold" />
                        <TextInput id="lastName" type="text" v-model="form.lastName"
                            class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                            required />
                        <InputError :message="form.errors.lastName" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="age" value="Age" class="text-slate-700 font-semibold" />
                        <TextInput id="age" type="number" v-model="form.age"
                            class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all" />
                        <InputError :message="form.errors.age" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="gender" value="Gender" class="text-slate-700 font-semibold" />
                        <select id="gender" v-model="form.gender"
                            class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all py-2 px-3 text-slate-700">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <InputError :message="form.errors.gender" />
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Professional Info</h3>

                <div class="space-y-6">
                    <div class="space-y-1.5">
                        <InputLabel for="expertise" value="Medical Expertise / Specialization"
                            class="text-slate-700 font-semibold" />
                        <TextInput id="expertise" type="text" v-model="form.expertise"
                            placeholder="e.g. Cardiology, Pediatrics"
                            class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all" />
                        <InputError :message="form.errors.expertise" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="address" value="Clinic/Home Address" class="text-slate-700 font-semibold" />
                        <TextInput id="address" type="text" v-model="form.address"
                            class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all" />
                        <InputError :message="form.errors.address" />
                    </div>

                </div>

                <div v-if="mustVerifyEmail && user.email_verified_at === null"
                    class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <p class="text-sm text-amber-800 flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        Your email address is unverified.
                        <Link :href="route('verification.send')" method="post" as="button"
                            class="font-bold underline hover:text-amber-900 transition-colors">
                        Click here to re-send.
                        </Link>
                    </p>
                    <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                        A new verification link has been sent!
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 p-2">
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-600 flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Changes Saved
                    </p>
                </Transition>

                <PrimaryButton :disabled="form.processing"
                class="bg-indigo-600 hover:bg-indigo-700 px-8 py-2.5 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                Save Profile
            </PrimaryButton>
        </div>
    </form>
</section>
</template>
