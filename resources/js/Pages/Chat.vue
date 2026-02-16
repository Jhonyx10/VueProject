<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatLayout from '@/Pages/Chat/ChatLayout.vue'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    chats: Array,
});

</script>

<template>
    <Head title="Messages" />

    <AuthenticatedLayout>
        <ChatLayout :chats="chats" />
    </AuthenticatedLayout>
</template>