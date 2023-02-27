<template>
    <div v-bind="$attrs" ref="select">
        <InputLabel v-if="label" :required="required">{{ label }}</InputLabel>
            <select :disabled="disabled" :name="name" aria-label="Seleziona un paese" data-control="select2" :id="name" :data-placeholder="placeholder" :data-allow-clear="clearable"
                    class="form-select form-select-solid" :class="(form.errors?.[name]) ? 'is-invalid' : ''" :value="modelValue"
                    :data-dropdown-parent="modalId" :data-select2-id="name" :data-hide-search="hideSearch">
                <option v-if="placeholder" value="">{{ placeholder }}</option>
                <option v-for="item in items" :value="item.value">{{ item.title }}</option>
            </select>
        <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">{{ form.errors?.[name] }}</div>
    </div>
</template>
<script lang="ts">
    import { defineComponent, nextTick, PropType, ref } from "vue";
    import InputLabel from "@Components/Inputs/InputLabel.vue";

    export default defineComponent({
        name: "InputSelect",
        components: { InputLabel },
        data() {
            return {
                select: ref<HTMLElement>(),
                modalId: ref<string>()
            }
        },
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
                type: Object as PropType<AppForm<any>>,
                required: true
            },
            items: {
                type: Object as PropType<SelectOption[]>,
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
        mounted() {

            if (this.inModal) {
                const node = this.$refs.select as HTMLElement;
                this.modalId = '#' + node?.closest('.modal')?.id ?? '';
            }

            nextTick(() => {
                const selectElement = $(`select[name=${ this.name }]`);
                selectElement.select2({
                    tags: true,
                    minimumResultsForSearch: this.hideSearch ? Infinity: 0,
                    createTag: function (params) {
                        let term = params.term.trim();
                        if (term === '') {
                            return null;
                        }
                        return {
                            id: JSON.stringify({
                                newValue: true,
                                value: term
                            }),
                            text: term
                        }
                    }
                });
                const emitUpdate = (value) => {
                    this.$emit('update:modelValue', value)
                }
                selectElement.on('select2:select', (e) => {
                    emitUpdate(e.params.data.id)
                })
                selectElement.on('select2:clear', () => {
                    emitUpdate(null);
                })
                selectElement.on('select2:open', () => {
                    this.form.clearErrors(this.name as never);
                })
            })
        },
        watch: {
            modelValue(newValue) {
                $(`select[name=${ this.name }]`).val(newValue).trigger('change');
            }
        },
    })
</script>
