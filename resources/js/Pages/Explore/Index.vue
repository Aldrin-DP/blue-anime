<template>
    <Head title="Explore - " />
    <div>
        <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
            <h1
                class="text-3xl xl:text-4xl font-extrabold tracking-wider text-gray-800 dark:text-gray-300"
            >
                Explore the Deep
            </h1>
            <BaseText>
                Explore and search through thousands of anime.
            </BaseText>

            <div
                tabindex="0"
                class="flex lg:justify-between items-center mt-4 mb-3 gap-2 border rounded-2xl border-gray-300 dark:border-gray-700 focus-within:outline-2 focus-within:outline-blue-700"
            >
                <input
                    v-model="form.search"
                    @keydown.enter="searchAnime"
                    type="text"
                    class="w-full text-lg pl-5 py-3 outline:none focus:border-none focus:outline-0 text-gray-800 dark:text-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="Explore"
                />
                <MagnifyingGlassIcon
                    @click="searchAnime"
                    class="size-8 text-gray-700 mr-5 cursor-pointer"
                />
            </div>
            <div>
                <button
                    @click="showFilters"
                    class="flex border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 font-bold tracking-wide gap-1 items-center border px-3 py-2 rounded-lg cursor-pointer"
                >
                    <AdjustmentsHorizontalIcon class="size-6" />
                    Filters
                </button>
            </div>

            <div
                v-if="isFilterOpen"
                class="lg:grid gap-5 grid-cols-3 mt-4 border border-gray-300 dark:border-gray-700 shadow rounded-xl p-5 bg-gray-100/70 dark:bg-gray-800/50 backdrop-blur-md"
            >
                <div class="mb-2">
                    <label
                        class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase"
                        >Status</label
                    >
                    <div class="relative">
                        <span class="absolute block top-3 right-3">
                            <ChevronDownIcon
                                class="size-4 dark:text-gray-400"
                            />
                        </span>
                        <select
                            @input="handleInput"
                            v-model="form.status"
                            class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
                        >
                            <option value="" selected>All Status</option>
                            <option value="RELEASING">Ongoing</option>
                            <option value="FINISHED">Completed</option>
                            <option value="CANCELLED">Cancelled</option>
                            <option value="NOT_YET_RELEASED">
                                Not Yet Released
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
                                class="size-4 dark:text-gray-400"
                            />
                        </span>
                        <select
                            v-model="form.format"
                            class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
                        >
                            <option value="">All Format</option>
                            <option value="TV">TV</option>
                            <option value="MOVIE">Movie</option>
                            <option value="ONA">ONA</option>
                            <option value="OVA">OVA</option>
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
                                class="size-4 dark:text-gray-400"
                            />
                        </span>
                        <select
                            v-model="form.season"
                            class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700"
                        >
                            <option value="">All Season</option>
                            <option value="SUMMER">SUMMER</option>
                            <option value="WINTER">WINTER</option>
                            <option value="FALL">FALL</option>
                            <option value="OVA">OVA</option>
                        </select>
                    </div>
                </div>
            </div>

            <section>
                <div class="mt-5">
                    <div class="flex justify-between items-center mb-3">
                        <p class="mb-2 text-gray-700 dark:text-gray-400">
                            Results
                        </p>
                        <div class="flex items-center gap-1">
                            <button
                                @click="prevPage"
                                :disabled="currentPage === 1"
                                class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                            >
                                <ChevronLeftIcon
                                    class="size-5 text-gray-800 dark:text-gray-300"
                                    :class="{
                                        'text-slate-400 dark:text-slate-700':
                                            currentPage === 1,
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
                                        'bg-blue-600 rounded text-gray-300':
                                            page === currentPage,
                                    }"
                                    class="w-full"
                                >
                                    {{ page }}
                                </button>
                            </div>
                            <button
                                @click="nextPage"
                                :disabled="currentPage * 18 >= episodes"
                                class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                            >
                                <ChevronRightIcon
                                    class="size-5 text-gray-800 dark:text-gray-300"
                                    :class="{
                                        'text-slate-400':
                                            currentPage * 18 >= episodes,
                                    }"
                                />
                            </button>
                        </div>
                    </div>
                    <div v-if="form.processing">
                        <SkeletonCard></SkeletonCard>
                    </div>
                    <div v-else-if="filteredAnime.length > 0">
                        <AnimeCard :anime="filteredAnime" />
                    </div>
                    <div
                        v-else
                        class="font-bold text-[17px] tracking-wide text-gray-700 dark:text-gray-400"
                    >
                        We couldn't find any results for your search.
                    </div>
                </div>
            </section>
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
} from "@heroicons/vue/24/solid";
import AnimeCard from "../../Components/Anime/AnimeCard.vue";
import SkeletonCard from "../../Components/Skeleton/SkeletonCard.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    components: {
        MagnifyingGlassIcon,
        ChevronDownIcon,
        AdjustmentsHorizontalIcon,
        AnimeCard,
        SkeletonCard,
        ChevronRightIcon,
        ChevronLeftIcon,
    },
    props: {
        data: Object,
    },
    data() {
        return {
            ANILIST_API: "https://graphql.anilist.co",
            form: useForm({
                search: "",
                status: "",
                format: "",
                season: "",
                page: 1,
            }),
            isLoading: false,
            hasError: false,
            isFilterOpen: false,
        };
    },
    computed: {
        pageInfo() {
            return this.data.data.Page.pageInfo;
        },
        animeData() {
            return this.data.data.Page.media;
        },
        filteredAnime() {
            const filters = ["MOVIE", "TV", "OVA", "ONA"];
            return this.data.data.Page.media.filter((anime) =>
                filters.includes(anime.format),
            );
        },
        paginationPages() {
            const totalPages = Math.ceil(
                this.pageInfo.total / this.pageInfo.perPage,
            );
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
        currentPage() {
            return this.pageInfo.currentPage;
        },
    },
    methods: {
        searchAnime() {
            this.form.get("/explore", {
                preserveState: true,
                preserveScroll: true,
            });
        },
        showFilters() {
            this.isFilterOpen = !this.isFilterOpen;
        },
        goToPage(page) {
            this.form.page = page;
            this.searchAnime();
        },
        prevPage() {
            this.form.page--;
            this.searchAnime();
        },
        nextPage() {
            this.form.page++;
            this.searchAnime();
        },
    },
    mounted() {},
    watch: {
        "form.status"() {
            this.searchAnime();
        },
        "form.format"() {
            this.searchAnime();
        },
        "form.season"() {
            this.searchAnime();
        },
    },
};
</script>
