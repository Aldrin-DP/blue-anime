<template>
    <div class="bg-gray-900 text-gray-200 flex items-center h-20">
        <header class="w-full h-full mx-20 flex justify-between items-center">
            <div class="flex gap-2">
                <img src="/public/images/ba_logo.png" width="20px" alt="">
                <h2 class="font-bold">Blue Anime</h2>
            </div>

            <div class="flex gap-5">
                <li>Home</li>
                <li>Find Anime</li>
            </div>

            <div>
                <input type="text" class="border">
            </div>
            <button>Dark Mode</button>
            <a href="">Sign Up</a>
            <div>
                <BaseButton
                    variant="primary"
                    disabled
                >
                    Sign up
                </BaseButton>
            </div>
        </header>
    </div>
</template>

<script>
import BaseButton from '../../Components/Base/BaseButton.vue';
export default {
    components: {
        BaseButton
    },
    data() {
        return {

        }
    }
}
</script>
