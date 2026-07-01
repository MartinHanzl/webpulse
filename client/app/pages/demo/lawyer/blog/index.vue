<script setup lang="ts">
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'lawyer';
const c = useLawyerContent();
const ph = useStockImages().get('lawyer');

useHead(() => ({ title: 'Blog — Veritas' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Blog"
      title="Právní aktuality"
      :image="ph.hero"
      :crumbs="[{ label: 'Blog' }]"
    />

    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš deník"
          title="Z právní praxe"
          text="Sledujte novinky v legislativě, praktické rady a komentáře našich advokátů."
          align="center"
        />

        <div class="mt-14 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          <NuxtLink
            v-for="a in c.articles"
            :key="a.slug"
            :to="`/demo/${slug}/blog/${a.slug}`"
            class="reveal group flex flex-col overflow-hidden rounded-3xl border border-brand-ink/5 bg-brand-cream shadow-sm transition-transform duration-300 hover:-translate-y-2"
          >
            <div class="relative h-56 overflow-hidden">
              <img
                :src="a.image"
                :alt="a.title"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute left-5 top-5 rounded-full bg-white px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand shadow"
              >
                {{ a.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col p-7">
              <h3 class="text-xl leading-snug">{{ a.title }}</h3>
              <p class="mt-3 text-sm leading-relaxed text-brand-muted">{{ a.perex }}</p>
              <div class="mt-5 flex items-center gap-3 text-sm text-brand-muted">
                <span class="flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-base text-brand">calendar_today</span>
                  {{ a.date }}
                </span>
                <span class="size-1 rounded-full bg-brand-muted/50" />
                <span class="flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-base text-brand">person</span>
                  {{ a.author }}
                </span>
              </div>
              <span
                class="mt-6 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand transition-colors group-hover:text-brand-dark"
              >
                Číst dál
                <span
                  class="material-symbols-outlined text-lg transition-transform group-hover:translate-x-1"
                  >arrow_forward</span
                >
              </span>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
