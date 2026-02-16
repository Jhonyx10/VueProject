<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DoctorsForm from '@/Pages/Doctors/AddDoctorsForm.vue';
import DoctorCard from '@/Pages/Doctors/DoctorCard.vue';
import { useRole } from '@/composables/useRole.js';
import { ref, computed } from 'vue';
import { MagnifyingGlassIcon, FunnelIcon } from '@heroicons/vue/24/outline';

const { isAdmin } = useRole();

const props = defineProps({
    doctors: Array
});

// State for filtering
const searchQuery = ref('');
const selectedExpertise = ref('');

// Get unique expertise list for the dropdown
const expertiseList = computed(() => {
    const list = props.doctors
        .map(d => d.doctor_profile?.expertise)
        .filter(Boolean); // Remove null/undefined
    return [...new Set(list)]; // Return unique values
});

// Filtered Doctors Logic
const filteredDoctors = computed(() => {
    return props.doctors.filter(doctor => {
        const profile = doctor.doctor_profile;
        
        // Search Logic: Match against firstName, lastName, or account name
        const searchTerm = searchQuery.value.toLowerCase();
        const matchesSearch = 
            doctor.name?.toLowerCase().includes(searchTerm) ||
            profile?.firstName?.toLowerCase().includes(searchTerm) ||
            profile?.lastName?.toLowerCase().includes(searchTerm);

        // Filter Logic: Match against expertise
        const matchesExpertise = 
            !selectedExpertise.value || 
            profile?.expertise === selectedExpertise.value;

        return matchesSearch && matchesExpertise;
    });
});
</script>

<template>
    <Head title="Doctor's List" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Medical Directory</h2>
                    <p class="text-slate-500 text-sm">Find and book specialized healthcare providers.</p>
                </div>
                <div v-if="isAdmin">
                    <DoctorsForm />
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mb-8 bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
                <div class="relative flex-1">
                    <MagnifyingGlassIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search by doctor name..." 
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                    />
                </div>

                <div class="relative min-w-[200px]">
                    <FunnelIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                    <select 
                        v-model="selectedExpertise"
                        class="w-full pl-11 pr-10 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 appearance-none transition-all text-sm text-slate-600"
                    >
                        <option value="">All Specialties</option>
                        <option v-for="exp in expertiseList" :key="exp" :value="exp">
                            {{ exp }}
                        </option>
                    </select>
                </div>
            </div>

            <div v-if="filteredDoctors.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <DoctorCard
                    v-for="doctor in filteredDoctors"
                    :key="doctor.id"
                    :doctor="doctor"
                />
            </div>

            <div v-else class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <MagnifyingGlassIcon class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-bold text-slate-800">No doctors found</h3>
                <p class="text-slate-500">Try adjusting your search or filters.</p>
                <button @click="searchQuery = ''; selectedExpertise = ''" class="mt-4 text-indigo-600 font-bold text-sm hover:underline">
                    Clear all filters
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>