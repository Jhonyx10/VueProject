<script setup>
import { UserIcon } from '@heroicons/vue/24/outline';
import BarChart from '@/Pages/Charts/BarChart.vue';
import PieChart from '@/Pages/Charts/PieChart.vue';

const props = defineProps({
    doctorsCount: Number,
    usersCount: Number,
    appPendingCount: Number,
    monthlyAppointmentCount: Array,
    appointmentStatusCount: Array
});
</script>

<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Pending Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ appPendingCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Total Doctors</p>
                        <p class="text-3xl font-bold text-gray-900">{{ doctorsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Registered Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ usersCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <h3 class="mb-4 text-sm font-medium text-gray-600 uppercase tracking-wider">Appointments Growth</h3>
                <div class="h-[400px]">
                    <BarChart v-if="monthlyAppointmentCount.length" :monthlyAppointmentCount="monthlyAppointmentCount" />
                    <div v-else class="h-full flex items-center justify-center text-gray-400 border-2 border-dashed rounded-lg">No growth data.</div>
                </div>
            </div>

            <div class="md:col-span-1 bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <h3 class="mb-4 text-sm font-medium text-gray-600 uppercase tracking-wider">Status Distribution</h3>
                <div class="h-[400px]">
                    <PieChart v-if="appointmentStatusCount.length" :appointmentStatusCount="appointmentStatusCount" />
                    <div v-else class="h-full flex items-center justify-center text-gray-400 border-2 border-dashed rounded-lg">No status data.</div>
                </div>
            </div>
        </div>
    </div>
</template>