<script setup lang="ts">
defineProps({
  title: {
    type: String,
    default: "Default Title",
  },
  description: {
    type: String,
    default: "Default Description",
  },
});
</script>

<template>
  <div
    class="bg-primary border border-secondary p-6 rounded-lg flex-auto w-1/3 lg:w-1/4"
  >
    <BasePropsParagraph color="light" size="base" bold="bold" class="mb-2">
      {{ title }}</BasePropsParagraph
    >
    <BasePropsParagraph color="light" size="base">
      {{ description }}</BasePropsParagraph
    >
  </div>
</template>
