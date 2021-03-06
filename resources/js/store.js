import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        user: null
    },

    mutations: {
        setUserData(state, userData) {
            state.user = userData;
            localStorage.setItem("user", JSON.stringify(userData));
            axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`;
        },

        clearUserData() {
            localStorage.removeItem("user");
            location.reload();
        }
    },

    actions: {
        async login({ commit }, credentials) {
            return await axios
                .get("/sanctum/csrf-cookie")
                .then(async _response => {
                    return await axios
                        .post("/api/login", credentials)
                        .then(({ data }) => {
                            commit("setUserData", data);
                        });
                });
        },

        logout({ commit }) {
            commit("clearUserData");
        }
    },

    getters: {
        isLogged: state => !!state.user
    }
});
