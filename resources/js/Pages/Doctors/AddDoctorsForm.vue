<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { UserPlusIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const openForm = ref(false);
const formOpen = () => (openForm.value = true);

const closeModal = () => {
    openForm.value = false;
    form.reset();
    form.clearErrors();
};

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'doctor'
});

const submitForm = () => {
    form.post('register/doctor', {
        onSuccess: () => closeModal(),
        preserveScroll: true,
    });
};
</script>

<template>
    <section>
        <PrimaryButton @click="formOpen"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition-all py-2.5">
            <UserPlusIcon class="h-5 w-5" />
            Add New Doctor
        </PrimaryButton>

        <Modal :show="openForm" @close="closeModal" maxWidth="md">
            <div class="overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Create Doctor Account</h2>
                        <p class="text-sm text-slate-500 mt-1">Fill in the details to register a new medical professional.
                        </p>
                    </div>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <XMarkIcon class="h-6 w-6" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-8 space-y-5">
                    <div class="space-y-1.5">
                        <InputLabel for="name" value="Full Name" class="text-slate-700 font-semibold ml-0.5" />
                        <div class="relative">
                            <TextInput id="name" v-model="form.name" placeholder="Dr. John Doe"
                                class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all" />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="email" value="Professional Email" class="text-slate-700 font-semibold ml-0.5" />
                        <TextInput id="email" type="email" v-model="form.email" placeholder="doctor@clinic.com"
                            class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="space-y-1.5">
                        <InputLabel for="password" value="Temporary Password" class="text-slate-700 font-semibold ml-0.5" />
                        <TextInput id="password" type="password" v-model="form.password" placeholder="••••••••"
                            class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all" />
                        <p class="text-[11px] text-slate-400 mt-1 italic">The doctor will be prompted to change this upon
                            first login.</p>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                        <button type="button" @click="closeModal"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 px-8 py-2.5 rounded-xl shadow-lg shadow-indigo-100">
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Register Doctor</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </section>
</template>