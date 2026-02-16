<script setup>
import { computed, ref, onMounted } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3"; // Cleaned up imports
import CancelAppointment from "@/Pages/Appointments/CancelAppointment.vue";
import DeleteConfirmModal from "@/Pages/Appointments/DeleteConfirmModal.vue";
import {
    TrashIcon,
    FolderOpenIcon,
    XCircleIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    FunnelIcon
} from "@heroicons/vue/24/outline";

const props = defineProps({
    appointments: { type: Array, required: true },
});

const authUser = usePage().props.auth.user;
const showDeleteModal = ref(false);
const idToDelete = ref(null);
const showCancelModal = ref(false);
const selectedAppointmentId = ref(null);

// --- Pagination & Filtering State ---
const currentFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = 5;

const filters = ['all', 'pending', 'confirmed', 'complete', 'cancelled', 'expired'];

const triggerCancel = (id) => {
    selectedAppointmentId.value = id;
    showCancelModal.value = true;
};

const triggerDelete = (id) => {
    idToDelete.value = id;
    showDeleteModal.value = true;
};

// 1. Filter by User AND Status
const filteredAppointments = computed(() => {
    let list = props.appointments.filter(
        (app) => app.patient_id === authUser.id || app.patient_id === authUser._id
    );

    if (currentFilter.value !== 'all') {
        list = list.filter(app => app.status === currentFilter.value);
    }
    
    // Sort by date (newest first)
    return list.sort((a, b) => new Date(b.date) - new Date(a.date));
});

// 2. Paginate the filtered results
const paginatedAppointments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredAppointments.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredAppointments.value.length / itemsPerPage));

const setFilter = (filter) => {
    currentFilter.value = filter;
    currentPage.value = 1; // Reset to page 1 on filter change
};

// Formatting & Auto-Expire logic remains same
const formatTime = (time) => {
    if (!time) return "";
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const h = hour % 12 || 12;
    return `${h}:${minutes} ${ampm}`;
};

onMounted(() => {
    // Expiry check (added 40 min grace period logic from previous context)
    filteredAppointments.value.forEach(app => {
        if (['pending', 'confirmed'].includes(app.status)) {
            const appDateTime = new Date(`${app.date}T${app.time}`);
            const expiryTime = new Date(appDateTime.getTime() + 40 * 60000); 
            if (new Date() > expiryTime) {
                router.patch(route('appointments.update', app._id || app.id), { action: 'expire' }, { preserveScroll: true });
            }
        }
    });
});
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">My Schedule</h2> 
            
            <div class="flex flex-wrap gap-2">
                <button 
                    v-for="filter in filters" 
                    :key="filter"
                    @click="setFilter(filter)"
                    :class="currentFilter === filter ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-50 border-slate-100'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border transition-all"
                >
                    {{ filter }}
                </button>
            </div>
        </div>

        <div v-if="filteredAppointments.length === 0" class="bg-white rounded-[32px] border border-dashed border-slate-300 p-12 text-center shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-2">No {{ currentFilter }} appointments</h3>
            <p class="text-slate-500">Try changing your filter or scheduling a new visit.</p>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="(app, index) in paginatedAppointments"
                :key="app._id || app.id"
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all"
            >
                <div class="bg-indigo-600 text-white px-4 py-3 rounded-2xl text-center min-w-[75px]">
                    <p class="text-[10px] font-bold uppercase opacity-80">{{ new Date(app.date).toLocaleString("default", { month: "short" }) }}</p>
                    <p class="text-2xl font-black">{{ new Date(app.date).getDate() }}</p>
                </div>

                <div class="flex-1">
                    <h4 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">
                        Dr. {{ app.doctor?.doctor_profile?.lastName || 'TBD' }}
                    </h4>
                    <div class="text-slate-500 text-sm">Scheduled for {{ formatTime(app.time) }}</div>
                </div>

                <div class="flex items-center gap-4">
                    <span
                        :class="{
                            'bg-amber-50 text-amber-600 border-amber-100': app.status === 'pending',
                            'bg-blue-50 text-blue-600 border-blue-100': app.status === 'confirmed',
                            'bg-green-50 text-green-600 border-green-100': app.status === 'complete',
                            'bg-red-50 text-red-600 border-red-100': ['cancelled', 'expired'].includes(app.status),
                        }"
                        class="hidden sm:block px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border"
                    >
                        {{ app.status }}
                    </span>

                    <button v-if="app.status === 'cancelled'" @click="triggerDelete(app._id || app.id)" class="p-2 text-red-400 hover:text-red-600"><TrashIcon class="w-5 h-5" /></button>
                    <button v-if="['pending', 'confirmed'].includes(app.status)" @click="triggerCancel(app._id || app.id)" class="p-2 text-slate-300 hover:text-red-500"><XCircleIcon class="w-5 h-5" /></button>
                    <Link v-if="app.status === 'complete'" :href="route('diagnosis.show', { diagnosis: app._id || app.id })" class="bg-green-50 text-green-600 p-3 rounded-2xl"><FolderOpenIcon class="w-6 h-6" /></Link>
                </div>
            </div>

            <div v-if="totalPages > 1" class="flex items-center justify-center gap-4 pt-4">
                <button 
                    @click="currentPage--" 
                    :disabled="currentPage === 1"
                    class="p-2 rounded-xl bg-white border border-slate-200 disabled:opacity-30 transition-all"
                >
                    <ChevronLeftIcon class="w-5 h-5" />
                </button>
                <span class="text-sm font-bold text-slate-600">Page {{ currentPage }} of {{ totalPages }}</span>
                <button 
                    @click="currentPage++" 
                    :disabled="currentPage === totalPages"
                    class="p-2 rounded-xl bg-white border border-slate-200 disabled:opacity-30 transition-all"
                >
                    <ChevronRightIcon class="w-5 h-5" />
                </button>
            </div>
        </div>

        <CancelAppointment :show="showCancelModal" :appointment_id="selectedAppointmentId" @close="showCancelModal = false" />
        <DeleteConfirmModal :show="showDeleteModal" :appointmentId="idToDelete" @close="showDeleteModal = false" />
    </div>
</template>
