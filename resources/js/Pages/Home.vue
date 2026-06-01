<template>
    <Head title="Home - " />

    <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
        <section class="mb-8">
            <BaseHeading> Making Waves </BaseHeading>
            <BaseText> The hottest anime everyone is diving into right now. </BaseText>

            <SkeletonCard v-if="isLoading" />
            <AnimeCard class="mt-5" v-else :anime="trendingAnime" />
        </section>

        <section class="mb-2">
            <BaseHeading> Fresh from Deep </BaseHeading>
            <BaseText> Newly added episodes. </BaseText>

            <SkeletonCard v-if="isLoading" />
            <AnimeCard class="mt-5" :anime="newEpisodes" />
        </section>
    </div>
</template>

<script>
    import AnimeCard from '../Components/Anime/AnimeCard.vue';
    import SkeletonCard from '../Components/Skeleton/SkeletonCard.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        components: {
            AnimeCard,
            SkeletonCard
        },
        data() {
            return {
                ANILIST_API: 'https://graphql.anilist.co',
                trendingAnime: [],
                newEpisodes: [],
                form: useForm(),
                isLoading: false,
                hasError: false
            }
        },
        methods: {
            async fetchTrendingAnime() {
                this.isLoading = true;
                this.hasError = false;

                try {
                    const query = `
                        query Page($page: Int, $perPage: Int, $type: MediaType, $status: MediaStatus, $sort: [MediaSort]) {
                            Page(page: $page, perPage: $perPage) {
                                media(type: $type, status: $status, sort: $sort) {
                                    id
                                    title {
                                        english
                                        romaji
                                    }
                                    coverImage {
                                        extraLarge
                                    }
                                    format
                                }
                            }
                        }
                    `
                    const variables = {page: 1, perPage: 18, type: 'ANIME', status: 'RELEASING', sort: 'TRENDING_DESC' };

                    const response = await fetch(this.ANILIST_API, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            query: query,
                            variables: variables
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to fetch trending anime');
                    }

                    const data = await response.json();
                    this.trendingAnime = data.data.Page.media;

                } catch (error){
                    this.hasError = true;
                    console.error(error);
                } finally {
                    this.isLoading = false;
                }

            },
            async fetchNewEpisodes() {
                this.isLoading = true;
                this.hasError = false;

                try {
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
                    const trendingResponse = await fetch(this.ANILIST_API, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            query: trendingQuery,
                            variables: { page: 1, perPage: 18 }
                        })
                    });

                    if (!trendingResponse.ok) {
                        throw new Error('Failed to fetch trending episode.');
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
                    const variables = { trendingIds, "page": 1, "perPage": 18 };
                    const response = await fetch('https://graphql.anilist.co', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ query, variables })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to fetch latest episodes.');
                    }

                    const data = await response.json();

                    this.newEpisodes = data.data.Page.airingSchedules.map((item) => ({
                        ...item.media,
                        episode: item.episode
                    }))

                } catch (error){
                    this.hasError = true;
                    console.error(error);
                } finally {
                    this.isLoading = false;
                }

            },
            showAnime(animeId) {
                this.form.get(`/anime/${animeId}`);
            }
        },
        mounted() {
            this.fetchTrendingAnime();
            this.fetchNewEpisodes();
        }
    }
</script>
