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
          <div class="flex items-center">
            <NuxtLink
              to="/"
              class="text-sm font-medium text-gray-400 hover:text-blue-400"
            >
              <HomeIcon
                class="h-5 w-5 flex-shrink-0 text-gray-400 hover:text-blue-400"
                aria-hidden="true"
              />
            </NuxtLink>
          </div>
        </li>
        <li v-if="breadcrumbLinks[0]">
          <div class="flex items-center">
            <ChevronRightIcon
              class="h-5 w-5 flex-shrink-0 text-gray-400"
              aria-hidden="true"
            />
            <NuxtLink
              :to="breadcrumbLinks[0].href"
              class="ml-4 text-sm font-medium text-gray-400 hover:text-blue-400"
            >
              {{ breadcrumbLinks[0].text }}
            </NuxtLink>
          </div>
        </li>
        <li v-if="breadcrumbLinks[1]">
          <div class="flex items-center">
            <ChevronRightIcon
              class="h-5 w-5 flex-shrink-0 text-gray-400"
              aria-hidden="true"
            />
            <NuxtLink
              :to="breadcrumbLinks[1].href"
              class="ml-4 text-sm font-medium text-gray-400 hover:text-blue-400"
            >
              {{ breadcrumbLinks[1].text }}
            </NuxtLink>
          </div>
        </li>
        <li v-if="breadcrumbLinks[2]">
          <div class="flex items-center">
            <ChevronRightIcon
              class="h-5 w-5 flex-shrink-0 text-gray-400"
              aria-hidden="true"
            />
            <NuxtLink
              :to="breadcrumbLinks[2].href"
              class="ml-4 text-sm font-medium text-gray-400 hover:text-blue-400"
            >
              {{ breadcrumbLinks[2].text }}
            </NuxtLink>
          </div>
        </li>
      </ol>
    </nav>
  </div>
</template>
<script setup>
import {HomeIcon, ChevronRightIcon} from "@heroicons/vue/20/solid";
</script>
<script>
export default {
  props: {
    breadcrumbLinks: {
      type: Array,
      required: true
    }
  }
}
</script>