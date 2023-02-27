<template>
    <div v-bind="$attrs" ref="selectEl">
        <InputLabel v-if="label" :required="required">
            {{ label }}
            <span class="badge badge-light-primary">
                {{ selectedMembers.length }}
            </span>
        </InputLabel>
        <div class="mb-2" v-if="selectedMembers?.length">
            <div class="bg-light-primary pe-2 rounded-pill me-2 mb-2 d-inline-block cursor-default" v-for="member in selectedMembers">
                <div class="d-flex flex-center">
                    <div class="symbol symbol-circle symbol-25px me-2">
                        <div class="symbol-label bg-primary text-white fs-8">{{ member.initials }}</div>
                    </div>
                    <div class="d-flex justify-content-start flex-column flex-center">
                        <span class="text-whi fs-6">{{ member.first_name }}</span>
                    </div>
                    <button type="button" class="close btn btn-sm p-0 ms-2" aria-label="Close" data-bs-toggle="tooltip" title="rimuovi" data-bs-trigger="hover" @click.prevent="removeMember(member.id)">
                        <i aria-hidden="true" class="bi bi-x-lg p-1 rounded-circle text-hover-danger" />
                    </button>
                </div>
            </div>
        </div>
        <div class="mb-2" v-else>
            <div class="bg-light-danger px-2 py-1 w-100 rounded-pill me-2 mb-2 d-inline-block cursor-default">
                <div class="d-flex flex-center">
                    <div class="d-flex justify-content-start flex-column flex-center">
                        <span class="text-whi fs-6">Nessun Membro Assegnato</span>
                    </div>
                </div>
            </div>
        </div>
        <select :name="name" aria-label="Seleziona un paese" data-control="select2" :id="name" :data-placeholder="placeholder"
                class="form-select form-select-solid" :class="(form.errors?.[name]) ? 'is-invalid' : ''"
                :data-dropdown-parent="modalId" :data-select2-id="name">
            <option v-if="placeholder" value="">{{ placeholder }}</option>
            <option v-for="item in options" :value="item.value">{{ item.title }}
            </option>
        </select>
        <div :class="(form.errors?.[name]) ? 'invalid-feedback' : ''" v-if="form.errors?.[name]">
            {{ form.errors?.[name] }}
        </div>
    </div>
</template>
<script lang="ts">
    import { InertiaForm } from "@inertiajs/vue3";
    import { computed, defineComponent, nextTick, onMounted, ref, shallowRef } from "vue";
    import InputLabel from "@Components/Inputs/InputLabel.vue";
    import { useMembersStore } from "@Stores/useMembersStore";
    import User from "@Models/User";

    export default defineComponent({
        name: "InputMembersSelect",
        components: { InputLabel },
        setup(props, { emit }) {
            const modalId = ref('');
            const unselectedItems = ref<SelectOption[]>();
            const members = useMembersStore().all;
            const element = shallowRef();
            const selectEl = ref();

            const selected = computed<number[]>(() => {
                return props.modelValue as number[];
            })

            const options = computed(() => {
                return useMembersStore().selectOptions.filter(item => !selected.value.includes(item.value));
            })

            const selectedMembers = computed<User[]>(() => {
                let filtered: any[] = [];
                selected.value.forEach(id => {
                    const member = members.find(member => member.id == id) ?? null
                    filtered.push(member)
                });
                return filtered;
            })

            function removeMember(id: number): void {
                const newValue = [...selected.value];
                const index = newValue.indexOf(id);
                if (index !== -1) {
                    newValue.splice(index, 1)
                }
                emit('update:modelValue', newValue);
            }

            onMounted(() => {

                if (props.inModal) {
                    const node = selectEl.value as HTMLElement;
                    modalId.value = '#' + node?.closest('.modal')?.id ?? '';
                }

                nextTick(() => {
                    element.value = $(`select[name=${ props.name }]`);
                    element.value.select2();

                    element.value.on('select2:selecting', (e) => {
                        const value = e.params.args.data.id;

                        if (selected.value.includes(value)) {
                            e.preventDefault();
                            return;
                        }

                        const newValues = [...selected.value];
                        newValues.push(Number(value));
                        e.preventDefault();
                        element.value.select2('close').trigger('change');
                        emit('update:modelValue', newValues);

                        element.value.select2('destroy');
                        element.value.select2()
                    })
                })
            })

            return {
                modalId,
                selected,
                unselectedItems,
                members,
                options,
                selectedMembers,
                selectEl,
                removeMember
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
                type: Object as InertiaForm<any>,
                required: true
            },
            inModal: Boolean,
            required: Boolean,
            label: String
        },
        emits: ['update:modelValue']
    })
</script>
