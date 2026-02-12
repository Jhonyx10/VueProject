<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
    ShieldCheckIcon, 
    BeakerIcon, 
    ClipboardDocumentListIcon, 
    ClockIcon,
    ExclamationTriangleIcon,
    ChevronLeftIcon,
    PlusIcon,
    TrashIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: Object
});

const form = useForm({
    appointment_id: props.appointment?.appointment_id || '', 
    patient_id: props.appointment?.patient_id || '',
    type: 'primary',
    status: 'preliminary',
    condition_name: '',
    clinical_codes: [{ code: '', system: 'ICD-10' }],
    severity: 'moderate',
    onset_date: '',
    notes: '',
    vitals_at_time: {
        bp: '',
        temp: '',
        heart_rate: ''
    },
    is_resolved: false
});

const addCode = () => form.clinical_codes.push({ code: '', system: 'ICD-10' });
const removeCode = (index) => form.clinical_codes.splice(index, 1);

const submit = () => {
    form.post(route('diagnosis.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50/50 pb-12">
            <div 
                v-motion
                :initial="{ opacity: 0, y: -20 }"
                :enter="{ opacity: 1, y: 0 }"
                class="bg-white border-b border-slate-200 px-6 py-4 mb-8 sticky top-0 z-30 shadow-sm"
            >
                <div class="max-w-5xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link :href="route('appointments.index')" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
                            <ChevronLeftIcon class="w-5 h-5 text-slate-500" />
                        </Link>
                        <div>
                            <h1 class="text-xl font-bold text-slate-800">Clinical Consultation</h1>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Patient: {{ appointment.patient_name }}</p>
                        </div>
                    </div>
                    <button 
                        @click="submit" 
                        :disabled="form.processing" 
                        v-motion
                        :hover="{ scale: 1.02 }"
                        :tap="{ scale: 0.98 }"
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center gap-2"
                    >
                        <ShieldCheckIcon v-if="!form.processing" class="w-5 h-5" />
                        <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Saving...' : 'Complete Diagnosis' }}
                    </button>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="space-y-6">
                    <section 
                        v-motion
                        :initial="{ opacity: 0, x: -20 }"
                        :enter="{ opacity: 1, x: 0, transition: { delay: 100 } }"
                        class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm"
                    >
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <ClockIcon class="w-4 h-4" /> Visit Context
                        </h3>
                        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 mb-4 group hover:bg-indigo-100/50 transition-colors">
                            <p class="text-xs font-bold text-indigo-400 uppercase mb-1">Chief Complaint</p>
                            <p class="text-indigo-900 text-sm leading-relaxed italic">"{{ appointment.notes }}"</p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Appointment ID</span>
                                <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    #{{ appointment.appointment_id ? appointment.appointment_id.slice(-6).toUpperCase() : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </section>

                    <section 
                        v-motion
                        :initial="{ opacity: 0, x: -20 }"
                        :enter="{ opacity: 1, x: 0, transition: { delay: 200 } }"
                        class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm transition-all duration-500"
                        :class="{ 'ring-2 ring-red-500 shadow-lg shadow-red-50': form.errors['vitals_at_time.bp'] || form.errors['vitals_at_time.temp'] }"
                    >
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center justify-between gap-2">
                           <span class="flex items-center gap-2"> <BeakerIcon class="w-4 h-4 text-emerald-500" /> Vitals Snapshot</span>
                           <ExclamationTriangleIcon v-if="form.errors['vitals_at_time.bp'] || form.errors['vitals_at_time.temp']" class="w-4 h-4 text-red-500 animate-bounce" />
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">BP (mmHg)</label>
                                <input v-model="form.vitals_at_time.bp" type="text" placeholder="120/80" class="w-full mt-1 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 group-hover:bg-slate-100 transition-all">
                                <p v-if="form.errors['vitals_at_time.bp']" class="text-[9px] text-red-500 mt-1 ml-2 font-bold uppercase">{{ form.errors['vitals_at_time.bp'] }}</p>
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Temp (°C)</label>
                                <input v-model="form.vitals_at_time.temp" type="text" placeholder="36.5" class="w-full mt-1 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 group-hover:bg-slate-100 transition-all">
                                <p v-if="form.errors['vitals_at_time.temp']" class="text-[9px] text-red-500 mt-1 ml-2 font-bold uppercase">{{ form.errors['vitals_at_time.temp'] }}</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div 
                    v-motion
                    :initial="{ opacity: 0, scale: 0.98 }"
                    :enter="{ opacity: 1, scale: 1, transition: { delay: 150 } }"
                    class="lg:col-span-2 space-y-6"
                >
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <ClipboardDocumentListIcon class="w-6 h-6 text-indigo-500" /> Assessment
                            </h3>
                            <div class="flex bg-slate-100 p-1 rounded-xl">
                                <button v-for="t in ['primary', 'secondary']" :key="t" @click="form.type = t" type="button" 
                                    :class="form.type === t ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" 
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all capitalize"
                                >
                                    {{ t }}
                                </button>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="relative">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Diagnosis Name</label>
                                <input v-model="form.condition_name" type="text" 
                                    :class="{ 'ring-2 ring-red-500 border-transparent': form.errors.condition_name }" 
                                    class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 px-6 text-lg focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-300" 
                                    placeholder="e.g. Acute Respiratory Infection">
                                <ExclamationTriangleIcon v-if="form.errors.condition_name" class="w-5 h-5 text-red-500 absolute right-4 top-12" />
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Severity</label>
                                    <select v-model="form.severity" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-3 text-sm focus:ring-indigo-500">
                                        <option value="mild">Mild</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="severe">Severe</option>
                                        <option value="acute">Acute</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                                    <select v-model="form.status" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-3 text-sm focus:ring-indigo-500">
                                        <option value="preliminary">Preliminary</option>
                                        <option value="final">Final Diagnosis</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <label class="text-sm font-bold text-slate-700">Clinical Codes</label>
                                    <button @click="addCode" type="button" class="text-indigo-600 text-xs font-bold flex items-center gap-1 hover:bg-indigo-50 px-3 py-1 rounded-lg transition-colors">
                                        <PlusIcon class="w-3 h-3" /> Add Code
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <TransitionGroup 
                                        enter-active-class="transition duration-300 ease-out"
                                        enter-from-class="transform opacity-0 -translate-x-4"
                                        enter-to-class="transform opacity-100 translate-x-0"
                                        leave-active-class="transition duration-200 ease-in"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95"
                                    >
                                        <div v-for="(item, index) in form.clinical_codes" :key="index" class="flex gap-3 group">
                                            <input v-model="item.code" placeholder="ICD-10 Code" class="flex-1 bg-slate-50 border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                                            <select v-model="item.system" class="bg-slate-50 border-slate-100 rounded-xl text-sm w-32 focus:ring-2 focus:ring-indigo-500">
                                                <option>ICD-10</option>
                                                <option>SNOMED</option>
                                            </select>
                                            <button v-if="form.clinical_codes.length > 1" @click="removeCode(index)" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </TransitionGroup>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Clinical Notes & Observations</label>
                                <textarea v-model="form.notes" rows="4" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-indigo-500 focus:bg-white transition-all" placeholder="Enter detailed clinical findings..."></textarea>
                            </div>

                            <div 
                                class="flex items-center gap-3 p-5 rounded-2xl border-2 transition-all cursor-pointer"
                                :class="form.is_resolved ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-100'"
                                @click="form.is_resolved = !form.is_resolved"
                            >
                                <div class="relative flex items-center">
                                    <input v-model="form.is_resolved" type="checkbox" class="w-6 h-6 rounded-md text-emerald-600 border-slate-300 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <p class="text-sm font-bold tracking-tight" :class="form.is_resolved ? 'text-emerald-800' : 'text-amber-800'">
                                        Mark condition as resolved
                                    </p>
                                    <p class="text-xs opacity-70" :class="form.is_resolved ? 'text-emerald-600' : 'text-amber-600'">
                                        This will move the diagnosis to the patient's history.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>