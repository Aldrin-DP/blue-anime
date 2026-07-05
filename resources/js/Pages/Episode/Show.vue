<template>
    <div>
        <Head></Head>
        <div class="p-0 m-0 px-5 lg:p-10 xl:px-15 xl:py-5">
            <div
                class="mt-5 lg:mt-0 mb-5 flex items-center justify-start flex-wrap gap-2"
            >
                <p
                    @click="goToAnime(anime.id)"
                    class="text-base lg:text-[17px] text-gray-600 dark:text-gray-400 tracking-wide truncate cursor-pointer hover:text-blue-500 transition-all duration-300"
                >
                    {{ animeTitle }}
                </p>
                <ChevronRightIcon
                    class="size-5 text-gray-600 dark:text-gray-400"
                />
                <p
                    v-if="currentEpisode"
                    class="tracking-wide text-gray-900 dark:text-gray-200 text-base lg:text-[17px]"
                >
                    Episode {{ currentEpisode }}
                </p>
            </div>
            <div class="flex flex-col lg:flex-row gap-3 xl:gap-5">
                <div class="w-full xl:w-8/12 rounded-xl">
                    <div class="w-full">
                        <!-- video container -->
                        <div
                            ref="videoWrapper"
                            class="w-full relative overflow-hidden rounded-xl shadow-2xl"
                            @mousemove="handleMouseMove"
                            @keydown="handleKeyDown($event)"
                        >
                            <video
                                ref="video"
                                crossorigin="anonymous"
                                class="w-full roundex-xl"
                                @click="togglePlayback"
                                @timeupdate="handleTimeUpdate"
                                @loadedmetadata="handleLoadedMetaData"
                                @canplay="handleCanPlay"
                                @progress="handleProgress"
                            >
                                <track
                                    :src="episodeData.subtitleEn"
                                    kind="subtitles"
                                    srclang="en"
                                    label="English"
                                    default
                                />
                            </video>
                            <div v-show="controlsVisible">
                                <div
                                    class="flex items-center gap-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                >
                                    <button
                                        @click="skipBackward10"
                                        class="cursor-pointer rounded-full p-1.5 text-gray-200 backdrop-blur-md bg-gray-950/20"
                                    >
                                        <span
                                            class="material-symbols-outlined text-5xl!"
                                            >replay_10</span
                                        >
                                    </button>
                                    <button
                                        @click="togglePlayback"
                                        class="cursor-pointer rounded-full p-3 text-gray-200 backdrop-blur-md bg-gray-950/20"
                                    >
                                        <span v-if="isPlaying">
                                            <PauseIcon class="size-16" />
                                        </span>
                                        <span v-else>
                                            <PlayIcon class="size-16" />
                                        </span>
                                    </button>
                                    <button
                                        @click="skipForward10"
                                        class="cursor-pointer rounded-full p-1.5 text-gray-200 backdrop-blur-md bg-gray-950/20"
                                    >
                                        <span
                                            class="material-symbols-outlined text-5xl!"
                                            >forward_10</span
                                        >
                                    </button>
                                </div>
                            </div>
                            <div
                                v-if="isLoading"
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                            >
                                <svg
                                    class="mr-1 -ml-1 size-15 animate-spin text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <button
                                    v-if="isIntroVisible"
                                    @click="skipIntro"
                                    class="absolute bottom-20 right-5 px-3 py-1.5 border border-gray-600 backdrop-blur-md cursor-pointer rounded bg-gray-950/70 text-gray-300 font-semibold tracking-wide"
                                >
                                    Skip Intro
                                </button>
                            </div>
                            <div
                                v-show="controlsVisible"
                                class="w-full absolute bottom-0 left-0 pt-1 px-3 backdrop-blur-md bg-gray-950/80 text-gray-300"
                            >
                                <!-- progress bar -->
                                <div
                                    @click="handleSeek($event)"
                                    class="w-full h-1.5 cursor-pointer bg-gray-700 rounded my-2 relative"
                                >
                                    <div
                                        :style="{
                                            width: bufferedPercent + '%',
                                        }"
                                        class="h-full bg-gray-500 transition-all duration-300 absolute"
                                    ></div>

                                    <div
                                        :style="{ width: progressBar + '%' }"
                                        class="h-full bg-red-500 transition-all duration-300 absolute"
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
                                                @click="skipBackward10"
                                                class="border-gray-300 rounded flex cursor-pointer text-4xl"
                                            >
                                                <span
                                                    class="material-symbols-outlined text-2xl"
                                                    >replay_10</span
                                                >
                                            </button>
                                            <button
                                                @click="skipForward10"
                                                class="border-gray-300 rounded flex cursor-pointer"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                    >forward_10</span
                                                >
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
                                                @input="updateVolume($event)"
                                                type="range"
                                                min="0"
                                                max="1"
                                                step="0.01"
                                                class="w-16"
                                                v-model="volume"
                                            />
                                        </div>

                                        <button
                                            @click="toggleFullscreen($event)"
                                            class="cursor-pointer"
                                        >
                                            <span v-if="isFullscreen">
                                                <ArrowsPointingInIcon
                                                    class="size-6"
                                                />
                                            </span>
                                            <span v-else>
                                                <ArrowsPointingOutIcon
                                                    class="size-6"
                                                />
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full xl:w-4/12">
                    <div
                        class="flex border border-gray-300 dark:border-gray-700 justify-center gap-2 px-5 py-3 bg-linear-to-tr from-white/20 to-white/30 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
                    >
                        <div class="flex justify-center items-center">
                            <button
                                @click="prevEpisode"
                                :disabled="this.currentEpisode === 1"
                                :class="
                                    this.currentEpisode <= 1
                                        ? 'text-gray-500'
                                        : 'text-gray-800 dark:text-gray-300 cursor-not-allowed'
                                "
                                class="flex font-semibold cursor-pointer px-2 lg:px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700"
                            >
                                <ChevronLeftIcon class="size-6" />
                                <span> Prev </span>
                            </button>
                        </div>
                        <button
                            @click="scrollTo"
                            class="flex font-semibold px-2 lg:px-4 py-2 text-gray-800 dark:text-gray-300 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-700"
                        >
                            All Episodes
                        </button>
                        <div class="flex justify-center items-center">
                            <button
                                @click="nextEpisode"
                                :disabled="this.currentEpisode >= this.episodes"
                                :class="
                                    this.currentEpisode >= this.episodes
                                        ? 'text-gray-500'
                                        : 'text-gray-800 dark:text-gray-300 cursor-not-allowed'
                                "
                                class="flex font-semibold px-2 lg:px-4 py-2 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-700"
                            >
                                <span> Next </span>
                                <ChevronRightIcon class="size-6" />
                            </button>
                        </div>
                    </div>
                    <div
                        class="mt-3 p-5 border border-gray-300 dark:border-gray-700 bg-linear-to-br from-white/20 to-white/30 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
                    >
                        <div>
                            <div class="flex gap-2">
                                <div
                                    class="w-37.5 border-2 border-gray-200 dark:border-gray-700 p-0.75 bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3"
                                >
                                    <img
                                        :src="anime.coverImage.extraLarge"
                                        alt=""
                                        class="rounded w-full h-full object-cover object-center"
                                    />
                                </div>
                                <div class="w-1/2">
                                    <p
                                        @click="goToAnime(anime.id)"
                                        class="font-semibold tracking-wide text-gray-800 dark:text-gray-300 cursor-pointer"
                                    >
                                        {{ animeTitle }}
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2 mb-2">
                                        <span
                                            v-for="(
                                                genre, index
                                            ) in anime.genres"
                                            :key="index"
                                            class="px-1 py-0.5 text-sm text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
                                        >
                                            {{ genre }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 mt-4">
                                        <div class="flex items-center">
                                            <StarIcon
                                                class="size-5 text-gray-800 dark:text-gray-300"
                                            >
                                            </StarIcon>
                                            <span
                                                class="text-gray-800 dark:text-gray-300"
                                                >{{ anime.averageScore }}%</span
                                            >
                                        </div>
                                        <span
                                            class="block w-1 h-1 rounded-full bg-gray-900 dark:bg-gray-400"
                                        ></span>
                                        <span
                                            class="text-gray-800 dark:text-gray-300"
                                            >{{ anime.format }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                class="line-clamp-4 text-gray-800 dark:text-gray-300 mt-2"
                            >
                                {{ cleanedDescription }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mt-3 text-xl font-semibold tracking-wider text-gray-800 dark:text-gray-300"
            >
                <span class="">{{ animeTitle }}</span>
                -
                <span v-if="currentEpisode">Episode {{ currentEpisode }}</span>
            </div>

            <section ref="episodes" class="mt-5 w-full scroll-mt-20">
                <div
                    class="w-full p-5 rounded-xl border border-gray-300 dark:border-gray-700"
                >
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
                                        ? 'border-blue-500 bg-blue-200 dark:bg-blue-500 dark:border-blue-200'
                                        : 'border-gray-300 dark:border-gray-700 bg-blue-100 dark:bg-linear-to-br dark:from-teal-950 dark:to-blue-950'
                                "
                                class="cursor-pointer flex items-center gap-3 border p-2 rounded-xl hover:shadow-lg hover:scale-102 transition-all duration-300"
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
    StarIcon,
} from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";

export default {
    props: {
        episodeData: Object,
        anime: Object,
        currentEpisode: Number,
        resumeTime: Number,
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
        StarIcon,
    },
    data() {
        return {
            form: useForm(),
            watchHistoryForm: useForm({
                currentTime: 0,
                isCompleted: false,
                title: "",
                format: "",
                coverImage: "",
            }),
            currentPage: 1,
            sorted: "asc",
            isPlaying: true,
            isMuted: false,
            isFullscreen: false,
            currentTime: 0,
            bufferedPercent: 0,
            duration: 0,
            progress: 0,
            volume: 0.5,
            savedVolume: 0,
            isIntroVisible: false,
            controlsVisible: false,
            hideTimeout: null,
            isLoading: true,
            lastSavedTime: 0,
            intervalId: null,
        };
    },

    mounted() {
        this.initPlayer();
        this.displayCurrentEpisode();
        this.initProgressAutoSave();
    },
    beforeUnmount() {
        this.saveProgress();

        clearInterval(this.intervalId);
    },
    methods: {
        handleMouseMove() {
            this.resetControlsTimer();
        },
        handleProgress() {
            this.updateBuffered();
        },
        handleLoadedMetaData() {
            this.setVideoDuration();
            this.resumeCurrentTime();
            this.setupSubtitlePosition();
        },
        handleTimeUpdate() {
            this.syncCurrentTime();
            this.updateIntroVisibility();
        },
        handleCanPlay() {
            this.setIsLoadingToFalse();
        },
        handleKeyDown(event) {
            event.preventDefault();
            this.handleMouseMove();

            if (event.code === "Space") {
                this.togglePlayback();
            }
            if (event.code === "KeyF") {
                this.toggleFullscreen();
            }
            if (event.code === "KeyM") {
                this.toggleMute();
            }
            if (event.code === "ArrowLeft") {
                this.skipBackward10();
            }
            if (event.code === "ArrowRight") {
                this.skipForward10();
            }
        },

        async toggleFullscreen() {
            const videoWrapper = this.$refs.videoWrapper;

            console.log(document.fullscreenElement);

            if (document.fullscreenElement !== null) {
                await document.exitFullscreen();
                this.isFullscreen = false;
            } else {
                await videoWrapper.requestFullscreen();
                this.isFullscreen = true;
            }
        },

        playVideo() {
            const video = this.getVideoEl();
            if (!video) return;

            video.play();
        },
        pauseVideo() {
            const video = this.getVideoEl();
            if (!video) return;

            video.pause();
        },
        seek(time) {
            const t = Number(time);
            if (!Number.isFinite(t) || t < 0) return;

            this.getVideoEl().currentTime = t;
        },
        skipForward10() {
            const video = this.getVideoEl();

            video.currentTime = video.currentTime + 10;
        },
        skipBackward10() {
            const video = this.getVideoEl();
            video.currentTime = video.currentTime - 10;
        },
        skipIntro() {
            this.seek(this.episodeData.intro.end);
        },
        updateVolume(event) {
            this.isMuted = false;
            this.volume = event.target.value;

            this.getVideoEl().volume = this.volume;
        },
        toggleMute() {
            this.isMuted = !this.isMuted;

            const video = this.getVideoEl();

            if (this.isMuted) {
                video.volume = 0;
                this.savedVolume = this.volume;
                this.volume = 0;
            } else {
                this.volume = this.savedVolume;
                video.volume = this.volume;
            }
        },
        handleSeek(event) {
            const rect = event.currentTarget.getBoundingClientRect();
            const clientX = event.clientX - rect.left;

            const width = rect.width;
            const percent = clientX / width;

            const time = percent * this.getVideoEl().duration;

            this.seek(time);
        },
        togglePlayback() {
            if (this.isPlaying) {
                this.pauseVideo();
            } else {
                this.playVideo();
            }
            this.isPlaying = !this.isPlaying;
        },
        prevEpisode() {
            const episode = this.currentlyPlayingEpisode - 1;

            this.form.get(`/anime/${this.anime.id}/episodes/${episode}`);
        },
        nextEpisode() {
            const episode = this.currentlyPlayingEpisode + 1;

            this.form.get(`/anime/${this.anime.id}/episodes/${episode}`);
        },
        scrollTo() {
            const episodesEl = this.$refs.episodes;

            episodesEl.scrollIntoView({
                behavior: "smooth",
            });
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

        initPlayer() {
            const video = this.getVideoEl();
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

            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                video.play();
            });
        },
        initProgressAutoSave() {
            if (this.intervalId) clearInterval(this.intervalId);

            this.intervalId = setInterval(() => {
                this.saveProgress();
            }, 30000);
        },
        syncCurrentTime() {
            this.currentTime = getVideoEl().currentTime;
        },
        saveProgress() {
            if (!this.$page.props.auth.user) return;

            const video = this.getVideoEl();
            if (!video) return;

            const currentTime = video.currentTime;

            if (
                this.lastSavedTime !== null &&
                Math.abs(currentTime - this.lastSavedTime) < 5
            )
                return;

            this.lastSavedTime = currentTime;

            const isCompleted = currentTime >= video.duration * 0.9;

            this.watchHistoryForm.currentTime = currentTime;
            this.watchHistoryForm.isCompleted = isCompleted;

            this.watchHistoryForm.title = this.anime.title.english
                ? this.anime.title.english
                : this.anime.title.romaji;
            this.watchHistoryForm.format = this.anime.format;
            this.watchHistoryForm.coverImage = this.anime.coverImage.extraLarge;

            this.watchHistoryForm.post(
                `/watch-histories/${this.anime.id}/${this.currentlyPlayingEpisode}`,
                {
                    preserveState: true,
                    preserveScroll: true,
                },
            );
        },
        resetControlsTimer() {
            if (this.isLoading) return;

            this.controlsVisible = true;

            clearTimeout(this.hideTimeout);

            this.hideTimeout = setTimeout(() => {
                this.controlsVisible = false;
            }, 2500);
        },
        updateBuffered() {
            const video = this.getVideoEl();

            if (!video) return;

            if (video.buffered.length > 0) {
                const bufferedEnd = video.buffered.end(
                    video.buffered.length - 1,
                );
                this.bufferedPercent = (bufferedEnd / video.duration) * 100;
            }
        },
        resumeCurrentTime() {
            if (!this.resumeTime) return;

            this.seek(this.resumeTime);
        },
        updateIntroVisibility() {
            const episodeIntro = this.episodeData.intro;

            this.isIntroVisible =
                this.currentTime >= episodeIntro.start &&
                this.currentTime <= episodeIntro.end;
        },
        setupSubtitlePosition() {
            console.log("Subtitle");
            const track = this.getVideoEl().textTracks[0];
            console.log(track);
            if (track) {
                for (let cue of track.cues) {
                    cue.line = -3;
                    cue.snapToLines = true;
                }
            }
        },
        displayCurrentEpisode() {
            const page = Math.ceil(this.currentlyPlayingEpisode / 20);

            this.currentPage = Math.ceil(page);
        },
        getVideoEl() {
            return this.$refs.video;
        },
        setIsLoadingToFalse() {
            this.isLoading = false;
        },
        setVideoDuration() {
            this.duration = this.getVideoEl().duration;
        },
    },

    computed: {
        animeTitle() {
            return this.anime.title.english
                ? this.anime.title.english
                : this.anime.title.romaji;
        },
        cleanedDescription() {
            const description = this.anime.description;
            const cleaned = description.replace(/<[^>]*>/g, "\n");

            return cleaned;
        },
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
            let minute = Math.floor(this.currentTime / 60);
            let second = this.currentTime % 60;

            if (second < 10) {
                second = "0" + second;
            }

            return `${minute}:${second} - ${this.formattedDuration}`;
        },
        progressBar() {
            return (this.currentTime / this.duration) * 100;
        },
        currentlyPlayingEpisode() {
            return this.currentEpisode;
        },
    },
};
</script>
