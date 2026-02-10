<template>
    <div
        class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white/80 backdrop-blur-md sticky top-0"
    >
        <div class="flex items-center">
            <div
                class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold"
            >
                S
            </div>
            <div class="ml-3">
                <p class="text-sm font-bold text-slate-800">Dr. Sarah Connor</p>
                <p class="text-[10px] text-green-500 font-medium">Online Now</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button
                class="p-2 text-slate-400 hover:text-indigo-600 transition-colors"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                    />
                </svg>
            </button>
            <button
                class="p-2 text-slate-400 hover:text-indigo-600 transition-colors"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                    />
                </svg>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30">
        <div class="flex items-end gap-2 max-w-[80%]">
            <div class="h-8 w-8 rounded-full bg-slate-200 flex-shrink-0"></div>
            <div
                class="bg-white border border-slate-200 p-3 rounded-2xl rounded-bl-none shadow-sm"
            >
                <p class="text-sm text-slate-700">
                    Hello! I've reviewed the latest blood tests for patient John
                    Doe.
                </p>
                <p class="text-[9px] text-slate-400 mt-1">10:42 AM</p>
            </div>
        </div>

        <div class="flex items-end gap-2 max-w-[80%] ml-auto flex-row-reverse">
            <div class="h-8 w-8 rounded-full bg-indigo-600 flex-shrink-0"></div>
            <div
                class="bg-indigo-600 p-3 rounded-2xl rounded-br-none shadow-md"
            >
                <p class="text-sm text-white">
                    That's great. Are there any immediate concerns we should
                    address?
                </p>
                <p class="text-[9px] text-indigo-200 mt-1">10:45 AM</p>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border-t border-slate-100">
        <div
            class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-200 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all"
        >
            <button class="p-2 text-slate-400 hover:text-indigo-600">
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                    />
                </svg>
            </button>
            <input
                type="text"
                placeholder="Type a message..."
                class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-slate-700"
            />
            <button
                class="bg-indigo-600 text-white p-2 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95"
            >
                <svg
                    class="w-5 h-5 rotate-90"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>
