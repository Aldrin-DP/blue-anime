<template>
  <Head title="Edit Profile - " />

  <div class="p-4 lg:p-10 xl:px-15 xl:py-10">
    <!-- Change Account -->
    <section
      class="lg:my-5 w-full bg-linear-to-tr from-white/30 to-white/40 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 shadow-lg dark:shadow p-5 rounded-lg"
    >
      <BaseHeading size="large"> Change Account</BaseHeading>
      <BaseText> Change your Username and Email </BaseText>

      <form @submit.prevent="updateAccount" class="mt-5">
        <div class="mb-2">
          <BaseLabel> Username </BaseLabel>
          <input
            v-model="accountForm.username"
            type="text"
            class="px-3 py-1.5 w-full rounded-lg border dark:border-bg-transparent border-gray-200 dark:border-blue-800 focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
            placeholder="vegeta@gmail.com"
            required
          />
          <BaseInputError :message="accountForm.errors.username" />
        </div>

        <div class="mb-2">
          <BaseLabel> Email </BaseLabel>
          <input
            v-model="accountForm.email"
            type="text"
            class="px-3 py-1.5 w-full rounded-lg border dark:border-bg-transparent border-gray-200 dark:border-blue-800 focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
            placeholder="vegeta@gmail.com"
            required
          />
          <BaseInputError :message="accountForm.errors.email" />
        </div>

        <div class="flex justify-between items-center mt-5">
          <div
            v-if="$page.props.flash.accountUpdated"
            class="text-green-500 font-semibold"
          >
            {{ $page.props.flash.accountUpdated }}
          </div>
          <BaseButton
            loadingText="Processing..."
            :isProcessing="accountForm.processing"
            variant="primary"
          >
            Save Changes
          </BaseButton>
        </div>
      </form>
    </section>
    <!-- Change Password -->
    <section
      class="my-5 w-full bg-linear-to-tr from-white/30 to-white/40 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 shadow-lg dark:shadow p-5 rounded-lg"
    >
      <BaseHeading size="large"> Change Password</BaseHeading>
      <BaseText> Dive back in and pick up where you left off. </BaseText>

      <form @submit.prevent="updatePassword" class="mt-5">
        <div class="mb-3">
          <BaseLabel> Current Password </BaseLabel>
          <input
            v-model="passwordForm.current_password"
            type="password"
            class="px-3 py-1.5 w-full rounded-lg border dark:border-transparent border-gray-200 focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
            placeholder="********"
            required
          />
          <BaseInputError :message="passwordForm.errors.current_password" />
        </div>
        <div class="mb-3">
          <BaseLabel> New Password </BaseLabel>
          <input
            v-model="passwordForm.password"
            type="password"
            class="px-3 py-1.5 w-full rounded-lg border dark:border-transparent border-gray-200 focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
            placeholder="********"
            required
          />
          <BaseInputError :message="passwordForm.errors.password" />
        </div>
        <div class="mb-3">
          <BaseLabel> Confirm Password </BaseLabel>
          <input
            v-model="passwordForm.password_confirmation"
            type="password"
            class="px-3 py-1.5 w-full rounded-lg border dark:border-transparent border-gray-200 focus:outline-blue-300 dark:outline-none bg-gray-50 dark:bg-gray-300 opacity-90"
            placeholder="********"
            required
          />
          <BaseInputError
            :message="passwordForm.errors.password_confirmation"
          />
        </div>
        <div class="flex justify-between items-center mt-5">
          <div
            v-if="$page.props.flash.passwordUpdated"
            class="text-green-500 font-semibold"
          >
            {{ $page.props.flash.passwordUpdated }}
          </div>
          <BaseButton
            loadingText="Processing..."
            :isProcessing="passwordForm.processing"
            variant="primary"
          >
            Save Changes
          </BaseButton>
        </div>
      </form>
    </section>

    <!-- Delete Account -->
    <section
      class="my-5 w-full bg-linear-to-tr from-white/30 to-white/40 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 shadow-lg dark:shadow p-5 rounded-lg"
    >
      <BaseHeading size="large"> Delete Account</BaseHeading>
      <BaseText> Dive back in and pick up where you left off. </BaseText>

      <div class="flex justify-end">
        <BaseButton variant="danger"> DELETE MY ACCOUNT </BaseButton>
      </div>
    </section>
  </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
  data() {
    return {
      accountForm: useForm({
        username: this.$page.props.auth.user.username,
        email: this.$page.props.auth.user.email,
      }),
      passwordForm: useForm({
        current_password: null,
        password: null,
        password_confirmation: null,
      }),
    };
  },
  methods: {
    updateAccount() {
      this.accountForm.patch("/profile/account", {
        preserveScroll: true,
        preserveState: true,
      });
    },
    updatePassword() {
      this.passwordForm.patch("/profile/password", {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          this.passwordForm.reset();
        },
      });
    },
  },
};
</script>
