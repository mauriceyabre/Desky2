<template>
    <div class="card">
        <div class="card-body p-20 min-h-200px d-flex flex-column flex-center">
            <span class="spinner-border text-primary w-50px h-50px align-middle ms-2"></span>
            <span class="mt-4">Attendere</span>
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
        name: 'AppLoader',
    })
</script>
