<template>
  <div>
    <Head title="Home - " />

    <div class="p-4 lg:p-10 xl:px-15 xl:py-10">
      <!-- Continue Watching -->
      <section v-if="continueAnime.length > 0" class="mb-8">
        <BaseHeading> Continue Watching </BaseHeading>
        <BaseText>
          The deep remembers where you stopped, continue your journey.
        </BaseText>

        <div class="mt-3 flex items-center gap-2 overflow-x-auto w-full pb-5">
          <div v-for="anime in continueAnime" class="w-72 shrink-0">
            <div class="rounded-md mr-3">
              <div
                class="aspect-4/2 relative"
                @mousemove="showRemoveButton(anime.id)"
              >
                <img
                  class="w-full h-full object-cover object-center transition-all duration-200 rounded-md"
                  :src="
                    anime.bannerImage ? anime.bannerImage : anime.coverImage
                  "
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

      <!-- Anime Banner Carousel -->
      <section class="mb-8">
        <div class="relative">
          <div v-for="(anime, index) in featuredAnime" :key="anime.id">
            <div
              v-if="index === counter"
              :style="{
                backgroundImage: `url(${anime.banner_image ? anime.banner_image : anime.cover_image})`,
              }"
              class="w-full h-65 lg:h-[450px] md:aspect-21/9 sm:aspect-3/1 bg-cover bg-center object-cover object-center rounded-xl flex items-center justify-center lg:items-end"
            >
              <div
                class="w-full h-full pb-9 text-center lg:text-start px-3 lg:p-5 flex flex-col items-center lg:items-start lg:justify-end"
              >
                <h3
                  class="w-fit mt-3 text-lg md:text-xl lg:text-4xl lg:tracking-wider font-bold p-1 lg:p-2 inline-block backdrop-blur-sm rounded bg-black/40 text-gray-300"
                >
                  {{ anime.title ? anime.title : anime.romaji_title }}
                </h3>
                <div class="flex items-center justify-center gap-2 mt-1">
                  <div
                    class="lg:tracking-wider lg:mt-2 font-semibold px-1 py-0.5 text-center w-[55px] h-[25px] backdrop-blur-sm rounded bg-black/40 text-gray-300"
                  >
                    {{ anime.format }}
                  </div>
                  <div
                    class="flex gap-1 items-center lg:mt-2 font-semibold px-1 py-0.5 text-center w-[55px] h-[25px] backdrop-blur-sm rounded bg-black/40 text-gray-300"
                  >
                    <StarIcon class="size-4 text-gray-300"> </StarIcon>
                    <span class="text-gray-300 text-sm md:text-base">{{
                      formattedScore(anime.score).toFixed(1)
                    }}</span>
                  </div>
                  <div
                    class="lg:tracking-wider lg:mt-2 font-semibold px-1 py-0.5 text-center w-[50px] h-[25px] backdrop-blur-sm rounded bg-black/40 text-gray-300"
                  >
                    {{ anime.season_year }}
                  </div>
                </div>
                <div class="flex flex-wrap gap-1 mt-2 mb-2">
                  <span
                    v-for="(genre, index) in anime.genres"
                    :key="index"
                    class="px-1.5 py-0.5 text-sm text-gray-200 font-semibold bg-black/40 rounded-full"
                  >
                    {{ genre }}
                  </span>
                </div>
                <div
                  class="mt-auto text-lg lg:mt-2 w-full"
                  @click="
                    watchNow(anime.api_id, 1, isInWatchHistories(anime.id))
                  "
                >
                  <button
                    v-if="isInWatchHistories(anime.id)"
                    class="flex justify-center items-center gap-2 cursor-pointer w-full md:w-auto px-3 py-1 trancking-wide font-semibold hover:text-gray-900 transition-all duration-300 bg-gray-100 rounded"
                  >
                    <PlayIcon class="size-6 text-gray-800" /> Continue Watching
                  </button>

                  <button
                    v-else
                    class="flex justify-center items-center gap-2 cursor-pointer w-full md:w-auto px-3 py-1 trancking-wide font-semibold hover:text-gray-900 transition-all duration-300 bg-gray-100 rounded"
                  >
                    <PlayIcon class="size-6 text-gray-800" />
                    Watch now
                  </button>
                </div>
              </div>
            </div>
            <div
              class="absolute bottom-1 left-1/2 -translate-x-1/2 flex items-center justify-center gap-1 p-1 rounded lg:backdrop-blur-sm bg-transparent lg:bg-black/10"
            >
              <button :disabled="counter === 0" @click="prevBanner">
                <ChevronLeftIcon
                  :class="counter === 0 ? 'text-gray-600' : ''"
                  class="size-5 text-gray-400 cursor-pointer"
                />
              </button>

              <div
                v-for="(item, i) in featuredAnime.length"
                :class="i === counter ? 'bg-gray-400' : ''"
                class="h-3 w-3 rounded-full border border-gray-300 lg:border-gray-500 cursor-pointer shrink-0"
                @click="showSelectedBanner(i)"
              ></div>
              <button
                :disabled="counter === featuredAnime.length - 1"
                @click="nextBanner"
              >
                <ChevronRightIcon
                  :class="
                    counter === featuredAnime.length - 1 ? 'text-gray-600' : ''
                  "
                  class="size-5 text-gray-400 cursor-pointer"
                />
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- New Anime Episodes -->
      <section class="mb-8">
        <BaseHeading> Fresh from Deep </BaseHeading>
        <BaseText> New episodes have surfaced, watch them now. </BaseText>

        <SkeletonCard v-if="!newEpisodes" />
        <AnimeCard class="mt-3" :anime="newEpisodes" />
      </section>
      <!-- Trending Anime -->
      <section class="mb-8">
        <BaseHeading> Making Waves </BaseHeading>
        <BaseText>
          The hottest anime everyone is diving into right now.
        </BaseText>

        <SkeletonCard v-if="!trendingAnime" />
        <AnimeCard class="mt-3" v-else :anime="trendingAnime" />
      </section>
      <!-- Most Popular Anime -->
      <section class="mb-8">
        <BaseHeading> Legends of the Deep </BaseHeading>
        <BaseText>
          The most popular anime that have stood the test of time.
        </BaseText>

        <SkeletonCard v-if="!popularAnime" />
        <AnimeCard class="mt-3" :anime="popularAnime" />
      </section>
      <!-- Top Rated Anime -->
      <section class="mb-8">
        <BaseHeading> Pearls of the Deep </BaseHeading>
        <BaseText> The highest rated anime treasured by the deep. </BaseText>

        <SkeletonCard v-if="!topRatedAnime" />
        <AnimeCard class="mt-3" :anime="topRatedAnime" />
      </section>
    </div>
  </div>
</template>

<script>
import AnimeCard from "../Components/Anime/AnimeCard.vue";
import SkeletonCard from "../Components/Skeleton/SkeletonCard.vue";

import {
  PlayIcon,
  XMarkIcon,
  StarIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
} from "@heroicons/vue/20/solid";

import { useForm } from "@inertiajs/vue3";

export default {
  components: {
    AnimeCard,
    SkeletonCard,
    PlayIcon,
    XMarkIcon,
    StarIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  props: {
    trendingAnime: Array,
    newEpisodes: Array,
    popularAnime: Array,
    topRatedAnime: Array,
    continueAnime: Array,
    featuredAnime: Array,
    watchHistoryIds: Array,
  },
  data() {
    return {
      counter: 0,
      animeBanners: [],
      counterId: null,
      form: useForm(),
      removeForm: useForm(),
      watchForm: useForm({
        isWatched: false,
      }),
      showConfirmationModal: false,
      removeAnime: {
        id: null,
        title: "",
        episode: 0,
      },
      isRemoveBtnVisible: false,
      timeout: null,
      activeContinueAnimeId: null,
      formattedFeaturedAnime: [],
      seen: [],
    };
  },
  mounted() {
    this.getTrendingAnimeWithBanner();
    console.log(this.newEpisodes);
    console.log(this.watchHistoryIds);

    clearInterval(this.counterId);

    this.counterId = setInterval(() => {
      this.showCarouselBanner();
    }, 8000);
  },
  methods: {
    isInWatchHistories(watchId) {
      return this.watchHistoryIds.includes(watchId);
    },
    watchNow(anilistId, episode, isWatched) {
      console.log(anilistId);
      this.watchForm.isWatched = isWatched;

      this.watchForm.get(`/anime/${anilistId}/episodes/${episode}`);
    },
    nextBanner() {
      this.counter++;
    },
    prevBanner() {
      this.counter--;
    },
    showSelectedBanner(index) {
      this.counter = index;
    },
    formattedScore(score) {
      return (score / 100) * 10;
    },
    showCarouselBanner() {
      this.counter++;
      if (this.counter === this.featuredAnime.length) {
        this.counter = 0;
      }
    },
    getTrendingAnimeWithBanner() {
      this.trendingAnime.filter((anime) => {
        if (anime.bannerImage) {
          this.animeBanners.push(anime);
        }
      });
    },
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
