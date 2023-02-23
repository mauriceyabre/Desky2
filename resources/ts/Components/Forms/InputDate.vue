<template>
    <input :name="name" id="kt_datepicker"
           class="form-control form-control-solid cursor-pointer"
           :placeholder="placeholder"
           ref="input"
           :value="modelValue"
           :class="(form.errors?.[name]) ? 'is-invalid' : ''"
           v-bind="$attrs"
           @change="(event) => { $emit('update:modelValue', event.target.value) }"
           @focus="form.clearErrors(name)"
    >
    <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">{{
            form.errors?.[name]
        }}
    </div>
</template>

<script lang="ts">
    import flatpickr from "flatpickr";
    import { Italian } from "flatpickr/dist/l10n/it";
    import { defineComponent, PropType, ref } from "vue";

    export default defineComponent({
        name: "InputDate",
        data() {
            return {
                flatpickr: ref()
            }
        },
        props: {
            modelValue: [String, Date],
            name: {
                type: String,
                required: true
            },
            placeholder: {
                type: String,
                default: 'Seleziona una data'
            },
            form: {
                type: Object as PropType<AppForm<any>>,
                required: true
            }
        },
        emits: ['update:modelValue'],
        mounted() {
            const datePicker: HTMLInputElement = this.$refs.input as HTMLInputElement;
            if (datePicker) {
                this.flatpickr = flatpickr(datePicker, {
                    dateFormat: "Y-m-d",
                    altFormat: "j F, Y",
                    altInput: true,
                    locale: Italian
                });
            }
        },
        unmounted() {
            setTimeout(() => {
                this.flatpickr.destroy()
            }, 1000)
        }
    })
</script>
