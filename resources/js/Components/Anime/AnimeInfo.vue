<template>
    <div
        class="grid xl:grid-cols-4 gap-5 mt-3 px-2 py-4 text-sm font-semibold text-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-800 bg-white/70 dark:bg-gray-900/70 rounded"
    >
        <div class="xl:hidden flex justify-between items-center">
            <div class="xl:hidden flex items-center gap-1">
                <span class="">{{ animeStatus }}</span>
            </div>
            <div class="xl:hidden flex items-center gap-1">
                <StarIcon class="size-6" />
                <span>{{ anime.averageScore }}%</span>
            </div>
            <div
                v-if="anime.nextAiringEpisode"
                class="xl:hidden flex items-center gap-1"
            >
                <span>{{ anime.nextAiringEpisode.episode - 1 }} Episodes</span>
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
            <span class="uppercase text-xs text-gray-600">Score:</span>
            <span class="">{{ anime.averageScore }}%</span>
        </div>
        <div
            v-if="anime.nextAiringEpisode"
            class="hidden xl:flex items-center gap-1"
        >
            <span class="uppercase text-xs text-gray-600">Episodes:</span>
            <span class="">{{ anime.nextAiringEpisode.episode - 1 }}</span>
        </div>
        <div v-else class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Episodes:</span>
            <span class="">{{ anime.episodes }} Episodes</span>
        </div>
        <div class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Released Year:</span>
            <span class="">{{ releaseYear }} </span>
        </div>
        <div class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Duration:</span>
            <span class="">{{ anime.duration }} mins</span>
        </div>
        <div class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Format:</span>
            <span class="">{{ anime.format }}</span>
        </div>
        <div class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Studio:</span>
            <span class="">{{ anime.studios.nodes[0].name }} </span>
        </div>
        <div class="hidden xl:flex items-center gap-1">
            <span class="uppercase text-xs text-gray-600">Season:</span>
            <span class="">{{ anime.season }} </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: Array,
    },
    data() {
        return {};
    },
    computed: {
        anime() {
            return this.data.data.Media;
        },
        releaseYear() {
            return `${this.anime.startDate.month}-${this.anime.startDate.day}-${this.anime.startDate.year}`;
        },
        animeStatus() {
            let status = this.anime.status.toLowerCase();

            if (status === "releasing") return "Airing";
            if (status === "finished") return "Completed";
        },
    },
};
</script>
