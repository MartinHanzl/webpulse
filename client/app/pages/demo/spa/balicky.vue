<script setup lang="ts">
import { useSpaContent } from '~/../app/composables/useSpaContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'spa';
const c = useSpaContent();
const ph = useStockImages().get('spa');

useHead(() => ({ title: 'Balíčky — Serenity' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section
      class="relative flex min-h-[420px] items-center justify-center overflow-hidden pb-16 pt-28 text-center"
    >
      <img :src="ph.facts" alt="" class="absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-brand-dark/60" />
      <div class="container-x relative">
        <h1 class="text-5xl !text-white sm:text-6xl">Balíčky</h1>
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/80">
          <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-white">Domů</NuxtLink>
          <span class="material-symbols-outlined text-base">chevron_right</span>
          <span class="text-brand">Balíčky</span>
        </nav>
      </div>
    </section>

    <!-- INTRO + PACKAGE GRID -->
    <section class="bg-brand-soft py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Wellness balíčky"
          title="Vše, co potřebujete pro dokonalý odpočinek"
          text="Vyberte si balíček podle toho, kolik času si chcete dopřát jen pro sebe."
          align="center"
        />

        <div class="mt-16 grid items-stretch gap-8 lg:grid-cols-3">
          <div
            v-for="p in c.packages"
            :key="p.name"
            class="reveal relative flex flex-col rounded-3xl bg-white p-8 sm:p-10"
            :class="
              p.featured
                ? 'border-2 border-brand shadow-xl lg:-translate-y-4 lg:scale-105'
                : 'border border-brand-ink/10 shadow-sm'
            "
          >
            <span
              v-if="p.featured"
              class="absolute -top-4 left-1/2 -translate-x-1/2 rounded-full bg-brand px-5 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-lg"
              >Nejoblíbenější</span
            >

            <h3 class="text-2xl">{{ p.name }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ p.desc }}</p>

            <div class="mt-6 flex items-end gap-2 border-b border-brand-ink/10 pb-6">
              <span class="font-serif text-5xl font-semibold text-brand">{{ p.price }}</span>
              <span class="pb-1 text-sm text-brand-muted">/ {{ p.period }}</span>
            </div>

            <ul class="mt-6 flex flex-1 flex-col gap-4">
              <li v-for="f in p.features" :key="f" class="flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 text-xl text-brand"
                  >check_circle</span
                >
                <span class="text-brand-ink">{{ f }}</span>
              </li>
            </ul>

            <div class="mt-8">
              <ThemeButton
                :to="`/demo/${slug}/kontakt`"
                :variant="p.featured ? 'solid' : 'outline'"
                class="w-full"
                >Vybrat balíček</ThemeButton
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SPECIAL PRICING -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Flexibilní ceník"
          title="Ceník procedur"
          text="Ceny jednotlivých procedur, které můžete kombinovat dle libosti."
          align="center"
        />

        <div class="mx-auto mt-16 grid max-w-5xl gap-x-14 gap-y-8 md:grid-cols-2">
          <div v-for="item in c.priceList" :key="item.name" class="reveal flex items-center gap-5">
            <img
              :src="item.image"
              :alt="item.name"
              class="size-16 shrink-0 rounded-full object-cover"
            />
            <div class="flex flex-1 items-center gap-3">
              <div>
                <h3 class="text-lg">{{ item.name }}</h3>
                <p class="text-sm text-brand-muted">{{ item.desc }}</p>
              </div>
              <span class="mx-2 h-px flex-1 border-t border-dashed border-brand-ink/20" />
              <span class="font-serif text-xl font-semibold text-brand">{{ item.price }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
