<template>
  <div>
    <Head title="Home - " />

    <div class="p-4 lg:p-10 xl:px-15 xl:py-10">
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
                class="aspect-4/2 relative"
                @mousemove="showRemoveButton(anime.id)"
              >
                <img
                  class="w-full h-full object-cover object-center transition-all duration-200 rounded-md"
                  :src="anime.bannerImage"
                  alt=""
                />

                <PlayIcon
                  class="size-16 text-gray-300/50 hover:text-gray-100 text-center hover:bg-gray-700/50 rounded-full p-2 transition-all duration-300 backdrop-filter-md block cursor-pointer absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                  @click="continueWatching(anime.api_id, anime.episode)"
                />

                <div
                  class="absolute overflow-hidden left-[3%] bottom-[7px] w-[94%] h-1.5 bg-gray-700/80 backdrop-blur-md rounded"
                >
                  <div
                    :style="{ width: `${anime.progress}%` }"
                    class="h-full bg-red-400 opacity-70"
                  ></div>
                </div>

                <div
                  :class="
                    isRemoveBtnVisible && activeContinueAnimeId === anime.id
                      ? 'opacity-100'
                      : 'opacity-0'
                  "
                  class="absolute top-0 right-0 p-1 bg-gray-700/50 cursor-pointer transition-all duration-300"
                  @click="
                    requestRemoveContinueAnime(
                      anime.id,
                      anime.title,
                      anime.episode,
                    )
                  "
                >
                  <button>
                    <XMarkIcon
                      class="size-5 lg:size-6 text-red-400 dark:text-gray-400 cursor-pointer"
                    />
                  </button>
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

        <div v-if="showConfirmationModal" class="fixed inset-0 z-50">
          <div class="absolute inset-0 bg-black/20 backdrop-blur-none"></div>

          <div class="relative flex h-full items-center justify-center">
            <div
              class="rounded-lg bg-gray-200 dark:bg-gray-800 p-6 shadow-xl w-120"
            >
              <h3
                class="mb-3 font-semibold text-xl lg:text-2xl text-gray-800 dark:text-gray-300"
              >
                Remove from Continue Watching?
              </h3>
              <p class="text-gray-800 dark:text-gray-400">
                <span class="font-medium"
                  >{{ removeAnime.title }} - Episode
                  {{ removeAnime.episode }}</span
                >
                will be removed from your Continue Watching list.
              </p>
              <div class="mt-7 flex justify-between items-center w-full">
                <button
                  class="px-4 py-1.5 border border-gray-400 dark:border-gray-700 text-gray-700 dark:text-gray-400 cursor-pointer tracking-wide font-medium rounded-full"
                  @click="cancelRemoveContinueAnime"
                >
                  Cancel
                </button>
                <BaseButton
                  variant="danger"
                  :disabled="removeForm.processing"
                  :isProcessing="removeForm.processing"
                  @click="confirmRemoveContinueAnime"
                >
                  Remove
                </BaseButton>
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
        <BaseText> The highest rated anime treasured by the deep. </BaseText>

        <SkeletonCard v-if="!topRatedAnime" />
        <AnimeCard class="mt-5" :anime="topRatedAnime" />
      </section>
    </div>
  </div>
</template>

<script>
import AnimeCard from "../Components/Anime/AnimeCard.vue";
import SkeletonCard from "../Components/Skeleton/SkeletonCard.vue";

import { PlayIcon, XMarkIcon } from "@heroicons/vue/20/solid";

import { useForm } from "@inertiajs/vue3";

export default {
  components: {
    AnimeCard,
    SkeletonCard,
    PlayIcon,
    XMarkIcon,
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
      removeForm: useForm(),
      showConfirmationModal: false,
      removeAnime: {
        id: null,
        title: "",
        episode: 0,
      },
      isRemoveBtnVisible: false,
      timeout: null,
      activeContinueAnimeId: null,
    };
  },
  methods: {
    showRemoveButton(id) {
      this.activeContinueAnimeId = id;
      clearTimeout(this.timeout);
      this.isRemoveBtnVisible = true;

      this.timeout = setTimeout(() => {
        this.isRemoveBtnVisible = false;
      }, 3000);
    },
    confirmRemoveContinueAnime() {
      this.removeForm.patch(`/watch-histories/${this.removeAnime.id}`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.$inertia.reload({ only: ["continueAnime"] });
          this.showConfirmationModal = false;
        },
      });
    },
    cancelRemoveContinueAnime() {
      this.showConfirmationModal = false;
    },
    requestRemoveContinueAnime(id, title, episode) {
      this.removeAnime.id = id;
      this.removeAnime.title = title;
      this.removeAnime.episode = episode;
      console.log(
        this.removeAnime.id,
        this.removeAnime.title,
        this.removeAnime.episode,
      );
      this.showConfirmationModal = true;
    },
    toggleKebab(id, episode) {
      console.log(animeId, episode);
    },
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

console.log(this.newEpisodes);
