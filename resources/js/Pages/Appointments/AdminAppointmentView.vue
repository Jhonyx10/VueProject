<script setup>
import { computed, ref } from 'vue';
import { 
    UserGroupIcon, 
    CalendarIcon, 
    AdjustmentsHorizontalIcon,
    MagnifyingGlassIcon,
    TrashIcon,
    ShieldCheckIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointments: { type: Array, required: true },
    doctors: { type: Array, required: true }
});

const searchQuery = ref('');

// Admin sees EVERYTHING, but let's add a simple search filter
const filteredAppointments = computed(() => {
    return props.appointments.filter(app => {
        const search = searchQuery.value.toLowerCase();
        return (
            app.doctor?.doctor_profile?.lastName?.toLowerCase().includes(search) ||
            app.patient?.name?.toLowerCase().includes(search) ||
            app.status.toLowerCase().includes(search)
        );
    });
});
const formatTime = (time) => {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};
</script>

<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                    <CalendarIcon class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Bookings</p>
                    <p class="text-2xl font-black text-slate-800">{{ appointments.length }}</p>
                </div>
            </div>
            </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <ShieldCheckIcon class="w-7 h-7 text-indigo-600" />
                Appointment Master List
            </h2>
            
            <div class="relative max-w-sm w-full">
                <MagnifyingGlassIcon class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Search doctor or patient..." 
                    class="w-full pl-12 pr-4 py-3 bg-white border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-2xl text-sm transition-all"
                />
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Date & Time</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Patient</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Assigned Doctor</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="app in filteredAppointments" :key="app._id" class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">{{ new Date(app.date).toLocaleDateString() }}</span>
                                <span class="text-xs text-indigo-500 font-medium">{{ formatTime(app.time) }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold uppercase">
                                    {{ app.patient?.name?.charAt(0) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-800">{{ app.patient?.name }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-5 text-sm text-slate-600">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">
                                    Dr. {{ app.doctor?.doctor_profile?.lastName }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                                    {{ app.doctor?.doctor_profile?.expertise }}
                                </span>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <span :class="{
                                'bg-amber-50 text-amber-600 border-amber-100': app.status === 'pending',
                                'bg-green-50 text-green-600 border-green-100': app.status === 'confirmed',
                                'bg-red-50 text-red-600 border-red-100': app.status === 'cancelled',
                            }" class="px-3 py-1 rounded-lg text-[10px] font-black uppercase border">
                                {{ app.status }}
                            </span>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <button class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <TrashIcon class="w-5 h-5" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="filteredAppointments.length === 0" class="p-20 text-center">
                <p class="text-slate-400 font-medium">No appointments match your search criteria.</p>
            </div>
        </div>
    </div>
</template>