import { AxiosInstance } from "axios";
import { Moment } from "moment";
import { Dropdown } from "bootstrap/js/dist/dropdown.d.ts";
import { Modal } from "bootstrap/js/dist/modal.d.ts";
import AppCore from "@Helpers/AppCore";
export { Select2 } from "select2"

declare global {
    interface Window {
        axios: AxiosInstance
    }
    const axios: AxiosInstance
    const KTApp
    const KTComponents: {
        init()
    }
    const moment: typeof Moment
    const appCore: typeof AppCore

    namespace bootstrap {
        const Modal: Modal
        const Dropdown: Dropdown
    }
}
