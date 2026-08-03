<template>
  <div>
    <Head title="Explore - " />
    <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
      <h1
        class="text-3xl xl:text-4xl font-extrabold tracking-wider text-gray-800 dark:text-gray-300"
      >
        Explore the Deep
      </h1>
      <BaseText> Explore and search through thousands of anime. </BaseText>

      <div
        tabindex="0"
        class="flex lg:justify-between items-center mt-4 mb-3 gap-2 border rounded-2xl border-gray-300 dark:border-gray-700 focus-within:outline-2 focus-within:outline-blue-700"
      >
        <input
          v-model="searchForm.search"
          @keydown.enter="searchAnime"
          type="text"
          class="w-full text-lg pl-5 py-3 outline:none focus:border-none focus:outline-0 text-gray-800 dark:text-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-500"
          placeholder="Explore"
        />
        <MagnifyingGlassIcon
          @click="searchAnime"
          class="size-8 text-gray-700 dark:text-gray-300 mr-5 cursor-pointer"
        />
      </div>

      <button
        @click="showFilters"
        class="flex w-36 border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 font-bold tracking-wide gap-1 items-center border px-3 py-2 rounded-lg cursor-pointer"
      >
        <AdjustmentsHorizontalIcon class="size-6" />
        {{ isFilterOpen ? "Hide Filter" : "Show Filter" }}
      </button>

      <div
        v-if="isFilterOpen"
        class="mt-4 border border-gray-300 dark:border-gray-700 shadow rounded-xl p-5 bg-gray-100/70 dark:bg-gray-800/50 backdrop-blur-md"
      >
        <button
          @click="clearFilter"
          class="flex mb-4 border-gray-300 font-medium dark:border-gray-700 text-gray-800 dark:text-gray-300 tracking-wide gap-1 items-center border px-3 py-2 rounded-lg cursor-pointer"
        >
          Clear Filter
        </button>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Status</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.status"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">All Status</option>
                <option
                  v-for="status in statusOptions"
                  :key="status.value"
                  :value="status.value"
                >
                  {{ status.label }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Format</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.format"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">All Format</option>
                <option v-for="format in formats" :key="format" :value="format">
                  {{ format }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Season</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.season"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">All Season</option>
                <option v-for="season in seasons" :key="season" :value="season">
                  {{ season }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Year</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.year"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">All Year</option>
                <option v-for="year in years" :key="year" :value="year">
                  {{ year }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Country</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.country"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">All Countries</option>
                <option
                  v-for="country in countryOrigins"
                  :key="country.value"
                  :value="country.value"
                >
                  {{ country.label }}
                </option>
              </select>
            </div>
          </div>

          <div class="mb-2">
            <label
              class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
              >Sort</label
            >
            <div class="relative">
              <span class="absolute block top-3 right-3">
                <ChevronDownIcon
                  class="size-4 dark:text-gray-400 text-gray-600"
                />
              </span>
              <select
                v-model="searchForm.sort"
                class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
              >
                <option value="">Default</option>
                <option
                  v-for="sort in sorts"
                  :key="sort.value"
                  :value="sort.value"
                >
                  {{ sort.label }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="my-2">
          <button
            :class="'block lg:hidden'"
            class="border mb-2 px-2 py-1 uppercase text-sm tracking-wide cursor-pointer font-semibold rounded text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-700"
          >
            Show Genres
          </button>
          <label
            class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
            >Genres</label
          >

          <div class="relative">
            <div
              class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
            >
              <div
                v-for="genre in genres"
                :key="genre"
                class="w-full flex gap-2"
              >
                <input
                  v-model="searchForm.genres"
                  type="checkbox"
                  :value="genre"
                />
                <label for="" class="text-gray-800 dark:text-gray-300">{{
                  genre
                }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="data.data.length < 1" class="mt-5">
        <p class="text-gray-800 dark:text-gray-400 text-lg">
          We couldn't find any anime matching your search.
        </p>
      </div>

      <div v-else>
        <div class="mt-5 w-full lg:flex lg:justify-between items-center">
          <div class="flex items-center gap-1">
            <span class="text-gray-700 dark:text-gray-400">
              {{ formattedTotalAnime }} available anime
            </span>
          </div>
          <div class="flex items-center gap-1 mt-2 lg:mt-0">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
            >
              <ChevronLeftIcon
                class="size-5 text-gray-800 dark:text-gray-300"
                :class="{
                  'text-slate-400 dark:text-slate-700': currentPage === 1,
                }"
              />
            </button>
            <div
              v-for="(page, index) in paginationPages"
              :key="index"
              class="flex gap-1 w-8 h-8 border rounded border-gray-300 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-300"
            >
              <button
                :disabled="searchForm.processing"
                @click="goToPage(page)"
                :class="{
                  'bg-blue-600 rounded text-gray-300': page === currentPage,
                }"
                class="w-full"
              >
                {{ page }}
              </button>
            </div>
            <button
              @click="nextPage"
              :disabled="currentPage * 20 >= episodes"
              class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
            >
              <ChevronRightIcon
                class="size-5 text-gray-800 dark:text-gray-300"
                :class="{
                  'text-slate-400': currentPage * 20 >= episodes,
                }"
              />
            </button>
          </div>
        </div>

        <div
          class="mt-5 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 lg:gap-5"
        >
          <div
            v-for="(anime, index) in filteredAnime"
            :key="anime.id"
            class="mb-1 cursor-pointer"
            @click="goToAnime(anime.api_id)"
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
                :src="anime.cover_image"
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
                    {{ anime.title ? anime.title : anime.title_romaji }}
                  </h3>
                  <span
                    v-if="anime.episode"
                    class="text-gray-300 font-semibold"
                  >
                    Episode {{ anime.episode }}
                  </span>

                  <div class="flex items-center gap-2 mt-1">
                    <div class="flex items-center">
                      <StarIcon class="size-5 text-gray-300"> </StarIcon>
                      <span class="text-gray-300 text-sm md:text-base">{{
                        formattedScore(anime.score).toFixed(1)
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
              {{ anime.title ? anime.title : anime.title_romaji }}
            </h3>
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
  </div>
</template>

<script>
import {
  MagnifyingGlassIcon,
  ChevronDownIcon,
  AdjustmentsHorizontalIcon,
  ChevronRightIcon,
  ChevronLeftIcon,
  StarIcon,
} from "@heroicons/vue/24/solid";
import AnimeCard from "../../Components/Anime/AnimeCard.vue";
import SkeletonCard from "../../Components/Skeleton/SkeletonCard.vue";
import { useForm } from "@inertiajs/vue3";
import { ArrowUpTrayIcon } from "@heroicons/vue/16/solid";

export default {
  components: {
    AnimeCard,
    SkeletonCard,
    MagnifyingGlassIcon,
    ChevronDownIcon,
    AdjustmentsHorizontalIcon,
    ChevronRightIcon,
    ChevronLeftIcon,
    StarIcon,
  },
  props: {
    data: Object,
    selectedFilters: Object,
  },
  data() {
    return {
      statusOptions: [
        { label: "Finished", value: "FINISHED" },
        { label: "Ongoing", value: "RELEASING" },
        { label: "Upcoming", value: "NOT_YET_RELEASED" },
        { label: "Hiatus", value: "HIATUS" },
        { label: "Cancelled", value: "CANCELLED" },
      ],
      formats: ["TV", "MOVIE", "OVA", "ONA", "SPECIAL"],
      seasons: ["WINTER", "SPRING", "SUMMER", "FALL"],
      years: [],
      sorts: [
        { label: "Most Popular", value: "POPULARITY" },
        { label: "Top Rated", value: "SCORE" },
        { label: "Newest", value: "season_year" },
        { label: "Oldest", value: "oldest" },
      ],
      countryOrigins: [
        { label: "Japan", value: "JP" },
        { label: "China", value: "CN" },
        { label: "South Korea", value: "KR" },
        { label: "Taiwan", value: "TW" },
      ],
      genres: [
        "Action",
        "Adventure",
        "Comedy",
        "Drama",
        "Ecchi",
        "Fantasy",
        "Horror",
        "Mahou Shoujo",
        "Mecha",
        "Music",
        "Mystery",
        "Psychological",
        "Romance",
        "Sci-Fi",
        "Slice of Life",
        "Sports",
        "Supernatural",
        "Thriller",
      ],
      ANILIST_API: "https://graphql.anilist.co",
      searchForm: useForm({
        search: this.selectedFilters.search,
        status: this.selectedFilters.status,
        format: this.selectedFilters.format,
        season: this.selectedFilters.season,
        year: this.selectedFilters.year,
        country: this.selectedFilters.country,
        sort: this.selectedFilters.sort,
        genres: this.selectedFilters.genres,
        page: this.selectedFilters.page,
      }),
      form: useForm(),
      isLoading: false,
      hasError: false,
      isFilterOpen: false,
      showDetails: false,
      currentActiveAnimeId: null,
      currentPage: 1,
    };
  },
  mounted() {
    this.addYear();
  },
  methods: {
    clearFilter() {
      this.searchForm.status = "";
      this.searchForm.format = "";
      this.searchForm.season = "";
      this.searchForm.year = "";
      this.searchForm.country = "";
      this.searchForm.sort = "";
      this.searchForm.genres = [];
      this.searchForm.page = 1;
    },
    handleMouseEnter(animeId) {
      this.currentActiveAnimeId = animeId;
      this.showDetails = true;
    },
    handleMouseLeave(animeId) {
      this.currentActiveAnimeId = animeId;
      this.showDetails = false;
    },
    formattedScore(score) {
      return (score / 100) * 10;
    },
    addGenre(event) {
      const value = event.target.value;

      if (event.target.checked) {
        this.searchForm.genres.push(event.target.value);
      } else {
        const index = this.searchForm.genres.indexOf(value);
        if (index !== -1) {
          this.searchForm.genres.splice(index, 1);
        }
      }
    },
    addYear() {
      const currentYear = new Date().getFullYear();
      for (let i = currentYear; i >= 1970; i--) {
        this.years.push(i);
      }
    },
    searchAnime() {
      this.searchForm.get("/explore", {
        preserveState: true,
        preserveScroll: true,
      });
    },
    showFilters() {
      this.isFilterOpen = !this.isFilterOpen;
    },
    goToPage(page) {
      this.searchForm.page = page;
      this.currentPage = page;
      this.searchAnime();
    },
    prevPage(page) {
      this.searchForm.page = page;
      this.currentPage--;
      this.searchAnime();
    },
    nextPage(page) {
      this.searchForm.page = page;
      this.currentPage++;
      this.searchAnime();
    },
    goToAnime(anilistId) {
      this.form.get(`/anime/${anilistId}`, {
        preserveState: true,
      });
    },
  },
  computed: {
    formattedTotalAnime() {
      return this.data.total.toLocaleString();
    },
    filteredAnime() {
      return this.data.data;
    },
    paginationPages() {
      const totalPages = Math.ceil(this.data.total / 20);
      const pagesPerGroup = 5;
      const currentPage = this.currentPage;

      const currentGroup = Math.ceil(currentPage / pagesPerGroup);

      const startPage = (currentGroup - 1) * pagesPerGroup + 1;
      const endPage = Math.min(currentGroup * pagesPerGroup, totalPages);

      const paginateNumbers = [];

      for (let i = startPage; i <= endPage; i++) {
        paginateNumbers.push(i);
      }
      return paginateNumbers;
    },
  },
  watch: {
    "searchForm.status"() {
      this.searchAnime();
    },
    "searchForm.format"() {
      this.searchAnime();
    },
    "searchForm.season"() {
      this.searchAnime();
    },
    "searchForm.year"() {
      this.searchAnime();
    },
    "searchForm.country"() {
      this.searchAnime();
    },
    "searchForm.sort"() {
      this.searchAnime();
    },
    "searchForm.genres": {
      handler() {
        this.searchAnime();
      },
      deep: true,
    },
  },
};
</script>
