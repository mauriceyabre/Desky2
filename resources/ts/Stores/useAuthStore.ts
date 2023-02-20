import { computed, reactive, ref } from "vue";
import { defineStore } from "pinia";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";

export const useAuthStore = defineStore("authStore", () => {
    const router = useRouter()
    const accessToken = useStorage("access_token", "");
    const loading = ref(false)

    const check = computed(() => !!accessToken.value);

    function setAccessToken(value?: string|null) {
        accessToken.value = value;
        axios.defaults.headers.common[
            "Authorization"
            ] = `Bearer ${ accessToken.value }`;
    }

    function login(accessToken: string) {
        setAccessToken(accessToken);

        router.push({ name: "dashboard" });
    }

    function destroyTokenAndRedirectTo(routeName: string) {
        setAccessToken(null);
        router.push({ name: routeName });
    }

    async function logout() {
        return axios.post("auth/logout").then(() => {
            destroyTokenAndRedirectTo("auth.login");
        });
    }



    return { login, logout, check, destroyTokenAndRedirectTo, loading };
});
