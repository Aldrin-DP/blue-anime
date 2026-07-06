<template>
    <section class="backdrop-blur-md mt-3 p-3">
        <BaseHeading> Episodes </BaseHeading>
        <BaseText> {{ episodes }} episodes available </BaseText>

        <EpisodePagination
            :anime="anime"
            :episodesProgress="episodesProgress"
        />

        <EpisodeList :anime="anime" :episodesProgress="episodesProgress" />
    </section>
</template>

<script>
import EpisodePagination from "./EpisodePagination.vue";
import EpisodeList from "./EpisodeList.vue";

export default {
    components: {
        EpisodePagination,
        EpisodeList,
    },
    props: {
        anime: Object,
        episodesProgress: Array,
        currentEpisode: Number,
    },
    data() {
        return {
            currentPage: 1,
            sorted: "asc",
        };
    },
    computed: {
        episodes() {
            return this.anime.nextAiringEpisode
                ? this.anime.nextAiringEpisode.episode - 1
                : this.anime.episodes;
        },
    },
};
</script>
