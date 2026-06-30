<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useDemos } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';

definePageMeta({ layout: false });

const route = useRoute();
const slug = computed(() => String(route.params.slug));
const projectSlug = computed(() => String(route.params.project));
const { getDemo } = useDemos();
const demo = computed(() => getDemo(slug.value));
if (!demo.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const content = computed(() => useDemoContent(slug.value));
const project = computed(() => content.value.getProject(projectSlug.value));
if (!project.value)
  throw createError({ statusCode: 404, statusMessage: 'Reference nenalezena', fatal: true });

const others = computed(() => content.value.projects.filter((p) => p.slug !== project.value!.slug));

useHead(() => ({ title: `${project.value?.title} — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Reference"
      :title="project!.title"
      :image="project!.image"
      :crumbs="[{ label: 'Reference', to: `/demo/${slug}/reference` }, { label: project!.title }]"
    />

    <ThemeInnerProjectDetail :demo="demo" :dark="demo.dark" :project="project" :others="others" />
  </ThemeInnerLayout>
</template>
