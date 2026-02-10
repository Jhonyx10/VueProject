<script setup>
import { computed, ref } from 'vue'; // Added ref
import { usePage } from '@inertiajs/vue3';
import BookedAppointment from '@/Pages/Appointments/BookedAppointment.vue'

const props = defineProps({
    appointments: { type: Array, required: true },
    doctors: { type: Array, required: true }
});

const authUser = usePage().props.auth.user;
const showModal = ref(false); // Modal state

const myAppointments = computed(() => {
    return props.appointments.filter(app => 
        app.patient_id === authUser.id || app.patient_id === authUser._id
    );
});

const formatTime = (time) => {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">My Schedule</h2>
            <button 
                @click="showModal = true"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all active:scale-95 flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Book Appointment
            </button>
        </div>

        <div v-if="myAppointments.length === 0" 
            class="bg-white rounded-[32px] border border-dashed border-slate-300 p-12 text-center shadow-sm">
            <h3 class="text-2xl font-bold text-slate-800 mb-2">No Appointments Found</h3>
            <p class="text-slate-500 mb-8">Click the button above to schedule your first visit.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="app in myAppointments" :key="app._id || app.id" 
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
                
                <div class="bg-indigo-600 text-white px-4 py-3 rounded-2xl text-center min-w-[75px] shadow-lg shadow-indigo-100">
                    <p class="text-[10px] font-bold uppercase opacity-80">
                        {{ new Date(app.date).toLocaleString('default', { month: 'short' }) }}
                    </p>
                    <p class="text-2xl font-black">
                        {{ new Date(app.date).getDate() }}
                    </p>
                </div>

                <div class="flex-1">
                    <h4 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">
                        Dr. {{ app.doctor?.doctor_profile?.firstName }} {{ app.doctor?.doctor_profile?.lastName }}
                    </h4>
                    
                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 mt-1 text-slate-500 text-sm">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ formatTime(app.time) }}
                        </span>

                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ app.doctor?.doctor_profile?.expertise || 'Medical Specialist' }}
                        </span>
                    </div>

                    <p v-if="app.notes" class="mt-2 text-xs text-slate-400 italic line-clamp-1">
                        "{{ app.notes }}"
                    </p>
                </div>

                <div class="hidden sm:block">
                    <span :class="{
                        'bg-amber-50 text-amber-600 border-amber-100': app.status === 'pending',
                        'bg-green-50 text-green-600 border-green-100': app.status === 'confirmed',
                        'bg-red-50 text-red-600 border-red-100': app.status === 'cancelled'
                    }" class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border">
                        {{ app.status }}
                    </span>
                </div>
            </div>
        </div>

        <BookedAppointment 
            :show="showModal" 
            :doctors="doctors" 
            @close="showModal = false" 
        />
    </div>
</template>