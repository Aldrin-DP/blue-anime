<template>
    <div>
        <div v-if="!anime">
            <p>Loading</p>
        </div>
        <div v-else>
            <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 lg:gap-5 mt-5">
                <div
                    v-for="(anime, index) in anime" :key="anime.id"
                    class="mb-3"
                    @click="showAnime(anime.id)"
                >
                    <div class="border-2 border-gray-200 dark:border-gray-700 p-0.75  bg-gray-300 dark:bg-gray-400 rounded-lg aspect-2/3 relative">
                        <span v-if="anime.episode" class="absolute py-0.5 inline rounded-md px-2 text-xs  sm:text-sm shadow top-2 left-2 font-bold bg-blue-800 text-gray-300">
                            EP {{ anime.episode }}
                        </span>
                        <span v-if="anime.format" class="absolute py-0.5 inline rounded-md px-2 text-xs  sm:text-sm shadow top-2 right-2 font-bold bg-blue-800 text-gray-300">
                            {{ anime.format }}
                        </span>
                        <img :src="anime.coverImage.extraLarge" alt="" class="rounded w-full h-full object-cover object-center">
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 font-semibold truncate mt-1">
                        {{ anime.title.english ? anime.title.english : anime.title.romaji }}
                    </p>
                    <p v-if="anime.episode" class="text-gray-600 dark:text-gray-400 font-semibold text-[13px]">
                        Episode {{ anime.episode  }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { useForm } from '@inertiajs/vue3';

    export default {
        props: {
            anime: Array
        },
        data() {
            return {
                form: useForm(),
            }
        },
        methods: {
            showAnime(animeId) {
                this.form.get(`/anime/${animeId}`);
            }
        }
    }
</script>
