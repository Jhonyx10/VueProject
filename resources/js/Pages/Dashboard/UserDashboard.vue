<script setup>
import { ref, onMounted, computed } from 'vue';
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
    userUpcomingAppointment: {
        type: Object,
        default: null
    },
});

// --- Logic for Live Countdown & Session Availability ---
const countdownText = ref('');
const isTimeForMeeting = ref(false);
const isCurrentlyOngoing = ref(false);

const calculateTimeLogic = () => {
    if (!props.userUpcomingAppointment) return;

    const appointmentDateTime = new Date(`${props.userUpcomingAppointment.date} ${props.userUpcomingAppointment.time}`);
    const now = new Date();
    const diffInMs = appointmentDateTime - now;
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));

    // 1. Status Text Logic (40 min window)
    if (diffInMs <= 0 && diffInMs > -2400000) { // Current time to 40 mins past
        countdownText.value = "Session is active now";
        isCurrentlyOngoing.value = true;
    } else if (diffInMs <= -2400000) {
        countdownText.value = "Session timeframe ended";
        isCurrentlyOngoing.value = false;
    } else {
        const hours = Math.floor(diffInMinutes / 60);
        const mins = diffInMinutes % 60;
        countdownText.value = hours > 0 ? `${hours}h ${mins}m remaining` : `${mins}m remaining`;
        isCurrentlyOngoing.value = false;
    }

    // 2. Enable Video button 10 mins before and throughout the 40-min session
    isTimeForMeeting.value = diffInMinutes <= 10 && diffInMinutes > -40;
};

const form = useForm({
    action: 'expired', 
    reason: 'Session Expired - No completion within 40m window',      
});

const statusUpdate = () => {
    if (!props.userUpcomingAppointment || props.userUpcomingAppointment.status === 'completed') return;
    
    form.patch(route('appointments.update', props.userUpcomingAppointment._id || props.userUpcomingAppointment.id), {
        preserveScroll: true,
    });
}

onMounted(() => {
    calculateTimeLogic();
    const timer = setInterval(calculateTimeLogic, 60000); 

    if (props.userUpcomingAppointment) {
        const appointmentDateTime = new Date(`${props.userUpcomingAppointment.date} ${props.userUpcomingAppointment.time}`);
        const expiryTime = new Date(appointmentDateTime.getTime() + 40 * 60000); 
        const now = new Date();

        if (now > expiryTime) {
            statusUpdate();
        } else {
            const timeUntilExpiry = expiryTime - now;
            setTimeout(() => {
                statusUpdate();
            }, timeUntilExpiry);
        }
    }
});

// Formatting helpers remain the same...
const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', { 
        weekday: 'long', month: 'long', day: 'numeric' 
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
            <div 
                v-motion
                :initial="{ opacity: 0, x: -50 }"
                :enter="{ opacity: 1, x: 0, transition: { delay: 100 } }"
            >
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Upcoming Appointment</h1>
                <p class="text-slate-500 font-medium">
                    Welcome back, {{ userUpcomingAppointment?.patient?.name || 'User' }}. 
                    {{ userUpcomingAppointment ? 'Here is your next scheduled session.' : "You have no appointments today." }}
                </p>
            </div>

            <div
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-2xl w-fit transition-colors duration-500"
                :class="isCurrentlyOngoing ? 'text-emerald-600 bg-emerald-50 border border-emerald-100' : 'text-indigo-600 bg-indigo-50 border border-indigo-100/50'"
            >
                <span class="relative flex h-2 w-2">
                    <span :class="isCurrentlyOngoing ? 'bg-emerald-400' : 'bg-indigo-400'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                    <span :class="isCurrentlyOngoing ? 'bg-emerald-500' : 'bg-indigo-500'" class="relative inline-flex rounded-full h-2 w-2"></span>
                </span>
                {{ isCurrentlyOngoing ? 'Session Live' : 'System Ready' }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div 
                v-motion
                :initial="{ opacity: 0, y: 60 }"
                :enter="{ opacity: 1, y: 0, transition: { type: 'spring', stiffness: 100, delay: 200 } }"
                class="lg:col-span-2 relative group"
            >
                <div :class="isCurrentlyOngoing ? 'from-emerald-500 to-teal-500' : 'from-indigo-500 to-blue-500'" class="absolute -inset-1 bg-gradient-to-r rounded-[2.5rem] blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>
                
                <div class="relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden h-full">
                    <div v-if="userUpcomingAppointment" class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <span :class="isCurrentlyOngoing ? 'bg-emerald-500 animate-pulse' : 'bg-slate-900'" class="text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full transition-colors">
                                {{ isCurrentlyOngoing ? 'In Progress' : 'Up Next' }}
                            </span>
                            <div :class="isCurrentlyOngoing ? 'text-emerald-600' : 'text-indigo-600'" class="flex items-center gap-2 font-bold text-sm">
                                <ClockIcon class="w-5 h-5" :class="{'animate-spin-slow': isCurrentlyOngoing}" />
                                {{ countdownText }}
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center gap-8">
                            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center border border-slate-100 shadow-inner">
                                <UserIcon class="w-10 h-10 text-slate-300" />
                            </div>

                            <div class="flex-1">
                                <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-2">
                                    Dr. {{ userUpcomingAppointment.doctor?.doctor_profile?.lastName || userUpcomingAppointment.doctor?.name || 'Doctor' }}
                                </h2>
                                
                                <div class="flex flex-wrap gap-4 mt-4">
                                    <div v-if="userUpcomingAppointment.doctor?.doctor_profile?.expertise" class="flex items-center gap-2 text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 text-sm font-bold">
                                        {{ userUpcomingAppointment.doctor.doctor_profile.expertise }}
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-sm font-medium">
                                        <CalendarIcon class="w-4 h-4 text-indigo-500" />
                                        {{ formatDate(userUpcomingAppointment.date) }}
                                    </div>
                                </div>
                            </div>

                            <button 
                                :disabled="!isTimeForMeeting"
                                :class="isTimeForMeeting ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' : 'bg-slate-200 cursor-not-allowed text-slate-500'"
                                class="px-8 py-4 rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 text-sm text-white"
                            >
                                {{ isCurrentlyOngoing ? 'Join Session' : 'Start Session' }}
                                <ArrowRightIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div v-else class="h-full flex flex-col items-center justify-center py-20 text-center">
                        <CalendarIcon class="w-10 h-10 text-slate-300 mb-4" />
                        <h3 class="text-2xl font-bold text-slate-800">No appointments found</h3>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <VideoCameraIcon :class="isCurrentlyOngoing ? 'text-emerald-400' : 'text-indigo-400'" class="w-10 h-10 mb-6 transition-colors" />
                        <h3 class="text-xl font-bold mb-2">Virtual Care</h3>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                            {{ isTimeForMeeting ? 'The doctor is ready. You can now join the video call.' : 'Link will activate 10 minutes before the session.' }}
                        </p>
                        <button 
                            :disabled="!isTimeForMeeting"
                            :class="isTimeForMeeting ? 'bg-white text-slate-900 shadow-xl' : 'bg-white/10 text-white/40 cursor-not-allowed'"
                            class="w-full font-bold py-3 rounded-xl transition-all text-sm"
                        >
                            {{ isTimeForMeeting ? 'Launch Camera' : 'Waiting for Time...' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>