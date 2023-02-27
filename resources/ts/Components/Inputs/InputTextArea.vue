<template>
    <textarea :value="modelValue" :name="name" ref="textArea" class="form-control h-100px" :class="{'form-control-solid': fieldStyle === 'solid',
           'form-control-transparent': fieldStyle === 'transparent', '': fieldStyle === 'base', 'is-invalid': (form.errors?.[name])}"
            v-bind="$attrs" @input="(event) => { $emit('update:modelValue', event.target.value) }" :placeholder="placeholder" @focus="form.clearErrors(name)" />
    <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">{{
            form.errors?.[name]
        }}
    </div>
</template>
<script lang="ts">
    import { InertiaForm } from "@inertiajs/vue3";
    import { defineComponent, PropType } from "vue";

    export default defineComponent({
        name: "AppInputTextArea",
        props: {
            modelValue: [String, Number],
            name: {
                type: String,
                required: true
            },
            placeholder: String,
            form: {
                type: Object as PropType<InertiaForm<any>>,
                required: true
            },
            fieldStyle: {
                type: String,
                default: 'solid'
            }
        },
        emits: ['update:modelValue']
    })
</script>
