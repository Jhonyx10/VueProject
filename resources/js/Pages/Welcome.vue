<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Doctor Appointment System" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-white">
        <!-- NAVBAR -->
        <header class="flex items-center justify-between px-8 py-6 max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-blue-700">
                🏥 MedCare
            </h1>

            <nav class="flex gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Dashboard
                </Link>

                <template v-else>
                    <Link :href="route('login')" class="px-4 py-2 rounded-md text-blue-600 hover:bg-blue-100">
                    Login
                    </Link>

                    <Link v-if="canRegister" :href="route('register')"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- HERO SECTION -->
        <section class="max-w-7xl mx-auto px-8 py-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-800 leading-tight">
                    Book Doctor Appointments
                    <span class="text-blue-600">Anytime, Anywhere</span>
                </h2>

                <p class="mt-6 text-gray-600 text-lg">
                    Manage doctor schedules, patient records, and appointments in one
                    secure platform.
                </p>

                <div class="mt-8 flex gap-4">
                    <Link :href="route('register')"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    Book Appointment
                    </Link>

                    <Link :href="route('login')"
                        class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                    Doctor Login
                    </Link>
                </div>
            </div>

            <!-- IMAGE / ILLUSTRATION -->
            <div class="hidden md:block">
                <img src="https://img.freepik.com/free-vector/doctor-character-background_1270-84.jpg"
                    alt="Doctor Illustration" class="rounded-xl shadow-lg" />
            </div>
        </section>

        <!-- FEATURES -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-8">
                <h3 class="text-3xl font-bold text-center text-gray-800">
                    Why Choose MedCare?
                </h3>

                <div class="mt-12 grid md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl shadow hover:shadow-lg transition">
                        <h4 class="text-xl font-semibold text-blue-600 mb-3">
                            👨‍⚕️ Qualified Doctors
                        </h4>
                        <p class="text-gray-600">
                            View doctor profiles, specialties, and availability in real-time.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl shadow hover:shadow-lg transition">
                        <h4 class="text-xl font-semibold text-blue-600 mb-3">
                            📅 Easy Scheduling
                        </h4>
                        <p class="text-gray-600">
                            Book, reschedule, or cancel appointments with just a few clicks.
                        </p>
                    </div>

                    <div class="p-6 rounded-xl shadow hover:shadow-lg transition">
                        <h4 class="text-xl font-semibold text-blue-600 mb-3">
                            🔒 Secure Records
                        </h4>
                        <p class="text-gray-600">
                            Patient data is encrypted and safely stored.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="text-center py-10 text-gray-500">
            © {{ new Date().getFullYear() }} MedCare. All rights reserved.
        </footer>
    </div>
</template>
