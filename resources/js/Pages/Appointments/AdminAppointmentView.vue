<script setup>
import { computed, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { 
    UserGroupIcon, 
    CalendarIcon, 
    FolderOpenIcon,
    AdjustmentsHorizontalIcon,
    MagnifyingGlassIcon,
    TrashIcon,
    ShieldCheckIcon,
    FolderIcon,
    ChevronLeftIcon, // Add these for pagination
    ChevronRightIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointments: { type: Array, required: true },
    doctors: { type: Array, required: true }
});

const searchQuery = ref('');
const statusQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

// Reset to page 1 when searching
watch(searchQuery, () => {
    currentPage.value = 1;
});

const pendingCount = computed(() => {
    return props.appointments.filter(app => app.status === 'pending').length;
});

const completedCount = computed(() => {
    return props.appointments.filter(app => app.status === 'complete').length;
});

const filteredAppointments = computed(() => {
    const search = searchQuery.value.toLowerCase();
    const status = statusQuery.value.toLowerCase();

    return props.appointments.filter(app => {

        const matchesSearch = !search || (
            app.doctor?.doctor_profile?.lastName?.toLowerCase().includes(search) ||
            app.patient?.name?.toLowerCase().includes(search) ||
            app.status.toLowerCase().includes(search)
        );

        const matchesStatus = !status || app.status.toLowerCase() === status;

        return matchesSearch && matchesStatus;
    });
});

const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredAppointments.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredAppointments.value.length / itemsPerPage);
});

watch(statusQuery, () => {
    currentPage.value = 1;
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
             <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 text-amber-600 rounded-2xl flex items-center justify-center">
                    <CalendarIcon class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-amber-600 uppercase">Pending Bookings</p>
                    <p class="text-2xl font-black text-amber-600">{{ pendingCount }}</p>
                </div>
            </div>
             <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 text-green-600 rounded-2xl flex items-center justify-center">
                    <CalendarIcon class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-green-600 uppercase">Completed Bookings</p>
                    <p class="text-2xl font-black text-green-800">{{ completedCount }}</p>
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
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <div class="flex items-center gap-4">
                            <span>Status</span>
                            <select 
                                v-model="statusQuery"
                                class="ml-2 p-2 bg-transparent border-none text-[10px] font-black uppercase tracking-widest text-indigo-600 focus:ring-0 cursor-pointer"
                            >
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="complete">Complete</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="app in paginatedAppointments" :key="app._id" class="hover:bg-slate-50/30 transition-colors group">
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
                                'bg-blue-50 text-blue-600 border-green-100': app.status === 'confirmed',
                                'bg-green-50 text-green-600 border-green-100': app.status === 'complete',
                                'bg-red-50 text-red-600 border-red-100': app.status === 'cancelled',
                            }" class="px-3 py-1 rounded-lg text-[10px] font-black uppercase border">
                                {{ app.status }}
                            </span>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link 
                                    v-if="app.status === 'complete'"
                                    :href="route('diagnosis.show', { diagnosis: app._id || app.id })"
                                    class="p-2 text-slate-300 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all tooltip"
                                    title="View Diagnosis"
                                >
                                    <FolderOpenIcon class="w-5 h-5" />
                                </Link>

                                <button 
                                    class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    title="Delete Appointment"
                                    @click="deleteAppointment(app._id)"
                                >
                                    <TrashIcon class="w-5 h-5" />
                                </button> 
                            </div>                   
                        </td>
                    </tr>
                </tbody>
            </table>

           <div v-if="filteredAppointments.length === 0" class="p-20 text-center">
                <p class="text-slate-400 font-medium">No appointments match your search criteria.</p>
            </div>

            <div v-if="filteredAppointments.length > 0" class="px-8 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    Showing {{ ((currentPage - 1) * itemsPerPage) + 1 }} to 
                    {{ Math.min(currentPage * itemsPerPage, filteredAppointments.length) }} 
                    of {{ filteredAppointments.length }}
                </p>
                
                <div class="flex items-center gap-2">
                    <button 
                        @click="currentPage--" 
                        :disabled="currentPage === 1"
                        class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors"
                    >
                        <ChevronLeftIcon class="w-5 h-5" />
                    </button>

                    <div class="flex items-center gap-1">
                        <button 
                            v-for="page in totalPages" 
                            :key="page"
                            @click="currentPage = page"
                            :class="[
                                'w-10 h-10 rounded-xl text-sm font-bold transition-all',
                                currentPage === page 
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' 
                                    : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'
                            ]"
                        >
                            {{ page }}
                        </button>
                    </div>

                    <button 
                        @click="currentPage++" 
                        :disabled="currentPage === totalPages"
                        class="p-2 rounded-xl border border-slate-200 bg-white text-slate-600 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors"
                    >
                        <ChevronRightIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>