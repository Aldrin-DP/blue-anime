<template>
    <div>
        <div class="mt-5 animate-pulse grid grid-cols-3 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 lg:gap-5">
            <div v-for="n in 18" :index="n" class="mb-3">
                <div class="aspect-2/3">
                    <div class="h-full w-full bg-gray-300 dark:bg-gray-700 rounded"></div>
                </div>
                <div class="w-full h-5 bg-gray-300 dark:bg-gray-700 mt-2"></div>
            </div>
        </div>
    </div>
</template>
