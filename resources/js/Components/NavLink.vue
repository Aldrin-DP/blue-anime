<template>
    <Link
        :href="href"
        class="text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-200 hover:dark:shadow-gray-900 transition-all duration-300"
    >
        <slot></slot>
    </Link>
</template>

<script>
export default {
    props: {
        href: {
            type: String
        }
    },
    data() {

    }
}
</script>
