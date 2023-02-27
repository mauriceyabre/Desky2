<template>
    <div class="card" :class="{ 'card-flush': flush }">
        <div class="card-header" v-if="hasHeader">
            <div class="card-title m-0" v-if="title">
                <h2 class="fw-bold m-0">{{ title }}</h2>
            </div>
            <slot name="header">
                <div v-if="!title"></div>
                <div></div>
            </slot>
        </div>
        <div class="card-body" :class="{'pt-0': hasHeader}">
            <slot />
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
        name: 'AppCard',
        props: {
            flush: Boolean,
            title: String
        },
        computed: {
            hasHeader() {
                return !!this.$slots['header'] || !!this.title
            }
        }
    })
</script>
