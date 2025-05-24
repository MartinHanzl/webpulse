<script setup lang="ts">
import { ref } from "vue";
const { t } = useI18n();

const showBar = ref(true);
const showSettings = ref(false);

const cookies = ref({
  technical: true,
  marketing: false,
  analytics: false,
  advertisement: false,
});

function acceptCookie(
  string: "technical" | "marketing" | "analytics" | "advertisement",
) {}

function acceptAllCookies() {
  // Logic to accept all cookies
  showBar.value = false;
  showSettings.value = false;
}

function acceptSelectedCookies() {
  // Logic to accept selected cookies
  showBar.value = false;
  showSettings.value = false;
}
</script>

<template>
  <div
    class="z-10 absolute bottom-0 left-0 max-w-full w-full md:bottom-4 md:left-4 right-8 p-6 border border-secondary rounded md:max-w-md bg-dark"
  >
    <BasePropsParagraph class="mb-4" size="base">
      {{ t("cookies.description") }}
    </BasePropsParagraph>
    <div class="flex gap-x-4">
      <BaseButton size="lg">{{ t("cookies.acceptAll") }}</BaseButton>
      <BaseButton size="lg" @click="showSettings = true">{{
        t("cookies.settings")
      }}</BaseButton>
    </div>
    <LayoutCookiesDialog
      v-model:open="showSettings"
      :cookies="cookies"
      @accept-all="acceptAllCookies"
      @accept-selected="acceptSelectedCookies"
    />
  </div>
</template>
