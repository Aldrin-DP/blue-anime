<template>
    <div class="bg-gray-50 text-gray-800 dark:bg-gray-950 dark:text-gray-300 flex items-center sticky top-0 z-10">
        <header class="max-w-325 w-full lg:flex lg:mx-5 xl:mx-15 lg:items-center">
            <div class="flex h-18 justify-between items-center w-full px-3 lg:w-auto lg:px-0">
                <div class="flex items-center gap-1 lg:-ml-2">
                    <Link href="/">
                        <img src="/public/images/sea.png" width="55px" alt="logo" class="hover:scale-115 transition-all duration-300">
                    </Link>
                    <h2 class="font-bold text-xl lg:text-2xl text-gray-800 dark:text-gray-200">Sea Anime</h2>
                </div>
                <!-- Hamburger -->
                 <div class="lg:hidden flex justify-between items-center gap-5">
                    <button @click="toggle" class="">
                        <div v-if="state.isDark" class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300">
                            <SunIcon class="size-3.5"/>
                            <button>Shallow</button>
                        </div>
                        <div v-else class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300">
                            <MoonIcon class="size-3.5"/>
                            <button>Abyss</button>
                        </div>
                    </button>
                    <button @click="toggleNav" class="lg:hidden cursor-pointer border border-transparent  px-1 py-1 hover:border-gray-800 hover:text-gray-100 hover:shadow-lg hover:shadow-gray-900 transition-all duration-300">
                        <XMarkIcon v-if="isOpen" class="size-6.5" />
                        <Bars3Icon v-else class="size-6.5" />
                    </button>
                 </div>
            </div>

            <div
                :class="isOpen ? 'flex' : 'hidden'"
                class="lg:flex flex-col justify-between absolute w-full lg:w-auto px-3 lg:px-0 py-4 lg:py-0 bg-gray-50 dark:bg-gray-950 border-t border-gray-300 dark:border-gray-800 lg:flex-1 h-auto z-20 lg:flex-row lg:relative lg:z-0 lg:border-none"
            >
                <div
                    tabindex="0"
                    class="flex items-center mb-3 lg:mb-0 lg:mx-5 xl:mx-10 gap-2 border px-3 py-2 lg:py-1 rounded-full border-gray-300  dark:border-gray-900 focus-within:outline-2 focus-within:outline-blue-700"
                >
                    <input
                        type="text"
                        class="w-full lg:w-auto outline:none focus:border-none focus:outline-0 placeholder:text-gray-400"
                        placeholder="Search anime..."
                    >
                    <MagnifyingGlassIcon class="size-5"/>
                </div>

                <div class="flex flex-col flex-1 justify-between lg:flex-row lg:items-center">
                    <div class="flex flex-col items-center lg:flex-row lg:gap-2">
                        <NavLink class="w-full lg:w-auto" href="/"> Surface </NavLink>
                        <NavLink class="w-full lg:w-auto" href="/"> Explore the Deep</NavLink>
                    </div>

                    <div class="flex flex-col items-center lg:justify-between gap-2 mt-4 lg:mt-0 pt-3 lg:pt-0 border-t border-gray-300 dark:border-gray-800 lg:flex-row lg:border-none">
                        <button  @click="toggle" v-if="!isOpen">
                            <div v-if="state.isDark" class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300">
                                <SunIcon class="size-3.5"/>
                                <button>Shallow</button>
                            </div>
                            <div v-else class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300">
                                <MoonIcon class="size-3.5"/>
                                <button>Abyss</button>
                            </div>
                        </button>

                        <NavLink href="/" class="flex items-center justify-center w-full lg:w-auto gap-1">
                            <ArrowRightEndOnRectangleIcon class="size-5" />
                            Log in
                        </NavLink>
                        <div class="w-full lg:w-auto">
                            <BaseButton
                                variant="primary"
                                class="w-full lg:w-auto"
                            >
                                Sign up
                            </BaseButton>
                        </div>
                    </div>
                </div>
            </div>

        </header>
    </div>
</template>

<script>
import BaseButton from '../../Components/Base/BaseButton.vue';
import NavLink from '../../Components/NavLink.vue';
import { MagnifyingGlassIcon, SunIcon, MoonIcon, ArrowRightEndOnRectangleIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/24/solid';
import { useTheme } from '../../composables/useTheme';

export default {
    name: 'ThemeToggle',
    setup() {
        const { state, toggle } = useTheme()
        return { state, toggle }
    },
    components: {
        BaseButton,
        NavLink,
        MagnifyingGlassIcon,
        SunIcon,
        MoonIcon,
        ArrowRightEndOnRectangleIcon,
        Bars3Icon,
        XMarkIcon
    },
    data() {
        return {
            isOpen: false
        }
    },
    methods: {
        toggleNav() {
            this.isOpen = !this.isOpen;
        }
    }
}
</script>
