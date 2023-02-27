<template>
    <div class="row g-4 g-xxl-8">
        <div class="col-7">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h2>Dettagli</h2>
                    </div>
                </div>
                <div>
                    <form id="profile_details" class="form fv-plugins-bootstrap5 fv-plugins-framework" @reset.prevent @submit.prevent="submitDetails">
                        <div class="card-body pt-0">
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label required fw-bold fs-6 text-gray-600">Nome e Cognome</label>
                                <div class="col-lg-9">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <InputText :form="form" name="first_name" v-model="form.first_name" />
                                        </div>
                                        <div class="col-lg-6">
                                            <InputText :form="form" name="last_name" v-model="form.last_name" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span class="required">Paese</span>
                                </label>
                                <div class="col-lg-9 fv-row fv-plugins-icon-container">
                                    <InputSelect2 :options="countries" :form="form" name="address.country_code" id="country_code" placeholder="Seleziona un paese..." v-model="form.address.country_code" />
                                </div>
                            </div>
                            <div class="row mb-6" v-if="form.address.country_code === 'IT'">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">Indirizzo
                                </label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-12 fv-row">
                                            <InputText :form="form" name="address.street" class="mb-3" v-model="form.address.street" placeholder="Es. Via Mario Rossi, 10" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 fv-row pe-lg-2">
                                            <InputText :form="form" name="address.postcode" class="mb-3" v-model="form.address.postcode" placeholder="Cap" />
                                        </div>
                                        <div class="col-lg-6 fv-row px-lg-0">
                                            <InputText :form="form" name="address.city" class="mb-3" v-model="form.address.city" placeholder="Comune o Città" />
                                        </div>
                                        <div class="col-lg-3 fv-row ps-lg-2">
                                            <InputText :form="form" name="address.province" class="mb-3" v-model="form.address.province" placeholder="Prov." />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Mansione</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputText :form="form" name="job" field-type="text" placeholder="es. 3D Artist" v-model="form.job" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Telefono</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputText :form="form" name="phone" field-type="tel" placeholder="es. 3201234567" v-model="form.phone" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Compleanno</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputDate :form="form" name="birthday" v-model="form.birthday" />
                                </div>
                            </div>
                        </div>
                        <div class="separator" />
                        <div class="card-body">
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Codice Fiscale</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputText :form="form" name="tax_id" placeholder="es. KTVNGY77T30Y514A" v-model="form.tax_id" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Partita Iva</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputText :form="form" name="vat_id" placeholder="es. IT85458800571" v-model="form.vat_id" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>IBAN</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputText :form="form" name="iban" placeholder="es. IT29V8096100147J4HK47CRBD59" v-model="form.iban" />
                                </div>
                            </div>
                        </div>
                        <div class="separator" />
                        <div class="card-body">
                            <div class="row">
                                <label class="col-lg-3 col-form-label fw-bold fs-6 text-gray-600">
                                    <span>Data di assunzione</span>
                                </label>
                                <div class="col-lg-9 fv-row">
                                    <InputDate :form="form" name="hire_date" v-model="form.hire_date" :disabled="!user?.is_admin" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button class="btn btn-light btn-active-light-primary me-2" type="reset" @click.prevent="resetDetails" v-if="form.isDirty">Annulla
                            </button>
                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit" :disabled="form.processing || !form.isDirty">
                                <span class="indicator-label" v-if="!(form.processing)">Salva
                                </span>
                                <span class="indicator-label" v-else>Attendere...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <input type="hidden">
                        <div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h2>Account</h2>
                    </div>
                </div>
                <div class="card-body pt-0 pb-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                            <tbody class="fs-6 fw-bold text-gray-600">
                                <tr>
                                    <td>Email</td>
                                    <td>{{ user?.email }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" @click="isEmailModalOpen = true">
                                            <i class="bi bi-pencil-fill text-primary" />
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Password</td>
                                    <td>********</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" @click="isPasswordModalOpen = true">
                                            <i class="bi bi-pencil-fill text-primary" />
                                        </button>
                                    </td>
                                </tr>
                                <!-- TODO:: Aggiungere Funzione Cambio Ruolo -->
                                <!-- TODO:: Aggiungere Funzione Disattiva Account -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <teleport to="body">
        <EditMemberEmailModal :key="'email-form'" :selected_member="user" v-if="isEmailModalOpen" @modal_closed="isEmailModalOpen = false" />
        <EditMemberPasswordModal :key="'password-form'" v-if="isPasswordModalOpen" :selected_member="user" @modal_closed="isPasswordModalOpen = false" />
    </teleport>
</template>
<script setup lang="ts">
    import CountryModel from "@Models/Country";
    import { useToast } from 'vue-toastification';
    import { computed, onMounted, ref, watch } from "vue";
    import useForm from "@Composables/useForm";
    import InputText from "@Components/Inputs/InputText.vue";
    import InputDate from "@Components/Inputs/InputDate.vue";
    import { useUserStore } from "@Stores/useUserStore";
    import InputSelect2 from "@Components/Inputs/InputSelect2.vue";
    import EditMemberPasswordModal from "@Pages/App/Members/Partials/EditMemberPasswordModal.vue";
    import EditMemberEmailModal from "@Pages/App/Members/Partials/EditMemberEmailModal.vue";

    const userStore = useUserStore()

    const toast = useToast()
    const countries = CountryModel.getAllForSelect()

    const isPasswordModalOpen = ref(false)
    const isEmailModalOpen = ref(false)

    const user = computed(() => {
        return userStore.user;
    })

    const form = useForm({
        first_name: user.value?.first_name,
        last_name: user.value?.last_name,

        job: user.value?.job,

        iban: user.value?.iban,
        tax_id: user.value?.tax_id,
        vat_id: user.value?.vat_id,

        hire_date: user.value?.hire_date,

        address: {
            id: user.value?.address?.addressable_id,
            street: user.value?.address?.street,
            postcode: user.value?.address?.postcode,
            city: user.value?.address?.city,
            province: user.value?.address?.province,
            country_code: user.value?.address?.country_code,
        },

        phone: user.value?.phone,
        birthday: user.value?.birthday,
    })

    form.setDefaults()

    watch(() => form.address.country_code, (value, oldValue) => {
        if (value !== oldValue && value !== 'IT') {
            form.address.street = ''
            form.address.postcode = ''
            form.address.city = ''
            form.address.province = ''
        }
    })

    function submitDetails() {
        form
            .transform((data) => {
                let toEditDetails = {}
                let toEditAddress = {}
                Object.keys(data).forEach(key => {
                    if (key !== 'address') {
                        if (data[key] !== form.defaults()[key]) {
                            toEditDetails[key] = data[key]
                        }
                    } else {
                        Object.keys(data['address']).forEach(addKey => {
                            if (data['address'][addKey] !== form.defaults()['address'][addKey]) {
                                toEditAddress[addKey] = data['address'][addKey];
                            }
                        })
                    }
                })

                if (!!Object.keys(toEditAddress).length) {
                    toEditAddress['id'] = data['address']['id'] ?? null
                }

                return {
                    ...toEditDetails,
                    address: !!Object.keys(toEditAddress).length ? toEditAddress : undefined
                }
            })
            .put('members/' + user.value?.id, {
                onBefore(data): any {
                    console.log(data)
                    return;
                },
                onSuccess(res): any {
                    DESKY.toast('success', res.data.toast)
                    userStore.set(res.data.user)
                    userStore.pushToAuthStore()

                    form.setDefaults()
                }
            })
    }

    function resetDetails() {
        // form.reset();
        // form.clearErrors();
    }

    onMounted(async() => {
        // form = useForm({
        //     first_name: user.value?.first_name,
        //     last_name: user.value?.last_name,
        //
        //     job: user.value?.job,
        //
        //     iban: user.value?.iban,
        //     tax_id: user.value?.tax_id,
        //     vat_id: user.value?.vat_id,
        //
        //     hire_date: user.value?.hire_date,
        //
        //     address: {
        //         id: user.value?.address?.addressable_id,
        //         street: user.value?.address?.street,
        //         postcode: user.value?.address?.postcode,
        //         city: user.value?.address?.city,
        //         province: user.value?.address?.province,
        //         country_code: user.value?.address?.country_code,
        //     },
        //
        //     phone: user.value?.phone,
        //     birthday: user.value?.birthday,
        // })
    })
</script>
