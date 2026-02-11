<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useRole } from '@/composables/useRole';
import { 
    ArrowLeftIcon, 
    UserIcon, 
    ClipboardDocumentCheckIcon,
    BeakerIcon,
    CalendarIcon
} from '@heroicons/vue/24/outline';

const { isDoctor } = useRole();

const props = defineProps({
    diagnosis: { type: Object, required: true }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Diagnosis Details" />

    <AuthenticatedLayout>
        <div class="pb-20 bg-slate-50/50 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                
                <div class="py-6">
                    <Link :href="route('appointments.index')" class="inline-flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition-all font-bold text-sm group">
                        <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100">
                            <ArrowLeftIcon class="w-4 h-4" />
                        </div>
                        Back to Appointments
                    </Link>
                </div>

                <div class="bg-white rounded-[3rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 overflow-hidden">
                    <div class="bg-slate-900 p-8 md:p-12 text-white relative overflow-hidden">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="bg-indigo-500 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full">Official Medical Record</span>
                                    <span class="text-slate-400 text-sm font-mono opacity-60">REF-{{ diagnosis.id.slice(-6).toUpperCase() }}</span>
                                </div>
                                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-2">
                                    {{ diagnosis.patient?.name || 'Patient Report' }}
                                </h1>
                                <div class="flex items-center gap-6 text-slate-400 text-sm">
                                    <div class="flex items-center gap-2">
                                        <CalendarIcon class="w-4 h-4 text-indigo-400" />
                                        {{ formatDate(diagnosis.created_at) }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <UserIcon class="w-4 h-4 text-indigo-400" />
                                        Dr. {{ diagnosis.doctor.doctor_profile.lastName }}
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md p-5 rounded-3xl border border-white/10 text-center min-w-[140px]">
                                <p class="text-[10px] font-bold text-indigo-300 uppercase mb-1 tracking-widest">Status</p>
                                <p class="text-xl font-black text-white uppercase tracking-tight">Finalized</p>
                            </div>
                        </div>
                        <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-[100px]"></div>
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                            
                            <div class="lg:col-span-4 space-y-10">
                                <section v-if="!isDoctor">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Attending Physician</h3>
                                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-3xl border border-slate-100">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                                            DR
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">Dr. {{ diagnosis.doctor.doctor_profile.lastName }}</p>
                                            <p class="text-xs text-slate-500">{{ diagnosis.doctor.doctor_profile.expertise }}</p>
                                        </div>
                                    </div>
                                </section>
                                <section v-if="diagnosis.appointment.notes">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Chief Complaint</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <p class="text-md font-black text-slate-800">{{ diagnosis.appointment.notes }}</p>
                                    </div>
                                </section>
                                <section v-if="diagnosis.vitals_at_time">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Baseline Vitals</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div v-for="(val, key) in diagnosis.vitals_at_time" :key="key" 
                                             class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:border-indigo-100 transition-colors">
                                            <p class="text-[9px] font-black text-slate-400 uppercase mb-1">{{ key.replace('_', ' ') }}</p>
                                            <p class="text-lg font-black text-slate-800">{{ val }}</p>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="lg:col-span-8 space-y-12">
                                <section>
                                    <div class="flex items-center gap-2 mb-6">
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                        <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] px-4">Clinical Findings</span>
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                    </div>

                                    <div class="mb-8">
                                        <h3 class="text-sm font-bold text-indigo-600 uppercase mb-2">Primary Diagnosis</h3>
                                        <div class="text-4xl font-black text-slate-800 tracking-tight">
                                            {{ diagnosis.condition_name || 'General Observation' }}
                                        </div>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-3">Physician Notes</h3>
                                        <div class="bg-indigo-50/30 rounded-[2.5rem] p-8 border border-indigo-100/50 text-slate-700 leading-relaxed text-xl font-medium italic">
                                            "{{ diagnosis.notes || 'No clinical notes were recorded for this session.' }}"
                                        </div>
                                    </div>
                                </section>

                                <section v-if="diagnosis.clinical_codes?.length">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Medical Classification Codes</h3>
                                    <div class="flex flex-wrap gap-3">
                                        <div v-for="item in diagnosis.clinical_codes" :key="item.code" 
                                            class="flex items-center bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <span class="bg-slate-50 px-4 py-3 text-[10px] font-black text-slate-500 border-r border-slate-100">
                                                {{ item.system }}
                                            </span>
                                            <span class="px-4 py-3 text-sm font-black text-indigo-600">
                                                {{ item.code }}
                                            </span>
                                        </div>
                                    </div>
                                </section>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>