<script setup>
import { 
    CalendarIcon, 
    ClockIcon, 
    UserIcon, 
    ArrowRightIcon,
    VideoCameraIcon,
    ClipboardDocumentListIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
    upcomingAppointment: {
        type: Object,
        default: null
    },
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', { 
        weekday: 'long', 
        month: 'long', 
        day: 'numeric' 
    });
};

const formatTime = (time) => {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    const h = parseInt(hours);
    const ampm = h >= 12 ? 'PM' : 'AM';
    return `${h % 12 || 12}:${minutes} ${ampm}`;
};
</script>

<template>
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Physician Overview</h1>
                <p class="text-slate-500 font-medium">Welcome back, Doctor. Here is your schedule for today.</p>
            </div>
            <div class="flex items-center gap-2 text-sm font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-2xl">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                System Live
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2.5rem] blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>
                
                <div class="relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden h-full">
                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <span class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full">
                                Up Next
                            </span>
                            <div v-if="upcomingAppointment" class="flex items-center gap-2 text-indigo-600 font-bold text-sm">
                                <ClockIcon class="w-5 h-5" />
                                Starting in {{ formatTime(upcomingAppointment.time) }}
                            </div>
                        </div>

                        <div v-if="upcomingAppointment" class="flex flex-col md:flex-row md:items-center gap-8">
                            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center border border-slate-100 shadow-inner">
                                <UserIcon class="w-10 h-10 text-slate-300" />
                            </div>

                            <div class="flex-1">
                                <h2 class="text-4xl font-black text-slate-800 tracking-tight mb-2">
                                    {{ upcomingAppointment.patient?.name || 'Patient Name' }}
                                </h2>
                                <div class="flex flex-wrap gap-4 mt-4">
                                    <div class="flex items-center gap-2 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-sm font-medium">
                                        <CalendarIcon class="w-4 h-4 text-indigo-500" />
                                        {{ formatDate(upcomingAppointment.date) }}
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-sm font-medium">
                                        <ClipboardDocumentListIcon class="w-4 h-4 text-indigo-500" />
                                        Initial Consultation
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                               <a :href="route('diagnosis.create', { 
                                    appointment_id: upcomingAppointment._id || upcomingAppointment.id, 
                                    patient_id: upcomingAppointment.patient?._id || upcomingAppointment.patient?.id,
                                    patient_name: upcomingAppointment.patient?.name,
                                    notes: upcomingAppointment.notes
                                })"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2 active:scale-95 text-sm">
                                    Start Session
                                    <ArrowRightIcon class="w-4 h-4" />
                                </a>
                            </div>
                        </div>

                        <div v-else class="py-10 text-center">
                            <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <CalendarIcon class="w-8 h-8 text-slate-300" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">No upcoming sessions</h3>
                            <p class="text-slate-400">You're all caught up for the moment.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <VideoCameraIcon class="w-10 h-10 text-indigo-400 mb-6" />
                        <h3 class="text-xl font-bold mb-2">Virtual Care</h3>
                        <p class="text-slate-400 text-sm mb-6">Access your secure video link for remote consultations.</p>
                        <button class="w-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 text-white font-bold py-3 rounded-xl transition-all text-sm">
                            Launch Camera
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition-all"></div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Daily Schedule</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-1 bg-indigo-500 h-8 rounded-full"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">View All</p>
                                <p class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Manage Full Calendar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>