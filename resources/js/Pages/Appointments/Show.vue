<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    CalendarIcon, 
    ClockIcon, 
    UserIcon, 
    ChatBubbleLeftRightIcon,
    ArrowLeftIcon,
    CheckCircleIcon,
    XCircleIcon,
    ClockIcon as ClockOutlineIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: Object
});

const formatTime = (time) => {
    if (!time) return "";
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};
</script>

<template>
    <Head title="Appointment Details" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link :href="route('appointments.index')" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                        <ArrowLeftIcon class="w-4 h-4 mr-1" />
                        Back to Appointments
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-slate-100">
                    <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Appointment Details</h2>
                            <span :class="{
                                'bg-amber-100 text-amber-700': appointment.status === 'pending',
                                'bg-blue-100 text-blue-700': appointment.status === 'confirmed',
                                'bg-green-100 text-green-700': appointment.status === 'complete',
                                'bg-red-100 text-red-700': ['cancelled', 'expired'].includes(appointment.status),
                            }" class="px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider border border-white shadow-sm">
                                {{ appointment.status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em]">Consultation Info</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 text-slate-700">
                                        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600">
                                            <CalendarIcon class="w-5 h-5" />
                                        </div>
                                        <span class="font-semibold">{{ formatDate(appointment.date) }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-slate-700">
                                        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600">
                                            <ClockIcon class="w-5 h-5" />
                                        </div>
                                        <span class="font-semibold">{{ formatTime(appointment.time) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em]">Specialist</h3>
                                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-3xl border border-slate-100">
                                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-100">
                                        {{ appointment.doctor?.doctor_profile?.firstName?.charAt(0) || appointment.doctor?.name?.charAt(0) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">
                                            Dr. {{ appointment.doctor?.doctor_profile?.firstName }} {{ appointment.doctor?.doctor_profile?.lastName }}
                                        </h4>
                                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-tight">
                                            {{ appointment.doctor?.doctor_profile?.expertise || 'General Specialist' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.15em]">Patient Notes</h3>
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-slate-600 leading-relaxed italic">
                                "{{ appointment.notes || 'No notes provided by patient.' }}"
                            </div>
                        </div>

                        <div v-if="appointment.reason" class="space-y-4">
                            <h3 class="text-xs font-bold text-red-400 uppercase tracking-[0.15em]">Cancellation Reason</h3>
                            <div class="p-6 bg-red-50 rounded-3xl border border-red-100 text-red-600 leading-relaxed italic">
                                "{{ appointment.reason }}"
                            </div>
                        </div>

                        <div class="pt-4 flex flex-wrap gap-4">
                            <Link :href="route('chat.index')" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                <ChatBubbleLeftRightIcon class="w-5 h-5" />
                                Open Consultation Chat
                            </Link>

                            <Link v-if="appointment.status === 'complete'" :href="route('diagnosis.show', appointment.id)" class="flex-1 min-w-[200px] flex items-center justify-center gap-2 px-6 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition-all shadow-lg shadow-green-100">
                                <CheckCircleIcon class="w-5 h-5" />
                                View Medical Record
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
