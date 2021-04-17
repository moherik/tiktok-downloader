<template>
    <v-app id="inspire">
        <v-app-bar app color="white" flat>
            <v-container class="py-0 fill-height">
                <v-toolbar-title class="toolbar">
                    <div class="logo">
                        <div class="d-flex align-center">
                            <img :src="'/images/logo.svg'" class="mr-2" />
                            <img :src="'/images/tiktok.svg'" />
                        </div>
                        <span class="logo-desc">Video Downloader</span>
                    </div>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <template v-if="isLogged">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                icon
                                v-bind="attrs"
                                v-on="on"
                                @click="gotoDashboard"
                            >
                                <v-icon>mdi-view-dashboard-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Dashboard</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                icon
                                v-bind="attrs"
                                v-on="on"
                                @click="logout"
                            >
                                <v-icon>mdi-logout</v-icon>
                            </v-btn>
                        </template>
                        <span>Logout</span>
                    </v-tooltip>
                </template>
            </v-container>
        </v-app-bar>

        <v-main class="grey lighten-3">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="8" class="mx-auto">
                        <v-container class="text-center py-16">
                            <h1 class="mb-5">
                                Download
                                <span class="text-primary">TikTok</span>
                                Video
                            </h1>
                            <div class="mb-5">
                                Cara mudah untuk mengunduh video dari tiktok
                                tanpa watermark
                            </div>
                            <div class="search-box">
                                <input
                                    ref="urlBox"
                                    v-model="url"
                                    class="text-box"
                                    type="text"
                                    placeholder="Masukan URL vidio TikTok"
                                />
                                <div id="ads-below-search"></div>
                                <div
                                    v-if="url != ''"
                                    class="search-button"
                                    @click="searchVideo"
                                    @keyup.enter="searchVideo"
                                >
                                    <template v-if="!loading">
                                        <v-icon class="icon"
                                            >mdi-magnify</v-icon
                                        >
                                        <span class="text">Cari video</span>
                                    </template>
                                    <v-progress-circular
                                        v-else
                                        indeterminate
                                        size="20"
                                    ></v-progress-circular>
                                </div>
                                <div
                                    v-else
                                    class="search-button"
                                    @click="handlePaste"
                                >
                                    <v-icon class="icon" size="medium"
                                        >mdi-clipboard-outline</v-icon
                                    >
                                </div>
                            </div>

                            <detail
                                v-if="detail != null"
                                :detail="detail"
                                :streamUrl="streamUrl"
                            />
                        </v-container>
                    </v-col>
                </v-row>
            </v-container>

            <v-sheet>
                <v-container class="text-center py-16">
                    <div id="ads-above-step"></div>

                    <v-row align-center>
                        <v-col cols="12" sm="4">
                            <div class="icon-circle mb-4">
                                <v-icon color="white" large
                                    >mdi-video-outline</v-icon
                                >
                            </div>
                            <h2 class="my-2">1. Temukan Vidio</h2>
                            <p>
                                Dapatkan url dari TikTok dengan menekan tombol
                                share > salin url
                            </p>
                        </v-col>
                        <v-col cols="12" sm="4">
                            <div class="icon-circle mb-4">
                                <v-icon color="white" large
                                    >mdi-clipboard-outline</v-icon
                                >
                            </div>
                            <h2 class="my-2">2. Salin URL</h2>
                            <p>
                                Salin link TikTok kedalam kolom pencarian
                                diatas, dan klik tombol "Cari Vidio"
                            </p>
                        </v-col>
                        <v-col cols="12" sm="4">
                            <div class="icon-circle mb-4">
                                <v-icon color="white" large
                                    >mdi-download-outline</v-icon
                                >
                            </div>
                            <h2 class="my-2">3. Download File Vidio</h2>
                            <p>
                                Simpan vidio ke perangkat anda.
                            </p>
                        </v-col>
                    </v-row>

                    <div id="ads-below-step"></div>
                </v-container>
            </v-sheet>
        </v-main>

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
import axios from "axios";
import { mapGetters } from "vuex";
import Detail from "../component/Detail.vue";

export default {
    name: "Home",

    components: { Detail },

    data: () => ({
        drawer: false,
        group: null,
        url: "",
        loading: false,
        detail: null,
        streamUrl: "",
        link: null,
        snackbar: {
            show: false,
            text: "",
            timeout: 2000
        },
        ads: {
            belowSearch: null,
            aboveStep: null,
            belowStep: null
        }
    }),

    computed: {
        ...mapGetters(["isLogged"])
    },

    watch: {
        group() {
            this.drawer = false;
        }
    },

    beforeMount() {
        this.getAds();
    },

    methods: {
        async searchVideo(event) {
            this.loading = true;
            await axios
                .post("/api/video", { url: this.url })
                .then(({ data }) => {
                    this.getVideo(data.id);
                    event.preventDefault();
                })
                .catch(err => {
                    this.showSnackbar("Terjadi kesalahan");
                })
                .finally(() => (this.loading = false));
        },

        async getVideo(id) {
            await axios
                .get(`/api/video/${id}/detail`)
                .then(({ data }) => {
                    this.detail = data;
                    this.streamUrl = `http://localhost:8000/api/video/stream?url=${data.tiktok_url}`;
                })
                .catch(err => {
                    this.showSnackbar("Terjadi kesalahan");
                })
                .finally(() => (this.loading = false));
        },

        async getAds() {
            await axios
                .get("/api/ads/active")
                .then(({ data }) => {
                    data.forEach(ads => {
                        if (ads.placement == "below-search") {
                            this.ads.belowSearch = ads.code;
                        } else if (ads.placement == "above-step") {
                            this.ads.aboveStep = ads.code;
                        } else if (ads.placement == "below-step") {
                            this.ads.belowStep = ads.code;
                        }
                    });
                })
                .catch(err => console.error(err));
        },

        async handlePaste(event) {
            this.url = await navigator.clipboard.readText();
            this.searchVideo();
        },

        gotoDashboard() {
            this.$router.push("/dashboard");
        },

        logout() {
            this.$store.dispatch("logout");
        },

        showSnackbar(text) {
            this.snackbar.text = text;
            this.snackbar.show = true;
        }
    }
};
</script>
