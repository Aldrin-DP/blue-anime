<template>
    <div class="lg:hidden flex items-center gap-1">
        <span class="uppercase text-xs text-gray-600">Score:</span>
        <span>{{ anime.averageScore }}%</span>
    </div>
    <div v-if="anime.nextAiringEpisode" class="lg:hidden flex items-center gap-1">
        <span class="uppercase text-xs text-gray-600">Episodes:</span>
        <span>{{ anime.nextAiringEpisode.episode-1 }}</span>
    </div>
    <div v-else class="lg:hidden flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Episodes:</span>
        <span>{{ anime.episodes }} Episodes</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Status:</span>
        <span>{{ anime.status }}</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Score:</span>
        <span>{{ anime.averageScore }}%</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Type:</span>
        <span>{{ anime.format }}</span>
    </div>
    <div v-if="anime.nextAiringEpisode" class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Episodes:</span>
        <span>{{ anime.nextAiringEpisode.episode-1 }}</span>
    </div>
    <div v-else class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Episodes:</span>
        <span>{{ anime.episodes }} Episodes</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Duration:</span>
        <span>{{ anime.duration }} mins</span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Released Year:</span>
        <span>{{ anime.startDate.year  }} </span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Studio:</span>
        <span>{{ anime.studios.nodes[0].name  }} </span>
    </div>
    <div class="flex items-center gap-1">
        <span class="hidden lg:block uppercase text-xs text-gray-600">Season:</span>
        <span>{{ anime.season  }} </span>
    </div>
</template>

<script>

</script>
