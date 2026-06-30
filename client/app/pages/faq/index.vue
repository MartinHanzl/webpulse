<script setup lang="ts">
import { ref, computed } from 'vue';
import { useApi } from '~/../app/composables/useApi';
import type { FaqCategory } from '~/types/FaqCategory';

const { t, locale } = useI18n();
const api = useApi();

definePageMeta({ layout: false });

const pageMeta = ref({
  title: t('faq.title'),
  meta_title: t('faq.metaTitle'),
  meta_description: t('faq.metaDescription'),
});

const { data: categories } = useAsyncData<FaqCategory[]>(
  'faqCategories',
  () => api.faq.categories(locale.value),
  { watch: [locale], default: () => [] },
);

const activeCat = ref(0);
const openKey = ref<string | null>(null);

const currentFaqs = computed(() => categories.value?.[activeCat.value]?.faqs ?? []);

function toggle(key: string) {
  openKey.value = openKey.value === key ? null : key;
}
function selectCat(i: number) {
  activeCat.value = i;
  openKey.value = null;
}

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
        `/faq`,
    },
  ],
});
</script>

<template>
  <ThemeInnerLayout>
    <ThemeBreadcrumb :title="t('faq.title')" subtitle="FAQ" :crumbs="[{ label: t('faq.title') }]" />

    <section class="section">
      <div class="container-x">
        <div class="grid gap-10 lg:grid-cols-[280px_1fr] lg:gap-14">
          <!-- Category sidebar -->
          <aside v-if="categories && categories.length > 1" class="reveal">
            <div class="flex flex-col gap-2 lg:sticky lg:top-28">
              <button
                v-for="(cat, i) in categories"
                :key="cat.id"
                class="flex items-center justify-between rounded-2xl px-5 py-4 text-left text-sm font-semibold transition-colors"
                :class="
                  i === activeCat
                    ? 'bg-brand text-white shadow-lg shadow-brand/25'
                    : 'bg-brand-soft text-brand-ink hover:bg-brand/10'
                "
                @click="selectCat(i)"
              >
                {{ cat.name }}
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
              </button>
            </div>
          </aside>

          <!-- Accordion -->
          <div class="flex flex-col gap-4">
            <div
              v-for="(faq, i) in currentFaqs"
              :key="faq.id"
              class="reveal overflow-hidden rounded-2xl bg-white ring-1 ring-slate-100 transition-shadow"
              :class="openKey === `${activeCat}-${i}` ? 'shadow-lg' : ''"
              :style="{ transitionDelay: `${i * 60}ms` }"
            >
              <button
                class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
                @click="toggle(`${activeCat}-${i}`)"
              >
                <span class="text-lg font-bold text-brand-ink">{{ faq.question }}</span>
                <span
                  class="flex size-9 shrink-0 items-center justify-center rounded-full transition-all"
                  :class="
                    openKey === `${activeCat}-${i}`
                      ? 'rotate-45 bg-brand text-white'
                      : 'bg-brand-soft text-brand'
                  "
                >
                  <span class="material-symbols-outlined">add</span>
                </span>
              </button>
              <div
                v-show="openKey === `${activeCat}-${i}`"
                class="px-6 pb-6 text-[15px] leading-relaxed text-brand-muted"
                v-html="faq.answer"
              />
            </div>

            <div
              v-if="!currentFaqs.length"
              class="rounded-3xl bg-brand-cream py-20 text-center text-brand-muted"
            >
              <span class="material-symbols-outlined text-5xl text-brand/40">quiz</span>
              <p class="mt-4 text-lg font-semibold">Zatím tu nejsou žádné dotazy.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
