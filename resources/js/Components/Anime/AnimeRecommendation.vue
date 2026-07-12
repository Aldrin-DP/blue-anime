<template>
  <div>
    <section class="backdrop-blur-md mt-3 p-3">
      <h2
        class="font-bold text-lg tracking-wide text-gray-700 dark:text-gray-400 mb-3"
      >
        From the Same Depths
      </h2>
      <div
        class="grid gap-3 lg:gap-5 grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6"
      >
        <div
          class="cursor-pointer"
          v-for="anime in anime.recommendations.nodes"
        >
          <div
            class="border-2 border-gray-200 dark:border-gray-700 p-0.75 bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3 relative"
            @click="showAnime(anime.mediaRecommendation.id)"
          >
            <span
              v-if="anime.mediaRecommendation.format"
              class="absolute py-0.5 inline rounded-md px-2 text-xs sm:text-sm shadow top-2 right-2 font-bold bg-blue-800 text-gray-300"
            >
              {{ anime.mediaRecommendation.format }}
            </span>
            <img
              :src="anime.mediaRecommendation.coverImage.extraLarge"
              alt=""
              class="rounded w-full h-full object-cover object-center"
            />
          </div>
          <p
            class="text-gray-700 dark:text-gray-300 font-semibold truncate mt-1"
          >
            {{
              anime.mediaRecommendation.title.english
                ? anime.mediaRecommendation.title.english
                : anime.mediaRecommendation.title.romaji
            }}
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
export default {
  props: {
    anime: Object,
  },
  data() {
    return {
      form: useForm(),
    };
  },
  methods: {
    showAnime(animeId) {
      this.form.get(`/anime/${animeId}`);
    },
  },
};
</script>
