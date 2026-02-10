<template>
  <div
    class="group bg-white border border-slate-200 shadow-sm rounded-2xl p-5 hover:border-indigo-300 hover:shadow-md transition-all duration-300 ease-in-out max-w-sm relative overflow-hidden">
    <div
      class="absolute top-0 right-0 -mt-4 -mr-4 h-16 w-16 bg-indigo-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
    </div>

    <div class="flex items-center gap-4 mb-6">
      <div class="relative flex-shrink-0">
        <div
          class="h-14 w-14 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all duration-300">
          {{ doctor.name.charAt(0) }}
        </div>
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
        </span>
      </div>

      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <h2 class="text-base font-bold text-slate-800 truncate leading-tight">
            {{ doctor.name }}
          </h2>
          <span
            class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-tight">Staff</span>
        </div>
        <p class="text-slate-500 text-xs truncate mt-1 flex items-center gap-1">
          <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          {{ doctor.email }}
        </p>
      </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
      <div class="flex flex-col">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Joined</span>
        <span class="text-xs font-semibold text-slate-600">Feb 2024</span>
      </div>

      <button
        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 text-slate-700 text-sm font-semibold rounded-lg border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 active:scale-95">
        <span>View Profile</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  doctor: {
    type: Object,
    required: true
  }
});
</script>