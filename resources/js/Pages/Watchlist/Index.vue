<template>
  <div>
    <Head title="Watchlists - " />
    <div class="p-2 lg:p-10 xl:px-15 xl:py-10 relative">
      <div class="w-full">
        <div
          class="w-full flex flex-col lg:flex-row lg:justify-between lg:items-center"
        >
          <h2
            class="mb-2 font-semibold tracking-wider text-xl lg:text-2xl text-gray-800 dark:text-gray-300"
          >
            Watchlists
          </h2>
          <!-- filter status -->
          <div
            class="flex justify-betweeen items-center gap-2 text-base tracking-wide font-semibold w-full lg:w-auto overflow-x-auto whitespace-nowrap pb-3"
          >
            <BaseButton
              variant="filter"
              @click="selectActiveTab('all')"
              :class="
                activeTab === 'all'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
            >
              All
              <span class="text-sm">{{
                countAnimeStatus("all") ? countAnimeStatus("all") : ""
              }}</span>
            </BaseButton>
            <BaseButton
              variant="filter"
              :class="
                activeTab === 'watching'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
              @click="selectActiveTab('watching')"
            >
              Watching
              <span class="text-sm">{{
                countAnimeStatus("watching") ? countAnimeStatus("watching") : ""
              }}</span>
            </BaseButton>
            <BaseButton
              variant="filter"
              :class="
                activeTab === 'planning'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
              @click="selectActiveTab('planning')"
            >
              Plan to Watch
              <span class="text-sm">{{
                countAnimeStatus("plan_to_watch")
                  ? countAnimeStatus("plan_to_watch")
                  : ""
              }}</span>
            </BaseButton>
            <BaseButton
              variant="filter"
              :class="
                activeTab === 'completed'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
              @click="selectActiveTab('completed')"
            >
              Completed
              <span class="text-sm">{{
                countAnimeStatus("completed")
                  ? countAnimeStatus("completed")
                  : ""
              }}</span>
            </BaseButton>
            <BaseButton
              variant="filter"
              :class="
                activeTab === 'dropped'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
              @click="selectActiveTab('dropped')"
            >
              Dropped
              <span class="text-sm">{{
                countAnimeStatus("dropped") ? countAnimeStatus("dropped") : ""
              }}</span>
            </BaseButton>
            <BaseButton
              variant="filter"
              :class="
                activeTab === 'favorite'
                  ? 'bg-gradient-to-r from-gray-300/50 to-gray-300  dark:from-sea-800 dark:to-sea-900'
                  : 'bg-gradient-to-r/80 from-gray-100 to-gray-200  dark:from-sea-800/80 dark:to-sea-900/80'
              "
              @click="selectActiveTab('favorite')"
            >
              Favorites
              <span class="text-sm">{{
                countAnimeStatus("favorite") ? countAnimeStatus("favorite") : ""
              }}</span>
            </BaseButton>
          </div>
        </div>
      </div>

      <section class="mt-2 lg:mt-4">
        <div
          class="grid md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-5"
        >
          <div
            class="border rounded border-gray-300 dark:border-gray-700 p-1 sm:p-2 flex gap-3"
            v-for="watchlist in newWatchlistArray[activeTab]"
            :key="watchlist.id"
          >
            <div class="w-19 h-27 md:w-26 md:h-37 lg:w-30 lg:h-42.5">
              <img
                :src="watchlist.coverImage"
                class="w-full h-full object-cover object-center rounded-md"
                alt=""
              />
            </div>
            <div class="flex-1 flex flex-col justify-between">
              <div>
                <h3
                  class="text-sm md:text-[15px] lg:text-base cursor-pointer line-clamp-1 text-gray-800 dark:text-gray-300 font-semibold tracking-wide mb-1 lg:mb-0"
                  @click="goToAnime(watchlist.anilistId)"
                >
                  {{ watchlist.title }}
                </h3>
                <div
                  :class="
                    watchlist.status === 'watching' ? 'hidden md:flex' : 'flex'
                  "
                  class="flex items-center gap-2 mb-1 lg:mb-0"
                >
                  <div class="flex gap-0.5 items-center">
                    <StarIcon
                      class="size-5 lg:size-5 text-gray-800 dark:text-gray-300"
                    >
                    </StarIcon>
                    <span
                      class="text-sm md:text-[15px] lg:text-base text-gray-800 dark:text-gray-300"
                      >{{ formattedScore(watchlist.score).toFixed(1) }}</span
                    >
                  </div>
                  <span
                    class="block w-1 h-1 rounded-full bg-gray-900 dark:bg-gray-400"
                  ></span>
                  <span
                    class="text-sm md:text-[15px] lg:text-base text-gray-800 dark:text-gray-300"
                    >{{ watchlist.format }}</span
                  >
                  <span
                    class="block w-1 h-1 rounded-full bg-gray-900 dark:bg-gray-400"
                  ></span>
                  <span
                    class="text-sm md:text-[15px] lg:text-base text-gray-800 dark:text-gray-300"
                  >
                    {{ watchlist.episodes }} Episodes</span
                  >
                </div>
                <div
                  v-if="
                    watchlist.status === 'watching' &&
                    watchlist.lastWatched !== null
                  "
                  class="block mb-1 lg:mb-0 text-sm md:text-[15px] lg:text-base"
                >
                  <span class="text-gray-800 dark:text-gray-300"
                    >Episode {{ watchlist.lastWatchedEpisode }} of
                    {{ watchlist.episodes }}</span
                  >
                </div>
                <div
                  v-if="
                    watchlist.status === 'watching' &&
                    watchlist.lastWatched !== null
                  "
                  class="hidden lg:flex items-center gap-2"
                >
                  <div class="h-3 lg:h-4.5 bg-gray-400/30 -mt-1 w-1/2">
                    <div
                      :style="{
                        width: watchlist.lastWatched + '%',
                      }"
                      class="bg-gray-400/50 h-full"
                    ></div>
                  </div>
                  <div class="w-1/2 -mt-1 text-gray-800 dark:text-gray-300">
                    {{ Math.floor(watchlist.lastWatched) }}%
                  </div>
                </div>
                <div
                  v-if="
                    watchlist.status !== 'watching' &&
                    watchlist.status !== 'completed'
                  "
                  class="hidden md:flex text-sm lg:text-base mb-1 gap-1 text-gray-800 dark:text-gray-300"
                >
                  <span
                    v-for="(genre, index) in watchlist.genres.slice(0, 2)"
                    :key="index"
                    class="px-1 py-0.5 text-sm text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/30 dark:bg-gray-900/30 rounded"
                  >
                    {{ genre }}
                  </span>
                </div>
                <div
                  v-if="watchlist.status === 'completed'"
                  class="hidden lg:block text-sm lg:text-base text-gray-800 dark:text-gray-300"
                >
                  Completed {{ watchlist.completed_at }}
                </div>
                <div
                  v-if="watchlist.status"
                  :class="{
                    'bg-green-500': watchlist.status === 'watching',
                    'bg-blue-500': watchlist.status === 'plan_to_watch',
                    'bg-emerald-500': watchlist.status === 'completed',
                    'bg-red-500': watchlist.status === 'dropped',
                  }"
                  class="rounded w-26 text-center text-gray-100 p-1 inline-block text-[10px] font-bold uppercase tracking-widest lg:mt-1"
                >
                  {{ status(watchlist.status) }}
                </div>
              </div>

              <div class="mt-auto flex justify-between w-full">
                <button
                  v-if="
                    watchlist.status === 'watching' &&
                    watchlist.lastWatched !== null
                  "
                  class="text-xs md:text-sm rounded-full cursor-pointer font-medium tracking-wide border px-2 py-1 lg:px-2 lg:py-1 text-gray-700 dark:text-gray-400 border-gray-400 dark:border-gray-700"
                  @click="
                    continueWatching(
                      watchlist.anilistId,
                      watchlist.lastWatchedEpisode,
                    )
                  "
                >
                  Continue Watching
                </button>
                <button
                  v-else-if="
                    watchlist.status === 'watching' &&
                    watchlist.lastWatched === null
                  "
                  class="text-xs md:text-sm rounded-full cursor-pointer font-medium tracking-wide border px-2 py-1 lg:px-2 lg:py-1 text-gray-700 dark:text-gray-400 border-gray-400 dark:border-gray-700"
                  @click="watchEpisode(watchlist.anilistId, 1)"
                >
                  Start Watching
                </button>
                <button
                  v-if="watchlist.status === 'plan_to_watch'"
                  class="text-xs md:text-sm rounded-full cursor-pointer font-medium tracking-wide border px-2 py-1 lg:px-2 lg:py-1 text-gray-700 dark:text-gray-400 border-gray-400 dark:border-gray-700"
                  @click="watchEpisode(watchlist.anilistId, 1)"
                >
                  Start Watching
                </button>
                <button
                  v-if="
                    watchlist.status === 'dropped' ||
                    watchlist.status === 'completed'
                  "
                  class="text-xs md:text-sm rounded-full cursor-pointer font-medium tracking-wide border px-2 py-1 lg:px-2 lg:py-1 text-gray-700 dark:text-gray-400 border-gray-400 dark:border-gray-700"
                  @click="goToAnime(watchlist.anilistId)"
                >
                  View Details
                </button>
                <div class="flex gap-1">
                  <button @click="toggleFavorite(watchlist.anilistId)">
                    <HeartIcon
                      :class="watchlist.isFavorite ? 'text-pink-500' : ''"
                      class="size-5 lg:size-6 cursor-pointer text-gray-700 dark:text-gray-400"
                    />
                  </button>
                  <div class="relative">
                    <button @click="toggleKebab(watchlist.anilistId)">
                      <EllipsisVerticalIcon
                        class="size-5 lg:size-6 text-gray-700 dark:text-gray-400 cursor-pointer"
                      />
                    </button>
                    <div
                      v-if="isKebabOpen && id === watchlist.anilistId"
                      class="absolute bottom-[120%] right-[90%] bg-gray-200 dark:bg-gray-800 w-45 rounded-md h-auto p-1 border border-gray-300 dark:border-gray-700"
                    >
                      <div class="">
                        <ul class="w-full rounded-lg">
                          <label
                            class="text-gray-600 dark:text-gray-500 block w-full py-0.5 pl-1 border-b border-gray-300 dark:border-gray-600"
                            >Change status to:</label
                          >
                          <li
                            v-for="item in statuses"
                            :class="
                              watchlist.status === item
                                ? 'bg-gray-300! dark:bg-gray-900!'
                                : ''
                            "
                            :key="item"
                            class="capitalize py-0.5 pl-3 my-0.5 rounded text-gray-700 dark:text-gray-400 font-medium tracking-wide hover:text-gray-800 hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-gray-200 hover:cursor-pointer transition-all duration-300"
                            @click="updateStatus(watchlist.anilistId, item)"
                          >
                            {{ item }}
                          </li>
                        </ul>
                      </div>
                      <div
                        class="border-t border-gray-300 dark:border-gray-600"
                      >
                        <button
                          class="mt-2 mb-1 text-red-400 cursor-pointer tracking-wide font-medium w-full hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-red-500 hover:text-red-500 text-start pl-3 py-0.5 rounded"
                          @click="
                            requestRemoveWatchlist(
                              watchlist.title,
                              watchlist.id,
                            )
                          "
                        >
                          Remove
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="showConfirmationModal" class="fixed inset-0 z-50">
              <div
                class="absolute inset-0 bg-black/20 backdrop-blur-none"
              ></div>

              <div class="relative flex h-full items-center justify-center">
                <div
                  class="rounded-lg bg-gray-200 dark:bg-gray-800 p-6 shadow-xl w-120"
                >
                  <h3
                    class="mb-3 font-semibold text-xl lg:text-2xl text-gray-800 dark:text-gray-300"
                  >
                    Remove from watchlist?
                  </h3>
                  <p class="text-gray-800 dark:text-gray-400">
                    <span class="font-medium">{{ titleToBeRemoved }}</span>
                    will be removed from your watchlist. You can add it back
                    anytime.
                  </p>
                  <div class="mt-7 flex justify-between items-center w-full">
                    <button
                      class="px-4 py-1.5 border border-gray-400 dark:border-gray-700 text-gray-700 dark:text-gray-400 cursor-pointer tracking-wide font-medium rounded-full"
                      @click="cancelRemoveWatchlist()"
                    >
                      Cancel
                    </button>
                    <BaseButton
                      variant="danger"
                      :disabled="deleteForm.processing"
                      :isProcessing="deleteForm.processing"
                      @click="confirmRemoveWatchlist"
                    >
                      Remove
                    </BaseButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="activeTab === 'all' && countAnimeStatus('all') === 0">
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Your watchlist is empty.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Start exploring to build your collection.
            </p>
            <BaseButton
              variant="primary"
              :disabled="isProcessing"
              :isProcessing="isProcessing"
              loadingText="Loading..."
              class="mt-3 flex items-center justify-center w-42"
              @click="goToExplore"
            >
              <div class="flex items-center justify-center gap-2">
                <span> Explore Anime </span>
                <ArrowLongRightIcon class="size-6" />
              </div>
            </BaseButton>
          </div>
          <div
            v-if="
              activeTab === 'watching' && countAnimeStatus('watching') === 0
            "
          >
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              You're not watching anything right now.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Start exploring and start watching.
            </p>
            <BaseButton
              variant="primary"
              :disabled="isProcessing"
              :isProcessing="isProcessing"
              loadingText="Loading..."
              class="mt-3 flex items-center justify-center w-42"
              @click="goToExplore"
            >
              <div class="flex items-center justify-center gap-2">
                <span> Explore Anime </span>
                <ArrowLongRightIcon class="size-6" />
              </div>
            </BaseButton>
          </div>
          <div
            v-if="
              activeTab === 'planning' &&
              countAnimeStatus('plan_to_watch') === 0
            "
          >
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Your plan to watch list is empty.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Add anime you want to watch later.
            </p>
            <BaseButton
              variant="primary"
              :disabled="isProcessing"
              :isProcessing="isProcessing"
              loadingText="Loading..."
              class="mt-3 flex items-center justify-center w-42"
              @click="goToExplore"
            >
              <div class="flex items-center justify-center gap-2">
                <span> Explore Anime </span>
                <ArrowLongRightIcon class="size-6" />
              </div>
            </BaseButton>
          </div>
          <div
            v-if="
              activeTab === 'completed' && countAnimeStatus('completed') === 0
            "
          >
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              No completed anime yet.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Finished series will show up here.
            </p>
            <BaseButton
              variant="primary"
              :disabled="isProcessing"
              :isProcessing="isProcessing"
              loadingText="Loading..."
              class="mt-3 flex items-center justify-center w-42"
              @click="goToExplore"
            >
              <div class="flex items-center justify-center gap-2">
                <span> Explore Anime </span>
                <ArrowLongRightIcon class="size-6" />
              </div>
            </BaseButton>
          </div>
          <div
            v-if="activeTab === 'dropped' && countAnimeStatus('dropped') === 0"
          >
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              No dropped anime.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Anime you drop will appear here.
            </p>
          </div>
          <div
            v-if="
              activeTab === 'favorite' && countAnimeStatus('favorite') === 0
            "
          >
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              No favorites yet.
            </p>
            <p
              class="tracking-wide text-[17px] text-gray-700 dark:text-gray-400"
            >
              Anime you add to favorite will appear here.
            </p>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import {
  EllipsisVerticalIcon,
  HeartIcon,
  StarIcon,
  ArrowLongRightIcon,
} from "@heroicons/vue/24/outline";
import { useForm, router } from "@inertiajs/vue3";

export default {
  props: {
    watchlists: Array,
  },
  components: {
    EllipsisVerticalIcon,
    HeartIcon,
    StarIcon,
    ArrowLongRightIcon,
  },
  data() {
    return {
      form: useForm(),
      deleteForm: useForm(),
      watchForm: useForm(),
      favoriteForm: useForm(),
      updateForm: useForm({
        status: "",
      }),
      activeTab: "all",
      isProcessing: false,
      isKebabOpen: false,
      id: 0,
      lastSavedId: 0,
      statuses: ["watching", "planning", "completed", "dropped"],
      showConfirmationModal: false,
      titleToBeRemoved: "",
      idToBeRemoved: null,
    };
  },
  methods: {
    confirmRemoveWatchlist() {
      console.log(this.idToBeRemoved);
      this.deleteForm.delete(`/watchlists/${this.idToBeRemoved}`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.$inertia.reload({
            only: ["watchlists"],
          });
          this.showConfirmationModal = false;
        },
      });
    },
    requestRemoveWatchlist(title, id) {
      this.titleToBeRemoved = title;
      this.idToBeRemoved = id;
      this.showConfirmationModal = true;
    },
    cancelRemoveWatchlist() {
      this.showConfirmationModal = false;
    },
    updateStatus(anilistId, status) {
      console.log(anilistId, status);
      if (status === "planning") {
        status = "plan_to_watch";
      }
      this.updateForm.status = status;
      this.updateForm.patch(`/watchlists/${anilistId}`, {
        onSuccess: this.$inertia.reload({
          only: ["watchlists"],
        }),
        onFinish: () => {
          this.isKebabOpen = false;
        },
      });
    },
    toggleKebab(anilistId) {
      if (anilistId !== this.lastSavedId) {
        this.isKebabOpen = false;
      }
      this.id = anilistId;
      this.lastSavedId = anilistId;
      this.isKebabOpen = !this.isKebabOpen;
    },
    goToExplore() {
      router.visit("/explore", {
        onStart: () => {
          this.isProcessing = true;
        },
        onFinish: () => {
          this.isProcessing = false;
        },
      });
    },
    toggleFavorite(anilistId) {
      this.favoriteForm.patch(`/watchlists/${anilistId}/favorite`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: this.$inertia.reload({
          only: ["watchlists"],
        }),
      });
    },
    countAnimeStatus(status) {
      if (status === "all") {
        return this.watchlists.length;
      }
      if (status === "favorite") {
        return this.watchlists.filter(
          (watchlist) => watchlist.isFavorite === true,
        ).length;
      }

      let count = this.watchlists.filter(
        (watchlist) => watchlist.status === status,
      );

      return count.length;
    },
    selectActiveTab(status) {
      this.activeTab = status;
    },
    watchEpisode(anilistId, episode) {
      this.watchForm.get(`/anime/${anilistId}/episodes/${episode}`);
    },
    continueWatching(api_id, episode) {
      this.form.get(`/anime/${api_id}/episodes/${episode}`);
    },
    status(status) {
      if (status === "plan_to_watch") {
        status = "Plan to Watch";
      }

      return status;
    },
    formattedScore(score) {
      return (score / 100) * 10;
    },
    goToAnime(anilistId) {
      this.form.get(`/anime/${anilistId}`);
    },
  },
  computed: {
    newWatchlistArray() {
      return {
        all: this.watchlists,
        watching: this.watchlists.filter(
          (watchlist) => watchlist.status === "watching",
        ),
        planning: this.watchlists.filter(
          (watchlist) => watchlist.status === "plan_to_watch",
        ),
        completed: this.watchlists.filter(
          (watchlist) => watchlist.status === "completed",
        ),
        dropped: this.watchlists.filter(
          (watchlist) => watchlist.status === "dropped",
        ),
        favorite: this.watchlists.filter(
          (watchlist) => watchlist.isFavorite === true,
        ),
      };
    },
  },
};
</script>
