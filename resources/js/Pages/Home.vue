<template>
    <Head title="Home - " />

    <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
        <!-- Continue Watching -->
        <section v-if="continueAnime.length > 0" class="mb-10">
            <BaseHeading> Continue Watching </BaseHeading>
            <BaseText>
                The deep remembers where you stopped, continue your journey.
            </BaseText>

            <div class="grid grid-cols-2 lg:grid-cols-4 mt-5 gap-5">
                <div v-for="anime in continueAnime">
                    <div class="rounded-md">
                        <div
                            class="aspect-4/2 relative cursor-pointer"
                            @click="
                                continueWatching(anime.api_id, anime.episode)
                            "
                        >
                            <img
                                class="w-full h-full object-cover object-center hover:scale-101 transition-all duration-200 rounded-md"
                                :src="anime.bannerImage"
                                alt=""
                            />

                            <PlayIcon
                                class="size-16 text-gray-300/50 backdrop-filter-md block cursor-pointer absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                            />

                            <div
                                class="absolute overflow-hidden left-[3%] bottom-[7px] w-[94%] h-1.5 bg-gray-700/80 backdrop-blur-md rounded"
                            >
                                <div
                                    :style="{ width: `${anime.progress}%` }"
                                    class="h-full bg-red-400 opacity-70"
                                ></div>
                            </div>
                        </div>
                        <div>
                            <p
                                class="text-gray-700 dark:text-gray-300 font-semibold cursor-pointer truncate mt-1"
                                @click="goToAnime(anime.api_id)"
                            >
                                {{ anime.title }}
                            </p>
                            <p
                                v-if="anime.episode"
                                class="text-gray-600 dark:text-gray-400 font-semibold text-[13px]"
                            >
                                Episode {{ anime.episode }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- New Anime Episodes -->
        <section class="mb-10">
            <BaseHeading> Fresh from Deep </BaseHeading>
            <BaseText> New episodes have surfaced, watch them now. </BaseText>

            <SkeletonCard v-if="!newEpisodes" />
            <AnimeCard class="mt-5" :anime="newEpisodes" />
        </section>
        <!-- Trending Anime -->
        <section class="mb-10">
            <BaseHeading> Making Waves </BaseHeading>
            <BaseText>
                The hottest anime everyone is diving into right now.
            </BaseText>

            <SkeletonCard v-if="!trendingAnime" />
            <AnimeCard class="mt-5" v-else :anime="trendingAnime" />
        </section>
        <!-- Most Popular Anime -->
        <section class="mb-10">
            <BaseHeading> Legends of the Deep </BaseHeading>
            <BaseText>
                The most popular anime that have stood the test of time.
            </BaseText>

            <SkeletonCard v-if="!popularAnime" />
            <AnimeCard class="mt-5" :anime="popularAnime" />
        </section>
        <!-- Top Rated Anime -->
        <section class="mb-8">
            <BaseHeading> Pearls of the Deep </BaseHeading>
            <BaseText>
                The highest rated anime treasured by the deep.
            </BaseText>

            <SkeletonCard v-if="!topRatedAnime" />
            <AnimeCard class="mt-5" :anime="topRatedAnime" />
        </section>
    </div>
</template>

<script>
import AnimeCard from "../Components/Anime/AnimeCard.vue";
import SkeletonCard from "../Components/Skeleton/SkeletonCard.vue";

import { PlayIcon } from "@heroicons/vue/20/solid";

import { useForm } from "@inertiajs/vue3";

export default {
    components: {
        AnimeCard,
        SkeletonCard,
        PlayIcon,
    },
    props: {
        trendingAnime: Array,
        newEpisodes: Array,
        popularAnime: Array,
        topRatedAnime: Array,
        continueAnime: Array,
    },
    data() {
        return {
            form: useForm(),
        };
    },
    mounted() {
        console.log(this.continueAnime);
    },
    methods: {
        continueWatching(api_id, episode) {
            this.form.get(`/anime/${api_id}/episodes/${episode}`);
        },
        goToAnime(animeId) {
            console.log(typeof animeId);
            this.form.get(`/anime/${animeId}`);
        },
    },
};
</script>
