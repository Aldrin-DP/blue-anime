<template>
    <div class="bg-gray-950 text-gray-400 flex items-center h-20">
        <header class="w-full h-full mx-20 flex justify-between items-center">
            <div class="flex items-center gap-1">
                <Link href="/">
                    <img src="/public/images/sea.png" width="55px" alt="logo" class="hover:scale-115 transition-all duration-300">
                </Link>
                <h2 class="font-bold text-2xl">Sea Anime</h2>
            </div>

            <div tabindex="0"
                class="flex items-center gap-2 border px-3 py-1 rounded-full border-gray-700 focus-within:outline-2 focus-within:outline-blue-700">
                <input
                    type="text"
                    class="outline:none focus:border-none focus:outline-0"
                    placeholder="Search anime..."
                >
                <MagnifyingGlassIcon class="size-5"/>
            </div>

            <div class="flex items-center gap-5">
                <NavLink href="/"> Surface </NavLink>
                <NavLink href="/"> Explore the Deep </NavLink>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-0.5 text-[15px] font-semibold px-3 py-1.5 border border-transparent hover:text-gray-100 hover:border-gray-700 hover:rounded hover:shadow hover:shadow-blue-950 transition-all duration-300">
                    <MoonIcon class="size-4"/>
                    <button>Abyss</button>
                </div>

                <NavLink href="/" class="flex items-center gap-px">
                    <ArrowRightEndOnRectangleIcon class="size-5" />
                    Log in
                </NavLink>
                <div>
                    <BaseButton
                        variant="primary"
                        disabled
                    >
                        Join the Deep
                    </BaseButton>
                </div>
            </div>
        </header>
    </div>
</template>

<script>
import BaseButton from '../../Components/Base/BaseButton.vue';
import NavLink from '../../Components/NavLink.vue';
import { MagnifyingGlassIcon, SunIcon, MoonIcon, ArrowRightEndOnRectangleIcon } from '@heroicons/vue/24/solid';


export default {
    components: {
        BaseButton,
        NavLink,
        MagnifyingGlassIcon,
        SunIcon,
        MoonIcon,
        ArrowRightEndOnRectangleIcon
    },
    data() {
        return {

        }
    }
}
</script>
