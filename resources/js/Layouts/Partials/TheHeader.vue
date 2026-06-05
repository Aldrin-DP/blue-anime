<template>
    <div class="bg-gray-50/90 text-gray-800 dark:bg-gray-950/80 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800 flex items-center sticky backdrop-blur-md top-0 z-10000">
        <header class="max-w-325 w-full lg:flex lg:mx-10 xl:mx-15 lg:items-center">
            <div class="flex h-18 justify-between items-center w-full px-3 lg:w-auto lg:px-0">

                <AppLogo />

                 <div class="lg:hidden flex justify-between items-center gap-5">
                    <button @click="toggle" class="">
                        <div v-if="state.isDark" class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-100 hover:dark:shadow-gray-900 transition-all duration-300 cursor-pointer">
                            <SunIcon class="size-3.5"/>
                            <button class="cursor-pointer">Shallow</button>
                        </div>
                        <div v-else class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-100 hover:dark:shadow-gray-900 transition-all duration-300 cursor-pointer">
                            <MoonIcon class="size-3.5"/>
                            <button class="cursor-pointer">Abyss</button>
                        </div>
                    </button>
                    <!-- Hamburger -->
                    <button @click="toggleNav" class="lg:hidden cursor-pointer border border-transparent  px-1 py-1 hover:text-gray-700 hover:border-gray-300 hover:dark:border-gray-800 hover:dark:text-gray-100 hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300">
                        <XMarkIcon v-if="isOpen" class="size-6.5" />
                        <Bars3Icon v-else class="size-6.5" />
                    </button>
                 </div>
            </div>

            <div
                :class="isOpen ? 'flex' : 'hidden'"
                class="lg:flex flex-col justify-between border-b absolute w-full lg:w-auto px-3 lg:px-0 py-4 lg:py-0  bg-gray-50 dark:bg-gray-950 lg:bg-transparent lg:dark:bg-transparent border-t border-gray-300 dark:border-gray-800 lg:flex-1 h-auto z-20 lg:flex-row lg:relative lg:z-0 lg:border-none"
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
                        <NavLink class="w-full lg:w-auto" href="/explore"> Explore</NavLink>
                    </div>

                    <div class="flex flex-col items-center lg:justify-between gap-2 mt-4 lg:mt-0 pt-3 lg:pt-0 border-t border-gray-300 dark:border-gray-800 lg:flex-row lg:border-none">
                        <button
                            @click="toggle"
                            v-if="!isOpen"
                        >
                            <div
                                v-if="state.isDark"
                                class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-300 hover:dark:shadow-gray-900 transition-all duration-300 cursor-pointer"
                            >
                                <SunIcon class="size-3.5"/>
                                <button class="cursor-pointer">Shallow</button>
                            </div>
                            <div
                                v-else
                                class="flex items-center gap-0.5 text-[15px] text-gray-700 dark:text-gray-300  font-semibold tracking-wide px-3 py-1.5 border border-transparent hover:dark:text-gray-100 hover:text-gray-800 hover:border-gray-300 hover:dark:border-gray-700 hover:rounded hover:shadow-lg hover:shadow-gray-200 hover:dark:shadow-gray-900 transition-all duration-300 cursor-pointer"
                            >
                                <MoonIcon class="size-3.5"/>
                                <button class="cursor-pointer">Abyss</button>
                            </div>
                        </button>

                        <NavLink v-if="!$page.props.auth.user" href="/login" class="flex items-center justify-center w-full lg:w-auto gap-1">
                            <!-- <ArrowRightEndOnRectangleIcon class="size-5" /> -->
                            Log in
                        </NavLink>

                        <div class="w-full lg:w-auto relative ">
                            <div v-if="$page.props.auth.user" class="lg:cursor-pointer lg:border rounded-xl lg:border-gray-300 lg:dark:border-gray-800 hover:dark:text-gray-100 hover:text-gray-800">
                                <div @click="toggleAccountMenu" class="lg:px-4 lg:py-1.5 flex items-center gap-2 pb-3 border-b border-gray-300 dark:border-gray-700 lg:border-none">
                                    <p class=" px-2 bg-blue-700 rounded-full text-gray-300 font-bold text-lg">
                                        {{ username.charAt(0).toUpperCase() }}
                                    </p>
                                    <h4 class="lg:max-w-24 truncate">{{ username }}</h4>
                                </div>
                                <div {{ :class="isAccountMenuOpen ? 'block' : 'block lg:hidden'" }} class="flex flex-col mt-3 lg:px-2 lg:border lg:rounded-xl lg:bg-gray-100/90 lg:dark:bg-gray-950/80 lg:backdrop-filter-blur lg:border-gray-300 lg:dark:border-gray-800 lg:absolute lg:py-2 lg:w-50  lg:top-13 lg:right-0">
                                    <NavLink>Account Settings</NavLink>
                                    <NavLink>Library</NavLink>
                                    <div class="lg:mx-2 lg:mb-1">
                                        <BaseButton
                                            @click="logout"
                                            :isProcessing="form.processing"
                                            variant="danger"
                                            class="mt-5 w-full lg:mt-2"> Logout </BaseButton>
                                    </div>
                                </div>

                            </div>
                            <Link href="/register" v-else>
                                <BaseButton

                                    variant="primary"
                                    class="w-full lg:w-auto"
                                >
                                    Sign up
                                </BaseButton>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

        </header>
    </div>
</template>

<script>
import AppLogo from './AppLogo.vue';
import BaseButton from '../../Components/Base/BaseButton.vue';
import NavLink from '../../Components/NavLink.vue';
import { MagnifyingGlassIcon, SunIcon, MoonIcon, ArrowRightEndOnRectangleIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/24/solid';
import { useTheme } from '../../composables/useTheme';
import { useForm } from '@inertiajs/vue3';

export default {
    name: 'ThemeToggle',
    setup() {
        const { state, toggle } = useTheme()
        return { state, toggle }
    },
    components: {
        AppLogo,
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
            isOpen: false,
            isLoggedIn: false,
            isAccountMenuOpen: false,
            form: useForm()
        }
    },
    methods: {
        toggleNav() {
            this.isOpen = !this.isOpen;
        },
        toggleAccountMenu() {
            this.isAccountMenuOpen = !this.isAccountMenuOpen;
        },
        logout() {
            this.form.post('/logout', {
                onFinish: () => this.isAccountMenuOpen = false
            });
        }
    },
    computed: {
        username() {
            return this.$page.props.auth.user.username;
        }
    }
}
</script>
