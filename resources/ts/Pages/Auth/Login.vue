<template>
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" @submit.prevent="handleLogin">
            <div class="text-center mb-11">
                <h1 class="text-dark fw-bolder mb-3">Accedi</h1>
                <div class="text-gray-500 fw-semibold fs-6">con le tue credenziali</div>
            </div>
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <InputBase :form="form" name="email" placeholder="Email" v-model="form.email" :disabled="form.processing" />
            </div>
            <div class="fv-row mb-3 fv-plugins-icon-container">
                <InputPassword :form="form" name="password" placeholder="Password" v-model="form.password" :disabled="form.processing" />
            </div>
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                <div></div>
                <a class="link-primary">
                    Password Dimenticata ?
                </a>
            </div>
            <div class="d-grid mb-10">
                <button class="btn w-100 btn-primary" :disabled="auth.isLoading">
                    <span v-if="!auth.isLoading">Accedi</span>
                    <span v-if="auth.isLoading">
                        Attendere
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <div class="separator separator-content my-14">
                <span class="w-125px text-gray-500 fw-semibold fs-7">oppure</span>
            </div>
            <div class="row g-3 mb-9">
                <div class="col-md-12">
                    <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100 disabled">
                        <img alt="Logo" src="/assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3" />
                        Accedi con Google
                    </a>
                    <!-- TODO:: Implementare l'accesso con google -->
                </div>
            </div>
            <div class="text-gray-500 text-center fw-semibold fs-6 d-none">Sei sei ancora registrato?
                <a href="#" class="link-primary">Registrati</a>
                                                                           <!-- TODO:: Implementare la registrazione -->
            </div>
        </form>
</template>
<script setup lang="ts">
    import { ref } from "vue";
    import useForm from "@Composables/useForm";
    import InputBase from "@Components/Inputs/InputBase.vue";
    import InputPassword from "@Components/Inputs/InputPassword.vue";
    import { useAuthStore } from "@Stores/useAuthStore";
    import router from "../../Router/router";

    const auth = useAuthStore()
    const loading = ref(false)

    const form = useForm( {
        email: '',
        password: '',
        remember: true
    })

    function handleLogin() {
        auth.login(form.data())
            .then(() => {
                router.push({name: 'dashboard'})
            })
            .catch(err => {
            form.setErrors( err.response.data.errors)
        }).finally(() => form.password = "")
    }
</script>
