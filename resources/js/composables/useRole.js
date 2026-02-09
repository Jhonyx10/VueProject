import { usePage } from "@inertiajs/vue3";

export function useRole() {
    const role = usePage().props.auth.role;

    return {
        role,
        isAdmin: role === "admin",
        isDoctor: role === "doctor",
        isUser: role === "user",
    };
}
