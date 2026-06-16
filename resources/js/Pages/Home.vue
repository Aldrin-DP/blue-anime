<template>
    <Head title="Home - " />

    <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
        <!-- New Anime Episodes -->
        <section class="mb-10">
            <BaseHeading> Fresh from Deep </BaseHeading>
            <BaseText>
                New episodes have surfaced, watch them now.
            </BaseText>

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
import { useForm } from "@inertiajs/vue3";

export default {
    components: {
        AnimeCard,
        SkeletonCard,
    },
    props: {
        trendingAnime: Array,
        newEpisodes: Array,
        popularAnime: Array,
        topRatedAnime: Array
    },
    mounted() {
        console.log(this.trendingAnime);
        console.log(this.newEpisodes);
        console.log(this.popularAnime);
        console.log(this.topRatedAnime);
    }
};
</script>
