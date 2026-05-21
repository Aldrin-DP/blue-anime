<template>
    <Link
        :href="href"
        class="text-[15px] font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:text-gray-100 hover:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-900 transition-all duration-300"
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
