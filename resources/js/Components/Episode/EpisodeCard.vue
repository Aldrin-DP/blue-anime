<template>
    <div v-for="ep in currentPageEpisodes">
        <div
            @click="watchEpisode(anime.id, ep)"
            :class="
                ep === currentEpisode
                    ? 'border-blue-500 bg-blue-200 dark:bg-sea-800 dark:border-blue-200'
                    : 'border-gray-300 dark:border-gray-700 bg-blue-100 dark:bg-linear-to-br dark:from-teal-950 dark:to-blue-950'
            "
            class="relative cursor-pointer flex items-center gap-3 border p-2 rounded-xl hover:shadow-lg hover:scale-102 transition-all duration-300"
        >
            <span
                class="w-18 h-12 tracking-wider font-bold text-xl bg-blue-300 dark:bg-sea-700 text-blue-600 dark:text-blue-300 rounded-lg flex justify-center items-center"
                >{{ ep }}</span
            >
            <div class="w-full flex flex-col">
                <p class="font-semibold dark:text-gray-300 tracking-wide">
                    Episode {{ ep }}
                </p>
                <div
                    v-if="this.$page.props.auth.user"
                    class="h-1 w-full rounded mt-2 bg-gray-300 dark:bg-gray-500 overflow-hidden"
                >
                    <div
                        v-if="setWatchProgress(ep)"
                        :style="{
                            width: setWatchProgress(ep).progress + '%',
                        }"
                        class="h-1 bg-red-300"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm, router } from "@inertiajs/vue3";
export default {
    props: {
        anime: Object,
        episodesProgress: Array,
    },
    data() {
        return {
            watchForm: useForm(),
            currentPage: 1,
        };
    },
    methods: {
        watchEpisode(animeId, episode) {
            this.watchForm.get(`/anime/${animeId}/episodes/${episode}`);
        },
        setWatchProgress(ep) {
            if (!this.$page.props.auth.user) return;

            return this.episodesProgress.find((p) => p.episode === ep);
        },
    },
    computed: {
        currentPageEpisodes() {
            const start = (this.currentPage - 1) * 20 + 1;
            const end = Math.min(this.currentPage * 20, this.episodes);
            const pages = [];

            if (this.sorted === "desc") {
                const sortStart = this.episodes - (this.currentPage - 1) * 20;
                const sortEnd = Math.max(
                    this.episodes - this.currentPage * 20 + 1,
                    1,
                );
                for (let i = sortStart; i >= sortEnd; i--) {
                    pages.push(i);
                }
            } else {
                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
            }
            return pages;
        },
        episodes() {
            return this.anime.nextAiringEpisode
                ? this.anime.nextAiringEpisode.episode - 1
                : this.anime.episodes;
        },
    },
};
</script>
