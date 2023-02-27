<template>
    <div class="col">
        <slot />
    </div>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";

    export default defineComponent({
        name: "AppColumn",
        // props: {
        //     breakpoint: {
        //         type: [String,Array] as PropType<'sm'|'md'|'lg'|'xl'|'xxl'|'none'|[string, string]>,
        //         default: 'md'
        //     },
        //     col: {
        //         type: String as PropType<'fluid' | '2' | '4' | '6' | '8' | '10' | '12'>,
        //         required: true
        //     },
        // },
        // computed: {
        //     breakpointValue(): string|string[] {
        //         const isArray = String(this.breakpoint).startsWith('[');
        //         return (this.col !== 'fluid') ? (isArray ? String(this.breakpoint).replaceAll(/[\s\[\]']+/g, '').split(',') : this.breakpoint) : 'none'
        //     },
        //     computedClass() {
        //         if (this.col !== 'fluid' && (Number(this.col)) % 2 === 0) {
        //             if (this.breakpointValue === 'none') {
        //                 return `col-${this.col}`
        //             } else {
        //                 return (_.isArray(this.breakpointValue)) ? `col-${this.breakpointValue[0]}-${Number(this.col) / 2} col-${this.breakpointValue[1]}-${Number(this.col)}` : `col-${this.breakpointValue}-${this.col}`
        //             }
        //         } else {
        //             return 'col'
        //         }
        //     }
        // }
    })
</script>
