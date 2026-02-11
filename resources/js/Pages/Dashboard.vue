<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useRole } from '@/composables/useRole';
import AdminDashboard from '@/Pages/Dashboard/AdminDashboard.vue'
import DoctorsDashboard from '@/Pages/Dashboard/DoctorsDashboard.vue'
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    doctorsCount: Number,
    usersCount: Number,
    appPendingCount: Number,
    upcomingAppointment: Object,
    monthlyAppointmentCount: Array,
    appointmentStatusCount: Array
});

const { isAdmin, isDoctor, isUser} = useRole();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>   
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section v-if="isAdmin">
                    <AdminDashboard
                    :doctorsCount="doctorsCount"
                    :usersCount="usersCount"
                    :appPendingCount="appPendingCount"
                    :monthlyAppointmentCount="monthlyAppointmentCount"
                    :appointmentStatusCount="appointmentStatusCount"
                 />
                </section>
                 <section v-if="isDoctor">
                    <DoctorsDashboard
                    :upcomingAppointment="upcomingAppointment"
                 />
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
