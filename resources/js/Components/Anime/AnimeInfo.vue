<template>
  <div
    class="grid xl:grid-cols-4 gap-5 mt-3 px-2 py-4 text-sm font-semibold text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
  >
    <div class="xl:hidden flex justify-between items-center">
      <div class="xl:hidden flex items-center gap-1">
        <span class="">{{ animeStatus }}</span>
      </div>
      <div class="xl:hidden flex items-center gap-1">
        <StarIcon class="size-6" v-if="anime.averageScore" />
        <span v-if="anime.averageScore">{{ anime.averageScore }}%</span>
        <span v-else>TBA</span>
      </div>
      <div
        v-if="anime.nextAiringEpisode"
        class="xl:hidden flex items-center gap-1"
      >
        <span>{{ anime.nextAiringEpisode.episode - 1 }} Episodes</span>
      </div>
      <div v-else class="xl:hidden flex items-center gap-1">
        <span>{{ anime.episodes }} Episodes</span>
      </div>
    </div>

    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Status:</span>
      <span class="">{{ animeStatus }}</span>
    </div>
    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Score:</span>

      <span class="" v-if="anime.averageScore">{{
        formattedScore(anime.averageScore).toFixed(1)
      }}</span>
      <span v-else>TBA</span>
    </div>
    <div v-if="anime.episodes" class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Episodes:</span>
      <span class="">{{ anime.episodes ? anime.episodes : "TBA" }}</span>
    </div>
    <div v-else class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Episodes:</span>
      <span class="">{{ anime.nextAiringEpisode.episode - 1 }}</span>
    </div>

    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Released Date:</span>
      <span class="">{{ releaseYear }} </span>
    </div>
    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Duration:</span>
      <span class="" v-if="anime.duration"> {{ anime.duration }} mins</span>
      <span v-else>TBA</span>
    </div>
    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Format:</span>
      <span class="">{{ anime.format }}</span>
    </div>
    <div
      v-if="anime.studios.nodes[0]"
      class="hidden xl:flex items-center gap-1"
    >
      <span class="uppercase text-xs text-gray-600">Studio:</span>
      <span class="">{{ anime.studios.nodes[0].name }} </span>
    </div>
    <div class="hidden xl:flex items-center gap-1">
      <span class="uppercase text-xs text-gray-600">Season:</span>
      <span class="">{{ anime.season || "TBA" }} </span>
    </div>
  </div>
</template>

<script>
import { StarIcon } from "@heroicons/vue/20/solid";
export default {
  props: {
    data: Object,
  },
  components: {
    StarIcon,
  },
  data() {
    return {};
  },
  methods: {
    formattedScore(score) {
      return (score / 100) * 10;
    },
  },
  computed: {
    anime() {
      return this.data;
    },
    releaseYear() {
      let year = this.anime.startDate.year;
      let month = this.anime.startDate.month;
      let day = this.anime.startDate.day;
      if (year && month && day) {
        return `${this.anime.startDate.month}-${this.anime.startDate.day}-${this.anime.startDate.year}`;
      } else if (!year && !month && !day) {
        return "TBA";
      } else if (!day) {
        day = "?";
      } else {
      }
      return `${month}-${day}-${year}`;
    },
    animeStatus() {
      let status = this.anime.status.toLowerCase();

      if (status === "releasing") return "Ongoing";
      if (status === "finished") return "Completed";
      if (status === "not_yet_released") return "Upcoming";
    },
  },
};
</script>
