<template>
  <div>
    <small
      class="text-red-400 text-[14px] tracking-wide font-medium block ml-1 mt-1"
    >
      {{ message }}
    </small>
  </div>
</template>

<script>
export default {
  props: {
    message: String,
  },
};
</script>
