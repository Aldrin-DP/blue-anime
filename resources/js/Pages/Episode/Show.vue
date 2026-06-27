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
            <Link class="text-blue-500 tracking-wide text-[17px]"
                >Episode 2</Link
            >
        </div>
        <div class="flex flex-col lg:flex-row gap-3 lg:gap-10">
            <div class="w-full rounded-xl">
                <div class="w-full lg:w-200">
                    <div class="w-full overflow-hidden rounded-xl shadow-2xl">
                        <video
                            controls
                            ref="video"
                            class="w-full roundex-xl"
                        ></video>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <div
                    class="flex justify-center gap-2 p-3 bg-linear-to-tr from-white/50 to-white/60 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
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
                    class="mt-3 p-3 bg-linear-to-tr from-white/50 to-white/60 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 rounded-xl"
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
                                class="mt-2 font-semibold tracking-wide text-gray-800 dark:text-gray-300"
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

        <div class="mt-5 text-xl font-semibold tracking-wider">
            Episode 1 - Death Note
        </div>

        <section
            class="mt-10 w-full bg-linear-to-tr from-white/30 to-white/40 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 shadow-lg dark:shadow p-5 rounded-lg"
        >
            <div>
                <div>
                    <BaseHeading class="text-lg">
                        Death Note - Episode 1</BaseHeading
                    >
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Hls from "hls.js";
import { ChevronRightIcon, ChevronLeftIcon } from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
export default {
    props: {
        episodeData: Object,
        anime: Object,
    },
    components: {
        ChevronRightIcon,
        ChevronLeftIcon,
    },
    data() {
        return {
            form: useForm(),
        };
    },
    mounted() {
        this.playEpisode();
        console.log(this.anime);
    },
    methods: {
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
    },
};
</script>
