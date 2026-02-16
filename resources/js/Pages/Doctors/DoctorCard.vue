<template>
  <div
    v-if="shouldShowCard"
    v-motion
    :initial="{ opacity: 0, y: 20 }"
    :enter="{ opacity: 1, y: 0 }"
    :hover="{ y: -4 }"
    class="group bg-white border mt-4 border-slate-200 shadow-sm rounded-2xl p-5 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-50/50 transition-all duration-300 ease-in-out max-w-sm relative overflow-hidden"
  >
    <div class="absolute top-0 right-0 -mt-4 -mr-4 h-16 w-16 bg-indigo-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

    <div class="flex items-center gap-4 mb-6 relative z-10">
      <div class="relative flex-shrink-0">
        <div class="h-14 w-14 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 group-hover:rotate-3 transition-all duration-300">
          {{ doctor.doctor_profile?.firstName?.charAt(0) || doctor.name.charAt(0) }}
        </div>
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
        </span>
      </div>

      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <h2 class="text-base font-bold text-slate-800 truncate leading-tight group-hover:text-indigo-600 transition-colors">
            {{ doctor.doctor_profile ? `Dr. ${doctor.doctor_profile.firstName} ${doctor.doctor_profile.lastName}` : doctor.name }}
          </h2>
          
          <span class="px-2 py-0.5 rounded-md bg-slate-100 group-hover:bg-indigo-100 group-hover:text-indigo-600 text-[10px] font-bold text-slate-500 uppercase tracking-tight transition-colors">
            {{ doctor.doctor_profile?.expertise || 'Staff' }}
          </span>
        </div>
        <p class="text-slate-500 text-xs truncate mt-1 flex items-center gap-1 group-hover:text-slate-600">
          <svg class="h-3.5 w-3.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ doctor.email }}
        </p>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-slate-50 relative z-10">
      <div class="flex flex-col">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Joined</span>
        <span class="text-xs font-semibold text-slate-600">
            {{ formattedDate }}
        </span>
      </div>

      <Link
        :href="route('doctors_profile', doctor.id)"
        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 text-slate-700 text-sm font-semibold rounded-lg border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-lg hover:shadow-indigo-200 transition-all duration-200 active:scale-95"
      >
        <span>View Profile</span>
        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useRole } from '@/composables/useRole.js';

const { isUser } = useRole();

const props = defineProps({
  doctor: {
    type: Object,
    required: true
  }
});

// Format the created_at date (e.g., "Feb 2024")
const formattedDate = computed(() => {
  const date = new Date(props.doctor.created_at);
  return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
});

const shouldShowCard = computed(() => {
  // If the visitor is a regular user, only show doctors with a profile
  if (isUser.value) {
    return !!(props.doctor.doctor_profile || props.doctor.doctorProfile);
  }
  // Admins see all doctor accounts
  return true;
});
</script>