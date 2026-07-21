<template>
  <div
    class="flex flex-col h-full max-w-[1440px] mx-auto bg-gray-50 dark:bg-gray-950"
  >
    <TheHeader />

    <main
      class="bg-linear-to-br from-sea-100/30 to-sea-300/20 dark:bg-linear-to-b dark:from-sea-900 dark:from-5% dark:to-sea-950 flex-1"
    >
      <div class="">
        <slot></slot>
      </div>
    </main>

    <TheFooter
      class="mt-auto bg-linear-to-br from-sea-100/30 to-sea-300/20 dark:bg-linear-to-b dark:from-sea-900 dark:from-5% dark:to-sea-950"
    />
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
  mounted() {
    init();
  },
};
</script>
