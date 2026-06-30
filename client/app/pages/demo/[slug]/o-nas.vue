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

useHead(() => ({ title: `O nás — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Kdo jsme"
      title="O nás"
      :image="heroImage"
      :crumbs="[{ label: 'O nás' }]"
    />

    <ThemeInnerAbout :demo="demo" :dark="demo.dark" />
  </ThemeInnerLayout>
</template>
