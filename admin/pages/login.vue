<script setup lang="ts">
import {definePageMeta} from "#imports";
import { storeToRefs } from 'pinia'; // import storeToRefs helper hook from pinia
import { useAuthStore } from '~/stores/auth';

const { authenticateUser } = useAuthStore(); // use authenticateUser action from  auth store
const { authenticated } = storeToRefs(useAuthStore());

const router = useRouter();
const form = ref({
  email: '',
  password: ''
});

const login = async () => {
  await authenticateUser(form.value);
  if (authenticated) {
    await router.push('/');
  }
};
definePageMeta({
  layout: 'login'
});
</script>

<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img
        class="mx-auto h-10 w-auto"
        src="https://tailwindui.com/img/logos/mark.svg?color=blue&shade=600"
        alt="Your Company"
      >
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div
        class="space-y-6"
      >
        <div>
          <label
            for="email"
            class="block text-sm font-medium leading-6 text-gray-900"
          >Emailová adresa</label>
          <div class="mt-2">
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="none"
              required=""
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            >
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
            >Heslo</label>
          </div>
          <div class="mt-2">
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="none"
              required=""
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            >
          </div>
        </div>

        <div>
          <button
            class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            @click.prevent="login"
          >
            Přihlásit se
          </button>
        </div>
      </div>
    </div>
  </div>
</template>