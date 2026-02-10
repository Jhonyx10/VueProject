<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { CalendarIcon, ClockIcon, UserIcon, ChatBubbleBottomCenterTextIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    doctors: Array, // Pass your list of doctors here
});

const emit = defineEmits(['close']);

const form = useForm({
    doctor_id: '',
    date: '',
    time: '',
    notes: '',
});

const submit = () => {
    form.post(route('appointments.store'), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};

const closeModal = () => {
    emit('close');
    form.clearErrors();
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <div class="relative bg-white rounded-[2rem] overflow-hidden">
            <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Book Appointment</h2>
                    <p class="text-sm text-slate-500 mt-1">Schedule your next consultation</p>
                </div>
                <button @click="closeModal" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-8 pt-2 space-y-5">
                <div class="space-y-1.5">
                    <InputLabel for="doctor" value="Select Doctor" class="ml-1 text-slate-700 font-bold" />
                    <select
                        id="doctor"
                        v-model="form.doctor_id"
                        class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl transition-all pl-4 py-3 bg-slate-50 text-sm"
                        required
                    >
                        <option value="" disabled>Choose a specialist...</option>
                        <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">
                            Dr. {{ doctor.doctor_profile?.firstName }} {{ doctor.doctor_profile?.lastName }} 
                            — {{ doctor.doctor_profile?.expertise || 'General Practice' }}
                        </option>
                    </select>
                    <InputError :message="form.errors.doctor_id" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <InputLabel for="date" value="Date" class="ml-1 text-slate-700 font-bold" />
                        <TextInput
                            id="date"
                            type="date"
                            v-model="form.date"
                            class="block w-full border-slate-200 rounded-2xl bg-slate-50"
                            required
                        />
                        <InputError :message="form.errors.date" />
                    </div>
                    <div class="space-y-1.5">
                        <InputLabel for="time" value="Time" class="ml-1 text-slate-700 font-bold" />
                        <TextInput
                            id="time"
                            type="time"
                            v-model="form.time"
                            class="block w-full border-slate-200 rounded-2xl bg-slate-50"
                            required
                        />
                        <InputError :message="form.errors.time" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <InputLabel for="notes" value="Reason for Visit (Optional)" class="ml-1 text-slate-700 font-bold" />
                    <textarea
                        id="notes"
                        v-model="form.notes"
                        rows="3"
                        placeholder="Briefly describe your symptoms..."
                        class="block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl transition-all bg-slate-50 p-4 text-sm"
                    ></textarea>
                    <InputError :message="form.errors.notes" />
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button 
                        type="button" 
                        @click="closeModal"
                        class="flex-1 px-6 py-3.5 border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all"
                    >
                        Cancel
                    </button>
                    <PrimaryButton
                        class="flex-[2] justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-100 rounded-2xl text-base"
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Confirm Booking</span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>