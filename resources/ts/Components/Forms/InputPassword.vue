<template>
    <div v-bind="$attrs">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
        <div class="position-relative">
            <input autocomplete="off" :value="modelValue" :type="type" :name="name" class="form-control form-control-solid" :class="[size]" v-bind="$attrs" @input="$emit('update:modelValue', $event.target.value)" :placeholder="placeholder" @focus="form.clearErrors(name)" />
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" @click.prevent.stop="toggleVisibility">
                <i class="bi bi-eye-slash fs-2" :class="(form.errors?.[name]) ? 'text-danger' : ''" v-if="type === 'text'" />
                <i class="bi bi-eye fs-2" :class="(form.errors?.[name]) ? 'text-danger' : ''" v-else />
            </span>
        </div>
        <div :class="(form.errors?.[name]) ? 'text-danger' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import InputLabel from "@Components/Forms/InputLabel.vue";
    import { defineComponent } from "vue";
    import { ADForm } from "@Composables/useForm";

    export default defineComponent({
        name: "InputPassword",
        components: {InputLabel},
        data() {
            return {
                type: 'password'
            }
        },
        props: {
            modelValue: [String, Number],
            name: {
                type: String,
                required: true
            },
            placeholder: String,
            form: {
                type: Object as ADForm<any>,
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
        methods: {
            toggleVisibility() {
                if (this.type === 'password') {
                    this.type = 'text'
                } else {
                    this.type = 'password'
                }
            }
        },
        emits: ['update:modelValue']
    })
</script>
