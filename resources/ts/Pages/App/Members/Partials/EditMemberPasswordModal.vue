<template>
    <AppModal id="password" title="Modifica Password" :modal="modal" centered>
        <template #form="modalData">
            <div class="px-10 py-12">
                <form :id="modalData?.['form-id']" class="form fv-plugins-bootstrap5 fv-plugins-framework" autocomplete="off"
                    @submit.prevent="submit">
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputPassword required label="Password Attuale" :form="form" name="current_password" v-model="form.current_password" placeholder="Password Attuale" />
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputPasswordMeter required label="Nuova Password" :form="form" name="password" v-model="form.password" placeholder="Nuova Password" />
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputPassword required label="Ripeti Nuova Password" :form="form" name="password_confirmation" v-model="form.password_confirmation" placeholder="Ripeti Nuova Password" />
                    </div>
                    <div class="text-center pt-15">
                        <button type="button" class="btn btn-light me-3" @click.prevent="modal.close()">
                            Annulla
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing || !(form.isDirty)">
                            <span class="indicator-label" v-if="!form.processing">Modifica password</span>
                            <span class="indicator-label" v-else>Attendere...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </AppModal>
</template>
<script setup lang="ts">
    import AppModal from "@Components/AppModal.vue";
    import useModal from "@Composables/useModal";
    import { computed, onMounted } from "vue";
    import User from "@Models/User";
    import InputPassword from "@Components/Inputs/InputPassword.vue";
    import InputPasswordMeter from "@Components/Inputs/InputPasswordMeter.vue";
    import useForm from "@Composables/useForm";

    interface Props {
        selected_member: User
    }
    const props = defineProps<Props>()
    const emits = defineEmits(['modal_closed'])

    const modal = useModal('password_modal');

    const user = computed(() => props.selected_member)

    const form = useForm( {
        current_password: '',
        password: '',
        password_confirmation: ''
    })

    form.setDefaults()

    async function submit() {
        await axios.get('/')
    }

    onMounted(() => {
        modal.open()
        // modal.onClosing(() => resetForm())
        modal.onClosed(() => {
            emits('modal_closed');
        })
    })
</script>
