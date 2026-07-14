<template>
  <div
    ref="searchWrapper"
    tabindex="0"
    class="flex items-center relative mb-3 lg:mb-0 lg:mx-5 xl:mx-10 gap-2 border px-3 py-2 lg:py-1 rounded-full border-gray-300 dark:border-gray-900 focus-within:outline-2 focus-within:outline-blue-700"
  >
    <input
      v-model="search"
      type="text"
      class="w-full lg:w-auto outline:none focus:border-none focus:outline-0 placeholder:text-gray-400"
      placeholder="Search anime..."
    />
    <MagnifyingGlassIcon class="size-5" />

    <ul
      class="w-full h-auto border-gray-300 lg:absolute top-11 rounded-md left-0 z-10 bg-gray-200 dark:bg-gray-800"
    >
      <li
        v-for="result in results"
        :key="result.id"
        class="px-2 py-1 flex gap-2 items-center cursor-pointer hover:bg-gray-300 hover:dark:bg-gray-900 transition-all duration-300"
        @click="goToAnime(result.api_id)"
      >
        <div class="w-[32px] h-[44px] shrink-0">
          <img
            class="w-full h-full object-cover object-center"
            :src="result.cover_image"
            alt=""
          />
        </div>
        <span
          class="font-medium tracking-wide line-clamp-2 text-sm text-gray-700 dark:text-gray-400"
          >{{ result.title }}</span
        >
      </li>
    </ul>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import { MagnifyingGlassIcon } from "@heroicons/vue/20/solid";

export default {
  components: {
    MagnifyingGlassIcon,
  },
  data() {
    return {
      form: useForm(),
      search: "",
      timeout: null,
      results: [],
    };
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.addEventListener("click", this.handleClickOutside);
  },
  methods: {
    handleClickOutside(event) {
      if (!this.$refs.searchWrapper.contains(event.target)) {
        this.results = [];
      }
    },
    goToAnime(anilistId) {
      this.form.get(`/anime/${anilistId}`, {
        onFinish: (this.results = []),
      });
    },
  },
  watch: {
    search(value) {
      this.results = [];
      if (this.search.length < 2) {
        return;
      }
      clearTimeout(this.timeout);
      this.timeout = setTimeout(async () => {
        try {
          const response = await fetch(
            `/anime/search?search=${encodeURIComponent(this.search)}`,
          );
          const data = await response.json();
          this.results = data.searchList;
          console.log(this.results);
        } catch (error) {
          console.error(error);
        }
      }, 200);
    },
  },
};
</script>
