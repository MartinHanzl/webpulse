<script setup lang="ts">
const props = defineProps({
  type: {
    type: String,
    default: "h1",
    required: false,
  },
});
</script>

<template>
  <h1
    v-if="type === 'h1'"
    class="text-balance text-5xl font-bold tracking-tight text-white sm:text-6xl"
  >
    <slot />
  </h1>
  <h2
    v-if="type === 'h2'"
    class="text-balance text-4xl font-semibold tracking-tight text-white sm:text-5xl"
  >
    <slot />
  </h2>
  <h3
    v-if="type === 'h3'"
    class="text-balance text-3xl font-semibold tracking-tight text-white sm:text-4xl"
  >
    <slot />
  </h3>
  <h4
    v-if="type === 'h4'"
    class="text-balance text-2xl font-medium tracking-tight text-white sm:text-3xl"
  >
    <slot />
  </h4>
  <h5
    v-if="type === 'h5'"
    class="text-balance text-xl font-medium tracking-tight text-white sm:text-2xl"
  >
    <slot />
  </h5>
  <h6
    v-if="type === 'h6'"
    class="text-balance text-lg font-medium tracking-tight text-white sm:text-xl"
  >
    <slot />
  </h6>
</template>
