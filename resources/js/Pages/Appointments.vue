<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PatientAppointment from '@/Pages/Appointments/PatientAppointment.vue';
import DoctorsAppointments from '@/Pages/Appointments/DoctorsAppointments.vue';
import AdminAppointmentView from '@/Pages/Appointments/AdminAppointmentView.vue'
import { useRole } from '@/composables/useRole';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    appointments: Array,
    doctors: Array
});

const { isDoctor, isUser, isAdmin } = useRole();
</script>

<template>
    <Head title="Appointments" />

    <AuthenticatedLayout>
        <div class="py-4 bg-slate-50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <section v-if="isUser">
                    <PatientAppointment :appointments="appointments" :doctors="doctors"/>
                </section>

                <section v-if="isDoctor">
                    <DoctorsAppointments
                        :appointments="appointments"
                    />
                </section>

                <section v-if="isAdmin">
                    <AdminAppointmentView
                        :appointments="appointments"
                        :doctors="doctors"
                    />
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>