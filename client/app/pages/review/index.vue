<script setup lang="ts">
import { useSiteTheme } from '~/../app/composables/useSiteTheme';
import { useStockImages } from '~/../app/composables/useStockImages';

const { t, locale } = useI18n();
const { slug, dark } = useSiteTheme();
const tableQuery = ref({
  paginate: 15 as number,
  page: 1 as number,
  categoryId: null as number | null,
});

const localePath = useLocalePath();

const pageMeta = ref({
  title: t('review.title'),
  description: t('review.meta_description'),
  meta_title: t('review.meta_title'),
  meta_description: t('review.meta_description'),
});

const api = useApi();
const { data: reviewCategoriesData } = useAsyncData('reviewCategories', () =>
  api.review.categories(locale.value),
);

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

async function updatePage(page: number) {
  tableQuery.value.page = page;
  const reviews = getReviews();
  reviewsData.value = await reviews;
  if (import.meta.client) window.scrollTo({ top: 0, behavior: 'smooth' });
}

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
definePageMeta({
  name: 'review-index',
  layout: false,
});
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      :title="t('review.title')"
      subtitle="Reference"
      :image="useStockImages().get(slug).hero"
      :crumbs="[{ label: t('review.title') }]"
    />

    <section class="section" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <!-- Categories -->
        <div
          v-if="reviewCategoriesData && reviewCategoriesData.length"
          class="reveal mb-12 flex flex-wrap items-center justify-center gap-3"
        >
          <NuxtLink
            :to="localePath({ name: 'review' })"
            class="rounded-full bg-brand-pop px-5 py-2.5 text-sm font-semibold text-brand-ink"
            @click="tableQuery.categoryId = null"
          >
            Vše
          </NuxtLink>
          <NuxtLink
            v-for="category in reviewCategoriesData"
            :key="category.id"
            :to="
              localePath({
                name: 'review-category-id-slug',
                params: { id: category.id, slug: category.slug },
              })
            "
            class="rounded-full px-5 py-2.5 text-sm font-semibold transition-colors hover:bg-brand-pop hover:text-brand-ink"
            :class="dark ? 'bg-white/10 text-white' : 'bg-brand-pop/10 text-brand-ink'"
            @click="tableQuery.categoryId = category.id"
          >
            {{ category.name }}
          </NuxtLink>
        </div>

        <!-- Reviews grid -->
        <div
          v-if="reviewsData && reviewsData.data && reviewsData.data.length"
          class="grid gap-7 md:grid-cols-2 lg:grid-cols-3"
        >
          <NuxtLink
            v-for="(review, i) in reviewsData.data"
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
              <span
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop"
                >Číst více
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span></span
              >
            </div>
          </NuxtLink>
        </div>

        <!-- Empty state -->
        <div
          v-else
          class="rounded-3xl py-20 text-center"
          :class="dark ? 'bg-white/[0.04] text-white/60' : 'bg-brand-cream text-brand-muted'"
        >
          <span class="material-symbols-outlined text-5xl text-brand-pop/40">reviews</span>
          <p class="mt-4 text-lg font-semibold">Zatím tu nejsou žádné reference.</p>
        </div>

        <!-- Pagination -->
        <div v-if="reviewsData && reviewsData.total > tableQuery.paginate" class="mt-14">
          <BasePagination
            :page="tableQuery.page"
            :paginate="tableQuery.paginate"
            :total="reviewsData?.total || 0"
            @update-page="updatePage"
          />
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
