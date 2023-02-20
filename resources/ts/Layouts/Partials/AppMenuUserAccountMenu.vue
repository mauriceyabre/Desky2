<template>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label fs-3 fw-semibold">{{ user?.initials }}</div>
                </div>
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">{{ user?.name }}
                    </div>
                    <span class="fw-semibold text-muted fs-7">{{ user?.email }}</span>
                </div>
            </div>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <RouterLink :to="'#'" class="menu-link px-5">
                <i class="bi bi-person fs-4 me-4"></i>
                Profilo
            </RouterLink>
        </div>
        <div class="menu-item px-5">
            <RouterLink :to="'#'" class="menu-link px-5">
                <i class="bi bi-calendar-week fs-4 me-4"></i>
                Foglio Presenze
            </RouterLink>
        </div>
        <div class="menu-item px-5">
            <RouterLink :to="'#'" disabled class="menu-link px-5">
                <span class="menu-text">
                    <i class="bi bi-briefcase fs-4 me-4"></i>
                    Progetti
                </span>
            </RouterLink>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5 my-1">
            <RouterLink :to="'#'" class="menu-link px-5">
                <i class="bi bi-gear fs-4 me-4"></i>
                Impostazioni
            </RouterLink>
        </div>
        <div class="menu-item px-5">
            <a role="button" @click.prevent="auth.logout()" class="menu-link px-5 text-danger">
                <i class="bi bi-box-arrow-right fs-4 me-4 text-danger"></i>
                Esci
            </a>
        </div>
    </div>
</template>
<script setup lang="ts">
    import { computed, defineComponent } from "vue";
    import { useAuthStore } from "@Stores/useAuthStore";
    // import useAuth from "#Composables/useAuth";
    // import { usePage } from "@inertiajs/inertia-vue3";
    // import User from "@Models/User";

    const auth = useAuthStore()

    const login = () => {
    }
    const logout = () => {
    }

    const user = computed(() => {
        return {};
    });
</script>
