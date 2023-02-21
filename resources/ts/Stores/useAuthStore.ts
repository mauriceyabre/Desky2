import { computed, ref } from "vue"
import { defineStore } from "pinia"
import { useStorage } from "@vueuse/core"
import User from "@Models/User";

export const useAuthStore = defineStore("authStore", () => {
    const accessToken = useStorage("access_token", "")
    const isLoading = ref(false)
    const user = ref<User|null>(null)

    const check = computed(() => !!accessToken.value)
    const hasUser = computed(() => !!(user.value && Object.keys(user.value).length))

    function setAccessToken(value?: string|null) {
        accessToken.value = value;
        axios.defaults.headers.common[
            "Authorization"
            ] = `Bearer ${ accessToken.value }`;
    }

    function set(userObject: Object): void {
        user.value = new User(userObject);
    }

    async function fetch(): Promise<any> {
        isLoading.value = true
        return await axios.get('auth/user').then(res => {
            return res.data.user
        }).finally(() => {
            isLoading.value = false
        })
    }

    async function login(credentials: {email: string, password: string, remember?: boolean}) {
        isLoading.value = true
        return await axios.post('auth/login', credentials).then((res) => {
            setAccessToken(res.data.access_token)
            set(res.data.user)
        }).finally(() => {
            isLoading.value = false
        })
    }

    function destroyToken(routeName: string) {
        setAccessToken(null);
    }

    async function logout() {
        isLoading.value = true
        const loadingEl = document.createElement("div");
        document.body.prepend(loadingEl);
        loadingEl.classList.add("page-loader");
        loadingEl.classList.add("flex-column");
        loadingEl.classList.add("bg-dark");
        loadingEl.classList.add("bg-opacity-25");
        loadingEl.innerHTML = `
        <span class="spinner-border text-danger" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Logging Out...</span>
    `;

        // Show page loading
        KTApp.showPageLoading();

        return await axios.post("auth/logout").then(() => {
            destroyToken("auth.login");
            KTApp.hidePageLoading();
            loadingEl.remove();
        }).finally(() => {
            isLoading.value = false
        });
    }

    return {
        login,
        logout,
        set,
        fetch,
        hasUser,
        check,
        destroyToken,
        isLoading,
        user: computed(() => user.value ) };
});
