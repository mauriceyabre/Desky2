<template>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <p class="fs-1 text-white mb-0 opacity-75">Archivision</p>
                    <h1 class="fs-5x fs-fluid fw-bolder text-white">DESKY</h1>
                </div>
            </div>
            <div class="d-flex flex-center w-lg-50 p-10">
                <div class="card rounded-3 w-md-550px">
                    <div class="card-body p-10 p-lg-20">
                        <RouterView />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
    import { onMounted } from "vue";

    onMounted(() => {
        document.body.style.backgroundImage = "url('/assets/media/auth/bg9.jpg')";
    })
</script>
