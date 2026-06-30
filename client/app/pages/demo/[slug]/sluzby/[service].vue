<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useDemos } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';

definePageMeta({ layout: false });

const route = useRoute();
const slug = computed(() => String(route.params.slug));
const serviceSlug = computed(() => String(route.params.service));
const { getDemo } = useDemos();
const demo = computed(() => getDemo(slug.value));
if (!demo.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const content = computed(() => useDemoContent(slug.value));
const service = computed(() => content.value.getService(serviceSlug.value));
if (!service.value)
  throw createError({ statusCode: 404, statusMessage: 'Služba nenalezena', fatal: true });

const others = computed(() => content.value.services.filter((s) => s.slug !== service.value!.slug));

useHead(() => ({ title: `${service.value?.name} — ${demo.value?.brandName}` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Služba"
      :title="service!.name"
      :image="service!.image"
      :crumbs="[{ label: 'Služby', to: `/demo/${slug}/sluzby` }, { label: service!.name }]"
    />

    <ThemeInnerServiceDetail :demo="demo" :dark="demo.dark" :service="service" :others="others" />
  </ThemeInnerLayout>
</template>
