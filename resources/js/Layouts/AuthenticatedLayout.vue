<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-48 bg-blue-600 text-white flex flex-col fixed h-full">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-blue-600">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                <ApplicationLogo class="h-8 w-auto fill-current text-white" />
                <span class="font-semibold text-lg">Admin</span>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                    :class="[
                        'flex items-center gap-2 px-4 py-2 rounded-lg  text-gray-300 hover:bg-slate-700 hover:text-white',
                        route().current('dashboard') ? 'bg-blue-900 text-white' : ''
                    ]">
                    <HomeIcon class="h-5 w-5" />
                    Dashboard
                </NavLink>

                <NavLink v-if="isAdmin" :href="route('doctors')" :active="route().current('doctors')"
                     :class="[
                         'flex items-center gap-2 px-6 py-2 rounded-lg  text-gray-300 hover:bg-slate-700 hover:text-white',
                         route().current('doctors') ? 'bg-blue-900 text-white' : ''
                     ]">
                     <UserGroupIcon class="h-5 w-5" />
                    Doctors
                </NavLink>

                <NavLink :href="route('appointments.index')" :active="route().current('appointments.index')"
                     :class="[
                         'flex items-center gap-2 px-4 py-2 rounded-lg  text-gray-300 hover:bg-slate-700 hover:text-white',
                         route().current('appointments.index') ? 'bg-blue-900 text-white' : ''
                     ]">
                    <CalendarDaysIcon class="h-5 w-5" />
                    Appointments
                </NavLink>
            </nav>

            <!-- Footer / Logout -->
            <div class="p-4">
                <NavLink :href="route('logout')" method="post" as="button"
                    class="w-full text-left px-4 py-2 rounded text-gray-300 hover:bg-red-600 hover:text-white">
                    Settings
                </NavLink>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col ml-48">
             <nav class="bg-blue-600 h-16 flex items-center justify-end px-4 fixed left-48 right-0 z-10">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button class="inline-flex items-center px-3 py-2 text-white rounded-md">
                            {{ $page.props.auth.user.name }}
                            <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                </Dropdown>
            </nav>

            <!-- Main Content -->
            <main class="flex-1 pt-16 px-6 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
import { useRole } from '@/composables/useRole';
import { HomeIcon, UserGroupIcon, CalendarDaysIcon } from '@heroicons/vue/24/solid';

const { isAdmin, isDoctor, isUser } = useRole();
</script>
