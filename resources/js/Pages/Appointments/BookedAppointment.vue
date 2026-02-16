<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue'; // Import watch to sync ID
import { CalendarIcon, ClockIcon, UserIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    doctor: Object, // Changed from 'doctors: Array' to 'doctor: Object'
});

const emit = defineEmits(['close']);

const form = useForm({
    doctor_id: props.doctor?._id || props.doctor?.id || '',
    date: '',
    time: '',
    notes: '',
});

// Watch for modal opening to ensure the correct doctor ID is set
watch(() => props.show, (isShowing) => {
    if (isShowing) {
        form.doctor_id = props.doctor?._id || props.doctor?.id;
    }
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
        <div class="relative bg-slate-50 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
            <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Book Appointment</h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">Schedule your next consultation</p>
                </div>
                <button @click="closeModal" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-xl transition-all shadow-sm">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-8 pt-2 space-y-5 bg-white/50 backdrop-blur-sm rounded-t-[2.5rem]">
                
                <div class="space-y-1.5">
                    <InputLabel value="Selected Specialist" class="ml-2 text-slate-700 font-bold text-xs uppercase tracking-wider" />
                    <div class="flex items-center gap-3 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl">
                        <div class="h-10 w-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                            {{ doctor.doctor_profile?.firstName?.charAt(0) || doctor.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-indigo-900">
                                Dr. {{ doctor.doctor_profile?.firstName }} {{ doctor.doctor_profile?.lastName }}
                            </p>
                            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-tight">
                                {{ doctor.doctor_profile?.expertise || 'General Practice' }}
                            </p>
                        </div>
                    </div>
                    <input type="hidden" v-model="form.doctor_id" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <InputLabel for="date" value="Date" class="ml-2 text-slate-700 font-bold text-xs uppercase tracking-wider" />
                        <TextInput
                            id="date"
                            type="date"
                            v-model="form.date"
                            class="block w-full border-slate-100 rounded-2xl bg-white shadow-sm"
                            required
                        />
                        <InputError :message="form.errors.date" />
                    </div>
                    <div class="space-y-1.5">
                        <InputLabel for="time" value="Time" class="ml-2 text-slate-700 font-bold text-xs uppercase tracking-wider" />
                        <TextInput
                            id="time"
                            type="time"
                            v-model="form.time"
                            class="block w-full border-slate-100 rounded-2xl bg-white shadow-sm"
                            required
                        />
                        <InputError :message="form.errors.time" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <InputLabel for="notes" value="Reason for Visit" class="ml-2 text-slate-700 font-bold text-xs uppercase tracking-wider" />
                    <textarea
                        id="notes"
                        v-model="form.notes"
                        rows="3"
                        placeholder="Briefly describe your symptoms..."
                        class="block w-full border-slate-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl transition-all bg-white shadow-sm p-4 text-sm"
                    ></textarea>
                    <InputError :message="form.errors.notes" />
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button 
                        type="button" 
                        @click="closeModal"
                        class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all active:scale-95"
                    >
                        Cancel
                    </button>
                    <PrimaryButton
                        class="flex-[2] justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-100 rounded-2xl text-base font-black uppercase tracking-tight"
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