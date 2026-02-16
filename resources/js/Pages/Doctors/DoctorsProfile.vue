<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import BookedAppointment from "@/Pages/Appointments/BookedAppointment.vue";
import { ref } from "vue";
import { useRole } from '@/composables/useRole.js';
import {
    ChevronLeftIcon,
    EnvelopeIcon,
    AcademicCapIcon,
    ClockIcon,
    ShieldCheckIcon,
    MapPinIcon,
    UserIcon,
    CalendarIcon,
    ExclamationCircleIcon,
} from "@heroicons/vue/24/outline";

const showModal = ref(false);
const { isAdmin } = useRole();

const props = defineProps({
    doctor: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head
        :title="
            doctor.doctor_profile?.lastName
                ? `Dr. ${doctor.doctor_profile.lastName} - Profile`
                : 'Doctor Profile'
        "
    />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        href="/doctors"
                        class="p-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-sm"
                    >
                        <ChevronLeftIcon class="w-5 h-5 text-slate-600" />
                    </Link>
                    <h2 class="font-bold text-xl text-slate-800 leading-tight">
                        Professional Profile
                    </h2>
                </div>

                <div
                    :class="[
                        'flex items-center gap-2 px-4 py-1.5 rounded-full border transition-colors',
                        doctor.doctor_profile
                            ? 'bg-green-50 border-green-100'
                            : 'bg-amber-50 border-amber-100',
                    ]"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            :class="[
                                'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                                doctor.doctor_profile
                                    ? 'bg-green-400'
                                    : 'bg-amber-400',
                            ]"
                        ></span>
                        <span
                            :class="[
                                'relative inline-flex rounded-full h-2 w-2',
                                doctor.doctor_profile
                                    ? 'bg-green-500'
                                    : 'bg-amber-500',
                            ]"
                        ></span>
                    </span>
                    <span
                        :class="[
                            'text-[10px] font-black uppercase tracking-widest',
                            doctor.doctor_profile
                                ? 'text-green-700'
                                : 'text-amber-700',
                        ]"
                    >
                        {{
                            doctor.doctor_profile
                                ? "Active Practitioner"
                                : "Profile Incomplete"
                        }}
                    </span>
                </div>
            </div>
        </template>

        <div class="bg-slate-50/50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    v-if="doctor.doctor_profile"
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{ opacity: 1, y: 0 }"
                    class="bg-white border border-slate-200 rounded-[3rem] p-8 md:p-12 mb-8 shadow-sm relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl -mr-20 -mt-20"
                    ></div>

                    <div
                        class="relative z-10 flex flex-col md:flex-row items-center gap-10"
                    >
                        <div
                            class="h-44 w-44 rounded-[2.5rem] bg-slate-900 flex items-center justify-center text-white text-6xl font-black shadow-2xl rotate-3"
                        >
                            {{
                                doctor.doctor_profile?.firstName?.charAt(0) ||
                                doctor.name.charAt(0)
                            }}
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <span
                                class="px-4 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] border border-indigo-200"
                            >
                                {{
                                    doctor.doctor_profile?.expertise ||
                                    "Medical Staff"
                                }}
                            </span>

                            <h1
                                class="text-4xl font-black text-slate-900 mt-4 mb-2"
                            >
                                Dr. {{ doctor.doctor_profile?.firstName }}
                                {{ doctor.doctor_profile?.lastName }}
                            </h1>

                            <div
                                class="flex flex-wrap justify-center md:justify-start gap-6 mt-6"
                            >
                                <div
                                    class="flex items-center gap-2 text-slate-500 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100"
                                >
                                    <EnvelopeIcon
                                        class="w-5 h-5 text-indigo-500"
                                    />
                                    <span class="text-sm font-bold">{{
                                        doctor.email
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-slate-500 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100"
                                >
                                    <MapPinIcon
                                        class="w-5 h-5 text-indigo-500"
                                    />
                                    <span class="text-sm font-bold">{{
                                        doctor.doctor_profile?.address ||
                                        "No Address Provided"
                                    }}</span>
                                </div>
                            </div>

                            <div
                                class="mt-10 flex flex-wrap gap-4 justify-center md:justify-start"
                            >
                                <button
                                v-if="!isAdmin"
                                v-motion
                                :hover="{ scale: 1.05 }"
                                :tap="{ scale: 0.95 }"
                                @click="showModal = true"
                                    class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 active:scale-95"
                                >
                                    Schedule Appointment
                                </button>
                                <button
                                    class="px-10 py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-black text-sm hover:bg-slate-50 transition-all active:scale-95"
                                >
                                    Contact Info
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    v-motion
                    :initial="{ opacity: 0, scale: 0.95 }"
                    :enter="{ opacity: 1, scale: 1 }"
                    class="bg-white border-2 border-dashed border-slate-200 rounded-[3rem] p-12 md:p-24 mb-8 text-center relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:20px_20px] opacity-30"
                    ></div>

                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-amber-50 text-amber-500 mb-6"
                        >
                            <ClockIcon class="w-10 h-10" />
                        </div>

                        <h2 class="text-3xl font-black text-slate-800 mb-3">
                            Profile Under Construction
                        </h2>

                        <p
                            class="text-slate-500 max-w-md mx-auto text-lg leading-relaxed"
                        >
                            Dr. {{ doctor.name }} is still
                            <span class="text-indigo-600 font-bold"
                                >finishing up their profile setup</span
                            >. Check back soon to see their full credentials and
                            expertise!
                        </p>

                        <div class="mt-10 flex flex-col items-center gap-4">
                            <div
                                class="flex items-center gap-3 px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100 shadow-sm"
                            >
                                <EnvelopeIcon class="w-5 h-5 text-slate-400" />
                                <span class="text-sm font-bold text-slate-600"
                                    >Contact: {{ doctor.email }}</span
                                >
                            </div>

                            <Link
                                href="/doctors"
                                class="text-sm font-black text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-2"
                            >
                                <ChevronLeftIcon class="w-4 h-4" />
                                Return to Directory
                            </Link>
                        </div>
                    </div>
                </div>

                <div
                    v-if="doctor.doctor_profile"
                    class="grid grid-cols-1 lg:grid-cols-3 gap-8"
                >
                    <div class="lg:col-span-2 space-y-8">
                        <div
                            class="bg-white border border-slate-200 rounded-[2.5rem] p-10 shadow-sm"
                        >
                            <h3
                                class="text-xl font-black text-slate-800 mb-8 flex items-center gap-3"
                            >
                                <UserIcon class="w-6 h-6 text-indigo-600" />
                                General Information
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                        >Age</span
                                    >
                                    <p class="text-lg font-bold text-slate-700">
                                        {{
                                            doctor.doctor_profile?.age || "N/A"
                                        }}
                                        Years Old
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                        >Gender</span
                                    >
                                    <p class="text-lg font-bold text-slate-700">
                                        {{
                                            doctor.doctor_profile?.gender ||
                                            "Not Specified"
                                        }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                        >Specialization</span
                                    >
                                    <p class="text-lg font-bold text-slate-700">
                                        {{
                                            doctor.doctor_profile?.expertise ||
                                            "General Staff"
                                        }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                        >Member Since</span
                                    >
                                    <p class="text-lg font-bold text-slate-700">
                                        Feb 2024
                                    </p>
                                </div>
                            </div>

                            <div
                                class="mt-10 p-6 bg-indigo-50/50 rounded-3xl border border-indigo-100/50"
                            >
                                <h4
                                    class="text-sm font-black text-indigo-900 mb-2"
                                >
                                    Practice Bio
                                </h4>
                                <p
                                    class="text-indigo-800/70 text-sm leading-relaxed"
                                >
                                    Dedicated
                                    {{
                                        doctor.doctor_profile?.expertise ||
                                        "Medical Professional"
                                    }}
                                    providing specialized care at
                                    {{
                                        doctor.doctor_profile?.address ||
                                        "our clinic"
                                    }}.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div
                            class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl"
                        >
                            <ShieldCheckIcon
                                class="w-12 h-12 text-indigo-400 mb-6"
                            />
                            <h3 class="text-2xl font-black mb-2">
                                Verified Expert
                            </h3>
                            <p
                                class="text-slate-400 text-sm mb-8 leading-relaxed"
                            >
                                Verification status for clinical practice and
                                professional credentials.
                            </p>
                        </div>

                        <div
                            v-if="doctor.doctor_profile?.address"
                            class="bg-white border border-slate-200 rounded-[2.5rem] p-8 shadow-sm"
                        >
                            <h4
                                class="text-sm font-black text-slate-800 mb-4 flex items-center gap-2"
                            >
                                <MapPinIcon class="w-5 h-5 text-indigo-500" />
                                Location
                            </h4>
                            <p class="text-sm text-slate-500 font-medium mb-4">
                                {{ doctor.doctor_profile?.address }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <BookedAppointment 
            :show="showModal" 
            :doctor="doctor" 
            @close="showModal = false" 
        />
    </AuthenticatedLayout>
</template>
