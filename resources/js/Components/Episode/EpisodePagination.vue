<template>
  <div class="flex justify-between items-center my-4">
    <div class="flex items-center gap-1">
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

    <button
      @click="toggleSort"
      class="p-1 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 cursor-pointer"
    >
      <BarsArrowUpIcon v-if="sorted === 'asc'" class="size-6" />
      <BarsArrowDownIcon v-else class="size-6" />
    </button>
  </div>
</template>

<script>
import {
  ArrowsUpDownIcon,
  ChevronRightIcon,
  ChevronLeftIcon,
  BarsArrowUpIcon,
  BarsArrowDownIcon,
} from "@heroicons/vue/20/solid";
import EpisodeList from "./EpisodeList.vue";
import { useForm, router } from "@inertiajs/vue3";
export default {
  props: {
    anime: Object,
    episodesProgress: Array,
  },
  components: {
    EpisodeList,
    ArrowsUpDownIcon,
    ChevronRightIcon,
    ChevronLeftIcon,
    BarsArrowUpIcon,
    BarsArrowDownIcon,
  },
  emits: ["sorted", "page"],
  data() {
    return {
      currentPage: 1,
      sorted: "asc",
    };
  },
  methods: {
    nextPage() {
      this.currentPage++;
    },
    prevPage() {
      this.currentPage--;
    },
    goToPage(page) {
      this.currentPage = page;
      this.$emit("page", this.currentPage);
    },
    toggleSort() {
      this.currentPage = 1;
      if (this.sorted === "asc") {
        this.sorted = "desc";
      } else if (this.sorted === "desc") {
        this.sorted = "asc";
      }
      this.$emit("sorted", this.sorted);
    },
  },
  computed: {
    paginationPages() {
      const totalPages = Math.ceil(this.episodes / 20);
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
    episodes() {
      return this.anime.nextAiringEpisode
        ? this.anime.nextAiringEpisode.episode - 1
        : this.anime.episodes;
    },
  },
};
</script>
