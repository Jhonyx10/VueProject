<script setup>
import { UserIcon } from '@heroicons/vue/24/outline'; // Optional: if using Heroicons

const props = defineProps({
    doctorsCount: {
        type: Number,
        default: 0
    },
    usersCount: {
        type: Number,
        default: 0
    },
    appPendingCount: {
        type: Number,
        default: 0
    },
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

         <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            <div class="p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">
                        Pending Appointments
                    </p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ appPendingCount }}
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a :href="route('appointments.index')" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    View all appointments →
                </a>
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
                    <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">
                        Total Doctors
                    </p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ doctorsCount }}
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a :href="route('doctors')" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    View all doctors →
                </a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            <div class="p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-green-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 uppercase tracking-wider">
                        Registered Users
                    </p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ usersCount }}
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a :href="route('doctors')" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    View all  users → 
                </a>
            </div>
        </div>

        </div>
</template>