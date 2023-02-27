<template>
    <label class="fw-bold fs-6 mb-2" :class="{'required': required}"><slot>Titolo</slot></label>
</template>

<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
    name: "InputLabel",
    props: {
        required: Boolean
    }
})
</script>
