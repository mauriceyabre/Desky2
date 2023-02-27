<template>
    <div class="row" :class="`g-${Math.ceil(Number(gutter) / 2)} g-md-${gutter}`">
        <slot />
    </div>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";

    export default defineComponent({
        name: "AppRow",
        props: {
            gutter: {
                type: String as PropType<'0'|'1'|'2'|'3'|'4'|'5'|'6'|'7'|'8'|'9'|'10'>,
                default: '0'
            }
        }
    })
</script>
