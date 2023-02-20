<template>
    <div class="page d-flex flex-row flex-column-fluid">
        <AppAsideBase />
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper" ref="contentContainer">
            <AppHeaderMobile />
            <AppHeader />
            <div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
                <div id="kt_content_container" class="container-xxl">
                    <RouterView />
                </div>
                <!--                    <transition name="page" mode="out-in" @after-enter="onAfterEnter">
                                        <div class="container-fluid h-100" id="kt_content_container" :key="componentKey">
                                            <slot />
                                        </div>
                                    </transition>-->
            </div>
            <AppFooter />
        </div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                        fill="currentColor" />
            </svg>
        </span>
    </div>
</template>
<script setup lang="ts">
    import AppAsideBase from "@Layouts/Partials/AppAsideBase.vue";
    import AppHeader from "@Layouts/Partials/AppHeader.vue";
    import AppHeaderMobile from "@Layouts/Partials/AppHeaderMobile.vue";
    import AppFooter from "@Layouts/Partials/AppFooter.vue";
    import { nextTick, onMounted } from "vue";
    import AppCore from "@Helpers/AppCore";

    const emit = defineEmits(['pageLoaded']);

    onMounted(async () => {
        if (!!document.body.style.backgroundImage) {
            document.body.style.backgroundImage = 'none'
        }
        AppCore().init()
    })
</script>
<style>
    .page-enter-active {
        transition: all 0.25s ease-out;
    }

    .page-leave-active {
        transition: all 0.25s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .page-enter-from,
    .page-leave-to {
        transform: translateY(10px);
        opacity: 0;
    }
</style>
