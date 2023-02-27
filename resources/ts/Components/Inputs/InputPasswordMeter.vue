<template>
    <div v-bind="$attrs" data-kt-password-meter="true">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <div class="position-relative mb-3">
            <input autocomplete="off" :value="modelValue" type="password" :name="name" class="form-control form-control-solid" :class="[size]" v-bind="$attrs" @input="$emit('update:modelValue', $event.target.value)" :placeholder="placeholder" @focus="form.clearErrors(name)" />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                data-kt-password-meter-control="visibility">
                <i class="bi bi-eye-slash fs-2" :class="(form.errors?.[name]) ? 'text-danger' : ''"></i>
                <i class="bi bi-eye fs-2 d-none" :class="(form.errors?.[name]) ? 'text-danger' : ''"></i>
            </span>
        </div>
        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
        </div>
        <div class="text-muted">
            Usa 8 o più caratteri con lettere, numeri e simboli.
        </div>
        <div :class="(form.errors?.[name]) ? 'text-danger' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { defineComponent } from "vue";

    export default defineComponent({
        name: "InputPasswordMeter",
        components: {InputLabel},
        props: {
            modelValue: [String, Number],
            name: {
                type: String,
                required: true
            },
            placeholder: String,
            form: {
                type: Object as AppForm<any>,
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
        mounted() {
            // @ts-ignore
            KTPasswordMeter.createInstances();
        }
    })
</script>
