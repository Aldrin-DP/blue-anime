<template>
    <button
        :class="btnClasses"
        :disabled="disabled || isProcessing"
    >
        <span v-if="isProcessing" class="flex items-center justify-center">
            <svg
                class="mr-1 -ml-1 size-4 animate-spin text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    >
                    </circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
            </svg>
            Processing
        </span>
        <span v-else>
            <slot></slot>
        </span>
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
        isProcessing: {
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
                'cursor-pointer px-5 py-2 rounded-full font-bold text-gray-200 bg-gradient-to-b from-blue-700 to-blue-900 text-sm',
                {
                    'bg-gradient-to-b from-blue-700 to-blue-900 hover:opacity-90 transition-all duration-300': this.variant === 'primary' && !this.isProcessing,
                    'bg-gradient-to-b from-green-700 to-green-900 hover:opacity-90 transition-all duration-300': this.variant === 'secondary' && !this.isProcessing,
                    'bg-gradient-to-b from-red-700 to-red-900 hover:opacity-90 transition-all duration-300': this.variant === 'danger' && !this.isProcessing,
                    'opacity-70 bg-blue-700 cursor-not-allowed': this.disabled || this.isProcessing
                },
            ];
        }
    }
}
</script>
