<template>
  <div v-for="ep in currentPageEpisodes">
    <div
      :class="
        ep === currentEpisode
          ? 'border-blue-500 bg-blue-200 dark:bg-sea-800 dark:border-blue-200'
          : 'border-gray-300 dark:border-gray-700 bg-blue-100 dark:bg-linear-to-br dark:from-teal-950 dark:to-blue-950'
      "
      class="relative flex items-center gap-3 border p-2 rounded-xl transition-all duration-300"
    >
      <span
        class="w-18 h-12 cursor-pointer tracking-wider font-bold text-xl bg-blue-300 dark:bg-sea-700 text-blue-600 dark:text-blue-300 rounded-lg flex justify-center items-center"
        @click="watchEpisode(anime.id, ep)"
        >{{ ep }}</span
      >
      <div class="w-full flex flex-col">
        <div class="flex justify-between items-center">
          <p
            class="font-semibold dark:text-gray-300 tracking-wide cursor-pointer"
            @click="watchEpisode(anime.id, ep)"
          >
            Episode {{ ep }}
          </p>
          <div
            v-if="this.$page.props.auth.user"
            class="relative"
            @mouseenter="handleMouseEnter(ep)"
            @mouseleave="handleMouseLeave"
          >
            <button
              v-if="setCompleted(ep)?.isCompleted"
              :class="{
                'text-sea-700! border-green-500! dark:text-green-500! dark:border-green-500!':
                  setCompleted(ep)?.isCompleted,
              }"
              class="cursor-pointer border-gray-300 dark:border-gray-700 text-[15px] rounded tracking-wide text-gray-800 dark:text-gray-400 hover:text-blue-400 hover:border-sea-300 transition-all duration-500"
              @click="updateWatchStatus(anime.id, ep, false)"
            >
              <CheckCircleIcon class="size-6" />
            </button>

            <button
              v-else
              class="cursor-pointer border-gray-300 dark:border-gray-700 text-[15px] rounded tracking-wide text-gray-800 dark:text-gray-400 hover:text-green-400 hover:border-sea-300 transition-all duration-500"
              @click="updateWatchStatus(anime.id, ep, true)"
            >
              <EyeIcon class="size-6" />
            </button>
            <div
              v-if="ep === currentlyHoveredEpisode"
              class="text-center font-medium bg-gray-200/40 text-gray-800 rounded text-[13px] rounded-br-lg px-1.5 py-1 absolute -top-7 right-4 w-33 backdrop-blur-md"
            >
              {{
                setCompleted(ep)?.isCompleted
                  ? "Mark as Unwatched"
                  : "Mark as Watched"
              }}
            </div>
          </div>
        </div>

        <div
          v-if="this.$page.props.auth.user"
          class="h-1 w-full rounded mt-4 bg-gray-300 dark:bg-gray-500 overflow-hidden"
        >
          <div
            v-if="setWatchProgress(ep)"
            :style="{
              width: setWatchProgress(ep).progress + '%',
            }"
            class="h-1 bg-red-300"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { CheckCircleIcon, EyeIcon } from "@heroicons/vue/24/outline";
import { useForm, router } from "@inertiajs/vue3";
export default {
  props: {
    anime: Object,
    episodesProgress: Array,
    currentEpisode: Number,
    sorted: String,
    currentPage: Number,
  },
  components: {
    CheckCircleIcon,
    EyeIcon,
  },
  data() {
    return {
      watchForm: useForm(),
      updateWatchStatusForm: useForm({
        currentTime: 0,
        duration: 0,
        isCompleted: false,
      }),
      isCompleted: false,
      currentlyHoveredEpisode: null,
    };
  },
  methods: {
    handleMouseEnter(ep) {
      this.currentlyHoveredEpisode = ep;
    },
    handleMouseLeave() {
      this.currentlyHoveredEpisode = null;
    },
    updateWatchStatus(anilistId, episode, isCompleted) {
      const currentEpisode = this.setWatchProgress(episode);
      console.log(currentEpisode);

      let episodeCurrentTime = currentEpisode?.currentTime;
      let episodeDuration = currentEpisode?.duration;

      if (episodeCurrentTime === undefined) {
        episodeCurrentTime = 0;
      }

      if (episodeDuration === undefined) {
        episodeDuration = 0;
      }

      this.updateWatchStatusForm.currentTime = episodeCurrentTime;
      this.updateWatchStatusForm.duration = episodeDuration;
      this.updateWatchStatusForm.isCompleted = isCompleted;

      this.updateWatchStatusForm.patch(
        `/watch-histories/${anilistId}/${episode}`,
        {
          preserveScroll: true,
          preserveState: true,
        },
      );
    },
    watchEpisode(animeId, episode) {
      this.watchForm.get(`/anime/${animeId}/episodes/${episode}`);
    },
    setWatchProgress(ep) {
      if (!this.$page.props.auth.user) return;

      return this.episodesProgress.find((p) => p.episode === ep);
    },
    setCompleted(ep) {
      if (!this.$page.props.auth.user) return;

      return this.episodesProgress.find((p) => p.episode === ep) ?? "";
    },
  },
  computed: {
    currentPageEpisodes() {
      const start = (this.currentPage - 1) * 20 + 1;
      const end = Math.min(this.currentPage * 20, this.episodes);
      const pages = [];

      if (this.sorted === "desc") {
        const sortStart = this.episodes - (this.currentPage - 1) * 20;
        const sortEnd = Math.max(this.episodes - this.currentPage * 20 + 1, 1);
        for (let i = sortStart; i >= sortEnd; i--) {
          pages.push(i);
        }
      } else {
        for (let i = start; i <= end; i++) {
          pages.push(i);
        }
      }
      return pages;
    },
    episodes() {
      return this.anime.nextAiringEpisode
        ? this.anime.nextAiringEpisode.episode - 1
        : this.anime.episodes;
    },
  },
};
</script>
