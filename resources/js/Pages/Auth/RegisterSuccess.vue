<template>
    <Head title="Register Successfully -" />
    <div class="flex justify-center w-full">
        <div
            class="my-5 w-full max-w-115 bg-gradient-to-tr from-gray-100 to-blue-100 dark:border dark:border-gray-700 dark:bg-gradient-to-br dark:from-blue-950 dark:to-teal-950 shadow-lg dark:shadow-lg p-5 rounded-lg"
        >
            <h2
                class="text-3xl tracking-wider font-bold text-gray-700 dark:text-gray-300"
            >
                Welcome <br class="sm:hidden" />
                to the Deep
            </h2>
            <p class="mt-3 text-gray-600 dark:text-gray-400">
                Account created. You can now login and start exploring thousands
                of anime.
            </p>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Redirecting to login...
            </p>

            <Link
                href="/login"
                class="block mt-10 text-blue-600 hover:text-blue-700 cursor-pointer"
            >
                Go to Login page.
            </Link>
        </div>
    </div>
</template>

<script>
import BaseButton from "../../Components/Base/BaseButton.vue";
import { router } from "@inertiajs/vue3";

export default {
    components: {
        BaseButton,
    },
    data() {
        return {};
    },
    methods: {
        redirectToLogin() {
            setTimeout(() => {
                router.visit("/login");
            }, 3000);
        },
    },
    mounted() {
        this.redirectToLogin();
    },
};
</script>
