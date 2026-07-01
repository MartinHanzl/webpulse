<script setup lang="ts">
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

useHead(() => ({ title: 'Blog — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Příběhy z kuchyně"
      title="Blog"
      :image="ph.hero"
      :crumbs="[{ label: 'Blog' }]"
    />

    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš deník"
          title="Příběhy z kuchyně"
          text="Tipy od šéfkuchaře, zákulisí restaurace a inspirace pro vaše chuťové pohárky."
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
                class="absolute left-5 top-5 rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-dark shadow"
              >
                {{ a.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col p-7">
              <span class="text-sm text-brand-muted">{{ a.date }}</span>
              <h3 class="mt-2 text-xl uppercase leading-snug tracking-wide">{{ a.title }}</h3>
              <p class="mt-3 text-sm leading-relaxed text-brand-muted">{{ a.perex }}</p>
              <span
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand-accent transition-colors group-hover:text-brand"
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
