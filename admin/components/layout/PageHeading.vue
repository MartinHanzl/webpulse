<script setup lang="ts">
import {ref, defineProps, defineEmits} from 'vue';
import Breadcrumbs from "~/components/navigation/Breadcrumbs.vue";

defineProps({
  pageHeadingData: {
    type: Object,
    required: true
  }
});
const emit = defineEmits(['save-item'])
</script>

<template>
  <div>
    <Breadcrumbs :breadcrumb-links="pageHeadingData.breadcrumbLinks" />
    <div class="mt-2 sm:mb-4 lg:mb-8 md:flex md:items-center md:justify-between">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          {{ pageHeadingData.title }}
        </h2>
      </div>
      <div class="mt-4 flex flex-shrink-0 md:ml-4 md:mt-0">
        <NuxtLink
          v-if="pageHeadingData.actionButton && pageHeadingData.actionButton.type === 'link'"
          :to="pageHeadingData.actionButton.href"
          type="button"
          class="ml-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
        >
          {{ pageHeadingData.actionButton.text }}
        </NuxtLink>
        <button
          v-else-if="pageHeadingData.actionButton && pageHeadingData.actionButton.type === 'save'"
          type="button"
          class="ml-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          @click="emit('save-item')"
        >
          {{ pageHeadingData.actionButton.text }}
        </button>
      </div>
    </div>
  </div>
</template>