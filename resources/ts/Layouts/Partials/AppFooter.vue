<template>
    <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
        <div class="container-xxl d-flex flex-column flex-md-row flex-stack">
            <div class="text-dark order-2 order-md-1">
                <span class="text-gray-400 fw-semibold me-1">Creato da</span>
                <a href="#" class="text-muted text-hover-primary fw-semibold me-2 fs-6 disabled">Maurice Yabre
                </a>
            </div>
            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                <li class="menu-item">
                    <span class="text-gray-600">{{ '00' }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";
    // import { useCurrentTime } from "#Composables/useCurrentTime";

    export default defineComponent({
        name: 'AppFooter',
        setup() {
            // const {fullTime} = useCurrentTime()
            return {
                // fullTime
            }
        }
    })
</script>
