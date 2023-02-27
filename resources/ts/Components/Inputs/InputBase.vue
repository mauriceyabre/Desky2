<template>
    <div v-bind="$attrs">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <input :value="modelValue" :type="type" :name="name" class="form-control form-control-solid" :class="[{'is-invalid': (form.errors?.[name])}, size]" @input="$emit('update:modelValue', $event.target.value)" :placeholder="placeholder" @focus="form.clearErrors(name)" />
        <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { defineComponent, PropType } from "vue";
    import { AppForm } from "@Composables/useForm";

    export default defineComponent({
        name: "InputBase",
        components: { InputLabel },
        props: {
            modelValue: [String, Number],
            type: {
                type:String as PropType<'text'|'tel'|'email'|'password'>,
                default: 'text'
            },
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
            inputSize: String as PropType<'small'|'large'>
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
