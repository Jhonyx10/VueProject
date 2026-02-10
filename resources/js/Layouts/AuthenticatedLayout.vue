<template>
    <div class="flex min-h-screen bg-slate-50 font-sans antialiased text-slate-900">
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col fixed h-full z-20 transition-all duration-300">
            <div class="h-20 flex items-center px-8">
                <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                <div class="p-2 bg-indigo-600 rounded-xl group-hover:rotate-6 transition-transform">
                    <ApplicationLogo class="h-6 w-6 fill-current text-white" />
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-800">MedCore</span>
                </Link>
            </div>

          <nav class="flex-1 px-3 space-y-1 mt-4">
        <p class="px-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 mb-4 opacity-70">
            Menu
        </p>
    
        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
            class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
            :class="route().current('dashboard')
                ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'">
        
            <div v-if="route().current('dashboard')" 
                class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full">
            </div>

            <HomeIcon class="h-5 w-5 transition-transform duration-300" 
                :class="route().current('dashboard') ? 'scale-110' : 'group-hover:scale-110'" />
        
            <span class="font-semibold tracking-wide text-sm">Dashboard</span>
        </NavLink>

        <NavLink v-if="isAdmin" :href="route('doctors')" :active="route().current('doctors')"
            class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
            :class="route().current('doctors')
                ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'">
        
            <div v-if="route().current('doctors')" 
                class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full">
            </div>

            <UserGroupIcon class="h-5 w-5 transition-transform duration-300" 
                :class="route().current('doctors') ? 'scale-110' : 'group-hover:scale-110'" />
        
            <span class="font-semibold tracking-wide text-sm">Doctors</span>
        </NavLink>

        <NavLink :href="route('appointments.index')" :active="route().current('appointments.index')"
            class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
            :class="route().current('appointments.index')
                ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'">
        
            <div v-if="route().current('appointments.index')" 
                class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full">
            </div>

            <CalendarDaysIcon class="h-5 w-5 transition-transform duration-300" 
                :class="route().current('appointments.index') ? 'scale-110' : 'group-hover:scale-110'" />
        
            <span class="font-semibold tracking-wide text-sm">Appointments</span>
        </NavLink>

         <NavLink :href="route('chat.index')" :active="route().current('chat.index')"
                class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                :class="route().current('chat.index')
                    ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                    : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'">
        
                <div v-if="route().current('chat.index')" 
                    class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full">
                </div>

                <ChatBubbleLeftIcon class="h-5 w-5 transition-transform duration-300" 
                    :class="route().current('chat.index') ? 'scale-110' : 'group-hover:scale-110'" />
        
                <span class="font-semibold tracking-wide text-sm">Chat</span>
            </NavLink>
    </nav>

            <div class="p-4 border-t border-slate-100">
                <div class="bg-slate-50 rounded-2xl p-4 flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-slate-500 capitalize">
                            {{ isAdmin ? 'Administrator' : (isDoctor ? 'Doctor' : 'User') }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col ml-64">
            <header
                class="h-20 flex items-center justify-between px-8 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-10">
                <div class="flex items-center">
                    <h2 class="text-lg font-semibold text-slate-800 capitalize">
                        {{ route().current().split('.')[0] }}
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-400 hover:bg-slate-100 rounded-full transition-colors relative">
                        <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </button>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-1 p-1 hover:bg-slate-100 rounded-lg transition-colors">
                                <div
                                    class="h-8 w-8 bg-slate-200 rounded-lg flex items-center justify-center text-slate-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                        </path>
                                    </svg>
                                </div>
                            </button>
                        </template>

                        <template #content>
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Manage Account</p>
                            </div>
                            <DropdownLink :href="route('profile.edit')">Account Settings</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-600">Log Out
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </header>

        <main class="p-0">
            <div class="max-w-7xl mx-auto">
                <slot />
            </div>
        </main>
    </div>
</div></template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import { useRole } from '@/composables/useRole';
import { HomeIcon, UserGroupIcon, CalendarDaysIcon, ChatBubbleLeftIcon } from '@heroicons/vue/24/solid';

const { isAdmin, isDoctor, isUser } = useRole();
</script>
