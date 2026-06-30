<script setup lang="ts">
import { useApi } from '~/../app/composables/useApi';
import { useSiteTheme } from '~/../app/composables/useSiteTheme';
import { useStockImages } from '~/../app/composables/useStockImages';

const { t, locale } = useI18n();
const route = useRoute();
const api = useApi();
const { slug, dark } = useSiteTheme();

definePageMeta({ layout: false });

// 1. STAZENI DAT S HLIDANIM ZMEN (watch)
const {
  data: pageData,
  error: pageError,
  pending: pagePending,
} = useAsyncData(
  () => `page-${route.params.id}`,
  () =>
    api.page
      .page(route.params.id, locale.value)
      .then()
      .catch(() => {
        console.log('Page not found, throwing error');
        throw createError({
          statusCode: 404,
          statusMessage: 'Page Not Found test',
        });
      }),
  {
    watch: [() => route.params.id, locale],
  },
);

// 2. FUNKCE PRO KANONICKOU URL
function canonicalUrl() {
  const appUrl = useRuntimeConfig().public.appUrl;
  let string = locale.value !== 'cs' ? `${appUrl}/${locale.value}` : appUrl;
  string += `/${t('canonical.info')}`;
  string += pageData.value && pageData.value.id ? `/${pageData.value.id}` : `/${route.params.id}`;
  string +=
    pageData.value && pageData.value.slug ? `/${pageData.value.slug}` : `/${route.params.slug}`;

  return string;
}

// 3. REAKTIVNI METADATA PRES COMPUTED
const pageMeta = computed(() => {
  const defaultTitle = t('info.title');
  const defaultDesc = t('info.meta_description');

  return {
    title: pageData.value?.name ? `${pageData.value.name} | ${defaultTitle}` : defaultTitle,
    description: pageData.value?.description || defaultDesc,
    meta_title: pageData.value?.meta_title || pageData.value?.name || defaultTitle,
    meta_description:
      pageData.value?.meta_description || pageData.value?.description || defaultDesc,
    id: route.params.id,
    slug: route.params.slug,
  };
});

// 4. REAKTIVNI USEHEAD
useHead(() => ({
  title: pageMeta.value.title,
  meta: [
    { name: 'description', content: pageMeta.value.description },
    { property: 'og:title', content: pageMeta.value.meta_title },
    { property: 'og:description', content: pageMeta.value.meta_description },
  ],
  link: [
    {
      rel: 'canonical',
      href: canonicalUrl(),
    },
  ],
}));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      :title="pageData?.name || t('info.title')"
      subtitle="Informace"
      :image="useStockImages().get(slug).hero"
      :crumbs="[{ label: pageData?.name || t('info.title') }]"
    />

    <section class="section" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <article
          v-if="!pagePending && !pageError && pageData"
          class="reveal mx-auto max-w-4xl rounded-3xl p-7 shadow-sm sm:p-12 md:p-16"
          :class="dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white ring-1 ring-slate-100'"
        >
          <div
            class="article-content text-lg leading-relaxed"
            :class="[dark ? 'article-content--dark text-white/70' : 'text-brand-ink']"
            v-html="pageData.text"
          ></div>
        </article>

        <div v-else-if="pagePending" class="flex min-h-[40vh] items-center justify-center">
          <div
            class="size-16 animate-spin rounded-full border-4 border-brand-pop/20 border-t-brand-pop"
          ></div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>

<style scoped>
.article-content :deep(h2) {
  @apply mb-6 mt-12 text-3xl font-bold leading-tight text-brand-ink;
}

.article-content :deep(h3) {
  @apply mb-4 mt-10 text-2xl font-bold text-brand-ink;
}

.article-content :deep(p) {
  @apply mb-4;
}

.article-content :deep(a) {
  @apply font-semibold text-brand-pop underline decoration-brand-pop/40 decoration-2 underline-offset-4 transition-all hover:decoration-brand-pop;
}

.article-content :deep(ul) {
  @apply mb-4 list-inside list-disc space-y-2;
}

.article-content :deep(li) {
  @apply pl-2;
}

.article-content :deep(blockquote) {
  @apply my-8 rounded-r-xl border-l-4 border-brand-pop bg-brand-pop/10 p-6 text-xl italic;
}

.article-content :deep(img) {
  @apply my-10 h-auto max-w-full rounded-xl;
}

.article-content :deep(strong) {
  @apply font-bold text-brand-ink;
}

/* Dark theme overrides */
.article-content--dark :deep(h2),
.article-content--dark :deep(h3),
.article-content--dark :deep(strong) {
  @apply text-white;
}

.article-content--dark :deep(blockquote) {
  @apply border-brand-pop bg-white/[0.04] text-white/80;
}
</style>
