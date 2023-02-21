import { computed, ref } from "vue"
import { defineStore } from "pinia"
import User from "@Models/User"
import Attendance from "@Models/Attendance";

export const useAttendancesStore = defineStore("attendancesStore", () => {
    const isLoading = ref(false)
    const attendances = ref<Attendance[]|null>(null)

    function formatAttendances(items: Object) {
        let attendances: Attendance[]|null = null;
        if (items && Object.keys(items).length) {
            attendances = Object.values(items).map(item => new Attendance(item))
        }
        return attendances
    }

    function setAttendances(items: Object): void {
        attendances.value = formatAttendances(items);
    }

    async function fetchAttendances(user_id: number, date?: string ): Promise<any> {
        isLoading.value = true
        if (!date) {
            date = moment().format('YYYY-MM')
        }

        return await axios.get('attendances', { params: { user_id, date } }).then(res => {
            return res.data.attendances
        }).finally(() => {
            isLoading.value = false
        })
    }

    async function load(user_id: number, date?: string) {
        try {
            setAttendances(await fetchAttendances(user_id, date));
        } catch (e) {
            throw e
        }
    }

    return {
        setAttendances,
        fetchAttendances,
        load,
        isLoading,
        attendances: computed(() => attendances.value ) };
});
