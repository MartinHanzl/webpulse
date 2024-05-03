<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import {defineProps, watch} from 'vue';

const defPage = defineModel('page', {
  type: Number,
  default: 1
});
const page = ref(defPage.value);

const props = defineProps({
  pagination: {
    type: Object,
    required: true
  }
});

function changePage(pageObject) {
  if (pageObject.page !== 0 || pageObject.page !== page.value) {
    page.value = pageObject.page;
  }
}

const previousIsDisabled = computed(() => {
  return page.value === 1;
});
const nextIsDisabled = computed(() => {
  return props.pagination.lastPage === page.value;
});

function previousPage() {
  if (!previousIsDisabled.value) page.value = page.value - 1;
}

function nextPage() {
  if (!nextIsDisabled.value) page.value = page.value + 1;
}
const paginationPages = ref([
  { current: false, page: 1, text: '1' },
  { current: false, page: 0, text: '...' },
  { current: false, page: 3, text: '3' },
]);

function generatePages() {
  const pages = [];

  pages.push({ current: 1 === page.value, page: 1, text: 1 });

  if (1 < page.value - 2) {
    pages.push({ current: false, page: 0, text: '...' });
  }
  if (1 < page.value - 1) {
    for (let i = page.value - 1; i <= page.value + 1; i++) {
      if (i < props.pagination.lastPage + 1) {
        pages.push({ current: i === page.value, page: i, text: i.toString() });
      }
    }
  } else {
    for (let i = 2; i <= 4; i++) {
      pages.push({current: i === page.value, page: i, text: i.toString()});
    }
  }
  if (page.value + 2 < props.pagination.lastPage) {
    pages.push({ current: false, page: 0, text: '...' });
  }
  if (page.value - 1 < props.pagination.lastPage - 1) {
    for (let i = props.pagination.lastPage; i <= props.pagination.lastPage; i++) {
      pages.push({current: i === page.value, page: i, text: i.toString()});
    }
  }
  paginationPages.value = pages;
}

watch(page, () => {
  generatePages();
  // defPage.value = page.value;
});
</script>

<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white py-3 px-2 md:px-0">
    <div class="flex flex-1 justify-between sm:hidden">
      <span
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        @click="previousPage"
      ><ChevronLeftIcon /></span>
      <span
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        @next="nextPage"
      ><ChevronRightIcon /></span>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-400">
          Zobrazeno
          {{ ' ' }}
          <span class="font-medium">{{ pagination.currentPage }} ─ {{ pagination.perPage * pagination.currentPage }}</span>
          {{ ' ' }}
          z
          {{ ' ' }}
          <span class="font-medium">{{ pagination.total }}</span>
          {{ ' ' }}
          výsledků
        </p>
      </div>
      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-sm"
          aria-label="Pagination"
        >
          <span
            :class="[previousIsDisabled ? 'cursor-not-allowed' : 'cursor-pointer', 'relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0']"
            @click="previousPage"
          >
            <span class="sr-only">Předchozí</span>
            <ChevronLeftIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </span>
          <!-- Current: "z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600", Default: "text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
          <span
            v-for="(pageObject, index) in paginationPages"
            :key="index"
            aria-current="page"
            :class="[pageObject.current === true ? 'bg-blue-600 text-white' : '', 'cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0']"
            @click="changePage(pageObject)"
          >{{ pageObject.text }}</span>
          <span
            class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
            @click="nextPage"
          >
            <span class="sr-only">Následující</span>
            <ChevronRightIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </span>
        </nav>
      </div>
    </div>
  </div>
</template>