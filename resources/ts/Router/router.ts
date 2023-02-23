import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import AuthLayout from "@Layouts/AuthLayout.vue";
import Login from "@Pages/Auth/Login.vue";
import Register from "@Pages/Auth/Register.vue";
import AppLayout from "@Layouts/AppLayout.vue";
import { useAuthStore } from "@Stores/useAuthStore";
import { useUserStore } from "@Stores/useUserStore";

async function auth(to, from, next) {
    if (!localStorage.getItem("access_token")) {
        return next({ name: "auth.login" });
    }
    next();
}

function guest(to, from, next) {
    if (localStorage.getItem("access_token")) {
        return next({ name: "dashboard" });
    }
    next();
}

const guestRoutes: RouteRecordRaw = {
    path: '/auth',
    component: AuthLayout,
    beforeEnter: guest,
    children: [
        {
            path: 'login',
            name: 'auth.login',
            component: Login
        },
        {
            path: 'register',
            name: 'auth.register',
            component: Register
        }
    ]
}

const authRoutes: RouteRecordRaw = {
    path: '',
    component: AppLayout,
    beforeEnter: auth,
    children: [
        {
            path: '',
            component: () => import('@Pages/App/Dashboard/Dashboard.vue'),
            alias: ['/dashboard', '/'],
            name: 'dashboard'
        },
        {
            path: 'profile',
            component: () => import('@Pages/App/Members/Partials/MemberShowLayout.vue'),
            beforeEnter: async () => {
                if (useAuthStore().hasUser) {
                    useUserStore().set(await useUserStore().fetch(useAuthStore().user!.id).then((res) => {
                        return res
                    }))
                }
            },
            children: [
                {
                    path: 'overview',
                    name: 'profile.overview',
                    alias: ['/profile'],
                    component: () => import('@Pages/App/Members/MemberOverview.vue'),
                    meta: {
                        title: 'Profilo'
                    }
                },
                {
                    path: 'timesheet',
                    name: 'profile.timesheet',
                    props: route => ({ date: route.query.date }),
                    component: () => import('@Pages/App/Members/MemberTimesheet.vue'),
                    meta: {
                        title: 'Foglio Presenze'
                    }
                },
                {
                    path: 'settings',
                    name: 'profile.settings',
                    component: () => import('@Pages/App/Members/MemberSettings.vue'),
                    meta: {
                        title: 'Impostazioni'
                    }
                }
            ]
        }
    ]
}

const router = createRouter({
    history: createWebHistory(import.meta.env.APP_URL),
    routes: [
        authRoutes,
        guestRoutes
    ],
});

router.beforeEach(async (to, from, next) => {
    const store = useAuthStore()
    if (store.check && !store.hasUser) {
        store.set(await store.fetch())
    }

    const title = to.meta.title as string
    const titleFromParams = to.params.pageTitle
    if (title) {
        document.title = title
    }
    if (titleFromParams) {
        document.title = `${ titleFromParams } - ${ document.title }`;
    }

    next()
})

export default router;
