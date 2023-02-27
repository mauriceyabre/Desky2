<template>
    <div class="row">
        <div class="col-md-7">
            <AppCard flush title="Informazioni">
                <AppDataList>
                    <AppDataRow label="Nome">{{ user?.name }}</AppDataRow>
                    <AppDataRow label="Mansione/Ruolo" v-if="user?.jobTitle">{{ user.jobTitle }}</AppDataRow>
                    <AppDataRow label="Email">{{ user?.email }}</AppDataRow>
                    <AppDataRow label="Telefono" v-if="user?.phone">{{ user.phone }}</AppDataRow>
                    <AppDataRow label="Paese" v-if="user?.countryName">{{ user.countryName }}</AppDataRow>
                    <AppDataRow label="Indirizzo" v-if="user?.hasAddress">
                        <span v-html="user?.plainAddress" />
                    </AppDataRow>
                    <AppDataRow label="Data di Nascita" v-if="user?.birthday">{{ moment(user.birthday).format('ll').capitalize() }}
                        <span class="text-muted fw-normal">({{ user?.age }} anni)</span>
                    </AppDataRow>
                    <div class="separator" />
                    <AppDataRow label="Codice Fiscale" v-if="user?.tax_id">{{ user?.tax_id }}</AppDataRow>
                    <AppDataRow label="Partita Iva" v-if="user?.vat_id">{{ user?.vat_id }}</AppDataRow>
                    <AppDataRow label="IBAN" v-if="user?.iban">{{ user?.iban }}</AppDataRow>
                    <div class="separator" />
                    <AppDataRow label="Data di Assunzione" v-if="user?.hire_date">{{ moment(user?.hire_date).format('ll').capitalize() }}</AppDataRow>
                </AppDataList>
            </AppCard>
        </div>
        <div class="col-md-5">
            <AppCard flush>
                Coming Soon
            </AppCard>
        </div>
    </div>
</template>
<script setup lang="ts">
    import AppCard from "@Components/AppCard.vue";
    import AppDataList from "@Components/AppDataList.vue";
    import AppDataRow from "@Components/AppDataRow.vue";
    import { useUserStore } from "@Stores/useUserStore";
    import { computed } from "vue";

    const userStore = useUserStore()
    const user = computed(() => userStore.user)
</script>
