<template>
    <div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-direction="start" data-kt-drawer-name="aside" data-kt-drawer-overlay="true"
            data-kt-drawer-toggle="#kt_aside_toggle" data-kt-drawer-width="auto">
        <div class="aside-logo flex-column-auto pt-10 pt-lg-20" id="kt_aside_logo">
            <RouterLink :to="{name: 'dashboard'}">
                <img alt="Logo" src="/assets/media/logos/demo9.svg" class="h-40px" />
            </RouterLink>
        </div>
        <div id="kt_aside_menu" class="aside-menu flex-column-fluid menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-icon-gray-400 menu-arrow-gray-400">
            <AppAsideMenu />
        </div>
        <div id="kt_aside_footer" class="aside-footer align-items-end"></div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";
    import AppAsideMenu from "@Layouts/Partials/AppAsideMenu.vue";

    export default defineComponent({
        name: 'AppAsideBase',
        components: {AppAsideMenu}

    })
</script>
