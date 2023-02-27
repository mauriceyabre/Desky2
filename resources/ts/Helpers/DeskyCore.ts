import { useToast } from "vue-toastification";

interface DeskyCore {
    toast: () => void
}

const toaster = useToast()

export default function DeskyCore(): DeskyCore {
    function toast(type: 'success' | 'error' | 'warning' | 'info' = 'info', message: string = '') {
        toaster.success(message)
    }

    return {
        toast
    }
}
