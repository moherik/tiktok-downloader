import Router from "vue-router";
import Home from "./view/home.vue";
import Dashboard from "./view/dashboard.vue";
import Login from "./view/login.vue";

export const router = new Router({
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/dashboard",
            name: "dashboard",
            component: Dashboard
        },
        {
            path: "/login",
            name: "login",
            component: Login
        }
    ],
    mode: "history"
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem("user");

    if (to.name === "dashboard" && !loggedIn) next({ name: "login" });

    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next("/login");
        return;
    }
    next();
});
