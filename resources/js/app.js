import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import { router } from "./routes";
import { store } from "./store";

import axios from "axios";

import "vuetify/dist/vuetify.min.css";

Vue.use(VueRouter);
Vue.use(Vuetify);

axios.defaults.withCredentials = true;

const app = new Vue({
    vuetify: new Vuetify(),
    router,
    store,
    created() {
        const userInfo = localStorage.getItem("user");
        if (userInfo) {
            const userData = JSON.parse(userInfo);
            this.$store.commit("setUserData", userData);
        }
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    this.$store.dispatch("logout");
                }
                return Promise.reject(error);
            }
        );
    }
}).$mount("#app");
