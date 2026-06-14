<template>
    <Head title="Hello" />
    <div>
        <div class="p-5 lg:p-10 xl:px-15 xl:py-10">
            <section class="my-5 w-full bg-gradient-to-tr from-gray-100 to-blue-100 dark:border dark:border-gray-700 dark:bg-gradient-to-br dark:from-blue-950 dark:to-teal-950 shadow-lg dark:shadow-lg p-5 rounded-lg">
                <BaseHeading size="large"> Change Username and Email</BaseHeading>
                <BaseText> Dive back in and pick up where you left off. </BaseText>
                <form @submit.prevent="testing" class="mt-5">
                    <div class="mb-3">
                        <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Username</label>
                        <input v-model="form.username" type="text" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="vegeta@gmail.com" required>
                        <small v-if="form.errors.username" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.email }} </small>
                    </div>
                    <div class="mb-3">
                        <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Email</label>
                        <input v-model="form.email" type="text" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="vegeta@gmail.com" required>
                        <small v-if="form.errors.email" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.email }} </small>
                    </div>
                    <div class="flex justify-end mt-5">
                        <BaseButton variant="primary"> Save Changes </BaseButton>
                    </div>
                </form>
            </section>

            <section class="my-5 w-full bg-gradient-to-tr from-gray-100 to-blue-100 dark:border dark:border-gray-700 dark:bg-gradient-to-br dark:from-blue-950 dark:to-teal-950 shadow-lg dark:shadow-lg p-5 rounded-lg">
                <BaseHeading size="large"> Change Password</BaseHeading>
                <BaseText> Dive back in and pick up where you left off. </BaseText>
                <form @submit.prevent="testing" class="mt-5">
                    <div class="mb-3">
                        <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Current Password</label>
                        <input v-model="form.username" type="password" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="********" required>
                        <small v-if="form.errors.username" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.email }} </small>
                    </div>
                    <div class="mb-3">
                        <label class="block uppercase text-xs tracking-wide font-semibold text-gray-600 dark:text-gray-400 mb-1.5">New Password</label>
                        <input v-model="form.email" type="password" class="px-3 py-1.5 w-full rounded-lg border dark:border-none border-gray-200 dark:border-blue-800  focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90" placeholder="********" required>
                        <small v-if="form.errors.email" class="text-red-400 tracking-wide font-medium block ml-1 mt-1"> {{ form.errors.email }} </small>
                    </div>
                    <div class="flex justify-end mt-5">
                        <BaseButton variant="primary"> Save Changes </BaseButton>
                    </div>
                </form>
            </section>

            <section class="my-5 w-full bg-gradient-to-tr from-gray-100 to-blue-100 dark:border dark:border-gray-700 dark:bg-gradient-to-br dark:from-blue-950 dark:to-teal-950 shadow-lg dark:shadow-lg p-5 rounded-lg">
                <BaseHeading size="large"> Delete Account</BaseHeading>
                <BaseText> Dive back in and pick up where you left off. </BaseText>


                    <div class="flex justify-end">
                        <BaseButton variant="danger"> DELETE MY ACCOUNT </BaseButton>
                    </div>
            </section>
        </div>
    </div>
</template>

<script>
    import { useForm } from '@inertiajs/vue3';
    import BaseButton from '../../../Components/Base/BaseButton.vue';

    export default {
        components: {
            BaseButton
        },
        data() {
            return {
                form: useForm({
                    username:  this.$page.props.auth.user.username,
                    email: this.$page.props.auth.user.email,
                    password: null
                })
            }
        },
        methods: {
            testing() {
                this.form.put('/account/settings');
            }
        }
    }
</script>
