<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import CancellationReason from "@/Pages/Appointments/CancellationReason.vue";
import {
    ClockIcon,
    UserIcon,
    CalendarDaysIcon,
    ChatBubbleLeftEllipsisIcon,
    CheckCircleIcon,
    FolderIcon,
    FolderOpenIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    appointments: { type: Array, required: true },
});

const authUser = usePage().props.auth.user;

const showCancelModal = ref(false);
const selectedAppointment = ref(null); // Changed from ID to Object

const triggerCancel = (appointment) => {
    selectedAppointment.value = appointment;
    showCancelModal.value = true;
};

// Filter appointments where the logged-in doctor is the assigned doctor
const mySchedule = computed(() => {
    return props.appointments.filter(
        (app) => app.doctor_id === authUser.id || app.doctor_id === authUser._id
    );
});

const appointmentCount = computed(() => {
    return props.appointments.filter((app) => {
        const isMyAppointment =
            app.doctor_id === authUser.id || app.doctor_id === authUser._id;

        const isActive = !["complete", "cancelled"].includes(
            app.status?.toLowerCase()
        );

        return isMyAppointment && isActive;
    });
});

const formatTime = (time) => {
    if (!time) return "";
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Patient Appointments
                </h2>
                <p class="text-sm text-slate-500">
                    You have {{ appointmentCount.length }} consultations
                    scheduled
                </p>
            </div>
            <div class="flex gap-2">
                <div
                    class="bg-white border border-slate-200 px-4 py-2 rounded-2xl shadow-sm text-xs font-bold text-slate-600"
                >
                    Today's Date: {{ new Date().toLocaleDateString() }}
                </div>
            </div>
        </div>

        <div
            v-if="mySchedule.length === 0"
            class="bg-white rounded-[32px] border border-dashed border-slate-300 p-16 text-center shadow-sm"
        >
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-3xl mb-4"
            >
                <CalendarDaysIcon class="w-10 h-10 text-slate-300" />
            </div>
            <h3 class="text-xl font-bold text-slate-800">
                No Consultations Yet
            </h3>
            <p class="text-slate-500 max-w-xs mx-auto">
                When patients book appointments with you, they will appear here.
            </p>
        </div>

        <div v-else class="grid gap-4">
            <div
                v-for="app in mySchedule"
                :key="app._id || app.id"
                class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row md:items-center gap-6 group"
            >
                <div
                    class="flex flex-row md:flex-col items-center justify-center bg-slate-50 px-6 py-4 rounded-[2rem] min-w-[120px] gap-2 border border-slate-100"
                >
                    <span class="text-indigo-600 font-black text-lg">{{
                        formatTime(app.time)
                    }}</span>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-slate-400"
                    >
                        {{
                            new Date(app.date).toLocaleDateString("en-US", {
                                month: "short",
                                day: "numeric",
                            })
                        }}
                    </span>
                </div>

                <div
                    class="flex-1 flex items-center gap-4 border-l-0 md:border-l border-slate-100 md:pl-6"
                >
                    <div
                        class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 shrink-0"
                    >
                        <UserIcon class="w-6 h-6" />
                    </div>
                    <div>
                        <p
                            class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-0.5"
                        >
                            Patient Name
                        </p>
                        <h4 class="font-bold text-slate-800 text-xl">
                            {{ app.patient?.name || "Guest Patient" }}
                        </h4>
                    </div>
                </div>

                <div
                    class="flex-1 bg-slate-50/50 p-4 rounded-2xl border border-slate-50"
                >
                    <div class="flex items-center gap-2 mb-1">
                        <ChatBubbleLeftEllipsisIcon
                            class="w-4 h-4 text-slate-400"
                        />
                        <span
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter"
                            >Reason for visit</span
                        >
                    </div>
                    <p class="text-sm text-slate-600 line-clamp-2">
                        {{ app.notes || "No specific notes provided." }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- button to show if the appointment status is confirmed -->
                    <Link
                        v-if="app.status === 'confirmed'"
                        :href="
                            route('diagnosis.create', {
                                appointment_id: app._id || app.id,
                                patient_id: app.patient?._id || app.patient?.id,
                                patient_name: app.patient?.name,
                                notes: app.notes,
                            })
                        "
                        class="bg-indigo-50 text-indigo-600 p-3 rounded-2xl hover:bg-indigo-100 transition-colors group-hover:scale-105"
                    >
                        <FolderIcon class="w-6 h-6" />
                    </Link>
                    <!-- button to show if the appointment status is complete -->
                    <Link
                        v-if="app.status === 'complete'"
                        :href="
                            route('diagnosis.show', {
                                diagnosis: app._id || app.id,
                            })
                        "
                        class="bg-green-50 text-green-600 p-3 rounded-2xl hover:bg-green-100 transition-colors group-hover:scale-105"
                    >
                        <FolderOpenIcon class="w-6 h-6" />
                    </Link>
                    <!-- button to confirm the appointment -->
                    <Link
                        v-if="app.status === 'pending'"
                        method="patch"
                        :as="button"
                        :href="
                            route('appointments.update', {
                                appointment: app._id || app.id,
                            })
                        "
                        :data="{ action: 'confirm' }"
                        class="bg-amber-50 text-amber-600 p-3 rounded-2xl hover:bg-amber-100 transition-colors group-hover:scale-105 flex items-center justify-center"
                    >
                        <CheckCircleIcon class="w-6 h-6" />
                    </Link>
                    <button
                        v-if="app.status === 'cancelled'"
                        @click="triggerCancel(app)"
                        class="p-3 bg-red-50 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all"
                        title="Cancel Appointment"
                    >
                        <TrashIcon class="w-6 h-6" />
                    </button>

                    <div class="flex flex-col">
                        <span
                            class="text-[10px] font-black uppercase text-slate-400 mb-1 ml-1"
                            >Status</span
                        >
                        <span
                            :class="{
                                'bg-amber-100 text-amber-700':
                                    app.status === 'pending',
                                'bg-blue-100 text-blue-700':
                                    app.status === 'confirmed',
                                'bg-green-100 text-green-700':
                                    app.status === 'complete',
                                'bg-red-100 text-red-700':
                                    app.status === 'cancelled',
                            }"
                            class="px-4 py-1.5 rounded-xl text-[10px] font-bold uppercase border border-white shadow-sm"
                        >
                            {{ app.status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <CancellationReason
            :show="showCancelModal"
            :appointment="selectedAppointment"
            @close="showCancelModal = false"
        />
    </div>
</template>
