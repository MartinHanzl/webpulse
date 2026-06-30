<script setup lang="ts">
import { navigateTo } from '#imports';

definePageMeta({ layout: false });

// Default demo landing → Lawn
await navigateTo('/demo/lawn', { replace: true });
</script>

<template>
  <div />
</template>
