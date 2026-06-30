<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useDemos } from '~/../app/composables/useDemos';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const route = useRoute();
const slug = computed(() => String(route.params.slug));
const { getDemo } = useDemos();
const demo = computed(() => getDemo(slug.value));
if (!demo.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const heroImage = computed(() => useStockImages().get(slug.value).hero);

useHead(() => ({ title: `Kontakt — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Kontakt"
      title="Ozvěte se nám"
      :image="heroImage"
      :crumbs="[{ label: 'Kontakt' }]"
    />

    <ThemeSectionContact
      :demo="demo"
      :dark="demo.dark"
      subtitle="Kontakt"
      title="Ozvěte se nám"
      text="Napište nám nezávaznou poptávku a my se vám ozveme s návrhem řešení i cenou."
    />
  </ThemeInnerLayout>
</template>
