<script setup lang="ts">
const { locale, locales } = useI18n();
const switchLocalePath = useSwitchLocalePath();

const availableLocales = computed(() => {
  return locales.value.filter((i) => i.code !== locale.value);
});
</script>

<template>
  <div>
    <div class="relative flex w-full flex-col">
      <NuxtLink
        class="hidden"
        v-for="locale in availableLocales"
        :key="locale.code"
        :to="switchLocalePath(locale.code)"
      >
        {{ locale.name }}
      </NuxtLink>
      <main class="flex-auto">
        <slot />
      </main>
    </div>
    <LayoutCookiesBar />
  </div>
</template>
