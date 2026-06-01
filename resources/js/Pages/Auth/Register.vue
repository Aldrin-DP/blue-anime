<template>
    <Head title="Register - " />
    <div class="flex flex-col items-center">
        <div class="w-full max-w-115 flex my-10 flex-col items-center">
            <BaseHeading> Begin Your Dive </BaseHeading>
            <BaseText> Begin your dive and explore thousands of anime. </BaseText>

            <form @submit.prevent="register" class="my-5 w-full bg-gradient-to-tr from-gray-100 to-blue-100 dark:border dark:border-gray-700 dark:bg-gradient-to-br dark:from-blue-950 dark:to-teal-950 shadow-lg dark:shadow-lg p-5 rounded-lg">
                <div class="mb-3">
                    <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Username</label>
                    <input v-model="form.username" type="text" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="Vegeta77" required>
                    <small v-if="form.errors.username" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.username }} </small>
                </div>
                <div class="mb-3">
                    <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Email</label>
                    <input v-model="form.email" type="text" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="vegeta@gmail.com" required>
                    <small v-if="form.errors.email" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.email }} </small>
                </div>
                <div class="mb-3">
                    <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Password</label>
                    <input
                        v-model="form.password"
                        class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
                        placeholder="********" type="password" required>
                    <small v-if="form.errors.password" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.password }} </small>
                </div>
                <div class="mb-3">
                    <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Confirm Password</label>
                    <input v-model="form.password_confirmation" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="********" type="password" required>
                    <small v-if="form.errors.password_confirmation" class="text-red-400 tracking-wide font-medium block ml-1 mt-1">Confirm Password is required.</small>
                </div>
                <BaseButton
                    variant="primary"
                    :isProcessing="form.processing"
                    class="w-full mt-2 py-3 text-[16px]"
                >
                    Sign up
                </BaseButton>

            </form>
            <p class="text-gray-600 dark:text-gray-400">
                Already have an account?
                <Link href="/login" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Log in here
                </Link>
            </p>
        </div>
    </div>
</template>

<script>
import BaseButton from '../../Components/Base/BaseButton.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { BaseButton },
    data() {
        return {
            form: useForm({
                username: null,
                email: null,
                password: null,
                password_confirmation: null
            })
        }
    },
    methods: {
        register() {
            this.form.post('/register', {
                onError: () => this.form.reset('password', 'password_confirmation'),
                onBefore: () => this.form.clearErrors(),
            });
            console.log('okay');
        }
    }
}
</script>
