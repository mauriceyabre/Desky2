<template>
    <div class="row">
        <AppColumn :class="`col-md-${column}`">
            <label class="fw-semibold text-muted">
                <slot name="label">
                    {{ label }}
                </slot>
            </label>
        </AppColumn>
        <AppColumn :class="`col-md-${12 - Number(column)}`">
            <span class="fw-semibold text-gray-800 fs-6">
                <slot>Info</slot>
            </span>
        </AppColumn>
    </div>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";
    import AppColumn from "@Components/AppColumn.vue";

    export default defineComponent({
        name: "AppDataRow",
        components: { AppColumn },
        props: {
            column: {
                type: String as PropType<'2'|'3'|'4'|'5'|'6'>,
                default: '4'
            },
            label: String
        }
    })
</script>
