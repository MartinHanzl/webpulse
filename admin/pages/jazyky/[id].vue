<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import { ref } from 'vue';
import { Switch } from '@headlessui/vue';
import { useLanguagesStore } from '~/stores/languages';
import {storeToRefs} from "pinia";

const languagesStore = useLanguagesStore();
const activeTranslations = storeToRefs(languagesStore.languages);

const pageHeadingData = {
  title: 'Jazyky',
  breadcrumbLinks: [
    {
      text: 'Jazyky',
      href: '/jazyky',
    },
    {
      text: 'Přidat jazyk',
      href: '/jazyky/pridat',
    },
  ],
  actionButton: {
    text: 'Uložit',
    type: 'save'
  }
};
const currentTab = ref('#informace');
const tabs = [
  { name: 'Informace', href: '#informace', current: true },
  { name: 'Překlady', href: '#preklady', current: false },
];

function changeTab(tab: any) {
  currentTab.value = tab;
  tabs.forEach((tab) => {
    tab.current = tab.href === currentTab.value;
  });
}

const id = ref(0);
const pending = ref(false);
const form = ref({
  name: '',
  code: '',
  iso: '',
  active: true
});
async function saveItem() {
  pending.value = true;
  await useFetch('/api/admin/language', {
    method: 'POST',
    body: form.value,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  }).then(() => {
    useRouter().push('/jazyky');
    pending.value = false;
  })
}

async function loadItem() {
  pending.value = true;
  await useFetch('/api/admin/language/' + id.value, {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  }).then((response) => {
    form.value.name = response.data.value.data.name;
    form.value.code = response.data.value.data.code;
    form.value.iso = response.data.value.data.iso;
    form.value.active = response.data.value.data.active;
    pending.value = false;
  })
}
onMounted(() => {
  id.value = useRoute().params.id !== 'pridat' ? useRoute().params.id : 0;
  if (id.value !== 0) {
    loadItem();
  }
  changeTab('#informace');
});
</script>
<template>
  <div>
    <PageHeading :page-heading-data="pageHeadingData" @save-item="saveItem" />
    languages: {{ activeTranslations }}
    <div v-if="!pending">
      <div class="sm:hidden">
        <label
          for="tabs"
          class="sr-only"
        >Select a tab</label>
        <select
          id="tabs"
          name="tabs"
          class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
        >
          <option
            v-for="tab in tabs"
            :key="tab.name"
            :selected="tab.current"
            @click="changeTab(tab.href)"
          >
            {{ tab.name }}
          </option>
        </select>
      </div>
      <div class="hidden sm:block">
        <nav
          class="flex space-x-4"
          aria-label="Tabs"
        >
          <a
            v-for="tab in tabs"
            :key="tab.name"
            :href="tab.href"
            :class="[tab.current ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']"
            :aria-current="tab.current ? 'page' : undefined"
            @click="changeTab(tab.href)"
          >{{ tab.name }}</a>
        </nav>
        <div v-if="currentTab === '#informace' && !pending">
          {{ form }}
          <div class="space-y-12">
            <div>
              <h2 class="text-base font-semibold leading-7 text-gray-900">
                Základní informace
              </h2>
              <p class="mt-1 text-sm leading-6 text-gray-600">
                Zde vyplníze základní informace o daném jazyce.
              </p>
              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-2">
                  <label
                    for="first-name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                  >Název</label>
                  <div class="mt-2">
                    <input
                      id="first-name"
                      v-model="form.name"
                      type="text"
                      name="first-name"
                      autocomplete="given-name"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    >
                  </div>
                </div>
              </div>
              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-2">
                  <label
                    for="last-name"
                    class="block text-sm font-medium leading-6 text-gray-900"
                  >Kód</label>
                  <div class="mt-2">
                    <input
                      id="last-name"
                      v-model="form.code"
                      type="text"
                      name="last-name"
                      autocomplete="family-name"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    >
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label
                    for="email"
                    class="block text-sm font-medium leading-6 text-gray-900"
                  >Iso kód</label>
                  <div class="mt-2">
                    <input
                      id="email"
                      v-model="form.iso"
                      name="email"
                      type="email"
                      autocomplete="email"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                    >
                  </div>
                </div>
              </div>
              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-full">
                  <label
                    for="postal-code"
                    class="block text-sm font-medium leading-6 text-gray-900"
                  >Aktivní</label>
                  <div class="mt-2">
                    <Switch
                      v-model="form.active"
                      :class="[form.active ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']"
                    >
                      <span class="sr-only">Use setting</span>
                      <span :class="[form.active ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
                        <span
                          :class="[form.active ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                          aria-hidden="true"
                        >
                          <svg
                            class="h-3 w-3 text-gray-400"
                            fill="none"
                            viewBox="0 0 12 12"
                          >
                            <path
                              d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            />
                          </svg>
                        </span>
                        <span
                          :class="[form.active ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                          aria-hidden="true"
                        >
                          <svg
                            class="h-3 w-3 text-blue-600"
                            fill="currentColor"
                            viewBox="0 0 12 12"
                          >
                            <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                          </svg>
                        </span>
                      </span>
                    </Switch>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-else-if="currentTab === '#preklady' && !pending">
          překlady
        </div>
      </div>
    </div>
  </div>
</template>