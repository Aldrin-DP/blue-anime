<template>
    <div class="flex flex-col h-auto">
        <TheHeader />

        <main class="bg-gradient-to-bl from-blue-50 to-teal-50 dark:bg-gradient-to-bl dark:from-blue-950 dark:to-teal-950 flex-1">
            <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
                <slot></slot>
            </div>
        </main>

        <TheFooter />
    </div>
</template>

<script>
import TheHeader from './Partials/TheHeader.vue';
import TheFooter from './Partials/TheFooter.vue';
import { useTheme } from '../composables/useTheme';

const { init } = useTheme()

export default {
    components: {
        TheHeader,
        TheFooter
    },
    data() {
        return {

        }
    },
    mounted() {
        init()
    }
}
</script>
