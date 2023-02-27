<template>
    <div v-bind="$attrs">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <input :value="modelValue" type="email" :name="name" class="form-control form-control-solid" :class="[{'is-invalid': (form.errors?.[name])}, size]" v-bind="$attrs" @input="$emit('update:modelValue', $event.target.value)" placeholder="es. email@email.com" @focus="form.clearErrors(name)" />
        <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import { InertiaForm } from "@inertiajs/inertia-vue3";
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { defineComponent } from "vue";

    export default defineComponent({
        name: "InputEmail",
        components: { InputLabel },
        props: {
            modelValue: [String, Number],
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
                } else if (this.inputSize == 'large') {
                    return 'form-control-lg'
                }
                return;
            }
        },
        emits: ['update:modelValue'],
    })
</script>
