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
                
                <div class="py-6" v-motion :initial="{ opacity: 0, x: -10 }" :enter="{ opacity: 1, x: 0 }">
                    <Link :href="route('diagnosis.index')" class="inline-flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition-all font-bold text-sm group">
                        <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 group-hover:scale-110 transition-all">
                            <ArrowLeftIcon class="w-4 h-4" />
                        </div>
                        Back
                    </Link>
                </div>

                <div 
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :enter="{ opacity: 1, y: 0, transition: { type: 'spring', damping: 25, stiffness: 100 } }"
                    class="bg-white rounded-[3rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 overflow-hidden"
                >
                    <div class="bg-slate-900 p-8 md:p-12 text-white relative overflow-hidden">
                        <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="bg-indigo-500 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full">
                                        Official Medical Record
                                    </span>
                                    <span class="text-slate-400 text-sm font-mono opacity-60">
                                        REF-{{ diagnosis.id.slice(-6).toUpperCase() }}
                                    </span>
                                </div>
                                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-4">
                                    {{ diagnosis.patient?.name || 'Patient Report' }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-6 text-slate-400 text-sm">
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

                            <div 
                                v-motion
                                :initial="{ scale: 0.8, opacity: 0 }"
                                :enter="{ scale: 1, opacity: 1, transition: { delay: 400 } }"
                                class="bg-white/10 backdrop-blur-md p-5 rounded-3xl border border-white/10 text-center min-w-[140px] relative overflow-hidden"
                            >
                                <p class="text-[10px] font-bold text-indigo-300 uppercase mb-1 tracking-widest">Status</p>
                                <p class="text-xl font-black text-white uppercase tracking-tight">Finalized</p>
                                <div class="absolute inset-0 bg-white/5 animate-pulse pointer-events-none"></div>
                            </div>
                        </div>
                        <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-[100px]"></div>
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                            
                            <div class="lg:col-span-4 space-y-10">
                                <section v-if="!isDoctor" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 300 } }">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Attending Physician</h3>
                                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-3xl border border-slate-100 hover:bg-white hover:border-indigo-100 transition-all cursor-default group">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500">
                                            DR
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">Dr. {{ diagnosis.doctor.doctor_profile.lastName }}</p>
                                            <p class="text-xs text-slate-500">{{ diagnosis.doctor.doctor_profile.expertise }}</p>
                                        </div>
                                    </div>
                                </section>

                                <section v-if="diagnosis.appointment?.notes" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 400 } }">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Chief Complaint</h3>
                                    <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100">
                                        <p class="text-md font-bold text-slate-800 italic leading-relaxed">
                                            "{{ diagnosis.appointment.notes }}"
                                        </p>
                                    </div>
                                </section>

                                <section v-if="diagnosis.vitals_at_time" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 500 } }">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Baseline Vitals</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div v-for="(val, key) in diagnosis.vitals_at_time" :key="key" 
                                             class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:border-indigo-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                            <p class="text-[9px] font-black text-slate-400 uppercase mb-1">{{ key.replace('_', ' ') }}</p>
                                            <p class="text-lg font-black text-slate-800">{{ val }}</p>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="lg:col-span-8 space-y-12">
                                <section v-motion :initial="{ opacity: 0, y: 20 }" :enter="{ opacity: 1, y: 0, transition: { delay: 600 } }">
                                    <div class="flex items-center gap-2 mb-8">
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                        <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] px-4">Clinical Findings</span>
                                        <div class="h-px flex-1 bg-slate-100"></div>
                                    </div>

                                    <div class="mb-10">
                                        <h3 class="text-sm font-bold text-indigo-600 uppercase mb-2">Primary Diagnosis</h3>
                                        <div class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
                                            {{ diagnosis.condition_name || 'General Observation' }}
                                        </div>
                                    </div>

                                    <div class="mb-10">
                                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-3">Physician Notes</h3>
                                        <div class="bg-indigo-50/30 rounded-[2.5rem] p-8 md:p-10 border border-indigo-100/50 text-slate-700 leading-relaxed text-xl font-medium italic relative">
                                            <div class="absolute top-4 left-4 text-indigo-200 text-6xl font-serif select-none pointer-events-none opacity-50">“</div>
                                            "{{ diagnosis.notes || 'No clinical notes were recorded for this session.' }}"
                                        </div>
                                    </div>
                                </section>

                                <section v-if="diagnosis.clinical_codes?.length" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 700 } }">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Medical Classification Codes</h3>
                                    <div class="flex flex-wrap gap-3">
                                        <div v-for="item in diagnosis.clinical_codes" :key="item.code" 
                                            class="flex items-center bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-indigo-100 hover:border-indigo-200 transition-all group">
                                            <span class="bg-slate-50 px-4 py-3 text-[10px] font-black text-slate-500 border-r border-slate-100 group-hover:bg-indigo-50 group-hover:text-indigo-400 transition-colors">
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