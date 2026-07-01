<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { DemoLawn, DemoTree, DemoLandscaping, DemoRestaurant, DemoLawyer } from '#components';
import { useDemos } from '~/../app/composables/useDemos';

definePageMeta({ layout: false });

const route = useRoute();
const { getDemo } = useDemos();

const slug = computed(() => String(route.params.slug));
const demo = computed(() => getDemo(slug.value));

if (!demo.value) {
  throw createError({ statusCode: 404, statusMessage: 'Demo nenalezeno', fatal: true });
}

const componentMap = {
  lawn: DemoLawn,
  tree: DemoTree,
  landscaping: DemoLandscaping,
  restaurant: DemoRestaurant,
  lawyer: DemoLawyer,
};
const variantComponent = computed(
  () => componentMap[slug.value as keyof typeof componentMap] ?? DemoLawn,
);

useHead(() => ({
  title: `${demo.value?.brandName} — ${demo.value?.tagline}`,
  meta: [
    {
      name: 'description',
      content: `${demo.value?.tagline}. ${demo.value?.industry} — ukázková prezentace.`,
    },
  ],
}));
</script>

<template>
  <ThemeDemoLayout v-slot="{ demo: d }" :slug="slug">
    <component :is="variantComponent" :demo="d" />
  </ThemeDemoLayout>
</template>
