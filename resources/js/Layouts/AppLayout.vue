<template>
    <div class="flex flex-col h-auto bg-gray-50 dark:bg-gray-950">
        <TheHeader />

        <main
            class="bg-gradient-to-br from-sea-100/30 to-sea-300/20 dark:bg-gradient-to-b dark:from-sea-900 dark:from-5% dark:to-sea-950 flex-1"
        >
            <div class="">
                <slot></slot>
            </div>
        </main>

        <TheFooter />
    </div>
</template>

<script>
import TheHeader from "./Partials/TheHeader.vue";
import TheFooter from "./Partials/TheFooter.vue";
import { useTheme } from "../composables/useTheme";

const { init } = useTheme();

export default {
    components: {
        TheHeader,
        TheFooter,
    },
    data() {
        return {};
    },
    mounted() {
        init();
    },
};
</script>
