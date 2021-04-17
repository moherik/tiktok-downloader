<template>
    <v-row v-if="detail != null" class="mt-16 text-left">
        <v-col cols="12" sm="4">
            <video :src="streamUrl" controls class="video" />
        </v-col>
        <v-col cols="12" sm="7">
            <div class="d-flex align-start author">
                <v-avatar>
                    <img :src="detail.author_avatar" alt="John" />
                </v-avatar>
                <div class="ml-4">
                    <p class="mb-0 mt-1">
                        <strong>{{ detail.author_username }}</strong>
                        {{ detail.author_nickname }}
                    </p>
                    <p>
                        {{ detail.desc }}
                    </p>
                    <p>
                        <strong>
                            <v-icon color="black" size="20px">mdi-music</v-icon>
                            {{ detail.mus_title }}
                            -
                            {{ detail.mus_author }}
                        </strong>
                    </p>
                    <div class="stats">
                        <div class="text-center">
                            <v-icon color="black" size="30px"
                                >mdi-heart-outline</v-icon
                            >
                            <div class="text-sm">
                                {{ nFormatter(detail.digg_count) }}
                            </div>
                        </div>
                        <div class="text-center">
                            <v-icon color="black" size="30px"
                                >mdi-chat-outline</v-icon
                            >
                            <div class="text-sm">
                                {{ nFormatter(detail.comment_count) }}
                            </div>
                        </div>
                        <div class="text-center">
                            <v-icon color="black" size="30px"
                                >mdi-share-outline</v-icon
                            >
                            <div class="text-sm">
                                {{ nFormatter(detail.share_count, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <a
                            class="btn-download"
                            :href="detail.vid_download_url"
                            target="_blank"
                        >
                            Download Video (No WM)
                        </a>
                    </div>
                </div>
            </div>
        </v-col>
    </v-row>
</template>

<script>
export default {
    name: "Detail",
    props: ["detail", "streamUrl"],
    methods: {
        nFormatter(num, digits) {
            var si = [
                { value: 1, symbol: "" },
                { value: 1e3, symbol: "k" },
                { value: 1e6, symbol: "M" },
                { value: 1e9, symbol: "G" },
                { value: 1e12, symbol: "T" },
                { value: 1e15, symbol: "P" },
                { value: 1e18, symbol: "E" }
            ];
            var rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
            var i;
            for (i = si.length - 1; i > 0; i--) {
                if (num >= si[i].value) {
                    break;
                }
            }
            return (
                (num / si[i].value).toFixed(digits).replace(rx, "$1") +
                si[i].symbol
            );
        }
    }
};
</script>

<style scoped>
.video {
    background-color: black;
    height: 370px;
    width: 209px;
    border-radius: 10px;
}
.link {
    color: black;
    text-decoration: none;
}
.link:hover {
    text-decoration: underline;
}
.stats {
    display: flex;
    gap: 14px;
}
.stats .text-sm {
    font-size: 12px;
}
</style>
