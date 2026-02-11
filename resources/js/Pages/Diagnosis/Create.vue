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
            <div class="bg-white border-b border-slate-200 px-6 py-4 mb-8">
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
                    <button @click="submit" :disabled="form.processing" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                        <ShieldCheckIcon class="w-5 h-5" />
                        Complete Diagnosis
                    </button>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div v-if="form.errors.condition_name" class="text-red-500 text-xs">{{ form.errors.condition_name }}</div>
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <ClockIcon class="w-4 h-4" /> Visit Context
                        </h3>
                        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 mb-4">
                            <p class="text-xs font-bold text-indigo-400 uppercase mb-1">Chief Complaint</p>
                            <p class="text-indigo-900 text-sm leading-relaxed">"{{ appointment.notes }}"</p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Appointment ID</span>
                                <span class="font-mono text-slate-700">
                                    #{{ appointment.appointment_id ? appointment.appointment_id.slice(-6).toUpperCase() : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm" :class="{ 'ring-2 ring-red-500': form.errors['vitals_at_time.bp'] || form.errors['vitals_at_time.temp'] }">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center justify-between gap-2">
                           <span class="flex items-center gap-2"> <BeakerIcon class="w-4 h-4" /> Vitals Snapshot</span>
                           <ExclamationTriangleIcon v-if="form.errors['vitals_at_time.bp'] || form.errors['vitals_at_time.temp']" class="w-4 h-4 text-red-500" />
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">BP (mmHg)</label>
                                <input v-model="form.vitals_at_time.bp" type="text" placeholder="120/80" :class="{ 'ring-1 ring-red-400': form.errors['vitals_at_time.bp'] }" class="w-full mt-1 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                                <p v-if="form.errors['vitals_at_time.bp']" class="text-[9px] text-red-500 mt-1 ml-2 font-bold uppercase tracking-tighter">{{ form.errors['vitals_at_time.bp'] }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2">Temp (°C)</label>
                                <input v-model="form.vitals_at_time.temp" type="text" placeholder="36.5" :class="{ 'ring-1 ring-red-400': form.errors['vitals_at_time.temp'] }" class="w-full mt-1 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                                <p v-if="form.errors['vitals_at_time.temp']" class="text-[9px] text-red-500 mt-1 ml-2 font-bold uppercase tracking-tighter">{{ form.errors['vitals_at_time.temp'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <ClipboardDocumentListIcon class="w-6 h-6 text-indigo-500" /> Assessment
                            </h3>
                            <div class="flex bg-slate-100 p-1 rounded-xl">
                                <button @click="form.type = 'primary'" type="button" :class="form.type === 'primary' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Primary</button>
                                <button @click="form.type = 'secondary'" type="button" :class="form.type === 'secondary' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Secondary</button>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Diagnosis Name</label>
                                <input v-model="form.condition_name" type="text" :class="{ 'ring-2 ring-red-500 border-transparent': form.errors.condition_name }" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-4 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="Enter condition name...">
                                <p v-if="form.errors.condition_name" class="text-xs text-red-500 mt-2 font-bold">{{ form.errors.condition_name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Severity</label>
                                    <select v-model="form.severity" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-3 text-sm">
                                        <option value="mild">Mild</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="severe">Severe</option>
                                        <option value="acute">Acute</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                                    <select v-model="form.status" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-3 text-sm">
                                        <option value="preliminary">Preliminary</option>
                                        <option value="final">Final Diagnosis</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-bold text-slate-700">Clinical Codes (ICD-10 / SNOMED)</label>
                                    <button @click="addCode" type="button" class="text-indigo-600 text-xs font-bold flex items-center gap-1 hover:underline">
                                        <PlusIcon class="w-3 h-3" /> Add Code
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <div v-for="(item, index) in form.clinical_codes" :key="index">
                                        <div class="flex gap-3">
                                            <input v-model="item.code" placeholder="Code" :class="{ 'ring-1 ring-red-400': form.errors[`clinical_codes.${index}.code`] }" class="flex-1 bg-slate-50 border-none rounded-xl text-sm">
                                            <select v-model="item.system" :class="{ 'ring-1 ring-red-400': form.errors[`clinical_codes.${index}.system`] }" class="bg-slate-50 border-none rounded-xl text-sm w-32">
                                                <option>ICD-10</option>
                                                <option>SNOMED</option>
                                            </select>
                                            <button v-if="form.clinical_codes.length > 1" @click="removeCode(index)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg">
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div v-if="form.errors[`clinical_codes.${index}.code`] || form.errors[`clinical_codes.${index}.system`]" class="flex gap-2 mt-1 ml-2">
                                            <p v-if="form.errors[`clinical_codes.${index}.code`]" class="text-[9px] text-red-500 font-bold uppercase tracking-tighter">{{ form.errors[`clinical_codes.${index}.code`] }}</p>
                                            <p v-if="form.errors[`clinical_codes.${index}.system`]" class="text-[9px] text-red-500 font-bold uppercase tracking-tighter">{{ form.errors[`clinical_codes.${index}.system`] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Clinical Notes & Observations</label>
                                <textarea v-model="form.notes" rows="4" class="w-full bg-slate-50 border-slate-100 rounded-2xl focus:ring-indigo-500 focus:bg-white" placeholder="Describe clinical findings..."></textarea>
                            </div>

                            <div class="flex items-center gap-2 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                                <input v-model="form.is_resolved" type="checkbox" id="resolved" class="w-5 h-5 rounded text-indigo-600 border-amber-200">
                                <label for="resolved" class="text-sm font-bold text-amber-800 tracking-tight">Mark condition as resolved</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>