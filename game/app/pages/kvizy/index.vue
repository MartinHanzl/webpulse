<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { ChevronUpIcon } from '@heroicons/vue/20/solid';
import {
  AdjustmentsHorizontalIcon,
  FaceFrownIcon,
  MagnifyingGlassIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import { useApi } from '~/composables/useApi';
import { useAsyncData } from '#app';

const search = ref('');

const api = useApi();
const { data: quizzesData } = useAsyncData(`quiz`, () => api.quiz.quizzes(search.value));
const { data: filtersData } = useAsyncData(`quiz-filters`, () => api.quiz.filter());
const selectedFilters = ref([]);

function loadQuizzes() {
  api.quiz
    .quizzes(search.value, selectedFilters.value)
    .then((response) => {
      quizzesData.value = response;
    })
    .catch((error) => {
      console.error('Error fetching quizzes:', error);
    });
}

function addToFilters(filter: string) {
  if (selectedFilters.value.includes(filter)) {
    selectedFilters.value = selectedFilters.value.filter((f) => f !== filter);
  } else {
    selectedFilters.value.push(filter);
  }

  loadQuizzes();
}

function resetFilters() {
  selectedFilters.value = [];
  search.value = '';
  loadQuizzes();
}

watch(search, () => loadQuizzes(), { immediate: true });

useHead(() => {
  return {
    title: 'Kvízy',
    meta: [
      {
        name: 'description',
        content: 'Zde najdete všechny kvízy, které jsou dostupné na našem webu.',
      },
    ],
  };
});
</script>

<template>
  <div>
    <div class="mb-10 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:p-8">
      <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="text-center lg:text-left">
          <BasePropsHeading type="h1" margin-bottom="mb-2" class="!text-center lg:!text-left">
            Kvízy
          </BasePropsHeading>
          <p class="font-quicksand text-sm text-gray-500">
            <span v-if="quizzesData">{{ quizzesData.length }} kvízů k dispozici</span>
          </p>
        </div>

        <div class="relative w-full lg:max-w-sm">
          <MagnifyingGlassIcon
            class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
          />
          <input
            v-model="search"
            type="text"
            placeholder="Hledat kvízy..."
            class="w-full rounded-full border-0 bg-background py-3 pl-12 pr-10 font-quicksand text-sm text-text ring-1 ring-inset ring-gray-200 transition placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-primary"
          />
          <button
            v-if="search"
            type="button"
            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
            @click="search = ''"
          >
            <XMarkIcon class="h-4 w-4" />
          </button>
        </div>
      </div>

      <Disclosure v-if="filtersData?.length" v-slot="{ open }" as="div" class="mt-6">
        <div class="flex flex-wrap items-center gap-3">
          <DisclosureButton
            class="inline-flex items-center gap-x-2 rounded-full bg-primaryLight/40 px-4 py-2 text-sm font-medium text-primaryDark transition hover:bg-primaryLight focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
          >
            <AdjustmentsHorizontalIcon class="h-5 w-5" />
            <span>Filtrovat podle tagů</span>
            <span
              v-if="selectedFilters.length"
              class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-primaryDark px-1.5 text-xs text-white"
            >
              {{ selectedFilters.length }}
            </span>
            <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 transition" />
          </DisclosureButton>

          <button
            v-if="selectedFilters.length || search"
            type="button"
            class="inline-flex items-center gap-x-1 text-xs text-gray-500 underline-offset-2 transition hover:text-primaryDark hover:underline"
            @click="resetFilters"
          >
            <XMarkIcon class="h-4 w-4" />
            Zrušit filtry
          </button>
        </div>

        <DisclosurePanel class="mt-4 flex flex-wrap gap-2">
          <button
            v-for="(filter, index) in filtersData"
            :key="index"
            type="button"
            :class="[
              selectedFilters.includes(filter.name)
                ? 'bg-primaryLight/60 text-primaryDark ring-primaryLight'
                : 'bg-white text-gray-600 ring-gray-200 hover:bg-gray-50',
              'inline-flex items-center gap-x-2 rounded-full px-3 py-1.5 text-xs ring-1 ring-inset transition md:text-sm',
            ]"
            @click="addToFilters(filter.name)"
          >
            <span>{{ filter.name }}</span>
            <span
              :class="[
                selectedFilters.includes(filter.name)
                  ? 'bg-primaryDark text-white'
                  : 'bg-background text-gray-500',
                'inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1 text-[11px]',
              ]"
            >
              {{ filter.count }}
            </span>
          </button>
        </DisclosurePanel>
      </Disclosure>
    </div>

    <div v-if="quizzesData?.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
      <QuizCard v-for="(quiz, index) in quizzesData" :key="index" :quiz="quiz" />
    </div>
    <div
      v-else-if="quizzesData"
      class="flex flex-col items-center justify-center rounded-3xl bg-white p-12 text-center shadow-sm ring-1 ring-gray-100"
    >
      <FaceFrownIcon class="mb-4 h-12 w-12 text-gray-300" />
      <BasePropsHeading type="h6" margin-bottom="mb-2">Žádné kvízy</BasePropsHeading>
      <p class="font-quicksand text-sm text-gray-500">
        Zkuste změnit hledaný výraz nebo zrušit filtry.
      </p>
      <BaseButton
        v-if="selectedFilters.length || search"
        class="mt-6"
        size="sm"
        @click="resetFilters"
      >
        Zrušit filtry
      </BaseButton>
    </div>
  </div>
</template>
