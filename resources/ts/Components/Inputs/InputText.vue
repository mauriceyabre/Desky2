<template>
    <div v-bind="$attrs">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <input :value="modelValue" type="text" :name="name" class="form-control form-control-solid" :class="[{'is-invalid': (form.errors?.[name])}, size]" @input="$emit('update:modelValue', $event.target.value)" :placeholder="placeholder" @focus="form.clearErrors(name)" />
        <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { defineComponent } from "vue";

    export default defineComponent({
        name: "InputText",
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
    })
</script>
