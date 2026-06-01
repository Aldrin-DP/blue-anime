<template>
    <Head title="Explore - "/>
    <div>
        <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
            <h1 class="text-3xl xl:text-4xl font-extrabold tracking-wider text-gray-800 dark:text-gray-300">Explore the Deep</h1>
            <BaseText> Explore and search through thousands of anime. </BaseText>

            <div
                tabindex="0"
                class="flex lg:justify-between items-center mt-4 mb-3 gap-2 border rounded-2xl border-gray-300  dark:border-gray-700 focus-within:outline-2 focus-within:outline-blue-700"
            >
                <input
                    v-model="form.search"
                    @keydown.enter="searchAnime"
                    type="text"
                    class="w-full text-lg pl-5 py-3  outline:none focus:border-none focus:outline-0 text-gray-800 dark:text-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    placeholder="Explore"
                >
                <MagnifyingGlassIcon @click="searchAnime" class="size-8 text-gray-700 mr-5 cursor-pointer"/>
            </div>
            <div>
                <button @click="showFilters" class="flex border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 font-bold tracking-wide gap-1 items-center border px-3 py-2 rounded-lg cursor-pointer">
                    <AdjustmentsHorizontalIcon class="size-6" />
                    Filter
                </button>
            </div>

            <div v-if="isFilterOpen" class="lg:grid gap-5 grid-cols-3 mt-4 border border-gray-300 dark:border-gray-700  shadow rounded-xl p-5 bg-gray-100/70 dark:bg-gray-800/50 backdrop-blur-md">
                <div class="mb-2">
                    <label class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase">Status</label>
                    <div class="relative">
                        <span class="absolute block top-3 right-3">
                            <ChevronDownIcon class="size-4 dark:text-gray-400" />
                        </span>
                        <select
                            @input="handleInput"
                            v-model="form.status" class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700">
                            <option value="" selected>All</option>
                            <option value="releasing">Ongoing</option>
                            <option value="finished">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="not_yet_released">Not Yet Released</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase">Format</label>
                    <div class="relative">
                        <span class="absolute block top-3 right-3">
                            <ChevronDownIcon class="size-4 dark:text-gray-400" />
                        </span>
                        <select v-model="form.format" class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700">
                            <option value="">All</option>
                            <option value="TV">TV</option>
                            <option value="MOVIE">Movie</option>
                            <option value="ONA">ONA</option>
                            <option value="OVA">OVA</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block mb-1 tracking-wider text-gray-600 dark:text-gray-400 text-xs lg:text-sm uppercase">Season</label>
                    <div class="relative">
                        <span class="absolute block top-3 right-3">
                            <ChevronDownIcon class="size-4 dark:text-gray-400" />
                        </span>
                        <select v-model="form.season" class="font-semibold text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-gray-800 appearance-none w-full border rounded-md px-2 py-2 border-gray-300 dark:border-gray-700">
                            <option value="">All</option>
                            <option value="TV">TV</option>
                            <option value="MOVIE">Movie</option>
                            <option value="ONA">ONA</option>
                            <option value="OVA">OVA</option>
                        </select>
                    </div>
                </div>
            </div>

            <section>
                <div class="mt-5">
                    <p class="mb-2 text-gray-700 dark:text-gray-400">{{ filteredAnime.length }} results</p>
                    <AnimeCard :anime="filteredAnime"/>
                </div>
            </section>

        </div>
    </div>
</template>

<script>
    import { MagnifyingGlassIcon, ChevronDownIcon, AdjustmentsHorizontalIcon } from '@heroicons/vue/24/solid';
    import AnimeCard from '../../Components/Anime/AnimeCard.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        components: {
            MagnifyingGlassIcon,
            ChevronDownIcon,
            AdjustmentsHorizontalIcon,
            AnimeCard
        },
        props: {
            data: Object
        },
        data() {
            return {
                ANILIST_API: 'https://graphql.anilist.co',
                filteredAnime: [],
                form: useForm({
                    search: '',
                    status: '',
                    format: '',
                    season: '',
                }),
                isLoading: false,
                hasError: false,
                isFilterOpen: false
            }
        },
        computed: {
            pageInfo() {
                return this.data.data.Page.pageInfo;
            },
        },
        methods: {
            searchAnime() {

                console.log(this.form);


                this.form.get('/explore');

            },
            showFilters() {
                this.isFilterOpen = !this.isFilterOpen;
            },
            handleInput() {
                this.searchAnime();
            }
        },
        mounted() {
            console.log(this.data);
            this.filteredAnime = this.data.data.Page.media.filter( anime => ['TV', 'MOVIE', 'SPECIAL', 'OVA', 'ONA'].includes(anime.format));
        }

    }
</script>
