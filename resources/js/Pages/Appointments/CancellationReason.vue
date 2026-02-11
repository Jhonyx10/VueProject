<script setup>
import Modal from '@/Components/Modal.vue';
import { TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    appointment: Object // The selected appointment object
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="md">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-red-50 text-red-500 rounded-xl">
                    <TrashIcon class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Cancellation Details</h2>
                    <p class="text-xs text-slate-500">Information regarding the cancelled visit</p>
                </div>
            </div>

            <hr class="border-slate-100 mb-5" />

            <div class="space-y-4">
                <div>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Patient</span>
                    <p class="text-slate-700 font-semibold">{{ appointment?.patient?.name || 'Guest Patient' }}</p>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-black uppercase text-red-400 tracking-widest block mb-2">Reason Provided</span>
                    <p class="text-sm text-slate-600 leading-relaxed italic">
                        "{{ appointment?.reason || 'No specific reason was provided for this cancellation.' }}"
                    </p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button 
                    @click="closeModal" 
                    class="w-full sm:w-auto bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors"
                >
                    Close
                </button>
            </div>
        </div>
    </Modal>
</template>