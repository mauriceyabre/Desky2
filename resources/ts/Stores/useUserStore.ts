import { computed, ref } from "vue"
import { defineStore } from "pinia"
import User from "@Models/User"
import Attendance from "@Models/Attendance";

export const useUserStore = defineStore("userStore", () => {
    const isLoading = ref(false)
    const user = ref<User|null>(null)

    function set(userObject: Object): void {
        user.value = new User(userObject);
    }

    async function fetch(id: number): Promise<any> {
        isLoading.value = true
        return await axios.get('members/' + id).then(res => {
            return res.data.user
        }).finally(() => {
            isLoading.value = false
        })
    }

    async function load(id: number) {
        set(await fetch(id))
    }

    return {
        set,
        fetch,
        load,
        isLoading,
        user: computed(() => user.value as User|null ) };
});
