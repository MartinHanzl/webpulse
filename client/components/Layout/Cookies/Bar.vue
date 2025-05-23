<script setup lang="ts">
import { ref } from "vue";
const { t } = useI18n();

const showBar = ref(true);
const showSettings = ref(false);
</script>

<template>
  <div
    class="z-10 absolute bottom-0 left-0 max-w-full w-full md:bottom-4 md:left-4 right-8 p-6 border border-secondary rounded md:max-w-md bg-dark"
  >
    <p class="mb-4 text-sm md:text-xsl">
      {{ t("cookies.description") }}
    </p>
    <div class="flex gap-x-4">
      <BaseButton size="lg">{{ t("cookies.acceptAll") }} </BaseButton>
      <BaseButton size="lg"> {{ t("cookies.settings") }} </BaseButton>
    </div>
  </div>
</template>
