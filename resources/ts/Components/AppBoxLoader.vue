<template>
    <div>
        <div class="rounded flex-center h-50 min-h-250px d-flex flex-column bg-light bg-opacity-25" v-bind="$attrs">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Attendere...</span>
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
        name: 'AppBoxLoader',
    })
</script>
