<template>
    <div
        class="flex min-h-screen bg-slate-50 font-sans antialiased text-slate-900"
    >
        <div
            v-if="isMobileMenuOpen"
            @click="isMobileMenuOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden backdrop-blur-sm transition-opacity"
        ></div>

        <aside
            :class="
                isMobileMenuOpen
                    ? 'translate-x-0'
                    : '-translate-x-full lg:translate-x-0'
            "
            class="w-64 bg-white border-r border-slate-200 flex flex-col fixed h-full z-40 transition-transform duration-300 ease-in-out"
        >
            <div class="h-20 flex items-center justify-between px-8">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 group"
                >
                    <div
                        class="p-2 bg-indigo-600 rounded-xl group-hover:rotate-6 transition-transform"
                    >
                        <ApplicationLogo
                            class="h-6 w-6 fill-current text-white"
                        />
                    </div>
                    <span
                        class="font-bold text-xl tracking-tight text-slate-800"
                        >MedCare</span
                    >
                </Link>
                <button
                    @click="isMobileMenuOpen = false"
                    class="lg:hidden p-2 text-slate-500"
                >
                    <XMarkIcon class="h-6 w-6" />
                </button>
            </div>

            <nav class="flex-1 px-3 space-y-1 mt-4">
                <p
                    class="px-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 mb-4 opacity-70"
                >
                    Menu
                </p>

                <NavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                    :class="
                        route().current('dashboard')
                            ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                            : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'
                    "
                >
                    <div
                        v-if="route().current('dashboard')"
                        class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full"
                    ></div>
                    <HomeIcon
                        class="h-5 w-5"
                        :class="
                            route().current('dashboard')
                                ? 'scale-110'
                                : 'group-hover:scale-110'
                        "
                    />
                    <span class="font-semibold tracking-wide text-sm"
                        >Dashboard</span
                    >
                </NavLink>

                <NavLink
                    v-if="isAdmin || isUser"
                    :href="route('doctors')"
                    :active="route().current('doctors')"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                    :class="
                        route().current('doctors')
                            ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                            : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'
                    "
                >
                    <div
                        v-if="route().current('doctors')"
                        class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full"
                    ></div>
                    <UserGroupIcon
                        class="h-5 w-5"
                        :class="
                            route().current('doctors')
                                ? 'scale-110'
                                : 'group-hover:scale-110'
                        "
                    />
                    <span class="font-semibold tracking-wide text-sm"
                        >Doctors</span
                    >
                </NavLink>

                <NavLink
                    :href="route('appointments.index')"
                    :active="route().current('appointments.index')"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                    :class="
                        route().current('appointments.index')
                            ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                            : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'
                    "
                >
                    <div
                        v-if="route().current('appointments.index')"
                        class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full"
                    ></div>
                    <CalendarDaysIcon
                        class="h-5 w-5"
                        :class="
                            route().current('appointments.index')
                                ? 'scale-110'
                                : 'group-hover:scale-110'
                        "
                    />
                    <span class="font-semibold tracking-wide text-sm"
                        >Appointments</span
                    >
                </NavLink>

                <NavLink
                    :href="route('diagnosis.index')"
                    :active="route().current('diagnosis.index')"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                    :class="
                        route().current('diagnosis.index')
                            ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                            : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'
                    "
                >
                    <div
                        v-if="route().current('diagnosis.index')"
                        class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full"
                    ></div>
                    <CalendarDaysIcon
                        class="h-5 w-5"
                        :class="
                            route().current('diagnosis.index')
                                ? 'scale-110'
                                : 'group-hover:scale-110'
                        "
                    />
                    <span class="font-semibold tracking-wide text-sm"
                        >Medical Records</span
                    >
                </NavLink>

                <NavLink
                    :href="route('chat.index')"
                    :active="route().current('chat.index')"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 ease-out overflow-hidden"
                    :class="
                        route().current('chat.index')
                            ? 'bg-indigo-600 shadow-lg shadow-indigo-200 text-white w-full'
                            : 'text-slate-500 hover:bg-white hover:shadow-sm hover:text-indigo-600 w-full'
                    "
                >
                    <div
                        v-if="route().current('chat.index')"
                        class="absolute left-0 top-3 bottom-3 w-1 bg-white rounded-r-full"
                    ></div>
                    <ChatBubbleLeftIcon
                        class="h-5 w-5"
                        :class="
                            route().current('chat.index')
                                ? 'scale-110'
                                : 'group-hover:scale-110'
                        "
                    />
                    <span class="font-semibold tracking-wide text-sm"
                        >Chat</span
                    >
                </NavLink>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <div
                    class="bg-slate-50 rounded-2xl p-4 flex items-center gap-3"
                >
                    <div
                        class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm shrink-0"
                    >
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-sm font-semibold text-slate-800 truncate"
                        >
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p class="text-xs text-slate-500 capitalize">
                            {{
                                isAdmin ? "Admin" : isDoctor ? "Doctor" : "User"
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div
            class="flex-1 flex flex-col transition-all duration-300 lg:ml-64 w-full"
        >
            <header
                class="h-20 flex items-center justify-between px-4 sm:px-8 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-10"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="isMobileMenuOpen = true"
                        class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors"
                    >
                        <Bars3Icon class="h-6 w-6" />
                    </button>
                    <h2
                        class="text-lg font-semibold text-slate-800 capitalize hidden sm:block"
                    >
                        {{ route().current().split(".")[0] }}
                    </h2>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <Dropdown align="right" width="64">
                        <template #trigger>
                            <button
                                @click="hasNewNotification = false"
                                class="p-2 text-slate-400 hover:bg-slate-100 rounded-full transition-colors relative"
                            >
                                <span
                                    v-if="hasNewNotification"
                                    class="absolute top-2 right-2 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"
                                ></span>
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    ></path>
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-bold text-slate-800">
                                    Recent Notifications
                                </p>
                            </div>
                            <div
                                v-if="notifications.length > 0"
                                class="max-h-64 overflow-y-auto"
                            >
                                <Link
                                    v-for="(n, idx) in notifications"
                                    :key="idx"
                                    :href="route('appointments.index')"
                                    class="block px-4 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors"
                                >
                                    <p class="text-xs text-slate-700">
                                        {{ n.message }}
                                    </p>
                                </Link>
                            </div>
                            <div v-else class="px-4 py-6 text-center">
                                <p class="text-xs text-slate-400">
                                    No new updates
                                </p>
                            </div>
                        </template>
                    </Dropdown>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center gap-1 p-1 hover:bg-slate-100 rounded-lg transition-colors"
                            >
                                <div
                                    class="h-8 w-8 bg-slate-200 rounded-lg flex items-center justify-center text-slate-600"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                                        ></path>
                                    </svg>
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')"
                                >Account Settings</DropdownLink
                            >
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-red-600 w-full text-left"
                                >Log Out</DropdownLink
                            >
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="showToast"
            class="fixed bottom-5 right-5 z-[100] max-w-md w-full bg-white shadow-2xl rounded-2xl border-l-4 pointer-events-auto overflow-hidden"
            :class="
                toastType === 'success'
                    ? 'border-emerald-500'
                    : 'border-indigo-600'
            "
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div
                            class="p-2 rounded-lg"
                            :class="
                                toastType === 'success'
                                    ? 'bg-emerald-100'
                                    : 'bg-indigo-100'
                            "
                        >
                            <CheckCircleIcon
                                v-if="toastType === 'success'"
                                class="h-6 w-6 text-emerald-600"
                            />
                            <CalendarDaysIcon
                                v-else
                                class="h-6 w-6 text-indigo-600"
                            />
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-slate-900">
                            {{
                                toastType === "success"
                                    ? "Status Updated"
                                    : "New Appointment"
                            }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ toastMessage }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="showToast = false"
                            class="rounded-md inline-flex text-slate-400 hover:text-slate-500 focus:outline-none"
                        >
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="h-1 bg-slate-100 w-full">
                <div
                    class="h-full animate-shrink"
                    :class="
                        toastType === 'success'
                            ? 'bg-emerald-500'
                            : 'bg-indigo-600'
                    "
                ></div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { useRole } from "@/composables/useRole";
import {
    HomeIcon,
    UserGroupIcon,
    CalendarDaysIcon,
    ChatBubbleLeftIcon,
    Bars3Icon,
    XMarkIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/solid";

const { isAdmin, isDoctor, isUser } = useRole();
const isMobileMenuOpen = ref(false);
const hasNewNotification = ref(false);
const notifications = ref([]);
const toastType = ref("info");

const showToast = ref(false);
const toastMessage = ref("");
let toastTimeout = null;

const page = usePage();

const triggerToast = (msg, type = "info") => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;

    if (toastTimeout) clearTimeout(toastTimeout);

    toastTimeout = setTimeout(() => {
        showToast.value = false;
    }, 5000);
};

onMounted(() => {
    const user = page.props.auth.user;
    if (user) {
        const userId = user.id || user._id;
        console.log("Listening on private channel: user." + userId);
        window.Echo.private(`user.${userId}`)
            .listen(".appointment.notification", (e) => {
                console.log("New Appointment Notification:", e);
                triggerToast(e.message, "info");
                notifications.value.unshift({
                    id: e.appointment.id || e.appointment._id,
                    message: e.message,
                });
                if (notifications.value.length > 5) notifications.value.pop();
                hasNewNotification.value = true;

                if (route().current("appointments.index")) {
                    router.reload({ only: ["appointments"] });
                }
            })
            .listen(".status.notification", (e) => {
                console.log("Status Update Notification:", e);
                triggerToast(e.message, "success");
                notifications.value.unshift({
                    id: e.appointment.id || e.appointment._id,
                    message: e.message,
                });
                if (notifications.value.length > 5) notifications.value.pop();
                hasNewNotification.value = true;

                if (route().current("appointments.index")) {
                    router.reload({ only: ["appointments"] });
                }
            });
    }
});

onUnmounted(() => {
    const user = page.props.auth.user;
    if (user) {
        const userId = user.id || user._id;
        window.Echo.leave(`user.${userId}`);
    }
});
</script>
