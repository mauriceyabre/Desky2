<template>
    <div class="d-flex flex-column gap-3 gap-md-6">
        <slot />
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
        name: "AppDataList"
    })
</script>
