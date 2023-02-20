import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import AuthLayout from "@Layouts/AuthLayout.vue";
import Login from "@Pages/Auth/Login.vue";
import Register from "@Pages/Auth/Register.vue";
import AppLayout from "@Layouts/AppLayout.vue";

function auth(to, from, next) {
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
    path: '/',
    component: AppLayout,
    beforeEnter: auth,
    children: [
        {
            path: '',
            component: () => import('@Pages/App/Dashboard/Dashboard.vue'),
            alias: ['/dashboard'],
            name: 'dashboard'
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

export default router;
