import { AxiosInstance } from "axios";
declare global {
    interface Window {
        axios: AxiosInstance
    }
    const axios: AxiosInstance
}
