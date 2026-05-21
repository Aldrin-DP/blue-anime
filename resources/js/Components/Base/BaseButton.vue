<template>
    <button
        :class="btnClasses"
        :disabled="disabled"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        variant: {
            type: String
        },
        disabled: {
            type: Boolean,
            default: false
        },
        loading: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {

        }
    },
    computed: {
        btnClasses() {
            return [
                'cursor-pointer px-5 py-2 rounded-full font-bold bg-gray-600 text-sm',
                {
                    'bg-gradient-to-b from-blue-700 to-blue-900 hover:opacity-90 transition-all duration-300': this.variant === 'primary',
                    'bg-gradient-to-b from-green-700 to-green-900 hover:opacity-90 transition-all duration-300': this.variant === 'secondary',
                    'bg-gradient-to-b from-red-700 to-red-900 hover:opacity-90 transition-all duration-300': this.variant === 'danger',
                },
            ];
        }
    }
}
</script>
