<template>
    <InputLabel>Fatt. Elettronica</InputLabel>
    <div class="form-check form-switch form-switch-lg form-check-custom form-check-success form-check-solid">
        <input id="switch_toggle" @input="$emit('update:modelValue', $event.target.checked)" :checked="modelValue" class="form-check-input " type="checkbox" v-bind="$attrs" />
        <label class="form-check-label fw-bold fs-6" for="switch_toggle">
            <span v-if="!!label"></span>
        </label>
    </div>
    <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">
        {{ form.errors?.[name] }}
    </div>
</template>
<script lang="ts">
    import { InertiaForm } from "@inertiajs/inertia-vue3";
    import { defineComponent } from "vue";
    import InputLabel from "@Components/Inputs/InputLabel.vue";

    export default defineComponent({
        name: "InputSwitchButton",
        components: { InputLabel },
        props: {
            modelValue: Boolean,
            name: {
                type: String,
                required: true
            },
            placeholder: String,
            form: {
                type: Object as InertiaForm<any>,
                required: true
            },
            required: Boolean,
            label: String,
            inputSize: String
        },
        computed: {
            size() {
                if (this.inputSize == 'small') {
                    return 'form-control-sm'
                } else if( this.inputSize == 'large') {
                    return 'form-control-lg'
                }
                return;
            }
        },
        emits: ['update:modelValue'],
    })
</script>
