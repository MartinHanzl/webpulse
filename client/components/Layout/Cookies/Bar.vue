<script setup lang="ts">
import { ref } from "vue";
const { t } = useI18n();

const showBar = ref(true);
const showSettings = ref(false);
</script>

<template>
  <div
    class="z-10 absolute bottom-8 left-8 p-6 border border-secondary rounded max-w-md bg-dark"
  >
    <p class="mb-4">
      {{ t("cookies.description") }}
    </p>
    <div class="flex gap-x-4">
      <BaseButton size="lg"> Souhlasím </BaseButton>
      <BaseButton size="lg"> Nastavení </BaseButton>
    </div>
  </div>
</template>
