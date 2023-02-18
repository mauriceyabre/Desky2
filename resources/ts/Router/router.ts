import { createRouter, createWebHistory } from "vue-router";
import AuthLayout from "@Layouts/AuthLayout.vue";
import Login from "@Pages/Auth/Login.vue";
import Register from "@Pages/Auth/Register.vue";

function auth(to, from, next) {
    if (!localStorage.getItem("access_token")) {
        return next({ name: "register" });
    }

    next();
}

function guest(to, from, next) {
    if (localStorage.getItem("access_token")) {
        return next({ name: "vehicles.index" });
    }

    next();
}

const router = createRouter({
    history: createWebHistory(import.meta.env.APP_URL),
    routes: [
        {
            path: '/auth',
            component: AuthLayout,
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
    ],
});

export default router;
