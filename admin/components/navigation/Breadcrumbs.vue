<template>
  <div>
    <nav
      class="hidden sm:flex"
      aria-label="Breadcrumb"
    >
      <ol
        role="list"
        class="flex items-center space-x-4"
      >
        <li>
          <div class="flex">
            <NuxtLink
              :to="links[0].href"
              class="text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              {{ links[0].text }}
            </NuxtLink>
          </div>
        </li>
        <li v-if="links[1]">
          <div class="flex items-center">
            <ChevronRightIcon
              class="h-5 w-5 flex-shrink-0 text-gray-400"
              aria-hidden="true"
            />
            <NuxtLink
              :to="links[1].href"
              class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              {{ links[1].text }}
            </NuxtLink>
          </div>
        </li>
        <li v-if="links[2]">
          <div class="flex items-center">
            <ChevronRightIcon
              class="h-5 w-5 flex-shrink-0 text-gray-400"
              aria-hidden="true"
            />
            <NuxtLink
              :to="links[2].href"
              class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              {{ links[2].text }}
            </NuxtLink>
          </div>
        </li>
      </ol>
    </nav>
  </div>
</template>
<script setup>
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/20/solid";
</script>
<script>
export default {
  props: {
    links: {
      type: Array,
      required: true
    }
  }
}
</script>