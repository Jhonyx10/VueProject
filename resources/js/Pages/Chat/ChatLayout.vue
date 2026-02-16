<script setup>
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    chats: {
        type: Array,
        default: () => []
    }
});

const scrollContainer = ref(null);
const searchQuery = ref('');
const newMessage = ref('');
const isSending = ref(false); // New: Loading state

// Logic to handle the active chat selection
// Force ID to string to ensure stable comparison
const activeChatId = ref(props.chats[0]?._id ? String(props.chats[0]._id) : null);

const activeChat = computed(() => {
    return props.chats.find(chat => String(chat._id) === String(activeChatId.value));
});

const filteredChats = computed(() => {
    return props.chats.filter(chat =>
        chat.other_user?.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Improved auto-scroll logic
const scrollToBottom = async () => {
    await nextTick();
    if (scrollContainer.value) {
        scrollContainer.value.scrollTo({
            top: scrollContainer.value.scrollHeight,
            behavior: 'smooth'
        });
    }
};

const selectChat = (chat) => {
    activeChatId.value = String(chat._id);
    scrollToBottom();
};

const sendMessage = () => {
    // Check if empty, no active chat, or already sending
    if (!newMessage.value.trim() || !activeChat.value || isSending.value) return;

    isSending.value = true;

    router.post(route('chat.store'), {
        room_id: activeChat.value._id,
        
        message: newMessage.value
    }, {
        // Crucial for Inertia: preserve scroll of the page, but we'll scroll the chat box
        preserveScroll: true,
        // Keep the state so the chat doesn't "flicker"
        preserveState: true,
        onSuccess: () => {
            newMessage.value = '';
            scrollToBottom();
        },
        onFinish: () => {
            isSending.value = false;
        }
    });
};

onMounted(() => {
    scrollToBottom();

    // Listen for messages in all rooms the user is part of
    props.chats.forEach(room => {
        window.Echo.private(`chat.room.${room._id}`)
            .listen('.message.sent', (e) => {
                console.log('Real-time message received:', e);

                // 1. Update the specific room's messages if it's the active one
                if (String(activeChatId.value) === String(room._id)) {
                    if (!room.chats) room.chats = [];
                    // Prevent duplicates
                    const exists = room.chats.some(m => String(m._id) === String(e.message._id));
                    if (!exists) {
                        room.chats.push(e.message);
                        scrollToBottom();
                    }
                }

                // 2. Update the room list info
                room.last_message = e.message.message;
                room.updated_at_human = 'just now';
            });
    });
});

onUnmounted(() => {
    props.chats.forEach(room => {
        window.Echo.leave(`chat.room.${room._id}`);
    });
});

// Watch for changes in message count to trigger scroll
watch(
    () => activeChat.value?.chats?.length,
    () => scrollToBottom()
);
</script>

<template>

    <Head title="Messages" />
    <div class="h-[calc(100vh-64px)]">
        <div class="max-w-7xl mx-auto h-full sm:px-6 lg:px-8">
            <div v-motion :initial="{ opacity: 0, scale: 0.98 }"
                :enter="{ opacity: 1, scale: 1, transition: { duration: 500 } }"
                class="bg-white overflow-hidden shadow-xl sm:rounded-2xl flex h-full border border-slate-200">
                <div class="w-full md:w-80 lg:w-96 border-r border-slate-100 flex flex-col bg-slate-50/50">
                    <div class="p-4 bg-white border-b border-slate-100">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">Messages</h2>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input v-model="searchQuery" type="text" placeholder="Search chats..."
                                class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all" />
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <div v-if="filteredChats.length === 0" class="p-8 text-center">
                            <p class="text-sm text-slate-400 font-medium">No conversations found</p>
                        </div>
                        <div v-for="(chat, index) in filteredChats" :key="chat._id" @click="selectChat(chat)" v-motion
                            :initial="{ opacity: 0, x: -20 }"
                            :enter="{ opacity: 1, x: 0, transition: { delay: 100 + (index * 50) } }"
                            :class="['flex items-center px-4 py-4 cursor-pointer transition-all border-l-4 relative',
                                String(activeChatId) === String(chat._id) ? 'bg-white border-indigo-500 shadow-sm z-10' : 'border-transparent text-slate-500 hover:bg-white/50']">
                            <div class="relative flex-shrink-0">
                                <div
                                    class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-white shadow-sm">
                                    {{ chat.other_user?.name?.charAt(0) || '?' }}
                                </div>
                            </div>
                            <div class="ml-3 overflow-hidden flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ chat.other_user?.name ||
                                        'Unknown User' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ chat.updated_at_human }}</p>
                                </div>
                                <p class="text-xs truncate text-slate-500">{{ chat.last_message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="activeChat" class="hidden md:flex flex-1 flex-col bg-white h-full relative">
                    <div
                        class="z-10 px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white/80 backdrop-blur-md">
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                {{ activeChat.other_user?.name?.charAt(0) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-slate-800">{{ activeChat.other_user?.name }}</p>
                                <p class="text-[10px] text-green-500 font-medium">Active Session</p>
                            </div>
                        </div>
                    </div>

                    <div ref="scrollContainer" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30">
                        <div v-if="!activeChat?.chats?.length"
                            class="h-full flex flex-col items-center justify-center opacity-40">
                            <p class="text-sm font-medium">No messages yet. Say hello!</p>
                        </div>

                        <div v-else v-for="msg in activeChat.chats" :key="msg._id" :class="['flex items-end gap-2 max-w-[80%]',
                            msg.sender_id == $page.props.auth.user.id ? 'ml-auto flex-row-reverse' : '']">
                            <div :class="['h-8 w-8 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-white',
                                msg.sender_id == $page.props.auth.user._id ? 'bg-indigo-600' : 'bg-slate-400']">
                                {{ msg.sender_id == $page.props.auth.user._id ? 'ME' :
                                    activeChat.other_user?.name?.charAt(0) }}
                            </div>

                            <div
                                :class="['p-3 rounded-2xl shadow-sm',
                                    msg.sender_id == $page.props.auth.user.id ? 'bg-indigo-600 rounded-br-none' : 'bg-white border border-slate-200 rounded-bl-none']">
                                <p
                                    :class="['text-sm', msg.sender_id == $page.props.auth.user.id ? 'text-white' : 'text-slate-700']">
                                    {{ msg.message }}
                                </p>
                                <p
                                    :class="['text-[9px] mt-1 uppercase font-bold', msg.sender_id == $page.props.auth.user.id ? 'text-indigo-200' : 'text-slate-400']">
                                    {{ msg.created_at_human }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border-t border-slate-100">
                        <div :class="['flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border transition-all',
                            isSending ? 'opacity-70' : 'focus-within:ring-1 focus-within:ring-indigo-500 border-slate-200']">

                            <input type="text" name="message" v-model="newMessage" @keyup.enter="sendMessage"
                                :disabled="isSending" placeholder="Type a message..."
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-slate-700 disabled:cursor-not-allowed" />

                            <button @click="sendMessage" :disabled="isSending || !newMessage.trim()"
                                class="bg-indigo-600 text-white p-2 rounded-xl hover:bg-indigo-700 transition-all disabled:bg-slate-300 disabled:cursor-not-allowed">
                                <svg v-if="!isSending" class="w-5 h-5 rotate-90" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                                <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="flex-1 flex items-center justify-center bg-slate-50">
                    <p class="text-slate-400">Select a conversation to start messaging</p>
                </div>
            </div>
        </div>
    </div>
</template>