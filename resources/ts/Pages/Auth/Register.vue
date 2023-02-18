<template>
    <AuthLayout>
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" @submit.prevent="login">
            <div class="text-center mb-11">
                <h1 class="text-dark fw-bolder mb-3">Accedi</h1>
                <div class="text-gray-500 fw-semibold fs-6">con le tue credenziali</div>
            </div>
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <input class="form-control bg-transparent" type="text" name="email" autocomplete="off" v-model="form.email" placeholder="Email" @focusin="form.clearErrors()" />
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <div class="fv-row mb-3 fv-plugins-icon-container">
                <input class="form-control bg-transparent" type="password" name="password" autocomplete="off" v-model="form.password" placeholder="Password" @focusin="form.clearErrors()" />
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                <div></div>
                <!--<AppLink v-if="canResetPassword" :href="route('login')" class="link-primary">-->
                <!--    Password Dimenticata ?-->
                <!--</AppLink>-->
            </div>
            <div class="d-grid mb-10">
                <!--<AppButtonSubmit block :processing="form.processing">-->
                <!--    Accedi-->
                <!--    <template #progress>-->
                <!--        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>-->
                <!--    </template>-->
                <!--</AppButtonSubmit>-->
            </div>
            <div class="alert alert-danger d-flex align-items-center p-3" v-if="showError">
                <div class="d-flex flex-column">
                    <span>{{ form.errors.email }}</span>
                </div>
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
    </AuthLayout>
</template>
<script setup lang="ts">
    // import AppButtonSubmit from "@Components/AppButtonSubmit.vue";
    // import { useForm } from "@inertiajs/inertia-vue3";
    import { computed } from "vue";
    import AuthLayout from "@Layouts/AuthLayout.vue";

    // const form = useForm('login-form', {
    //     email: '',
    //     password: '',
    //     remember: true
    // })

    const canResetPassword = false;

    // const showError = computed(() => form.errors.email);

    // const login = () => {
    //     form.post(route('login'));
    // }
</script>
