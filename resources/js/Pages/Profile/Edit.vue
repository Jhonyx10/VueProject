<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import ProfileAddUpdate from './Partials/ProfileAddUpdate.vue';
import { Head } from '@inertiajs/vue3';
import { useRole } from '@/composables/useRole';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    doctor: {
        type: Object,
    },
});

const {isDoctor, isUser} = useRole();

</script>

<template>
    <Head title="Account Settings" />

    <AuthenticatedLayout>
        <div class="py-10 bg-slate-50/50 min-h-screen">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

                <div 
                    v-motion
                    :initial="{ opacity: 0, y: -20 }"
                    :enter="{ opacity: 1, y: 0 }"
                    class="mb-8"
                >
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Account Settings</h1>
                    <p class="text-slate-500 text-sm">Manage your personal information, security, and professional profile.</p>
                </div>

                <div class="space-y-8">
                    <section
                        v-motion
                        :initial="{ opacity: 0, y: 30 }"
                        :enter="{ opacity: 1, y: 0, transition: { delay: 100 } }"
                        class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md"
                    >
                        <div class="p-6 sm:p-8">
                            <UpdateProfileInformationForm 
                                :must-verify-email="mustVerifyEmail" 
                                :status="status"
                                class="max-w-2xl" 
                            />
                        </div>
                    </section>

                    <section 
                        v-if="isDoctor"
                        v-motion
                        :initial="{ opacity: 0, scale: 0.95 }"
                        :enter="{ opacity: 1, scale: 1, transition: { delay: 200 } }"
                        class="relative"
                    >
                        <div class="absolute -left-2 top-0 bottom-0 w-1 bg-indigo-600 rounded-full hidden md:block"></div>
                    
                        <div 
                            class="bg-white border border-indigo-100 rounded-2xl shadow-sm overflow-hidden transition-all"
                            :class="{ 'ring-4 ring-indigo-50/50': !doctor, 'hover:shadow-md': doctor }"
                        >
                            <div class="bg-indigo-50/30 px-6 py-3 border-b border-indigo-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Medical Credentials</span>
                                </div>

                                <span 
                                    v-if="!doctor" 
                                    v-motion
                                    :initial="{ opacity: 1 }"
                                    :enter="{ 
                                        opacity: [1, 0.5, 1], 
                                        transition: { repeat: Infinity, duration: 2000 } 
                                    }"
                                    class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold uppercase"
                                >
                                    Profile Incomplete
                                </span>
                            </div>

                            <div class="p-6 sm:p-8">
                                <ProfileAddUpdate :doctor="doctor || {}" />
                            
                                <div 
                                    v-if="!doctor" 
                                    v-motion
                                    :initial="{ opacity: 0, height: 0 }"
                                    :enter="{ opacity: 1, height: 'auto', transition: { delay: 500 } }"
                                    class="mt-4 p-4 bg-slate-50 border border-dashed border-slate-200 rounded-xl overflow-hidden"
                                >
                                    <p class="text-xs text-slate-500 text-center">
                                        Please complete your professional profile to appear in the doctor directory.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section
                        v-motion
                        :initial="{ opacity: 0, y: 30 }"
                        :enter="{ opacity: 1, y: 0, transition: { delay: 300 } }"
                        class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md"
                    >
                        <div class="p-6 sm:p-8">
                            <UpdatePasswordForm class="max-w-2xl" />
                        </div>
                    </section>

                    <section 
                        v-motion
                        :initial="{ opacity: 0, y: 30 }"
                        :enter="{ opacity: 1, y: 0, transition: { delay: 400 } }"
                        :hover="{ x: [0, -2, 2, 0], transition: { duration: 0.3 } }"
                        class="bg-red-50/30 border border-red-100 rounded-2xl overflow-hidden"
                    >
                        <div class="p-6 sm:p-8">
                            <DeleteUserForm class="max-w-2xl" />
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
