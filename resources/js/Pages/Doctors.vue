<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DoctorsForm from '@/Pages/Doctors/AddDoctorsForm.vue';
import DoctorCard from '@/Pages/Doctors/DoctorCard.vue';

// get doctors from Inertia props
defineProps({
    doctors: Array
});
</script>

<template>
    <Head title="Doctor's List" />
   <AuthenticatedLayout>
      <div class="max-w-screen mt-6">
        <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold text-gray-800">Doctors List</h2>
              <DoctorsForm />
            </div>
        <div class="flex flex-wrap gap-2">
            <DoctorCard
              v-for="doctor in doctors"
              :key="doctor.id"
              :doctor="doctor"
            />
        </div>
      </div>
    </AuthenticatedLayout>

</template>

