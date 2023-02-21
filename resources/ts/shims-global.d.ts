import { AxiosInstance } from "axios";
import { Moment } from "moment";
import { Dropdown } from "bootstrap/js/dist/dropdown.d.ts";
import { Modal } from "bootstrap/js/dist/modal.d.ts";

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

    namespace bootstrap {
        const Modal: Modal
        const Dropdown: Dropdown
    }
}
