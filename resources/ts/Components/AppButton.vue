<template>
    <button role="button" :disabled="processing" :data-kt-indicator="indicator" type="button" :class="`btn ${'btn-' + color } ${(block) ? 'w-100' : ''}`" v-bind="$attrs">
        <span class="indicator-label" v-if="!processing">
            <slot>
                Bottone
            </slot>
        </span>
        <span v-else class="indicator-progress">
            <slot name="progress">
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                Attendere...
            </slot>
        </span>
    </button>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";

    export default defineComponent({
        name: 'AppButton',
        props: {
            processing: Boolean,
            color: {
                type: String as PropType<'primary' | 'secondary' | 'info' | 'light' | 'dark' | 'light-primary' | 'light-info' | 'light-dark' | 'warning' | 'light-warning' | 'danger' | 'light-danger'>,
                default: 'primary'
            },
            size: {
                type: String as PropType<'small' | 'large' | 'default'>,
                default: 'default'
            },
            block: Boolean
        },
        computed: {
            indicator() {
                return (this.processing) ? 'on' : 'off'
            }
        }
    })
</script>
