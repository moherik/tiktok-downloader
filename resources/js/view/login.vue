<template>
    <v-app>
        <v-container class="container">
            <h1 class="mb-5 text-center">Login</h1>
            <form @submit.prevent="login" class="form">
                <input
                    type="text"
                    class="text-box"
                    name="username"
                    placeholder="Nama pengguna"
                    v-model="username"
                    required
                />
                <input
                    type="password"
                    class="text-box"
                    name="password"
                    placeholder="Kata sandi"
                    v-model="password"
                    required
                />
                <button type="submit" class="btn">Login</button>
                <v-progress-circular
                    v-show="loading"
                    indeterminate
                ></v-progress-circular>
            </form>
        </v-container>

        <v-snackbar v-model="snackbar.show" :timeout="snackbar.timeout">
            {{ snackbar.text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="blue"
                    text
                    v-bind="attrs"
                    @click="snackbar.show = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
export default {
    name: "Login",
    data: () => ({
        username: null,
        password: null,
        loading: false,
        snackbar: {
            show: false,
            text: "",
            timeout: 2000
        }
    }),
    methods: {
        login() {
            this.loading = true;
            if (this.username == "" || this.password == "") {
                this.snackbar.text = "Kolom harus diisi";
                this.snackbar.show = true;
                return;
            }

            this.$store
                .dispatch("login", {
                    username: this.username,
                    password: this.password
                })
                .then(() => {
                    this.$router.push({ name: "dashboard" });
                })
                .catch(({ response }) => {
                    const data = response.data;

                    this.snackbar.text = `Login Error: ${data.message}`;
                    this.snackbar.show = true;
                })
                .finally(() => (this.loading = false));
        }
    }
};
</script>

<style scoped>
.container {
    margin: 120px auto 0px auto;
    width: 300px;
}
.form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
</style>
