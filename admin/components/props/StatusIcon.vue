<script setup lang="ts">
import { defineProps } from 'vue';
import { CheckIcon, XMarkIcon } from "@heroicons/vue/24/solid";
defineProps({
  status: {
    type: Boolean,
    required: true
  },
  proportions: {
    type: String,
    default: 'w-5 h-5'
  },
});
</script>
<template>
  <CheckIcon
    v-if="status"
    class="text-green-500"
    :class="proportions"
  />
  <XMarkIcon
    v-else
    class="text-red-500"
    :class="proportions"
  />
</template>