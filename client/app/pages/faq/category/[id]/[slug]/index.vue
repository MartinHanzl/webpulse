<script setup lang="ts">
import { ref, computed } from 'vue';
import { useApi } from '~/../app/composables/useApi';
import { useSiteTheme } from '~/../app/composables/useSiteTheme';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const { t, locale } = useI18n();
const route = useRoute();
const localePath = useLocalePath();
const api = useApi();
const { slug, dark } = useSiteTheme();

const pageMeta = ref({
  title: t('faq.title'),
  description: t('faq.metaDescription'),
  meta_title: t('faq.metaTitle'),
  meta_description: t('faq.metaDescription'),
  id: route.params.id,
  slug: route.params.slug,
});

const { data: categoriesData } = useAsyncData(`faqCategories-${route.params.id}`, () =>
  api.faq.categories(locale.value),
);

const { data: categoryData } = useAsyncData(`faqCategory-${route.params.id}`, () =>
  api.faq.categoryDetail(route.params.id, locale.value).then((data) => {
    pageMeta.value.title = data.name;
    pageMeta.value.meta_title = data.meta_title || data.name;
    pageMeta.value.meta_description = data.meta_description || data.description;
    return data;
  }),
);

const openKey = ref<string | null>(null);
function toggle(key: string) {
  openKey.value = openKey.value === key ? null : key;
}

const currentFaqs = computed(() => categoryData.value?.faqs ?? []);

const crumbs = computed(() => [
  { label: t('faq.title'), to: localePath('/faq') },
  { label: pageMeta.value.title },
]);

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
        `/faq/${t('canonical.category')}/${pageMeta.value.id}/${pageMeta.value.slug}`,
    },
  ],
});
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      :title="pageMeta.title"
      subtitle="FAQ"
      :crumbs="crumbs"
      :image="useStockImages().get(slug).hero"
    />

    <section class="section" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <div class="grid gap-10 lg:grid-cols-[280px_1fr] lg:gap-14">
          <!-- Category sidebar -->
          <aside v-if="categoriesData && categoriesData.length > 1" class="reveal">
            <div class="flex flex-col gap-2 lg:sticky lg:top-28">
              <NuxtLink
                v-for="cat in categoriesData"
                :key="cat.id"
                :to="
                  localePath({
                    name: 'faq-category-id-slug',
                    params: { id: cat.id, slug: cat.slug },
                  })
                "
                class="flex items-center justify-between rounded-2xl px-5 py-4 text-left text-sm font-semibold transition-colors"
                :class="
                  String(cat.id) === String(route.params.id)
                    ? 'bg-brand-pop text-brand-ink shadow-lg shadow-brand-pop/25'
                    : dark
                      ? 'bg-white/10 text-white hover:bg-brand-pop/20'
                      : 'bg-brand-pop/10 text-brand-ink hover:bg-brand-pop/15'
                "
              >
                {{ cat.name }}
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
              </NuxtLink>
            </div>
          </aside>

          <!-- Accordion -->
          <div class="flex flex-col gap-4">
            <div
              v-for="(faq, i) in currentFaqs"
              :key="faq.id"
              class="reveal overflow-hidden rounded-2xl transition-shadow"
              :class="[
                dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white ring-1 ring-slate-100',
                openKey === `${i}` ? 'shadow-lg' : '',
              ]"
              :style="{ transitionDelay: `${i * 60}ms` }"
            >
              <button
                class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
                @click="toggle(`${i}`)"
              >
                <span class="text-lg font-bold" :class="dark ? 'text-white' : 'text-brand-ink'">{{
                  faq.question
                }}</span>
                <span
                  class="flex size-9 shrink-0 items-center justify-center rounded-full transition-all"
                  :class="
                    openKey === `${i}`
                      ? 'rotate-45 bg-brand-pop text-brand-ink'
                      : dark
                        ? 'bg-white/10 text-brand-pop'
                        : 'bg-brand-pop/10 text-brand-pop'
                  "
                >
                  <span class="material-symbols-outlined">add</span>
                </span>
              </button>
              <div
                v-show="openKey === `${i}`"
                class="px-6 pb-6 text-[15px] leading-relaxed"
                :class="dark ? 'text-white/60' : 'text-brand-muted'"
                v-html="faq.answer"
              />
            </div>

            <div
              v-if="!currentFaqs.length"
              class="rounded-3xl py-20 text-center"
              :class="dark ? 'bg-white/[0.04] text-white/60' : 'bg-brand-cream text-brand-muted'"
            >
              <span class="material-symbols-outlined text-5xl text-brand-pop/40">quiz</span>
              <p class="mt-4 text-lg font-semibold">Zatím tu nejsou žádné dotazy.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
