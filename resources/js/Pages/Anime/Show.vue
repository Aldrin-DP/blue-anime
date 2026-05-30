<template>
    <div class="p-0 m-0 lg:p-10 xl:px-15 xl:py-10">
        <section class="relative h-full bg-cover bg-center lg:flex">
            <img :src="anime.coverImage.extraLarge" class="absolute inset-0 w-full h-full object-cover lg:hidden" alt="">
            <div class="hidden lg:block p-3 lg:p-0 w-4/12 xl:w-3/12">
                <div class="border-2 border-gray-200 dark:border-gray-700 p-0.75  bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3 ">
                    <img :src="anime.coverImage.extraLarge" alt="" class="rounded w-full h-full object-cover object-center">
                </div>
            </div>
            <div class="w-full lg:w-8/12 xl:w-9/12 relative z-10 p-3 bg-blue-50/80 dark:bg-gray-900/50 backdrop-filter-blur xl:bg-gradient-to-bl xl:from-blue-50 xl:to-teal-50 xl:dark:bg-gradient-to-bl xl:dark:from-blue-950 xl:dark:to-teal-950  h-full">
                <div>
                    <h2 class="font-extrabold text-xl mt-10 lg:mt-0 text-gray-900 dark:text-gray-100">
                        {{ anime.title.english ? anime.title.english : anime.title.romaji }}
                    </h2>
                    <div class="flex gap-2 mt-2">
                        <span v-for="(genre, index) in genres" :key="index"
                            class="px-2 py-1 text-sm text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/70 dark:bg-gray-900/70 rounded"
                        >
                            {{ genre }}
                        </span>
                    </div>

                     <AnimeInfo :data="data" />

                    <div
                        v-if="anime.nextAiringEpisode"
                        class="flex justify-between mt-3 px-2 py-4 text-sm text-blue-800 dark:text-blue-300 border border-blue-400 dark:border-blue-900 bg-blue-300/70 dark:bg-blue-800/70 rounded"
                    >
                        <span class="font-semibold tracking-wider">Next episode:</span>
                        <span class="font-bold">{{ airingAt }}</span>
                    </div>
                    <div class="mt-5 flex gap-1 items-center">
                        <BaseButton
                            variant="primary"
                            class="flex"
                        >
                            <div class="flex items-center justify-center">
                                <PlusIcon class="size-6" />
                                <span>Add to Watch List</span>
                            </div>
                        </BaseButton>

                        <BaseButton variant="primary">
                            <HeartIcon class="size-6" />
                        </BaseButton>
                    </div>

                    <div class="mt-3 px-2 py-4 text-sm text-gray-800 dark:text-gray-300 border-gray-400 dark:border-gray-800 bg-white/50 dark:bg-gray-900/70 rounded">
                        <span class="tracking-wider text-xs font-semibold">SYNOPSIS</span>
                        <div class="mt-3">
                            {{ truncatedDescription }}
                        </div>
                        <span
                            v-if="isDescriptionOver40"
                            @click="toggleTruncatedDescription"
                            class="cursor-pointer block font-bold mt-2"
                        >
                            {{ isTruncated ? 'Read More' : 'See Less' }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class=" backdrop-blur-md mt-3 p-3">

            <h2 class="text-2xl font-extrabold tracking-wider text-gray-800 dark:text-gray-300">Episodes</h2>
            <p class="font-semibold text-gray-500">{{ episodes }} episodes available</p>

            <div class="flex justify-between items-center my-4">
                <div class="flex items-center gap-1">
                    <button
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                    >
                        <ChevronLeftIcon
                            class="size-5 text-gray-800 dark:text-gray-300"
                            :class="{'text-slate-400 dark:text-slate-700': currentPage === 1 }"
                        />
                    </button>
                    <div
                        v-for="(page, index) in paginationPages"
                        :key="index"
                        class="flex gap-1 w-8 h-8 border rounded border-gray-300 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-300"
                    >
                        <button
                            @click="goToPage(page)"
                            :class="{'bg-blue-600 rounded text-gray-300': page === currentPage}"
                            class="w-full"
                        >
                            {{page}}
                        </button>
                    </div>
                    <button
                        @click="nextPage"
                        :disabled="(currentPage * 20) >= episodes"
                        class="w-8 h-8 flex justify-center items-center border rounded border-gray-300 dark:border-gray-700"
                    >
                        <ChevronRightIcon
                            class="size-5 text-gray-800 dark:text-gray-300"
                            :class="{'text-slate-400': (currentPage * 20) >= episodes }"
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
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <div v-for="ep in currentPageEpisodes">
                    <div class="cursor-pointer flex items-center gap-3 bg-blue-100 dark:bg-gradient-to-br dark:from-teal-950 dark:to-blue-950 border border-gray-300 dark:border-gray-700 p-2 rounded-xl hover:shadow-lg hover:scale-102 transition-all duration-300">
                        <span class="w-15 h-12 tracking-wider font-bold text-xl bg-blue-300 dark:bg-blue-700 text-blue-600 dark:text-blue-300 rounded-lg flex justify-center items-center">{{ ep }}</span>
                        <p class="font-semibold text-gray-700 dark:text-gray-300 tracking-wide">Episode {{ ep }}</p>
                    </div>
                </div>
            </div>


        </section>
    </div>
</template>

<script>
    import { StarIcon, HeartIcon, PlusIcon, ArrowsUpDownIcon, ChevronRightIcon, ChevronLeftIcon, BarsArrowUpIcon, BarsArrowDownIcon } from '@heroicons/vue/20/solid';
    import BaseButton from '../../Components/Base/BaseButton.vue';
    import AnimeInfo from '../../Components/Anime/AnimeInfo.vue';

    export default {
        components: {
            BaseButton,
            AnimeInfo,
            StarIcon,
            HeartIcon,
            PlusIcon,
            ArrowsUpDownIcon,
            ChevronRightIcon,
            ChevronLeftIcon,
            BarsArrowUpIcon,
            BarsArrowDownIcon
        },
        props: {
           data: Object
        },
        data() {
            return {
                now: Math.floor(Date.now() / 1000),
                isTruncated: true,
                isDescriptionOver40: true,
                currentPage: 1,
                sorted: 'desc'
            }
        },
        mounted() {
            setInterval(() => {
                this.now = Math.floor(Date.now() / 1000)
            }, 1000)
        },
        computed: {
            currentPageEpisodes(){
                const start = (this.currentPage - 1) * 20 + 1;
                const end = Math.min(this.currentPage * 20, this.episodes);
                const pages = [];


                if (this.sorted === 'desc') {
                    const sortStart = this.episodes - ((this.currentPage-1) * 20)
                    const sortEnd = Math.max(this.episodes - this.currentPage * 20 + 1, 1);
                    for (let i = sortStart; i >= sortEnd; i--){
                        pages.push(i);
                    }

                } else {
                    for (let i = start; i <= end; i++){
                        pages.push(i);
                    }
                }
                return pages;
            },
            paginationPages() {

                const totalPages = Math.ceil(this.episodes / 20); // 59
                const pagesPerGroup = 5;
                const currentPage = this.currentPage;

                const currentGroup = Math.ceil(currentPage / pagesPerGroup); // 2

                const startPage = (currentGroup-1) * pagesPerGroup + 1;
                const endPage = Math.min(currentGroup * pagesPerGroup, totalPages);


                const paginateNumbers = [];

                for (let i = startPage; i <= endPage; i++){
                    paginateNumbers.push(i);
                }
                return paginateNumbers;
            },
            anime() {
                return this.data.data.Media;
            },
            genres() {
                const genres = this.anime.genres;
                return genres.slice(0, 3);
            },
            airingAt() {

                const airingAt = this.anime.nextAiringEpisode.airingAt;
                const secondsUntilAiring = airingAt - this.now;

                const days = Math.floor(secondsUntilAiring / 86400);
                const hours = Math.floor((secondsUntilAiring % 86400) / 3600 );
                const mins = Math.floor((secondsUntilAiring % 3600) / 60 );
                const secs = Math.floor(secondsUntilAiring % 60);

                return `${days}d ${hours}h ${mins}m ${secs}s`;
            },
            truncatedDescription() {

                const description = this.anime.description;
                const cleaned = description.replace(/<[^>]*>/g, '\n');

                if (this.isTruncated) {
                    const newDescription = cleaned.trim().split(/\s+/);
                    let newWord = '';

                    if (newDescription.length > 40) {
                        for (let i = 0; i <= 30; i++) {
                            newWord = newWord + ' ' + newDescription[i]
                        }
                        newWord = newWord + '...';

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
                return this.anime.nextAiringEpisode ? this.anime.nextAiringEpisode.episode-1 : this.anime.episodes;
            }
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
            },
            toggleSort() {
                this.currentPage = 1;
                if (this.sorted === 'asc') {
                    this.sorted = 'desc'
                } else if (this.sorted === 'desc') {
                    this.sorted = 'asc';
                }
            }
        },

    }
</script>
