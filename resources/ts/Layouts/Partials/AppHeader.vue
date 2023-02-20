<template>
    <div id="kt_header" class="header py-6 py-lg-0" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{lg: '300px'}">
        <div class="header-container container-xxl">
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-20 py-3 py-lg-0 me-3">
                <h1 class="d-flex flex-column text-dark fw-bold my-1">
                    <span class="text-white fs-1">{{ pageTitle }}</span>
                </h1>
            </div>
            <div class="d-flex align-items-center flex-wrap">
                <div class="header-search py-3 py-lg-0 me-3">
                    <AppSearch />
                </div>
                <div class="d-flex align-items-center py-3 py-lg-0">
                    <div class="me-3">
                        <button @click.prevent="false" class="btn btn-icon btn-custom btn-active-color-primary position-relative">
                            <i class="bi bi-clock fs-4" />
                            <span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink" v-if="user.hasActiveSession" />
                        </button>
                    </div>
                    <div>
                        <button class="btn btn-primary btn-active-color-primary text-white position-relative" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            {{ user.first_name }}
                        </button>
                        <AppMenuUserAccountMenu />
                    </div>
                </div>
            </div>
        </div>
        <div class="header-offset"></div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";
    import AppSearch from "@Layouts/Partials/AppSearch.vue";
    import AppMenuUserAccountMenu from "@Layouts/Partials/AppMenuUserAccountMenu.vue";
    // import useAuth from "@Composables/useAuth";
    // import { usePage } from "@inertiajs/inertia-vue3";
    // import { useGlobalStore } from "#Helpers/Stores/useGlobalStore";

    export default defineComponent({
        name: 'AppHeader',
        components: { AppMenuUserAccountMenu, AppSearch },
        computed: {
            user() {
                return {
                    first_name: 'Maurice',
                    hasActiveSession: true
                };
            },
            pageTitle() {
                return 'Optimaze'
            }
        }
    })
</script>
