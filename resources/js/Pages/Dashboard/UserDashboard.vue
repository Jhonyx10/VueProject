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
const hasAutoUpdated = ref(false);

const calculateTimeLogic = () => {
    if (!props.userUpcomingAppointment) return;

    const appointmentDateTime = new Date(`${props.userUpcomingAppointment.date} ${props.userUpcomingAppointment.time}`);
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

const form = useForm({
    action: 'expired', 
    reason: 'Session Expired',      
});


const statusUpdate = () => {
    if (!props.userUpcomingAppointment) return;
    form.patch(route('appointments.update', props.userUpcomingAppointment._id || props.userUpcomingAppointment.id), {
        preserveScroll: true,
    });
}

onMounted(() => {
    calculateTimeLogic();
    setInterval(calculateTimeLogic, 60000); // Refresh every minute
});


// --- Formatting Helpers ---
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
                v-motion
                :initial="{ opacity: 0, scale: 0.5 }"
                :enter="{ 
                    opacity: 1, 
                    scale: 1, 
                    transition: { type: 'spring', stiffness: 200, delay: 300 } 
                }"
                class="flex items-center gap-2 text-sm font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-2xl w-fit"
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
                :initial="{ opacity: 0, y: 60 }"
                :enter="{ 
                    opacity: 1, 
                    y: 0, 
                    transition: { type: 'spring', stiffness: 100, damping: 15, delay: 200 } 
                }"
                class="lg:col-span-2 relative group"
            >
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2.5rem] blur opacity-15 group-hover:opacity-25 transition duration-1000"></div>
                
                <div class="relative bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden h-full">
                    <div v-if="userUpcomingAppointment" class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <span class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full">
                                Up Next
                            </span>
                            <div class="flex items-center gap-2 text-indigo-600 font-bold text-sm">
                                <ClockIcon class="w-5 h-5" />
                                {{ countdownText }}
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center gap-8">
                            <div 
                                v-motion
                                :initial="{ rotate: -15, scale: 0.5 }"
                                :enter="{ rotate: 0, scale: 1, transition: { delay: 400, type: 'spring' } }"
                                class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center border border-slate-100 shadow-inner"
                            >
                                <UserIcon class="w-10 h-10 text-slate-300" />
                            </div>

                           <div class="flex-1">
                                <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-2">
                                    Dr. {{ userUpcomingAppointment.doctor?.doctor_profile?.lastName || userUpcomingAppointment.doctor?.name || 'Doctor' }}<span>{{ userUpcomingAppointment.doctor?.doctor_profile?.firstName ? ', ' + userUpcomingAppointment.doctor.doctor_profile.firstName : '' }}</span>
                                </h2>
                                
                                <div class="flex flex-wrap gap-4 mt-4">
                                    <div v-if="userUpcomingAppointment.doctor?.doctor_profile?.expertise" class="flex items-center gap-2 text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100 text-sm font-bold">
                                        <UserIcon class="w-4 h-4" />
                                        {{ userUpcomingAppointment.doctor.doctor_profile.expertise }}
                                    </div>

                                    <div class="flex items-center gap-2 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-sm font-medium">
                                        <CalendarIcon class="w-4 h-4 text-indigo-500" />
                                        {{ formatDate(userUpcomingAppointment.date) }} at {{ formatTime(userUpcomingAppointment.time) }}
                                    </div>

                                    <div class="flex items-center gap-2 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-sm font-medium">
                                        <ClipboardDocumentListIcon class="w-4 h-4 text-indigo-500" />
                                        {{ userUpcomingAppointment.reason || 'General Consultation' }}
                                    </div>
                                </div>
                            </div>

                            <a 
                                v-motion
                                :hover="{ scale: 1.05 }"
                                :tap="{ scale: 0.95 }"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 cursor-pointer transition-all flex items-center justify-center gap-2 text-sm"
                            >
                                Start Session
                                <ArrowRightIcon class="w-4 h-4" />
                            </a>
                        </div>
                    </div>

                    <div v-else class="h-full flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                            <CalendarIcon class="w-10 h-10 text-slate-300" />
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">No appointments found</h3>
                        <p class="text-slate-400 max-w-xs mx-auto">You're all caught up!</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div 
                    v-motion
                    :initial="{ opacity: 0, x: 50 }"
                    :enter="{ opacity: 1, x: 0, transition: { delay: 400 } }"
                    class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group"
                >
                    <div class="relative z-10">
                        <VideoCameraIcon class="w-10 h-10 text-indigo-400 mb-6" />
                        <h3 class="text-xl font-bold mb-2">Virtual Care</h3>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                            {{ isTimeForMeeting ? 'The doctor is ready. You can now join the video call.' : 'Link will activate 10 minutes before the session.' }}
                        </p>
                        <button 
                            v-motion
                            :hover="isTimeForMeeting ? { scale: 1.02 } : {}"
                            :tap="isTimeForMeeting ? { scale: 0.98 } : {}"
                            :disabled="!isTimeForMeeting"
                            :class="isTimeForMeeting ? 'bg-white text-slate-900 shadow-xl' : 'bg-white/10 text-white/40 cursor-not-allowed'"
                            class="w-full font-bold py-3 rounded-xl transition-all text-sm"
                        >
                            {{ isTimeForMeeting ? 'Launch Camera' : 'Waiting for Time...' }}
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl group-hover:bg-indigo-500/40 transition-all"></div>
                </div>

                <div 
                    v-motion
                    :initial="{ opacity: 0, x: 50 }"
                    :enter="{ opacity: 1, x: 0, transition: { delay: 550 } }"
                    class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm"
                >
                    <h3 class="font-bold text-slate-800 mb-4">Quick Links</h3>
                    <div class="space-y-4">
                        <div 
                            v-motion
                            :hover="{ x: 8 }"
                            class="flex items-center gap-4 group cursor-pointer"
                        >
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