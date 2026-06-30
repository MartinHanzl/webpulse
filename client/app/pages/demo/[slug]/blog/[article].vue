<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useDemos } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';

definePageMeta({ layout: false });

const route = useRoute();
const slug = computed(() => String(route.params.slug));
const articleSlug = computed(() => String(route.params.article));
const { getDemo } = useDemos();
const demo = computed(() => getDemo(slug.value));
if (!demo.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const content = computed(() => useDemoContent(slug.value));
const article = computed(() => content.value.getArticle(articleSlug.value));
if (!article.value)
  throw createError({ statusCode: 404, statusMessage: 'Článek nenalezen', fatal: true });

const others = computed(() => content.value.articles.filter((a) => a.slug !== article.value!.slug));

useHead(() => ({ title: `${article.value?.title} — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Blog"
      :title="article!.title"
      :image="article!.image"
      :crumbs="[{ label: 'Blog', to: `/demo/${slug}/blog` }, { label: article!.title }]"
    />

    <ThemeInnerArticle :demo="demo" :dark="demo.dark" :article="article" :others="others" />
  </ThemeInnerLayout>
</template>
