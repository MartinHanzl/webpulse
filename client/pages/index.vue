<script setup lang="ts">
import { useI18n } from "vue-i18n";
const { t } = useI18n();
</script>

<template>
  <div class="relative w-full h-screen bg-cover bg-center">
    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
      <h1
        class="text-white text-4xl md:text-6xl font-bold tracking-wide font-inter animate-fade-in"
      >
        {{ t("welcome") }}
      </h1>
    </div>
  </div>
</template>
