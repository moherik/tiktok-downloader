<template>
    <v-app id="inspire">
        <v-app-bar app color="white" flat>
            <v-container class="py-0 fill-height">
                <v-toolbar-title class="toolbar">
                    <div class="logo" @click="gotoHome">
                        <div class="d-flex align-center">
                            <img :src="'/images/logo.svg'" class="mr-2" />
                            <img :src="'/images/tiktok.svg'" />
                        </div>
                        <span class="logo-desc">Video Downloader</span>
                    </div>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="logout">
                            <v-icon>mdi-logout</v-icon>
                        </v-btn>
                    </template>
                    <span>Logout</span>
                </v-tooltip>
            </v-container>
        </v-app-bar>

        <v-main class="grey lighten-3">
            <v-container class="mt-5">
                <v-tabs>
                    <v-tab>Daftar Video</v-tab>
                    <v-tab>Kode Iklan</v-tab>

                    <v-tab-item class="item">
                        <v-progress-circular
                            v-if="loading.video"
                            indeterminate
                            size="30"
                            class="mx-5 my-5"
                        ></v-progress-circular>

                        <div
                            v-else
                            v-for="video in videos"
                            :key="video.id"
                            class="video-item"
                        >
                            <div class="image">
                                <img :src="video.author_avatar" width="50px" />
                            </div>

                            <div class="detail">
                                <h2>{{ video.author_nickname }}</h2>
                                <h4>{{ video.desc }}</h4>
                                <a :href="video.tiktok_url" target="_blank">{{
                                    video.tiktok_url
                                }}</a>

                                <div class="mt-10">
                                    <a
                                        class="btn-download"
                                        :href="video.vid_download_url"
                                        target="_blank"
                                    >
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </v-tab-item>

                    <v-tab-item class="item">
                        <v-progress-circular
                            v-if="loading.ads"
                            indeterminate
                            size="30"
                            class="mx-5 my-5"
                        ></v-progress-circular>

                        <template v-else>
                            <div v-for="item in ads" :key="item.id">
                                <div class="d-flex flex-row align-center">
                                    <div>
                                        {{ item.placement }}
                                    </div>
                                    <v-switch
                                        small
                                        inset
                                        class="ml-auto"
                                        v-model="item.isEnable"
                                        @change="updateAds"
                                    ></v-switch>
                                </div>
                                <textarea
                                    class="text-box"
                                    v-model="item.code"
                                    @change="updateAds"
                                    rows="5"
                                ></textarea>
                            </div>
                        </template>
                    </v-tab-item>
                </v-tabs>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import axios from "axios";
export default {
    name: "Dashboard",
    data: () => ({
        videos: null,
        ads: null,
        loading: {
            video: false,
            ads: false
        }
    }),

    beforeMount() {
        this.getVideos();
        this.getAds();
    },

    methods: {
        gotoHome() {
            this.$router.push("/");
        },

        async getVideos() {
            this.loading.video = true;
            await axios
                .get("/api/video")
                .then(({ data }) => (this.videos = data))
                .catch(err => console.error(err))
                .finally(() => (this.loading.video = false));
        },

        async getAds() {
            this.loading.ads = true;
            await axios
                .get("/api/ads")
                .then(({ data }) => (this.ads = data))
                .catch(err => console.error(err))
                .finally(() => (this.loading.ads = false));
        },

        async updateAds() {
            await axios
                .post("/api/ads", { items: this.ads })
                .catch(err => console.error(err));
        },

        logout() {
            this.$store.dispatch("logout");
        }
    }
};
</script>

<style scoped>
.item {
    min-height: 400px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.video-item {
    display: flex;
    flex-direction: row;
    gap: 16px;
}
.video-item .image img {
    border-radius: 8px;
}
</style>
