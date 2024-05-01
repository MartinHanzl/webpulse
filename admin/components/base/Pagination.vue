<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

const page = defineModel('page', {
  type: Number,
  default: 1
})

defineProps({
  pagination: {
    type: Object,
    required: true
  }
});

function changePage() {
  page.value = Math.ceil(Math.random() *3);
}

function previousPage() {
  if (page.value > 1) {
    page.value = page.value - 1;
  }
}

function nextPage() {
  if (page.value < pagination.lastPage) {
    page.value = page.value + 1;
  }
}
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
        <p class="text-sm text-gray-700">
          Zobrazeno
          {{ ' ' }}
          <span class="font-medium">{{ pagination.perPage }}</span>
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
            class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
            @click="previousPage"
          >
            <span class="sr-only">Předchozí</span>
            <ChevronLeftIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </span>
          <!-- Current: "z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
          <span
            :aria-current="page"
            class="cursor-pointer relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            @click="changePage"
          >1</span>
          <span
            class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
            @click="changePage"
          >2</span>
          <span
            class="cursor-pointer relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex"
            @click="changePage"
          >3</span>
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