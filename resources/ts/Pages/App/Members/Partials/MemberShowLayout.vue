<template>
    <div class="card mb-4 mb-xl-8">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px">
                        <div class="symbol symbol-25px">
                            <div :class="`symbol-label fs-fluid fw-bold ${isArchived ? 'text-warning bg-light-warning' : 'text-primary bg-light-primary'}`">
                                <span class="fs-4x fw-normal">{{ user?.initials }}</span>
                            </div>
                        </div>
                        <div v-if="!isArchived" class="position-absolute translate-middle bottom-0 start-100 mb-6 rounded-circle border border-4 border-body h-20px w-20px" :class="user?.hasActiveSession ? 'bg-success' : 'bg-danger'"></div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-1" v-if="!isArchived">{{ user?.name }}</span>
                                <span class="text-warning fs-2 fw-bold me-1" v-if="isArchived">{{ user?.name }}
                                    <a href="#">
                                        <i class="bi bi-archive-fill fs-4 text-warning" />
                                    </a>
                                </span>
                                <span class="badge badge-light fw-bold ms-2 fs-8 py-1 px-3">{{ user?.jobTitle }}</span>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <i class="bi bi-hash" />
                                    </span>
                                    {{ user?.formattedId }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2" v-if="!!user?.shortAddress">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <i class="bi bi-geo-alt" />
                                    </span>
                                    {{ user?.shortAddress }}
                                </span>
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <i class="bi bi-envelope" />
                                    </span>
                                    {{ user?.email }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-0" v-if="!isArchived">
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="bi bi-three-dots fs-3"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="false" style="">
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                            Payments
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="me-0" v-else>
                                <button @click.prevent="destroy" class="btn btn-sm btn-icon btn-light btn-active-light-danger" data-bs-toggle="tooltip" title="Elimina Definitivamente">
                                    <i class="bi bi-x-lg fs-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
<!--                            <div class="d-flex flex-wrap">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$" data-kt-initialized="1">$4,500</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Earnings</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                                <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z"
                                                        fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="75" data-kt-initialized="1">75</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Projects</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="60" data-kt-countup-prefix="%" data-kt-initialized="1">%60</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-400">Success Rate</div>
                                </div>
                            </div>-->
                        </div>
                        <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3" v-if="user?.profileCompleteness < 100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-semibold fs-6 text-gray-400">Completezza Profilo</span>
                                <span class="fw-bold fs-6">{{ user?.profileCompleteness }}%</span>
                            </div>
                            <div class="h-5px mx-3 w-100 bg-light mb-3">
                                <div class="bg-success rounded h-5px" role="progressbar" :style="'width:'+user?.profileCompleteness+'%'" :aria-valuenow="user?.profileCompleteness" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item mt-2" v-for="tab in tabs" :key="tab.key">
                    <RouterLink role="link" class="nav-link text-active-primary ms-0 me-10 py-5" :to="tab.route" :class="{'active': activePage.includes(tab.key)}">
                        {{ tab.title }}
                    </RouterLink>
                </li>
            </ul>
        </div>
    </div>
    <RouterView />
<!--    <MemberTabOverview :user="user" v-if="selectedTab === 'overview'" />
    <MemberTabTimesheet :user="user" :data="data" v-if="selectedTab === 'timesheet'" />
    <MemberTabSettings :user="user" v-if="selectedTab === 'settings'" />-->
</template>
<script setup lang="ts">
    import User from "@Models/User";
    import { computed, onMounted, onUnmounted } from "vue";
    import { useAuthStore } from "@Stores/useAuthStore";
    import { useUserStore } from "@Stores/useUserStore";
    // import { usePage } from "@inertiajs/inertia-vue3";
    // import MemberTabOverview from "@Pages/App/Members/Tabs/MemberTabOverview.vue";
    // import MemberTabSettings from "@Pages/App/Members/Tabs/MemberTabSettings.vue";
    // import MemberTabTimesheet from "@Pages/App/Members/Tabs/MemberTabTimesheet.vue";
    // import SBCore from "@Helpers/SBCore";
    // import { Inertia } from "@inertiajs/inertia";

    interface Props {
        id?: number,
        // user: User,
        // tab: string,
        // is_auth: boolean
        // data?: {
        //     attendances: Object
        // }
    }

    const store = useUserStore()
    const auth = useAuthStore()

    const props = defineProps<Props>()

    const user = computed(() => store.user)
    const selectedTab = computed(() => 'overview')
    const activePage = computed(() => 'overview') // usePage().url.value)
    const isArchived = computed(() => false)

    const isAuth = computed(() => false)

    const tabs = [
        {
            key: 'overview',
            title: 'Panoramica',
            data: [],
            // component: MemberTabOverview,
            route: {name: 'profile.overview'} //(isAuth.value) ? route('profile.overview') : route('member.overview', { id: user.value.id })
        },
        {
            key: 'timesheet',
            title: 'Foglio Presenze',
            data: ['data'],
            // component: MemberTabTimesheet,
            route: { name: 'profile.timesheet' } //(isAuth.value) ? route('profile.timesheet') : route('member.timesheet', { id: user.value.id })
        },
        {
            key: 'settings',
            title: 'Impostazioni',
            // component: MemberTabSettings,
            route: { name: 'profile.settings' }, //(isAuth.value) ? route('profile.settings') : route('member.settings', { id: user.value.id }),
            data: []
        }
    ]

    function destroy() {
        // SBCore.dangerDialog({text: 'Sei sicuro di voler eliminare questo utente?\nL\'operazione non è reversibile.', title: 'Elimina Utente' }, () => {
        //     Inertia.delete(route('user.destroy', { id: user.value.id, action: 'redirect' }))
        // })
    }

    onMounted(async () => {
        if (auth.hasUser) {
            store.set(await store.fetch(auth.user!.id))
        }
    })

    onUnmounted(() => {
        store.$dispose()
    })

</script>
