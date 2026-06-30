<script setup lang="ts">
import { useSiteTheme } from '~/../app/composables/useSiteTheme';
import { useStockImages } from '~/../app/composables/useStockImages';

const { t, locale } = useI18n();
const route = useRoute();
const localePath = useLocalePath();
const { slug, dark } = useSiteTheme();
const pageMeta = ref({
  title: t('review.title'),
  description: t('review.meta_description'),
  meta_title: t('review.meta_title'),
  meta_description: t('review.meta_description'),
});

const api = useApi();
useAsyncData('reviewCategories', () => api.review.categories());

const { data: reviewData } = useAsyncData('reviewDetail', () =>
  api.review
    .reviewDetail(route.params.id, locale.value)
    .then()
    .catch(() => {
      throw createError({
        statusCode: 404,
        statusMessage: 'Page Not Found',
      });
    }),
);

const tableQuery = ref({
  paginate: 15 as number,
  page: 1 as number,
  categoryId: null as number | null,
});

const { data: reviewsData } = useAsyncData('reviews', () =>
  api.review.reviews(
    tableQuery.value.page,
    tableQuery.value.paginate,
    locale.value,
    tableQuery.value.categoryId,
  ),
);

const getReviews = () => {
  return api.review.reviews(
    tableQuery.value.page,
    tableQuery.value.paginate,
    locale.value,
    tableQuery.value.categoryId,
  );
};

watch(
  () => tableQuery.value.categoryId,
  () => {
    tableQuery.value.page = 1;
    getReviews();
  },
);

useHead({
  title: pageMeta.value.title,
  meta: [
    { name: 'description', content: pageMeta.value.meta_description },
    { property: 'og:title', content: pageMeta.value.meta_title },
    { property: 'og:description', content: pageMeta.value.meta_description },
  ],
  link: [
    {
      rel: 'canonical',
      href:
        useRuntimeConfig().public.appUrl +
        (locale.value !== 'cs' ? `/${locale.value}` : '') +
        `/${t('canonical.review')}`,
    },
  ],
});

const randomReviews = computed(() => {
  const data = reviewsData.value?.data || [];
  if (data.length <= 3) return data;

  const chosenIndices = new Set<number>();
  while (chosenIndices.size < 3) {
    const randomIndex = Math.floor(Math.random() * data.length);
    chosenIndices.add(randomIndex);
  }

  return Array.from(chosenIndices).map((i) => data[i]);
});

definePageMeta({ layout: false });
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      :title="reviewData?.name || t('review.title')"
      subtitle="Reference"
      :image="useStockImages().get(slug).hero"
      :crumbs="[
        { label: t('review.title'), to: localePath({ name: 'review' }) },
        { label: reviewData?.name || '…' },
      ]"
    />

    <section class="section" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <article
          v-if="reviewData"
          class="reveal mx-auto max-w-4xl rounded-3xl p-7 shadow-sm sm:p-12"
          :class="dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white ring-1 ring-slate-100'"
        >
          <!-- Categories -->
          <div v-if="reviewData?.categories?.length" class="mb-8 flex flex-wrap gap-2">
            <NuxtLink
              v-for="category in reviewData.categories"
              :key="category.id"
              :to="
                localePath({
                  name: 'review-category-id-slug',
                  params: { id: category.id, slug: category.slug },
                })
              "
              class="rounded-full px-4 py-1.5 text-xs font-semibold transition-colors hover:bg-brand-pop hover:text-brand-ink"
              :class="dark ? 'bg-white/10 text-white' : 'bg-brand-pop/10 text-brand-ink'"
            >
              {{ category.name }}
            </NuxtLink>
          </div>

          <div
            v-if="reviewData?.perex"
            class="mb-8 text-lg leading-relaxed"
            :class="dark ? 'text-white/70' : 'text-brand-muted'"
            v-html="reviewData.perex"
          ></div>

          <div
            class="review-text leading-relaxed"
            :class="dark ? 'text-white/70' : 'text-brand-ink'"
            v-html="reviewData.text"
          ></div>

          <!-- Gallery -->
          <div
            v-if="reviewData?.images?.length"
            class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
          >
            <BaseImage
              v-for="(img, index) in reviewData.images"
              :key="index"
              :src="img"
              alt=""
              class="h-64 w-full rounded-2xl object-cover shadow-sm"
            />
          </div>
        </article>

        <!-- Related reviews -->
        <div v-if="randomReviews.length" class="mx-auto mt-16 max-w-5xl">
          <h2
            class="mb-8 text-center text-2xl font-bold sm:text-3xl"
            :class="dark ? 'text-white' : ''"
          >
            Další reference
          </h2>
          <div class="grid gap-7 sm:grid-cols-2 md:grid-cols-3">
            <NuxtLink
              v-for="(review, i) in randomReviews"
              :key="review.id"
              :to="
                localePath({
                  name: 'review-id-slug',
                  params: { id: review.id, slug: review.slug || 'test' },
                })
              "
              class="reveal group flex flex-col overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
              :class="
                dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white ring-1 ring-slate-100'
              "
              :style="{ transitionDelay: `${(i % 3) * 80}ms` }"
            >
              <div class="aspect-[16/10] overflow-hidden">
                <BaseImage
                  v-if="review.image"
                  :src="review.image"
                  :alt="review.name"
                  class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div
                  v-else
                  class="flex size-full items-center justify-center"
                  :class="dark ? 'bg-white/[0.04]' : 'bg-brand-cream'"
                >
                  <span class="material-symbols-outlined text-5xl text-brand-pop/40"
                    >format_quote</span
                  >
                </div>
              </div>
              <div class="flex flex-1 flex-col p-7">
                <h3
                  class="text-lg font-bold leading-snug transition-colors group-hover:text-brand-pop"
                  :class="dark ? 'text-white' : ''"
                >
                  {{ review.name }}
                </h3>
                <p
                  class="mt-3 line-clamp-3 text-[15px]"
                  :class="dark ? 'text-white/60' : 'text-brand-muted'"
                  v-html="review.perex"
                />
              </div>
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>

<style scoped>
.review-text :deep(h2) {
  margin-top: 1.5rem;
}
</style>
