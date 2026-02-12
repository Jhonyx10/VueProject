<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3'; // Added usePage
import { useRole } from '@/composables/useRole';
import { computed } from 'vue'; // Added computed
import { 
    ClipboardDocumentCheckIcon, 
    ChevronRightIcon,
    MagnifyingGlassIcon,
    UserCircleIcon,
    ClockIcon,
    FolderOpenIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    patients: Array
});

const { isUser } = useRole();
const page = usePage();

// Filter logic based strictly on role
const filteredPatients = computed(() => {
    if (isUser.value) {
        // If the user is a patient, only show the record matching their ID
        return props.patients.filter(patient => patient.id === page.props.auth.user.id);
    }
    // If Admin or Doctor, show everything
    return props.patients;
});
</script>

<template>
    <Head title="Diagnosis Records" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Diagnosis Records
            </h2>
        </template>

        <div class="py-8 bg-slate-50/50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div 
                    v-motion
                    :initial="{ opacity: 0, y: -20 }"
                    :enter="{ opacity: 1, y: 0 }"
                    class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4"
                >
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-2 bg-white border border-slate-200 shadow-sm text-slate-700 text-xs font-bold rounded-2xl flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                            {{ filteredPatients.length }} Active Cases
                        </span>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <MagnifyingGlassIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                        <input 
                            type="text" 
                            placeholder="Search by patient name..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm"
                        />
                    </div>
                </div>

                <div 
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{ opacity: 1, y: 0, transition: { delay: 100 } }"
                    class="bg-white border border-slate-200 rounded-[2.5rem] overflow-hidden shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th v-if="!isUser" class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Patient Profile</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Diagnosis Summary</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Attending Physician</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr 
                                    v-for="(patient, index) in filteredPatients" 
                                    :key="patient.id"
                                    v-motion
                                    :initial="{ opacity: 0, x: -10 }"
                                    :enter="{ opacity: 1, x: 0, transition: { delay: 200 + (index * 50) } }"
                                    class="hover:bg-indigo-50/30 transition-all group relative"
                                >
                                    <td v-if="!isUser" class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-lg group-hover:bg-indigo-600 group-hover:text-white group-hover:rotate-6 transition-all duration-300">
                                                {{ patient.name?.charAt(0) || '?' }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ patient.name }}</p>
                                                <p class="text-[11px] text-slate-400 font-medium">{{ patient.email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        <div v-if="patient.patient_records?.length > 0">
                                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[11px] font-bold mb-1 group-hover:bg-white transition-colors">
                                                {{ patient.patient_records[0].condition_name || 'Checkup' }}
                                            </div>
                                            <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                                <ClockIcon class="w-3 h-3" />
                                                Updated {{ new Date(patient.patient_records[0].created_at).toLocaleDateString() }}
                                            </p>
                                        </div>
                                        <span v-else class="text-xs text-slate-300 italic">No history</span>
                                    </td>

                                    <td class="px-8 py-5">
                                        <div v-if="patient.patient_records?.[0]?.doctor?.doctor_profile" class="flex items-center gap-3">
                                            <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition-colors">
                                                <UserCircleIcon class="w-5 h-5 text-indigo-500" />
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700">
                                                    Dr. {{ patient.patient_records[0].doctor.doctor_profile.lastName }}
                                                </p>
                                                <p class="text-[10px] text-indigo-400 font-black uppercase tracking-tighter">
                                                    {{ patient.patient_records[0].doctor.doctor_profile.expertise }}
                                                </p>
                                            </div>
                                        </div>
                                        <span v-else class="text-xs text-slate-300">Pending Assignment</span>
                                    </td>

                                    <td class="px-8 py-5 text-right">
                                        <div v-if="patient.appointments?.length > 0" class="flex justify-end">
                                            <Link
                                                v-motion
                                                :hover="{ scale: 1.1 }"
                                                :href="route('diagnosis.show', { diagnosis: patient.appointments[0].id })"
                                                class="group/btn flex items-center gap-2 bg-green-50 text-green-600 px-4 py-2 rounded-2xl hover:bg-green-600 hover:text-white transition-all duration-300 shadow-sm shadow-green-100"
                                            >
                                                <span class="text-[10px] font-black uppercase tracking-wider">Open Record</span>
                                                <FolderOpenIcon class="w-5 h-5" />
                                            </Link>
                                        </div>
                                        
                                        <span v-else class="text-[10px] font-bold text-slate-300 uppercase italic">
                                            Awaiting Completion
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="patients.length === 0" class="py-24 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <ClipboardDocumentCheckIcon class="w-10 h-10 text-slate-200" />
                        </div>
                        <h3 class="text-xl font-black text-slate-800">No Patient Records Found</h3>
                        <p class="text-slate-400 text-sm max-w-xs mx-auto mt-2">Try adjusting your filters or adding a new patient record to the system.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>