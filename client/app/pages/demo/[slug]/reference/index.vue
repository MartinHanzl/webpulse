<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useDemos } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const route = useRoute();
const slug = computed(() => String(route.params.slug));
const { getDemo } = useDemos();
const demo = computed(() => getDemo(slug.value));
if (!demo.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const content = computed(() => useDemoContent(slug.value));
const projects = computed(() => content.value.projects);
const heroImage = computed(() => useStockImages().get(slug.value).hero);

useHead(() => ({ title: `Reference — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Naše práce"
      title="Reference"
      :image="heroImage"
      :crumbs="[{ label: 'Reference' }]"
    />

    <ThemeInnerProjects :demo="demo" :dark="demo.dark" :items="projects" />
  </ThemeInnerLayout>
</template>
