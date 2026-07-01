<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

const filters = computed(() => ['Vše', ...new Set(c.gallery.map((g) => g.category))]);
const activeFilter = ref('Vše');
const items = computed(() =>
  activeFilter.value === 'Vše'
    ? c.gallery
    : c.gallery.filter((g) => g.category === activeFilter.value),
);

useHead(() => ({ title: 'Galerie — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Naše kuchyně v obrazech"
      title="Galerie"
      :crumbs="[{ label: 'Galerie' }]"
      :image="ph.hero"
    />

    <!-- GALLERY -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Fotogalerie"
          title="Nahlédněte k nám"
          text="Pohled do naší kuchyně, na talíře i atmosféru restaurace. Vyberte kategorii."
          align="center"
        />

        <!-- Filter -->
        <div class="reveal mt-12 flex flex-wrap items-center justify-center gap-3">
          <button
            v-for="f in filters"
            :key="f"
            type="button"
            class="rounded-full border px-6 py-3 text-sm font-semibold uppercase tracking-wide transition-colors duration-300"
            :class="
              activeFilter === f
                ? 'border-brand bg-brand text-brand-dark'
                : 'border-brand-ink/15 text-brand-muted hover:border-brand/50 hover:text-brand-ink'
            "
            @click="activeFilter = f"
          >
            {{ f }}
          </button>
        </div>

        <!-- Grid -->
        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <figure
            v-for="item in items"
            :key="item.title"
            class="reveal group relative overflow-hidden rounded-3xl shadow-sm"
          >
            <img
              :src="item.image"
              alt=""
              class="h-72 w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 flex flex-col items-center justify-center gap-3 bg-brand-dark/70 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >
              <span
                class="flex size-14 items-center justify-center rounded-full bg-brand text-brand-dark"
              >
                <span class="material-symbols-outlined text-3xl">zoom_in</span>
              </span>
              <figcaption class="px-6 text-center">
                <p class="text-xl uppercase tracking-wide text-white">{{ item.title }}</p>
                <p class="mt-1 text-sm font-semibold uppercase tracking-wider text-brand-accent">
                  {{ item.category }}
                </p>
              </figcaption>
            </div>
          </figure>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
