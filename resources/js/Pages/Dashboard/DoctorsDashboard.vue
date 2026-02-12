<script setup>
import { onMounted, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
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

// --- Logic for Live Countdown & Session Availability ---
const countdownText = ref('');
const isTimeForMeeting = ref(false);
const hasAutoUpdated = ref(false);

const calculateTimeLogic = () => {
    if (!props.upcomingAppointment) return;

    const appointmentDateTime = new Date(`${props.upcomingAppointment.date} ${props.upcomingAppointment.time}`);
    const now = new Date();
    const diffInMs = appointmentDateTime - now;
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));

    // 1. Update Countdown Text
    if (diffInMs <= 0 && diffInMs > -1800000) { // If currently happening (up to 30 mins late)
        countdownText.value = "Session in progress";
    } else if (diffInMs <= -1800000) {
        countdownText.value = "Session ended";
    } else {
        const hours = Math.floor(diffInMinutes / 60);
        const mins = diffInMinutes % 60;
        countdownText.value = hours > 0 ? `${hours}h ${mins}m remaining` : `${mins}m remaining`;
    }

    // 2. Enable Video button 10 minutes before and during the session
    isTimeForMeeting.value = diffInMinutes <= 10 && diffInMinutes > -30;
};

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

const form = useForm({
    action: 'expire', 
    reason: 'Session Expired',      
});


const statusUpdate = () => {
    if (!props.upcomingAppointment) return;
    form.patch(route('appointments.update', props.upcomingAppointment._id || props.upcomingAppointment.id), {
        preserveScroll: true,
    });
}

onMounted(() => {
    calculateTimeLogic();
    setInterval(calculateTimeLogic, 60000); // Refresh every minute
    
    if (props.upcomingAppointment) {
        const appointmentDateTime = new Date(`${props.upcomingAppointment.date} ${props.upcomingAppointment.time}`);
        if (appointmentDateTime < new Date()) {
            statusUpdate();
        }
    }
});
</script>

<template>
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div 
                v-motion
                :initial="{ opacity: 0, x: -30 }"
                :enter="{ opacity: 1, x: 0, transition: { duration: 600, ease: 'easeOut' } }"
            >
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Physician Overview</h1>
                <p class="text-slate-500 font-medium">Welcome back, Doctor. Here is your schedule for today.</p>
            </div>

            <div 
                v-motion
                :initial="{ opacity: 0, scale: 0.8 }"
                :enter="{ 
                    opacity: 1, 
                    scale: 1, 
                    transition: { type: 'spring', stiffness: 250, delay: 200 } 
                }"
                class="flex items-center gap-2 text-sm font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-2xl w-fit border border-indigo-100/50"
            >
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                System Live
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div 
                v-motion
                :initial="{ opacity: 0, y: 40 }"
                :enter="{ 
                    opacity: 1, 
                    y: 0, 
                    transition: { type: 'spring', stiffness: 100, damping: 15, delay: 100 } 
                }"
                class="lg:col-span-2 relative group"
            >
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2.5rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                
                <div class="relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden h-full">
                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <span 
                                v-motion
                                :initial="{ opacity: 0, scale: 0.9 }"
                                :enter="{ opacity: 1, scale: 1, transition: { delay: 400 } }"
                                class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full"
                            >
                                Up Next
                            </span>
                            <div v-if="upcomingAppointment" class="flex items-center gap-2 text-indigo-600 font-bold text-sm">
                                <ClockIcon class="w-5 h-5 animate-pulse" />
                                {{ countdownText }}
                            </div>
                        </div>

                        <div v-if="upcomingAppointment" class="flex flex-col md:flex-row md:items-center gap-8">
                            <div 
                                v-motion
                                :initial="{ scale: 0.5, rotate: -15 }"
                                :enter="{ scale: 1, rotate: 0, transition: { type: 'spring', delay: 300, damping: 10 } }"
                                class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center border border-slate-100 shadow-inner group-hover:bg-white transition-colors duration-500"
                            >
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
                                <a 
                                    v-motion
                                    :hover="{ scale: 1.05, boxShadow: '0 20px 25px -5px rgb(79 70 229 / 0.2)' }"
                                    :tap="{ scale: 0.95 }"
                                    :href="route('diagnosis.create', { 
                                        appointment_id: upcomingAppointment._id || upcomingAppointment.id, 
                                        patient_id: upcomingAppointment.patient?._id || upcomingAppointment.patient?.id,
                                        patient_name: upcomingAppointment.patient?.name,
                                        notes: upcomingAppointment.notes
                                    })"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold transition-all flex items-center justify-center gap-2 text-sm"
                                >
                                    Start Session
                                    <ArrowRightIcon class="w-4 h-4" />
                                </a>
                            </div>
                        </div>

                        <div v-else class="py-10 text-center" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1 }">
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
                <div 
                    v-motion
                    :initial="{ opacity: 0, x: 40 }"
                    :enter="{ opacity: 1, x: 0, transition: { delay: 300, type: 'spring' } }"
                    class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group shadow-xl shadow-slate-200"
                >
                    <div class="relative z-10">
                        <VideoCameraIcon class="w-10 h-10 text-indigo-400 mb-6 group-hover:scale-110 transition-transform duration-500" />
                        <h3 class="text-xl font-bold mb-2">Virtual Care</h3>
                        <p class="text-slate-400 text-sm mb-6">Access your secure video link for remote consultations.</p>
                        <button 
                            v-motion
                            :hover="isTimeForMeeting ? { scale: 1.02, backgroundColor: 'rgba(255,255,255,0.15)' } : {}"
                            :tap="isTimeForMeeting ? { scale: 0.98 } : {}"
                            :disabled="!isTimeForMeeting"
                            :class="isTimeForMeeting ? 'bg-white/10 text-white shadow-xl' : 'bg-white/5 text-white/30 cursor-not-allowed'"
                            class="w-full backdrop-blur-md border border-white/10 font-bold py-3 rounded-xl transition-all text-sm"
                        >
                            {{ isTimeForMeeting ? 'Launch Camera' : 'Waiting for Time...' }}
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
                </div>

                <div 
                    v-motion
                    :initial="{ opacity: 0, x: 40 }"
                    :enter="{ opacity: 1, x: 0, transition: { delay: 450, type: 'spring' } }"
                    class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm hover:border-indigo-100 transition-colors"
                >
                    <h3 class="font-bold text-slate-800 mb-4">Daily Schedule</h3>
                    <div class="space-y-4">
                        <div 
                            v-motion
                            :hover="{ x: 8 }"
                            class="flex items-center gap-4 group cursor-pointer"
                        >
                            <div class="w-1 bg-indigo-500 h-8 rounded-full group-hover:h-12 transition-all duration-300"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-tight">View All</p>
                                <p class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">Manage Full Calendar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>