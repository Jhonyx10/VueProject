<script setup>
import { computed, ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import BookedAppointment from "@/Pages/Appointments/BookedAppointment.vue";
import CancelAppointment from "@/Pages/Appointments/CancelAppointment.vue";
import DeleteConfirmModal from "@/Pages/Appointments/DeleteConfirmModal.vue";
import {
    TrashIcon,
    FolderOpenIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    appointments: { type: Array, required: true },
    doctors: { type: Array, required: true },
});

const authUser = usePage().props.auth.user;
const showModal = ref(false);
const showDeleteModal = ref(false);
const idToDelete = ref(null);
const showCancelModal = ref(false);
const selectedAppointmentId = ref(null);

const triggerCancel = (id) => {
    selectedAppointmentId.value = id;
    showCancelModal.value = true;
};

const triggerDelete = (id) => {
    idToDelete.value = id;
    showDeleteModal.value = true;
};

const myAppointments = computed(() => {
    return props.appointments.filter(
        (app) =>
            app.patient_id === authUser.id || app.patient_id === authUser._id
    );
});

const formatTime = (time) => {
    if (!time) return "";
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};

onMounted(() => {
    myAppointments.value.forEach(app => {
        if (['pending', 'confirmed'].includes(app.status)) {
            const appDateTime = new Date(`${app.date}T${app.time}`);
            const now = new Date();
            
            if (appDateTime < now) {
                router.patch(route('appointments.update', app._id || app.id), {
                    action: 'expire'
                }, {
                    preserveScroll: true,
                });
            }
        }
    });
});
</script>

<template>
    <div class="space-y-6">
        <div 
            v-motion
            :initial="{ opacity: 0, y: -20 }"
            :enter="{ opacity: 1, y: 0 }"
            class="flex items-center justify-between px-2"
        >
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                My Schedule
            </h2>
            <button
                v-motion
                :hover="{ scale: 1.05 }"
                :tap="{ scale: 0.95 }"
                @click="showModal = true"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Book Appointment
            </button>
        </div>

        <div
            v-if="myAppointments.length === 0"
            v-motion
            :initial="{ opacity: 0, scale: 0.9 }"
            :enter="{ opacity: 1, scale: 1 }"
            class="bg-white rounded-[32px] border border-dashed border-slate-300 p-12 text-center shadow-sm"
        >
            <h3 class="text-2xl font-bold text-slate-800 mb-2">
                No Appointments Found
            </h3>
            <p class="text-slate-500 mb-8">
                Click the button above to schedule your first visit.
            </p>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="(app, index) in myAppointments"
                :key="app._id || app.id"
                v-motion
                :initial="{ opacity: 0, x: -30 }"
                :enter="{ 
                    opacity: 1, 
                    x: 0, 
                    transition: { 
                        delay: index * 100, // Staggers each item by 100ms
                        type: 'spring',
                        stiffness: 200,
                        damping: 20
                    } 
                }"
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all"
            >
                <div
                    v-motion
                    :initial="{ scale: 0.5 }"
                    :enter="{ scale: 1, transition: { delay: (index * 100) + 200 } }"
                    class="bg-indigo-600 text-white px-4 py-3 rounded-2xl text-center min-w-[75px] shadow-lg shadow-indigo-100"
                >
                    <p class="text-[10px] font-bold uppercase opacity-80">
                        {{ new Date(app.date).toLocaleString("default", { month: "short" }) }}
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
                        Scheduled for {{ formatTime(app.time) }}
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block">
                        <span
                            :class="{
                                'bg-amber-50 text-amber-600 border-amber-100': app.status === 'pending',
                                'bg-blue-50 text-blue-600 border-blue-100': app.status === 'confirmed',
                                'bg-green-50 text-green-600 border-green-100': app.status === 'complete',
                                'bg-red-50 text-red-600 border-red-100': app.status === 'cancelled',
                                'bg-slate-50 text-slate-400 border-slate-200': app.status === 'expired',
                            }"
                            class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border"
                        >
                            {{ app.status }}
                        </span>
                    </div>

                    <button
                        v-if="app.status === 'cancelled'"
                        v-motion
                        :hover="{ scale: 1.2, color: '#dc2626' }"
                        @click="triggerDelete(app._id || app.id)"
                        class="p-2 text-red-300 transition-all"
                    >
                        <TrashIcon class="w-5 h-5" />
                    </button>

                    <button
                        v-if="['pending', 'confirmed'].includes(app.status)"
                        v-motion
                        :hover="{ rotate: 90, scale: 1.1 }"
                        @click="triggerCancel(app._id || app.id)"
                        class="p-2 text-slate-300 hover:text-red-500 transition-all"
                    >
                        <XCircleIcon class="w-5 h-5 text-red-500" />
                    </button>

                    <Link
                        v-if="app.status === 'complete'"
                        v-motion
                        :hover="{ y: -3 }"
                        :href="route('diagnosis.show', { diagnosis: app._id || app.id })"
                        class="bg-green-50 text-green-600 p-3 rounded-2xl hover:bg-green-100 transition-colors"
                    >
                        <FolderOpenIcon class="w-6 h-6" />
                    </Link>
                </div>
            </div>
        </div>

        <BookedAppointment :show="showModal" :doctors="doctors" @close="showModal = false" />
        <CancelAppointment :show="showCancelModal" :appointment_id="selectedAppointmentId" @close="showCancelModal = false" />
        <DeleteConfirmModal :show="showDeleteModal" :appointmentId="idToDelete" @close="showDeleteModal = false" />
    </div>
</template>
