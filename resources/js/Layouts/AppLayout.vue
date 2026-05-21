<template>
    <div class="flex flex-col h-dvh">
        <TheHeader />

        <main class="flex-1 py-5 px-20">
            <slot></slot>
        </main>

        <TheFooter />
    </div>
</template>

<script>
import TheHeader from './Partials/TheHeader.vue';
import TheFooter from './Partials/TheFooter.vue';

export default {
    components: {
        TheHeader,
        TheFooter
    },
    data() {
        return {

        }
    }
}
</script>
