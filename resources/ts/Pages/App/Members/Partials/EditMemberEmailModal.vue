<template>
    <AppModal id="email" title="Modifica Email" :modal="modal" centered>
        <template #form="modalData">
            <div class="px-10 py-12">
                <form :id="modalData?.formId" class="form fv-plugins-bootstrap5 fv-plugins-framework" autocomplete="off"
                    @submit.prevent="submit">
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputText disabled required label="Indirizzo Email Attuale" :form="form" name="current_email" v-model="form.current_email" placeholder="Indirizzo Email Attuale" />
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputText required label="Nuovo Indirizzo Email" :form="form" name="email" v-model="form.email" placeholder="Nuovo Indirizzo Email" />
                    </div>
                    <div class="fv-row mb-7 fv-plugins-icon-container">
                        <InputText required label="Ripeti Nuovo Indirizzo Email" :form="form" name="email_confirmation" v-model="form.email_confirmation" placeholder="Ripeti Nuovo Indirizzo Email" />
                    </div>
                    <div class="text-center pt-15">
                        <button type="button" class="btn btn-light me-3" @click.prevent="modal.close()">
                            Annulla
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing || !(form.isDirty)">
                            <span class="indicator-label" v-if="!(form.processing)">Modifica indirizzo email</span>
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
    import InputText from "@Components/Inputs/InputText.vue";
    import useForm from "@Composables/useForm";

    interface Props {
        selected_member: User
    }
    const props = defineProps<Props>()
    const emits = defineEmits(['modal_closed'])

    const modal = useModal('email_modal');

    const user = computed(() => props.selected_member)

    const form = useForm({
        current_email: user.value.email,
        email: '',
        email_confirmation: ''
    })

    function resetForm() {
        // form.reset()
        // if (form.hasErrors) form.clearErrors()
    }

    function submit() {
        // form.put(route('user.update.email', user.value.id), {
        //     onSuccess() {
        //         modal.close()
        //     }
        // })
    }

    onMounted(() => {
        modal.open()
        modal.onClosing(() => resetForm())
        modal.onClosed(() => {
            emits('modal_closed')
        })
    })
</script>
