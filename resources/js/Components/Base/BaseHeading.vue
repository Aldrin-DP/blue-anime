<template>
    <div>
        <h2
            :class="size === 'large' ? 'text-2xl' : 'text-3xl'"
            class="font-bold tracking-wider text-gray-700 dark:text-gray-300"
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
