<template>
    <div class="flex flex-col h-dvh">
        <TheHeader />

        <main class=" bg-gradient-to-bl from-blue-950 to-teal-950 flex-1">
            <div class="py-5 px-20">
                <slot></slot>
            </div>
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
