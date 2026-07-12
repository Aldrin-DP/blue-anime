<template>
  <div>
    <Head :title="`${anime.title.english} -  `" />
    <div class="p-0 m-0 lg:p-10 xl:px-15 xl:py-10 relative">
      <section class="relative bg-cover bg-center lg:flex">
        <img
          :src="anime.coverImage.extraLarge"
          class="absolute inset-0 w-full h-full object-cover lg:hidden"
          alt=""
        />
        <div class="hidden lg:block p-3 lg:p-0 w-4/12 xl:w-3/12">
          <div
            class="border-2 border-gray-200 dark:border-gray-700 p-0.75 bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3"
          >
            <img
              :src="anime.coverImage.extraLarge"
              alt=""
              class="rounded w-full h-full object-cover object-center"
            />
          </div>
          <div
            v-if="anime.nextAiringEpisode"
            class="flex justify-between mt-1 px-2 py-4 mx-0.5 text-sm text-sea-800 dark:text-blue-300 border border-sea-300 dark:border-sea-700 bg-sea-100 dark:bg-sea-800 rounded"
          >
            <span class="font-semibold tracking-wider">Next episode:</span>
            <span class="font-bold">{{ airingAt }}</span>
          </div>
        </div>
        <div
          class="w-full lg:w-8/12 xl:w-9/12 relative z-10 p-3 bg-blue-50/80 dark:bg-gray-900/80 xl:bg-transparent xl:dark:bg-transparent"
        >
          <div>
            <h2
              class="font-extrabold text-xl lg:text-2xl mt-10 lg:mt-0 text-gray-900 dark:text-gray-100"
            >
              {{
                anime.title.english ? anime.title.english : anime.title.romaji
              }}
            </h2>
            <div class="flex gap-2 mt-2">
              <span
                v-for="(genre, index) in genres"
                :key="index"
                class="px-2 py-1 text-sm text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
              >
                {{ genre }}
              </span>
            </div>

            <AnimeInfo :data="anime" />

            <div
              v-if="anime.nextAiringEpisode"
              class="xl:hidden flex justify-between mt-3 px-2 py-4 text-sm text-sea-800 dark:text-blue-300 border border-sea-300 dark:border-sea-700 bg-sea-100 dark:bg-sea-800 rounded"
            >
              <span class="font-semibold tracking-wider">Next episode:</span>
              <span class="font-bold">{{ airingAt }}</span>
            </div>

            <div class="mt-5 flex gap-3 items-center">
              <BaseButton
                v-if="!inWatchlist"
                :isProcessing="form.processing"
                variant="primary"
                class="flex justify-center w-48"
                @click="addToWatchlist"
              >
                <div class="flex items-center justify-center">
                  <PlusIcon class="size-6" />
                  <span> Add to Watchlist </span>
                </div>
              </BaseButton>

              <div v-else class="relative">
                <button
                  class="font-semibold text-gray-300 rounded-lg dark:text-gray-300 bg-gradient-to-b from-sea-700 to-sea-800 w-48 px-2 py-2 flex justify-between items-center hover:cursor-pointer"
                  @click="toggleSelection"
                >
                  <span class="capitalize"> {{ status }}</span>
                  <ChevronRightIcon v-if="!isOpen" class="size-6" />
                  <ChevronDownIcon v-else class="size-6" />
                </button>

                <ul
                  v-if="isOpen"
                  class="absolute w-48 rounded-lg py-1 px-2 top-[45px] bg-gradient-to-b from-sea-700 to-sea-800"
                >
                  <li
                    v-for="item in statuses"
                    :key="item"
                    class="capitalize py-1.5 px-2 text-center bg-gradient-to-b from-sea-700/40 to-sea-800/40 my-1.5 rounded text-gray-300 font-semibold tracking-wide hover:cursor-pointer hover:text-gray-100 hover:from-sea-700/80 hover:to-sea-800/80 transition-all duration-300"
                    @click="updateStatus(anime.id, item)"
                  >
                    {{ item }}
                  </li>
                </ul>
              </div>

              <BaseButton variant="primary" @click="toggleFavorite(anime.id)">
                <HeartIcon
                  :class="isFavorited ? 'text-pink-500' : ''"
                  class="size-6"
                />
              </BaseButton>
            </div>

            <div
              class="mt-5 px-2 py-4 text-sm text-gray-800 dark:text-gray-300 border-gray-400 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
            >
              <span class="tracking-wider text-xs lg:text-base font-semibold"
                >SYNOPSIS</span
              >
              <div class="mt-3 lg:text-[15px]">
                {{ truncatedDescription }}
              </div>
              <span
                v-if="isDescriptionOver40"
                @click="toggleTruncatedDescription"
                class="cursor-pointer block font-bold mt-2 lg:text-[15px]"
              >
                {{ isTruncated ? "Read More" : "See Less" }}
              </span>
            </div>
          </div>
        </div>
      </section>

      <EpisodeSection :anime="anime" :episodesProgress="episodesProgress" />

      <AnimeRecommendation :anime="anime" />
    </div>
  </div>
</template>

<script>
import {
  StarIcon,
  HeartIcon,
  PlusIcon,
  ArrowsUpDownIcon,
  CheckIcon,
  ChevronRightIcon,
  ChevronDownIcon,
} from "@heroicons/vue/20/solid";
import { useForm, router } from "@inertiajs/vue3";
import BaseButton from "../../Components/Base/BaseButton.vue";
import AnimeInfo from "../../Components/Anime/AnimeInfo.vue";
import EpisodeSection from "../../Components/Episode/EpisodeSection.vue";
import AnimeRecommendation from "../../Components/Anime/AnimeRecommendation.vue";

export default {
  components: {
    EpisodeSection,
    AnimeRecommendation,
    BaseButton,
    AnimeInfo,
    StarIcon,
    HeartIcon,
    PlusIcon,
    ArrowsUpDownIcon,
    CheckIcon,
    ChevronRightIcon,
    ChevronDownIcon,
  },
  props: {
    anime: Object,
    inWatchlist: Boolean,
    episodesProgress: Array,
    status: String,
    isFavorited: Boolean,
  },
  data() {
    return {
      form: useForm({
        anilistId: "",
      }),
      updateForm: useForm({
        status: "",
      }),
      watchForm: useForm(),
      favoriteForm: useForm(),
      now: Math.floor(Date.now() / 1000),
      isTruncated: true,
      isDescriptionOver40: true,

      selectedLabel: "",
      statuses: ["watching", "plan to watch", "completed", "dropped"],
      isOpen: false,
    };
  },
  mounted() {
    console.log(this.status);
    setInterval(() => {
      this.now = Math.floor(Date.now() / 1000);
    }, 1000);
  },
  methods: {
    toggleFavorite(anilistId) {
      this.favoriteForm.patch(`/watchlists/${anilistId}/favorite`, {
        preserveState: true,
        preserveScroll: true,
      });
    },
    updateStatus(anilistId, status) {
      this.isOpen = false;
      if (this.status === status) {
        return;
      }
      if (this.status === "plan_to_watch") {
        this.status.replace("_", " ");
      }
      if (status === "plan to watch") {
        status = "plan_to_watch";
      }

      this.updateForm.status = status;
      this.updateForm.patch(`/watchlists/${anilistId}`);
    },
    toggleSelection() {
      this.isOpen = !this.isOpen;
    },
    addToWatchlist() {
      if (!this.$page.props.auth.user) {
        router.visit(
          `/login?redirect=${encodeURIComponent(window.location.pathname)}`,
        );
        return;
      }

      this.form.anilistId = this.anime.id;

      this.form.post("/watchlists", {
        preserveScroll: true,
        preserveState: true,
      });
    },
  },
  computed: {
    status() {
      if (this.status === "plan_to_watch") {
        return "Plan to Watch";
      }
      return this.status;
    },
    genres() {
      const genres = this.anime.genres;
      return genres.slice(0, 5);
    },
    airingAt() {
      const airingAt = this.anime.nextAiringEpisode.airingAt;
      const secondsUntilAiring = airingAt - this.now;

      const days = Math.floor(secondsUntilAiring / 86400);
      const hours = Math.floor((secondsUntilAiring % 86400) / 3600);
      const mins = Math.floor((secondsUntilAiring % 3600) / 60);
      const secs = Math.floor(secondsUntilAiring % 60);

      return `${days}d ${hours}h ${mins}m ${secs}s`;
    },
    truncatedDescription() {
      const description = this.anime.description;
      const cleaned = description.replace(/<[^>]*>/g, "\n");

      if (this.isTruncated) {
        const newDescription = cleaned.trim().split(/\s+/);
        let newWord = "";

        if (newDescription.length > 40) {
          for (let i = 0; i <= 30; i++) {
            newWord = newWord + " " + newDescription[i];
          }
          newWord = newWord + "...";

          return newWord;
        }
        this.isDescriptionOver40 = false;
        return cleaned;
      } else {
        return cleaned;
      }
    },
    toggleTruncatedDescription() {
      this.isTruncated = !this.isTruncated;
      this.truncatedDescription();
    },
    episodes() {
      return this.anime.nextAiringEpisode
        ? this.anime.nextAiringEpisode.episode - 1
        : this.anime.episodes;
    },
  },
};
</script>
