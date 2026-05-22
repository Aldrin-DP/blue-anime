<template>
    <Head title="Home - " />
    <div>
        <section>
            <h1 class="text-3xl font-bold text-gray-200 tracking-wider">Fresh from Deep</h1>
            <p class="text-gray-400 text-md">Newly added episodes</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 lg:gap-5 mt-5">
                <div
                    v-for="(episode, index) in newEpisodes" :key="index"
                    class="mb-3"
                >

                    <div class="border-2 border-gray-700 p-0.75 bg-gray-400 rounded-lg aspect-2/3 relative">
                        <span class="absolute py-0.5 inline rounded-md px-2 text-xs  sm:text-sm shadow top-2 left-2 font-bold bg-blue-800 text-gray-300">
                            EP {{ episode.episode }}
                        </span>
                        <img :src="episode.media.coverImage.extraLarge" alt="" class="rounded w-full h-full object-cover object-center">
                    </div>
                    <p class="text-gray-300 font-semibold truncate">
                        {{ episode.media.title.english ? episode.media.title.english : episode.media.title.romaji }}
                    </p>
                    <p class="text-gray-400 font-semibold text-[13px]">
                        Episode {{ episode.episode }}
                    </p>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                newEpisodes: [],
            }
        },
        methods: {
            async fetchNewEpisodes() {
                const trendingQuery = `
                    query($page: Int, $perPage: Int) {
                        Page (page: $page, perPage: $perPage) {
                            media (
                                type: ANIME
                                status: RELEASING
                                sort: TRENDING_DESC
                            ) {
                                id
                                title {
                                    romaji
                                    english
                                }
                            }
                        }
                    }
                `
                const trendingResponse = await fetch('https://graphql.anilist.co', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        query: trendingQuery,
                        variables: { page: 1, perPage: 30 }
                    })
                });

                if (!trendingResponse.ok) {
                    console.log('Failed fetching trending animes.');
                    return;
                }

                const trendingData = await trendingResponse.json();
                const trendingIds = trendingData.data.Page.media.map(m => m.id);

                const query = `
                    query($trendingIds: [Int], $page: Int, $perPage: Int) {
                        Page (page: $page, perPage: $perPage) {
                            airingSchedules (
                                notYetAired: false
                                mediaId_in: $trendingIds
                                sort: TIME_DESC
                            ) {
                                episode
                                airingAt
                                media {
                                    id
                                    popularity
                                    trending
                                    title {
                                        romaji
                                        english
                                    },
                                    coverImage {
                                        extraLarge
                                        large
                                    }
                                }
                            }
                        }
                    }`
                const variables = { trendingIds, "page": 1, "perPage": 30 };
                const response = await fetch('https://graphql.anilist.co', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query, variables })
                });

                if (!response.ok) {
                    console.log('Failed to fetch latest episodes.');
                    return;
                }

                const data = await response.json();
                this.newEpisodes = data.data.Page.airingSchedules;
            }
        },
        mounted() {
            this.fetchNewEpisodes();
        }
    }
</script>
