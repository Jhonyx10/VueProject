<script setup>
import { router } from "@inertiajs/vue3";
import { ExclamationTriangleIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    appointmentId: String,
});

const emit = defineEmits(["close"]);

const confirmDelete = () => {
    router.delete(route("appointments.destroy", props.appointmentId), {
        preserveScroll: true,
        onSuccess: () => emit("close"),
    });
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="relative bg-white p-8 overflow-hidden">
            
            <button 
                @click="$emit('close')" 
                class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-colors"
            >
                <XMarkIcon class="w-6 h-6" />
            </button>

            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6">
                    <ExclamationTriangleIcon class="w-10 h-10" />
                </div>

                <h3 class="text-2xl font-black text-slate-800 mb-2">Delete Record?</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">
                    This will permanently remove this appointment from your list. This action cannot be undone.
                </p>

                <div class="flex w-full gap-3">
                    <button 
                        @click="$emit('close')"
                        class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all active:scale-95"
                    >
                        Keep it
                    </button>
                    <button 
                        @click="confirmDelete"
                        class="flex-1 px-6 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-lg shadow-red-100 transition-all active:scale-95"
                    >
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </Modal>
</template>