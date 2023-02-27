import { AxiosInstance } from "axios";
import { Moment } from "moment";
import { Dropdown } from "bootstrap/js/dist/dropdown.d.ts";
import { Modal } from "bootstrap/js/dist/modal.d.ts";
import DeskyCore from "@Helpers/DeskyCore";
export { Select2 } from "select2"

declare global {
    interface Window {
        axios: AxiosInstance
        DESKY: DeskyCore
    }
    const axios: AxiosInstance

    const DESKY: DeskyCore

    const KTApp
    const KTMenu: {
        getInstance(element: unknown): {
            show: () => void
            hide: () => void
        } | undefined
    }
    const KTComponents: {
        init()
    }
    const moment: Moment

    namespace bootstrap {
        const Modal: Modal
        const Dropdown: Dropdown
    }
}
