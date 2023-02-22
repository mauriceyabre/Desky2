import { computed, onMounted, ref } from "vue";

export interface AppModal {
    readonly isOpen: boolean
    close(): void
    open(): void
    onClosing(fn: () => {}|void): void
    onClosed(fn: () => {}|void): void
    onOpened(fn: () => {}|void): void
    onOpening(fn: () => {} | void): void
    element(): HTMLElement|null
    bsElement() : typeof bootstrap.Modal
}

export default function useModal(modalId: string): AppModal {

    const isOpen = ref(false);

    function element() : HTMLElement|null {
        return document.getElementById(modalId);
    }

    function bsElement() : typeof bootstrap.Modal {
        return bootstrap.Modal.getOrCreateInstance(element());
    }

    function close() {
        bsElement().hide();
        isOpen.value = false
    }

    function open() {
        bsElement().show();
        isOpen.value = true;
    }

    function onClosed(fn: () => void): void {
        if (typeof element() !== "string")
            (element() as HTMLElement)?.addEventListener('hidden.bs.modal', fn)
    }

    function onClosing(fn: () => {}|void): void {
        if (typeof element() !== "string")
            (element() as HTMLElement)?.addEventListener('hide.bs.modal', fn)
    }

    function onOpened(fn: () => {}|void): void {
        (element() as HTMLElement)?.addEventListener('shown.bs.modal', fn)
    }

    function onOpening(fn: () => {} | void): void {
        (element() as HTMLElement)?.addEventListener('show.bs.modal', fn)
    }

    onMounted(() => {
        onClosed(() => {
            if (isOpen.value === true) isOpen.value = false;
        })
    })

    return { isOpen: computed(() => isOpen.value).value, close, open, onClosing, onClosed, onOpened, element, bsElement, onOpening}

}
