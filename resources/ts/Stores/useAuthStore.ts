import { computed, reactive } from "vue";
import { defineStore } from "pinia";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";

export const useAuthStore = defineStore("authStore", () => {
    const router = useRouter()
    const accessToken = useStorage("access_token", "");
    const check = computed(() => !!accessToken.value);

    function setAccessToken(value?: string|null) {
        accessToken.value = value;
        axios.defaults.headers.common[
            "Authorization"
            ] = `Bearer ${ accessToken.value }`;
    }

    function login(accessToken: string) {
        setAccessToken(accessToken);

        router.push({ name: "vehicles.index" });
    }

    function destroyTokenAndRedirectTo(routeName: string) {
        setAccessToken(null);
        router.push({ name: routeName });
    }

    async function logout() {
        return axios.post("auth/logout").finally(() => {
            destroyTokenAndRedirectTo("register");
        });
    }

    return { login, logout, check, destroyTokenAndRedirectTo };
});
