<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    Coming Soon
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
    import { computed } from "vue";
    import User from "@Models/User";

    interface Props {
        // user: User
    }
    const props = defineProps<Props>();
    // const user = computed(() => {
    //     return props.user
    // })
</script>
