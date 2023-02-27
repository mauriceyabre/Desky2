import { computed, ref } from "vue"
import { defineStore } from "pinia"
import User from "@Models/User"
import Attendance from "@Models/Attendance";
import { useAuthStore } from "@Stores/useAuthStore";

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

    function pushToAuthStore() {
        if (!!user.value) {
            useAuthStore().sync(user.value)
        } else {
            console.log('No User Found to Push')
        }
    }

    function getFromAuthStore() {
        if (!!useAuthStore().user) {
            user.value = useAuthStore().user
        } else {
            console.log('No User Found to Push')
        }
    }

    return {
        set,
        fetch,
        load,
        pushToAuthStore,
        getFromAuthStore,
        isLoading,
        user: computed(() => user.value as User|null ) };
});
