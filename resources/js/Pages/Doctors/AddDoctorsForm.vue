<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const openForm = ref(false);

const formOpen = () => (openForm.value = true);

const closeModal = () => {
    openForm.value = false;
    form.reset();
};

const form = useForm({
  name: '',
  email: '',
  password: ''
});

const submitForm = () => {
    form.post('/doctor', {
        onSuccess: () => {
        closeModal();
        form.clearErrors();
        form.reset();
    },
    });
};
</script>

<template>
    <section class="space-y-6">
        <PrimaryButton @click="formOpen">Add Doctor</PrimaryButton>

        <Modal :show="openForm" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-bold mb-4">Add Doctor</h2>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div class="flex gap-2">
                        <div>
                            <InputLabel for="name" value="User Name" />
                            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" />
                            <InputError :message="form.errors.email" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" v-model="form.password" class="mt-1 block w-full" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-4 space-x-2">
                        <PrimaryButton type="button" @click="closeModal" class="bg-gray-300 text-black">Cancel
                        </PrimaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </section>
</template>
