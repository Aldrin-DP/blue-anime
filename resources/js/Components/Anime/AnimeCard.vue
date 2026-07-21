<template>
  <div
    class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 lg:gap-5"
  >
    <div
      v-for="(anime, index) in anime"
      :key="anime.id"
      class="mb-1 cursor-pointer"
      @click="handleClick(anime.id, anime.episode)"
    >
      <div
        class="border-2 border-gray-200 dark:border-gray-700 p-0.75 bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3 relative overflow-hidden"
        @mouseenter="handleMouseEnter(anime.id)"
        @mouseleave="handleMouseLeave(anime.id)"
      >
        <span
          v-if="anime.episode"
          class="absolute py-0.5 inline rounded-md px-2 text-xs sm:text-sm shadow top-2 left-2 font-bold bg-blue-800 text-gray-300"
        >
          EP {{ anime.episode }}
        </span>
        <span
          v-if="anime.format && !anime.episode"
          class="absolute py-0.5 inline rounded-md px-2 text-xs sm:text-sm shadow top-2 right-2 font-bold bg-blue-800 text-gray-300"
        >
          {{ anime.format }}
        </span>
        <img
          :src="anime.coverImage?.extraLarge"
          alt=""
          class="rounded w-full h-full object-cover object-center"
        />
        <div
          v-if="showDetails && currentActiveAnimeId === anime.id"
          class="absolute top-0 left-0 w-full h-full bg-gray-600/60"
        >
          <div class="bg-gray-700/50 p-2 w-full h-full">
            <h3
              class="text-gray-300 font-semibold mb-1 text-sm md:text-base line-clamp-4"
            >
              {{
                anime.title.english ? anime.title.english : anime.title.romaji
              }}
            </h3>
            <span v-if="anime.episode" class="text-gray-300 font-semibold">
              Episode {{ anime.episode }}
            </span>

            <div class="flex items-center gap-2 mt-1">
              <div class="flex items-center">
                <StarIcon class="size-5 text-gray-300"> </StarIcon>
                <span class="text-gray-300 text-sm md:text-base">{{
                  formattedScore(anime.averageScore).toFixed(1)
                }}</span>
              </div>
              <span class="block w-1 h-1 rounded-full bg-gray-400"></span>
              <span class="text-gray-300 text-sm md:text-base">{{
                anime.format
              }}</span>
            </div>

            <div class="flex flex-wrap gap-1 mt-2 mb-2">
              <span
                v-for="(genre, index) in anime.genres"
                :key="index"
                class="px-1 py-0.5 text-sm text-gray-200 border border-gray-500 bg-white/30 rounded"
              >
                {{ genre }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <h3
        class="text-gray-700 dark:text-gray-300 font-semibold line-clamp-2 lg:line-clamp-1 mt-1"
      >
        {{ anime.title.english ? anime.title.english : anime.title.romaji }}
      </h3>
      <p
        v-if="anime.episode"
        class="text-gray-600 dark:text-gray-400 font-semibold text-[13px]"
      >
        Episode {{ anime.episode }}
      </p>
    </div>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import { StarIcon } from "@heroicons/vue/20/solid";

export default {
  props: {
    anime: Array,
  },
  components: {
    StarIcon,
  },
  data() {
    return {
      form: useForm(),
      watchForm: useForm(),
      showDetails: false,
      currentActiveAnimeId: null,
    };
  },
  methods: {
    handleMouseEnter(animeId) {
      this.currentActiveAnimeId = animeId;
      this.showDetails = true;
    },
    handleMouseLeave(animeId) {
      this.currentActiveAnimeId = animeId;
      this.showDetails = false;
    },
    handleClick(anilistId, episode) {
      if (episode) {
        this.watchForm.get(`/anime/${anilistId}/episodes/${episode}`);
        return;
      }
      this.form.get(`/anime/${anilistId}`);
    },
    formattedScore(score) {
      return (score / 100) * 10;
    },
  },
};
</script>
