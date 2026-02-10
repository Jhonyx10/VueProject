import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function useRole() {
    const page = usePage();

    // Use computed so it reacts to changes in the auth object
    const role = computed(() => page.props.auth.user?.role?.toLowerCase());

    return {
        role,
        isAdmin: computed(() => role.value === "admin"),
        isDoctor: computed(() => role.value === "doctor"),
        isUser: computed(() => role.value === "user"),
    };
}
