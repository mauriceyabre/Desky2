import { reactive, ref } from "vue";
import { defineStore } from "pinia";
import { useAuthStore } from "@Stores/useAuthStore";

export const useRegisterStore = defineStore("registerStore", () => {
    const auth = useAuthStore();
    const errors = ref({});
    const loading = ref(false);
    const form = reactive({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    function resetForm() {
        form.name = "";
        form.email = "";
        form.password = "";
        form.password_confirmation = "";

        errors.value = {}
    }

    async function handleSubmit() {
        if (loading.value) return;

        loading.value = true;
        errors.value = {};

        return window.axios
            .post("auth/register", form)
            .then((response) => {
                auth.login(response.data.access_token);
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    errors.value = error.response.data.errors;
                }
            })
            .finally(() => {
                form.password = "";
                form.password_confirmation = "";
                loading.value = false;
            });
    }

    return { form, errors, loading, resetForm, handleSubmit };
});
