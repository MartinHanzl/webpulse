<script setup lang="ts">
const sizeClasses = ref({
  base: "text-xs md:text-md lg:text-base",
  small: "text-xs md:text-sm lg:text-md",
});
const boldClasses = ref({
  base: "font-normal",
  bold: "font-bold",
  bolder: "font-extrabold",
});
const props = defineProps({
  size: {
    type: String,
    default: "base",
    required: false,
  },
  bold: {
    type: String,
    default: "base",
    required: false,
  },
});
const baseClass = computed(() => {
  return `${sizeClasses.value[props.size] || ""} ${boldClasses.value[props.bold] || ""} text-grayDark leading-6 tracking-tight`;
});
</script>
<template>
  <p :class="baseClass">
    <slot />
  </p>
</template>
