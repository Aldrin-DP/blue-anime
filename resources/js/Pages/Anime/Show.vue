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
                    <div class="grid xl:grid-cols-4 gap-5 mt-3 px-2 py-4 text-sm font-semibold text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/70 dark:bg-gray-900/70 rounded">
                        <div class="xl:hidden flex justify-between items-center">
                            <div class="xl:hidden flex items-center gap-1">
                                <StarIcon class="size-6" />
                                <span>{{ anime.averageScore }}%</span>
                            </div>
                            <div class="xl:hidden flex items-center gap-1">
                                <span class="">{{ animeStatus }}</span>
                            </div>
                            <div v-if="anime.nextAiringEpisode" class="xl:hidden flex items-center gap-1">
                                <span>{{ anime.nextAiringEpisode.episode-1 }} Episodes</span>
                            </div>
                            <div v-else class="xl:hidden flex items-center gap-1">
                                <span>{{ anime.episodes }} Episodes</span>
                            </div>
                        </div>
                        
                        <div class="hidden xl:flex items-center gap-1">
                            <span class="uppercase text-xs text-gray-600">Status:</span>
                            <span class="">{{ animeStatus }}</span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Score:</span>
                            <span class="">{{ anime.averageScore }}%</span>
                        </div>
                        <div v-if="anime.nextAiringEpisode" class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Episodes:</span>
                            <span class="">{{ anime.nextAiringEpisode.episode-1 }}</span>
                        </div>
                        <div v-else class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Episodes:</span>
                            <span class="">{{ anime.episodes }} Episodes</span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Released Year:</span>
                            <span class="">{{ releaseYear  }} </span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Duration:</span>
                            <span class="">{{ anime.duration }} mins</span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class=" uppercase text-xs text-gray-600">Type:</span>
                            <span class="">{{ anime.format }}</span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class="uppercase text-xs text-gray-600">Studio:</span>
                            <span class="">{{ anime.studios.nodes[0].name  }} </span>
                        </div>
                        <div class="hidden xl:flex items-center gap-1">
                            <span class="uppercase text-xs text-gray-600">Season:</span>
                            <span class="">{{ anime.season  }} </span>
                        </div>
                    </div>
                    <div v-if="anime.nextAiringEpisode" class="flex justify-between mt-3 px-2 py-4 text-sm text-blue-800 dark:text-blue-300 border border-blue-400 dark:border-blue-900 bg-blue-300/70 dark:bg-blue-800/70 rounded">
                        <span class="font-semibold tracking-wider">Next episode:</span>
                        <span class="font-bold">{{ airingAt }}</span>
                    </div>
                    <div class="mt-5 flex gap-1 items-center">
                        <BaseButton variant="primary" class="flex">
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
                            class="cursor-pointer block font-bold mt-2">
                            {{ isTruncated ? 'Read More' : 'See Less' }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class=" backdrop-filter-blur p-3">
            <h2 class="text-lg font-bold tracking-wider text-gray-800 dark:text-gray-200">EPISODES</h2>
            <div class="flex justify-between items-center my-2">
                <select name="" id="" class="px-2 py-1 border border-gray-300 ">
                    <option value="">Season 1</option>
                </select>
                <button>
                    <ArrowsUpDownIcon class="size-5" />
                </button>
            </div>
            <div class="grid gap-3 grid-cols-2 lg:grid-cols-5">
                <div v-for="(episode, index) in anime.streamingEpisodes">
                    <p>{{ episode.title }}</p>
                    <img :src="episode.thumbnail ? episode.thumbnail : anime.bannerImage" alt="">
                </div>
            </div>


        </section>
    </div>
</template>

<script>
    import { StarIcon, HeartIcon, PlusIcon, ArrowsUpDownIcon } from '@heroicons/vue/20/solid';
    import BaseButton from '../../Components/Base/BaseButton.vue';

    export default {
        components: {
            StarIcon,
            HeartIcon,
            BaseButton,
            PlusIcon,
            ArrowsUpDownIcon
        },
        props: {
           data: Object
        },
        data() {
            return {
                now: Math.floor(Date.now() / 1000),
                isTruncated: true,
                isDescriptionOver40: true,
            }
        },
        mounted() {
            setInterval(() => {
                this.now = Math.floor(Date.now() / 1000)
            }, 1000)
        },
        computed: {
            anime() {
                return this.data.data.Media;
            },
            genres() {
                const genres = this.data.data.Media.genres;
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
            releaseYear() {
                return `${this.anime.startDate.month}-${this.anime.startDate.day}-${this.anime.startDate.year}`
            },
            animeStatus() {
                let status = this.anime.status.toLowerCase();
                
                if (status === 'releasing') return 'Airing';
                if (status === 'finished') return 'Completed';
            }

        }
    }
</script>
