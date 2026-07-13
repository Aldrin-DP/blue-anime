<template>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
    <EpisodeCard
      :anime="anime"
      :episodesProgress="episodesProgress"
      :sorted="sorted"
      :currentEpisode="currentEpisode"
      :currentPage="currentPage"
    />
  </div>
</template>

<script>
import EpisodeCard from "./EpisodeCard.vue";

export default {
  components: {
    EpisodeCard,
  },
  props: {
    anime: Object,
    episodesProgress: Array,
    sorted: String,
    currentEpisode: Number,
    currentPage: Number,
  },
};
</script>
