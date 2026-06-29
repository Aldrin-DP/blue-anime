<template>
    <Head></Head>

    <div class="p-0 m-0 px-5 lg:p-10 xl:px-15 xl:py-5">
        <div
            class="mt-5 lg:mt-0 mb-5 flex items-center justify-start flex-wrap gap-2"
        >
            <BaseText
                @click="goToAnime(anime.id)"
                class="truncate cursor-pointer"
            >
                {{
                    anime.title.english
                        ? anime.title.english
                        : anime.title.romaji
                }}
            </BaseText>
            <ChevronRightIcon class="size-5 text-gray-600 dark:text-gray-400" />
            <Link
                v-if="currentEpisode"
                class="text-blue-500 tracking-wide text-[17px]"
                >Episode {{ currentEpisode }}</Link
            >
        </div>
        <div class="flex flex-col lg:flex-row gap-3 xl:gap-5">
            <div class="w-full xl:w-8/12 rounded-xl">
                <div class="w-full">
                    <div class="w-full overflow-hidden rounded-xl shadow-2xl">
                        <video
                            @timeupdate="updateCurrentTime"
                            @loadedmetadata="onLoadedMetadata"
                            ref="video"
                            class="w-full roundex-xl"
                            crossorigin="anonymous"
                        >
                            <track
                                :src="episodeData.subtitleEn"
                                kind="subtitles"
                                srclang="en"
                                label="English"
                                default
                            />
                        </video>
                        <div
                            class="w-full pt-1 px-3 backdrop-blur-sm bg-gray-950/90 text-gray-300"
                        >
                            <!-- progress bar -->
                            <div
                                @click="testing($event)"
                                class="w-full h-1.5 cursor-pointer bg-gray-400 rounded my-2"
                            >
                                <div
                                    :style="{ width: progressBar + '%' }"
                                    class="h-full bg-red-500 transition-all duration-300"
                                ></div>
                            </div>
                            <div
                                class="w-full flex items-center justify-between pt-1 pb-2"
                            >
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="togglePlayback"
                                        class="cursor-pointer"
                                    >
                                        <span v-if="isPlaying">
                                            <PauseIcon class="size-6" />
                                        </span>
                                        <span v-else>
                                            <PlayIcon class="size-6" />
                                        </span>
                                    </button>
                                    <div class="">
                                        {{ formatCurrentTime }}
                                        <!-- skip intro -->
                                    </div>
                                    <div class="flex items-center">
                                        <button
                                            @click="seek(-10)"
                                            class="border-gray-300 rounded flex cursor-pointer"
                                        >
                                            <span>10</span>
                                            <ChevronDoubleLeftIcon
                                                class="size-6"
                                            />
                                        </button>
                                        <button
                                            @click="seek(10)"
                                            class="border-gray-300 rounded flex cursor-pointer"
                                        >
                                            <ChevronDoubleRightIcon
                                                class="size-6"
                                            />
                                            <span>10</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <!-- subtitle -->
                                    <!-- volume -->
                                    <div class="flex">
                                        <button @click="toggleMute">
                                            <SpeakerXMarkIcon
                                                v-if="isMuted"
                                                class="size-6"
                                            />
                                            <SpeakerWaveIcon
                                                v-else
                                                class="size-6"
                                            />
                                        </button>

                                        <input
                                            @input="volumeTest($event)"
                                            type="range"
                                            min="0"
                                            max="1"
                                            step="0.01"
                                            class="w-16"
                                            v-model="volume"
                                        />
                                    </div>

                                    <!-- <button @click="toggleFullscreen" class="px-3.5">
                                    <span v-if="isFullscreen">
                                        <ArrowsPointingInIcon class="size-6" />
                                    </span>
                                    <span>
                                        <ArrowsPointingOutIcon class="size-6" />
                                    </span>
                                </button> -->
                                    <!-- full screen -->
                                    <button
                                        @click="toggleFullscreen"
                                        class="cursor-pointer"
                                    >
                                        <span v-if="isFullscreen">
                                            <ArrowsPointingInIcon
                                                class="size-6"
                                            />
                                        </span>
                                        <span>
                                            <ArrowsPointingOutIcon
                                                class="size-6"
                                            />
                                        </span>
                                    </button>
                                    <!-- Play, Pause, forward arrow(skip 10s), ChevronDoubleRightIcon arrow(back 10s), volume, full screen -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-4/12">
                <div
                    class="flex border border-gray-300 justify-center gap-2 px-5 py-3 bg-linear-to-tr from-white/20 to-white/30 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
                >
                    <div class="flex justify-center items-center">
                        <button
                            class="flex font-semibold cursor-pointer px-2 lg:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300"
                        >
                            <ChevronLeftIcon class="size-6" />
                            <span> Prev </span>
                        </button>
                    </div>
                    <select
                        class="font-semibold text-gray-700 cursor-pointer dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none border text-center rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
                    >
                        <option value="" selected>Episode 1</option>
                        <option value="RELEASING">Ongoing</option>
                        <option value="FINISHED">Completed</option>
                        <option value="CANCELLED">Cancelled</option>
                        <option value="NOT_YET_RELEASED">Episode 2312</option>
                    </select>
                    <div class="flex justify-center items-center">
                        <button
                            class="flex font-semibold px-2 lg:px-4 py-2 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300"
                        >
                            <span> Next </span>
                            <ChevronRightIcon class="size-6" />
                        </button>
                    </div>
                </div>
                <div
                    class="mt-3 p-5 border border-gray-300 bg-linear-to-br from-white/20 to-white/30 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
                >
                    <div class="flex gap-2">
                        <div
                            class="w-1/2 border-2 border-gray-200 dark:border-gray-700 p-0.75 bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3"
                        >
                            <img
                                :src="anime.coverImage.extraLarge"
                                alt=""
                                class="rounded w-full h-full object-cover object-center"
                            />
                        </div>
                        <div class="w-1/2">
                            <p
                                class="font-semibold tracking-wide text-gray-800 dark:text-gray-300"
                            >
                                {{
                                    anime.title.english
                                        ? anime.title.english
                                        : anime.title.romaji
                                }}
                            </p>
                            <div class="flex flex-wrap gap-2 mt-2 mb-2">
                                <span
                                    v-for="(genre, index) in anime.genres.slice(
                                        0,
                                        2,
                                    )"
                                    :key="index"
                                    class="px-1 py-0.5 text-sm text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
                                >
                                    {{ genre }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="uppercase text-xs text-gray-600 w-12"
                                    >Status:</span
                                >
                                <span
                                    class="text-gray-800 dark:text-gray-300"
                                    >{{ anime.status }}</span
                                >
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="uppercase text-xs text-gray-600 w-12"
                                    >Score:</span
                                >
                                <span class="text-gray-800 dark:text-gray-300"
                                    >{{ anime.averageScore }}%</span
                                >
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="uppercase text-xs text-gray-600 w-12"
                                    >Format:</span
                                >
                                <span
                                    class="text-gray-800 dark:text-gray-300"
                                    >{{ anime.format }}</span
                                >
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="uppercase text-xs text-gray-600 w-12"
                                    >Studio:</span
                                >
                                <span class="text-gray-800 dark:text-gray-300"
                                    >{{ anime.studios.nodes[0].name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="mt-5 text-xl font-semibold tracking-wider text-gray-800 dark:text-gray-300"
        >
            <span class="">{{ anime.title.english }}</span>
            -
            <span v-if="currentEpisode">Episode {{ currentEpisode }}</span>
        </div>

        <section class="mt-5 w-full">
            <div class="w-full p-5 rounded-xl border border-gray-300">
                <BaseHeading> Episodes </BaseHeading>
                <div class="flex justify-between items-center my-4">
                    <div class="flex items-center gap-1">
                        <button
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                        >
                            <ChevronLeftIcon
                                class="size-5 text-gray-800 dark:text-gray-300"
                                :class="{
                                    'text-slate-400 dark:text-slate-700':
                                        currentPage === 1,
                                }"
                            />
                        </button>
                        <div
                            v-for="(page, index) in paginationPages"
                            :key="index"
                            class="flex gap-1 w-8 h-8 border rounded border-gray-300 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-300"
                        >
                            <button
                                @click="goToPage(page)"
                                :class="{
                                    'bg-blue-600 rounded text-gray-300':
                                        page === currentPage,
                                }"
                                class="w-full"
                            >
                                {{ page }}
                            </button>
                        </div>
                        <button
                            @click="nextPage"
                            :disabled="currentPage * 20 >= episodes"
                            class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                        >
                            <ChevronRightIcon
                                class="size-5 text-gray-800 dark:text-gray-300"
                                :class="{
                                    'text-slate-400':
                                        currentPage * 20 >= episodes,
                                }"
                            />
                        </button>
                    </div>

                    <button
                        @click="toggleSort"
                        class="p-1 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 cursor-pointer"
                    >
                        <BarsArrowUpIcon
                            v-if="sorted === 'asc'"
                            class="size-6"
                        />
                        <BarsArrowDownIcon v-else class="size-6" />
                    </button>
                </div>
                <div
                    class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
                >
                    <div v-for="ep in currentPageEpisodes">
                        <div
                            @click="watchEpisode(animeData.id, ep)"
                            :class="
                                ep === currentEpisode
                                    ? 'border-blue-400 dark:border-blue-700'
                                    : 'border-gray-300 dark:border-gray-700'
                            "
                            class="cursor-pointer flex items-center gap-3 bg-blue-100 dark:bg-linear-to-br dark:from-teal-950 dark:to-blue-950 border p-2 rounded-xl hover:shadow-lg hover:scale-102 transition-all duration-300"
                        >
                            <span
                                class="w-15 h-12 tracking-wider font-bold text-xl bg-blue-300 dark:bg-blue-700 text-blue-600 dark:text-blue-300 rounded-lg flex justify-center items-center"
                                >{{ ep }}</span
                            >
                            <p
                                class="font-semibold text-gray-700 dark:text-gray-300 tracking-wide"
                            >
                                Episode {{ ep }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Hls from "hls.js";
import {
    ChevronRightIcon,
    ChevronLeftIcon,
    BarsArrowUpIcon,
    BarsArrowDownIcon,
    PlayIcon,
    PauseIcon,
    ChevronDoubleRightIcon,
    ChevronDoubleLeftIcon,
    SpeakerWaveIcon,
    SpeakerXMarkIcon,
    ArrowsPointingOutIcon,
    ArrowsPointingInIcon,
} from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";

export default {
    props: {
        episodeData: Object,
        anime: Object,
        currentEpisode: Number,
    },
    components: {
        ChevronRightIcon,
        ChevronLeftIcon,
        BarsArrowUpIcon,
        BarsArrowDownIcon,
        PlayIcon,
        PauseIcon,
        ChevronDoubleRightIcon,
        ChevronDoubleLeftIcon,
        SpeakerWaveIcon,
        SpeakerXMarkIcon,
        ArrowsPointingOutIcon,
        ArrowsPointingInIcon,
    },
    data() {
        return {
            form: useForm(),
            currentPage: 1,
            sorted: "asc",
            isPlaying: true,
            isMuted: false,
            isFullscreen: false,
            currentTime: 0,
            duration: 0,
            progress: 0,
            volume: 0.8,
        };
    },
    mounted() {
        this.playEpisode();
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
        paginationPages() {
            const totalPages = Math.ceil(this.episodes / 20);
            const pagesPerGroup = 5;
            const currentPage = this.currentPage;

            const currentGroup = Math.ceil(currentPage / pagesPerGroup);

            const startPage = (currentGroup - 1) * pagesPerGroup + 1;
            const endPage = Math.min(currentGroup * pagesPerGroup, totalPages);

            const paginateNumbers = [];

            for (let i = startPage; i <= endPage; i++) {
                paginateNumbers.push(i);
            }
            return paginateNumbers;
        },
        episodes() {
            return this.anime.nextAiringEpisode
                ? this.anime.nextAiringEpisode.episode - 1
                : this.anime.episodes;
        },
        formattedDuration() {
            const minute = Math.floor(this.duration / 60);
            const second = Math.floor(this.duration % 60);

            return `${minute}:${second}`;
        },
        formatCurrentTime() {
            const minute = Math.floor(this.currentTime / 60);
            const second = this.currentTime % 60;

            return `${minute} : ${second} - ${this.formattedDuration}`;
        },
        progressBar() {
            return (this.currentTime / this.duration) * 100;
        },
    },
    methods: {
        volumeTest(event) {
            this.isMuted = false;

            if (this.$refs.video.volume == 0) {
                this.isMuted = true;
            }

            const video = this.$refs.video;
            this.volume = event.target.value;
            video.volume = this.volume;
        },
        toggleMute() {
            this.isMuted = !this.isMuted;

            if (this.isMuted) {
                this.$refs.video.volume = 0;
            } else {
                this.$refs.video.volume = this.volume;
            }
        },
        testing(event) {
            const rect = event.currentTarget.getBoundingClientRect();
            const clientX = event.clientX - rect.left;

            const width = rect.width;
            const percent = clientX / width;

            const time = percent * this.$refs.video.duration;

            this.$refs.video.currentTime = time;
        },
        updateCurrentTime() {
            this.currentTime = Math.floor(this.$refs.video.currentTime);
        },
        onLoadedMetadata() {
            this.duration = this.$refs.video.duration;
        },
        seek(seconds) {
            this.$refs.video.currentTime =
                this.$refs.video.currentTime + seconds;
        },
        togglePlayback() {
            if (this.isPlaying) {
                this.$refs.video.pause();
                this.isPlaying = !this.isPlaying;
            } else {
                this.$refs.video.play();
                this.isPlaying = !this.isPlaying;
            }
        },
        playEpisode() {
            const video = this.$refs.video;
            const proxyBase = "https://anidb-proxy.seaanime.workers.dev/?";

            const defaultLoader = Hls.DefaultConfig.loader;
            const referer = this.episodeData.refererUrl;

            class ProxyLoader extends defaultLoader {
                load(context, config, callbacks) {
                    if (!context.url.includes("anidb-proxy")) {
                        context.url =
                            proxyBase +
                            "url=" +
                            encodeURIComponent(context.url) +
                            "&ref=" +
                            encodeURIComponent(referer);
                    }
                    super.load(context, config, callbacks);
                }
            }

            const hls = new Hls({ enableWorker: false, loader: ProxyLoader });
            hls.loadSource(this.episodeData.episodeUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());
        },
        goToAnime(animeId) {
            this.form.get(`/anime/${animeId}`);
        },
        nextPage() {
            this.currentPage++;
        },
        prevPage() {
            this.currentPage--;
        },
        goToPage(page) {
            this.currentPage = page;
        },
        toggleSort() {
            this.currentPage = 1;
            if (this.sorted === "asc") {
                this.sorted = "desc";
            } else if (this.sorted === "desc") {
                this.sorted = "asc";
            }
        },
    },
};
</script>
