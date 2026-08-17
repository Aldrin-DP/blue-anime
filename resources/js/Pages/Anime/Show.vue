<template>
  <div>
    <Head
      :title="`${anime.title.english ? anime.title.english : anime.title.romaji} -  `"
    />
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

            <div class="mt-5 flex gap-3 items-center flex-wrap sm:no-wrap">
              <BaseButton
                :isProcessing="watchForm.processing"
                :variant="
                  anime.status === 'NOT_YET_RELEASED' ? 'disabled' : 'primary'
                "
                loadingText="Loading..."
                class="flex justify-center w-full md:w-52"
                :disabled="anime.status === 'NOT_YET_RELEASED'"
                @click="handleWatchAction"
              >
                <div class="flex items-center justify-center gap-1">
                  <PlayIcon
                    v-if="anime.status !== 'NOT_YET_RELEASED'"
                    class="size-6"
                  />
                  <ClockIcon
                    v-if="anime.status === 'NOT_YET_RELEASED'"
                    class="size-5"
                  />
                  <span
                    v-if="anime.status === 'NOT_YET_RELEASED'"
                    class="text-base"
                  >
                    Coming Soon
                  </span>
                  <span v-else-if="status === 'watching'" class="text-base">
                    Continue Watching
                  </span>
                  <span v-else-if="status === 'completed'" class="text-base">
                    Watch Again
                  </span>
                  <span v-else-if="status === 'dropped'" class="text-base">
                    Resume Watching
                  </span>
                  <span v-else class="text-base">Watch Now</span>
                </div>
              </BaseButton>
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
                  <span class="capitalize"> {{ formattedStatus }}</span>
                  <ChevronRightIcon v-if="!isOpen" class="size-6" />
                  <ChevronDownIcon v-else class="size-6" />
                </button>

                <ul
                  v-if="isOpen"
                  class="absolute w-48 rounded-lg py-1 px-2 top-[45px] bg-gradient-to-b from-sea-700 to-sea-800"
                >
                  <li
                    v-for="status in watchStatusOptions"
                    :key="status.value"
                    :class="
                      anime.status === 'NOT_YET_RELEASED' &&
                      (status.value === 'watching' ||
                        status.value === 'dropped')
                        ? 'bg-gray-500! hover:bg-gray-600!'
                        : 'hover:from-sea-700/80 hover:to-sea-800/80'
                    "
                    class="capitalize py-1.5 px-2 text-center bg-gradient-to-b from-sea-700/40 to-sea-800/40 my-1.5 rounded text-gray-300 font-semibold tracking-wide hover:cursor-pointer hover:text-gray-100 transition-all duration-300"
                    @click="updateStatus(anime.id, status.value, status.label)"
                  >
                    {{ status.label }}
                  </li>
                </ul>
              </div>

              <BaseButton
                variant="primary"
                :disabled="favoriteForm.processing"
                @click="toggleFavorite(anime.id)"
              >
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
              <div class="mt-3 lg:text-[16px]">
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

      <div v-if="anime.status !== 'NOT_YET_RELEASED'" class="px-4 md:px-0">
        <EpisodeSection :anime="anime" :episodesProgress="episodesProgress" />
      </div>

      <div v-if="recommendations.length > 0">
        <AnimeRecommendation :recommendations="recommendations" />
      </div>
    </div>
  </div>
</template>

<script>
import {
  HeartIcon,
  PlusIcon,
  ArrowsUpDownIcon,
  CheckIcon,
  ChevronRightIcon,
  ChevronDownIcon,
  PlayIcon,
  ClockIcon,
} from "@heroicons/vue/20/solid";
import { useForm, router } from "@inertiajs/vue3";
import BaseButton from "../../Components/Base/BaseButton.vue";
import AnimeInfo from "../../Components/Anime/AnimeInfo.vue";
import EpisodeSection from "../../Components/Episode/EpisodeSection.vue";
import AnimeRecommendation from "../../Components/Anime/AnimeRecommendation.vue";
import { useToast } from "vue-toastification";

export default {
  components: {
    EpisodeSection,
    AnimeRecommendation,
    BaseButton,
    AnimeInfo,
    HeartIcon,
    PlusIcon,
    ArrowsUpDownIcon,
    CheckIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    PlayIcon,
    ClockIcon,
  },
  props: {
    anime: Object,
    inWatchlist: Boolean,
    episodesProgress: Array,
    status: String,
    isFavorited: Boolean,
    continueLastWatchedEpisode: Number,
  },
  data() {
    return {
      form: useForm({
        anilistId: "",
      }),
      watchForm: useForm(),
      updateForm: useForm({
        status: "",
      }),
      watchForm: useForm(),
      favoriteForm: useForm(),
      now: Math.floor(Date.now() / 1000),
      isTruncated: true,
      isDescriptionOver40: true,

      selectedLabel: "",
      watchStatusOptions: [
        { label: "Plan to Watch", value: "plan_to_watch" },
        { label: "Watching", value: "watching" },
        { label: "Completed", value: "completed" },
        { label: "Dropped", value: "dropped" },
      ],
      isOpen: false,
    };
  },
  mounted() {
    console.log(this.anime);
    console.log(this.continueLastWatchedEpisode);
    setInterval(() => {
      this.now = Math.floor(Date.now() / 1000);
    }, 1000);
  },
  created() {
    this.toast = useToast();
  },
  methods: {
    handleWatchAction() {
      let episode = this.continueLastWatchedEpisode;
      if (this.status === "plan_to_watch" || !this.continueLastWatchedEpisode) {
        episode = 1;
      }
      const animeId = this.anime.id;
      this.watchForm.get(`/anime/${animeId}/episodes/${episode}`, {
        onSuccess: () => {
          const message = this.$page.props.flash.episodeError;

          if (message) {
            this.toast.warning(message);
          }
        },
      });
    },
    toggleFavorite(anilistId) {
      if (!this.$page.props.auth.user) {
        router.visit(
          `/login?redirect=${encodeURIComponent(window.location.pathname)}`,
        );
        return;
      }
      this.favoriteForm.patch(`/watchlists/${anilistId}/favorite`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          if (this.isFavorited) {
            this.toast.success("Added to favorites.");
          } else {
            this.toast.success("Removed from favorites.");
          }
        },
      });
    },
    updateStatus(anilistId, status, statusLabel) {
      this.isOpen = false;
      if (this.status === status) {
        return;
      }
      if (
        this.anime.status === "NOT_YET_RELEASED" &&
        (status === "watching" || status === "dropped")
      ) {
        return;
      }

      this.updateForm.status = status;
      this.updateForm.patch(`/watchlists/${anilistId}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.toast.success(`Status changed to ${statusLabel}`);
        },
      });
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
        onSuccess: () => {
          this.toast.success(`Added to Watchlists`);
        },
      });
    },
  },
  computed: {
    formattedStatus() {
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
    recommendations() {
      const nodes = this.anime.recommendations?.nodes ?? [];

      return nodes.map((node) => node.mediaRecommendation).filter(Boolean);
    },
  },
};
</script>
