<script setup>
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    appointment_id: [String, Number]
});

const emit = defineEmits(['close']);

const form = useForm({
    action: 'cancel', 
    reason: '',      
});

const submitCancel = () => {
    form.patch(route('appointments.update', props.appointment_id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
    });
};

const closeModal = () => {
    emit('close');
    form.reset();
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800">Cancel Appointment</h2>
            <p class="text-sm text-slate-500 mt-1">
                Please provide a reason for cancelling this appointment.
            </p>

            <div class="mt-6">
                <InputLabel for="reason" value="Reason for Cancellation" />
                <TextInput
                    id="reason"
                    v-model="form.reason"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="e.g., Scheduling conflict"
                    required
                />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button @click="closeModal" class="px-4 py-2 text-slate-600 font-medium">
                    Keep Appointment
                </button>
                <button 
                    @click="submitCancel"
                    :disabled="form.processing"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl font-bold transition-all disabled:opacity-50"
                >
                    Confirm Cancellation
                </button>
            </div>
        </div>
    </Modal>
</template>