<template>
  <div>
    <h2
      class="font-bold text-[22px] md:text-3xl tracking-wider text-gray-700 dark:text-gray-300"
    >
      <slot></slot>
    </h2>
  </div>
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      default: "text-3xl",
    },
  },
};
</script>
