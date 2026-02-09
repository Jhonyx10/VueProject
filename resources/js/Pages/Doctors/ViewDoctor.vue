<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

const isOpen = ref(false);

const modalOpen = () => (isOpen.value = true);
const modalClose = () => (isOpen.value = false);

defineProps({
    doctor: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section>
        <PrimaryButton @click="modalOpen">View</PrimaryButton>

        <Modal :show="isOpen" @close="modalClose">
            <div class="p-6 space-y-3">
                <h2 class="text-xl font-bold text-gray-800">
                    Doctor Details
                </h2>

              <div class="flex gap-4 items-center">
                  <div class="text-gray-700 space-y-1">
                        <p><strong>Name:</strong> {{ doctor.firstName }} {{ doctor.lastName }}</p>
                        <p><strong>Age:</strong> {{ doctor.age }}</p>
                        <p><strong>Gender:</strong> {{ doctor.gender }}</p>
                        <p><strong>Address:</strong> {{ doctor.address }}</p>
                        <p><strong>Expertise:</strong> {{ doctor.expertise }}</p>
                    </div>
                    <div>
                        <div class="flex justify-around gap-2">
                            <div class="bg-slate-800 rounded-lg h-24">
                                <h2 class="text-white/70">number of client</h2>
                            </div>
                            <div class="bg-slate-800 rounded-lg">
                                    <h2 class="text-white/70">number of client</h2>
                                </div>
                                <div class="bg-slate-800 rounded-lg">
                                    <h2 class="text-white/70">number of client</h2>
                                </div>
                        </div>
                    </div>
              </div>

                <div class="flex justify-end pt-4">
                    <PrimaryButton @click="modalClose">
                        Close
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
