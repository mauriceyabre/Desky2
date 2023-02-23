import { computed, ref } from "vue"
import { defineStore } from "pinia"
import { useToast } from "vue-toastification";

const toaster = useToast()

export const useGlobalStore = defineStore("globalStore", () => {
    const isLoading = ref(false)

    function pushToast(type: 'success'|'error'|'warning'|'info' = 'info', message: string = '') {
        toaster.success(message)
    }

    return {
        isLoading,
        pushToast
    }
})
