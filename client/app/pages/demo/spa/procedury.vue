<script setup lang="ts">
import { useSpaContent } from '~/../app/composables/useSpaContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'spa';
const c = useSpaContent();
const ph = useStockImages().get('spa');

useHead(() => ({ title: 'Procedury — Serenity' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section
      class="relative flex min-h-[420px] items-center justify-center overflow-hidden pb-16 pt-28 text-center"
    >
      <img :src="ph.services[0]" alt="" class="absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-brand-dark/60" />
      <div class="container-x relative">
        <h1 class="text-5xl !text-white sm:text-6xl">Procedury</h1>
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/80">
          <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-white">Domů</NuxtLink>
          <span class="material-symbols-outlined text-base">chevron_right</span>
          <span class="text-brand">Procedury</span>
        </nav>
      </div>
    </section>

    <!-- INTRO + AWARD BADGE -->
    <section class="bg-brand-soft py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Luxusní služby
            </p>
            <h2 class="mt-5 text-4xl leading-tight sm:text-[44px]">
              Oživte tělo, mysl i duši našimi luxusními
              <span class="italic text-brand">procedurami</span>
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Každodenní shon si vybírá svou daň na těle i mysli. Pravidelná péče vám pomůže
              zpomalit, uvolnit se a načerpat novou energii — vyberte si z naší nabídky masáží,
              rituálů a lázní.
            </p>
          </div>

          <div class="reveal-right relative">
            <div class="overflow-hidden rounded-3xl shadow-lg">
              <img :src="ph.services[1]" alt="" class="aspect-[5/4] w-full object-cover" />
            </div>
            <div
              class="absolute -left-8 top-1/2 hidden size-32 -translate-y-1/2 place-items-center rounded-full bg-brand text-white shadow-xl md:grid"
            >
              <ThemeCircleText
                text="• OCENĚNÁ PÉČE • 28 LET ZKUŠENOSTÍ "
                :duration="20"
                class="size-full p-1"
              >
                <span class="material-symbols-outlined text-3xl">workspace_premium</span>
              </ThemeCircleText>
            </div>
          </div>
        </div>

        <!-- CATEGORY CARDS -->
        <div class="mt-20 grid gap-8 md:grid-cols-3">
          <div
            v-for="cat in c.categories"
            :key="cat.slug"
            class="reveal flex flex-col gap-4 rounded-3xl bg-white p-10 shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-16 items-center justify-center rounded-2xl bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">{{ cat.icon }}</span>
            </span>
            <h3 class="text-2xl">{{ cat.name }}</h3>
            <p class="leading-relaxed text-brand-muted">{{ cat.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICE ITEMS -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Co nabízíme"
          title="Objevte naše procedury"
          text="Vyberte si proceduru na míru vaší náladě i potřebám těla."
          align="center"
        />

        <div class="mt-16 flex flex-col gap-16">
          <div
            v-for="(t, i) in c.treatments"
            :key="t.slug"
            class="reveal grid items-center gap-10 lg:grid-cols-2"
          >
            <div
              class="group relative overflow-hidden rounded-3xl shadow-lg"
              :class="i % 2 === 1 ? 'lg:order-2' : ''"
            >
              <img
                :src="t.image"
                :alt="t.name"
                class="aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <!-- decorative slider arrows (visual only) -->
              <div
                class="pointer-events-none absolute inset-0 flex items-center justify-between p-5"
              >
                <span
                  class="flex size-11 items-center justify-center rounded-full bg-white/90 text-brand-ink shadow"
                >
                  <span class="material-symbols-outlined">chevron_left</span>
                </span>
                <span
                  class="flex size-11 items-center justify-center rounded-full bg-white/90 text-brand-ink shadow"
                >
                  <span class="material-symbols-outlined">chevron_right</span>
                </span>
              </div>
            </div>

            <div :class="i % 2 === 1 ? 'lg:order-1' : ''">
              <p class="text-sm font-semibold uppercase tracking-wider text-brand">
                {{ t.subtitle }}
              </p>
              <h3 class="mt-3 text-3xl">{{ t.name }}</h3>
              <p class="mt-5 text-lg leading-relaxed text-brand-muted">{{ t.desc }}</p>

              <div class="mt-8 flex flex-wrap items-center gap-5 border-t border-brand-ink/10 pt-6">
                <span class="font-serif text-3xl font-semibold text-brand-ink">{{ t.price }}</span>
                <span
                  v-if="t.discount"
                  class="rounded-full bg-brand px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white"
                  >Sleva {{ t.discount }}</span
                >
                <ThemeButton
                  :to="`/demo/${slug}/kontakt`"
                  variant="outline"
                  size="sm"
                  class="ml-auto"
                  >Rezervovat</ThemeButton
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
