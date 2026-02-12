<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatLayout from '@/Pages/Chat/ChatLayout.vue'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Sample data for design preview
const chats = ref([
    { id: 1, name: 'Dr. Sarah Connor', lastMsg: 'The patient is ready...', time: '10:45 AM', active: true, online: true },
    { id: 2, name: 'John Doe (Patient)', lastMsg: 'Thank you, doctor.', time: 'Yesterday', active: false, online: false },
    { id: 3, name: 'Medical Lab', lastMsg: 'Results are attached.', time: 'Feb 8', active: false, online: true },
]);
</script>

<template>
    <Head title="Messages" />

    <AuthenticatedLayout>
        <div class="py-6 h-[calc(100vh-64px)]">
            <div class="max-w-7xl mx-auto h-full sm:px-6 lg:px-8">
                <div 
                    v-motion
                    :initial="{ opacity: 0, scale: 0.98 }"
                    :enter="{ opacity: 1, scale: 1, transition: { duration: 500 } }"
                    class="bg-white overflow-hidden shadow-xl sm:rounded-2xl flex h-full border border-slate-200"
                >
                    <div 
                        v-motion
                        :initial="{ opacity: 0, x: -40 }"
                        :enter="{ opacity: 1, x: 0, transition: { delay: 200, duration: 600 } }"
                        class="w-full md:w-80 lg:w-96 border-r border-slate-100 flex flex-col bg-slate-50/50"
                    >
                        <div class="p-4 bg-white border-b border-slate-100">
                            <h2 class="text-xl font-bold text-slate-800 mb-4">Messages</h2>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search chats..."
                                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all" />
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto">
                            <div v-for="(chat, index) in chats" :key="chat.id"
                                v-motion
                                :initial="{ opacity: 0, x: -20 }"
                                :enter="{ opacity: 1, x: 0, transition: { delay: 300 + (index * 100) } }"
                                :hover="{ backgroundColor: '#ffffff', x: 4 }"
                                :class="['flex items-center px-4 py-4 cursor-pointer transition-all border-l-4 relative',
                                    chat.active ? 'bg-white border-indigo-500 shadow-sm z-10' : 'border-transparent text-slate-500']"
                            >
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm">
                                        {{ chat.name.charAt(0) }}
                                    </div>
                                    <span v-if="chat.online"
                                        v-motion
                                        :initial="{ scale: 0 }"
                                        :enter="{ scale: 1, transition: { type: 'spring', delay: 800 + (index * 100) } }"
                                        class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 border-2 border-white"></span>
                                </div>
                                <div class="ml-3 overflow-hidden flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ chat.name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ chat.time }}</p>
                                    </div>
                                    <p class="text-xs truncate text-slate-500">{{ chat.lastMsg }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div 
                        v-motion
                        :initial="{ opacity: 0 }"
                        :enter="{ opacity: 1, transition: { delay: 500, duration: 800 } }"
                        class="hidden md:flex flex-1 flex-col bg-white"
                    >
                        <ChatLayout/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>