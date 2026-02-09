<template>
  <div 
    class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-shadow duration-300 ease-in-out max-w-sm mx-auto"
  >
    <h2 class="text-xl font-semibold mb-3 text-gray-800">
      {{ doctor.firstName }} {{ doctor.lastName }}
    </h2>
    <p class="text-gray-600 mb-1"><span class="font-medium">Age:</span> {{ doctor.age }}</p>
    <p class="text-gray-600 mb-1"><span class="font-medium">Gender:</span> {{ doctor.gender }}</p>
    <p class="text-gray-600 mb-1"><span class="font-medium">Address:</span> {{ doctor.address }}</p>
    <p class="text-gray-600 mb-4"><span class="font-medium">Expertise:</span> {{ doctor.expertise }}</p>
    
    <div class="flex justify-end">
        <ViewButton :doctor="doctor"/>
    </div>
  </div>
</template>

<script setup>
import ViewButton from '@/Pages/Doctors/ViewDoctor.vue';

defineProps({
  doctor: {
    type: Object,
    required: true
  }
});
</script>
