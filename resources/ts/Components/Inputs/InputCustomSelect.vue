<template>
    <div>
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <div class="dropdown">
            <button class="btn btn-light w-100 text-start" @click.prevent data-bs-toggle="dropdown">
                {{ items.find(item => item.key === modelValue)?.['name'] }}
                <span class="position-absolute end-0 me-4">
                    <i class="bi bi-chevron-down" />
                </span>
            </button>
            <ul class="dropdown-menu w-100 overflow-auto mh-250px">
                <li class="fw-normal" v-for="(item, key) in items" :key="key" @click.prevent="$emit('update:modelValue', item.key)">
                    <span class="dropdown-item px-5 py-3 fs-6 cursor-pointer" :class="{'bg-light-primary text-primary': modelValue === item.key}">
                        {{ item.name }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { InertiaForm } from "@inertiajs/vue3";

    export default defineComponent({
        name: 'InputCustomSelect',
        components: { InputLabel },
        props: {
            modelValue: [String, Number, Array],
            name: {
                type: String,
                required: true
            },
            placeholder: {
                type: String
            },
            form: {
                type: Object as InertiaForm<any>,
                required: true
            },
            items: {
                type: Object as PropType<Role[]>,
                required: true
            },
            clearable: {
                type: Boolean,
                default: false
            },
            inModal: Boolean,
            required: Boolean,
            label: String,
            disabled: Boolean,
            hideSearch: {
                type: Boolean,
                default: false
            }
        },
        emits: ['update:modelValue'],
    })
</script>
