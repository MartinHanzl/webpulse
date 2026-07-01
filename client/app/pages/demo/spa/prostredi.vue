<script setup lang="ts">
import { ref, computed } from 'vue';
import { useSpaContent } from '~/../app/composables/useSpaContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'spa';
const c = useSpaContent();
const ph = useStockImages().get('spa');

const lb = ref();
const gallery = computed(() =>
  c.facilities.map((f) => ({ image: f.image, title: f.name, category: 'Prostředí' })),
);

const amenities = [
  { icon: 'spa', label: 'Sůl & aroma' },
  { icon: 'whatshot', label: 'Horké kameny' },
  { icon: 'self_improvement', label: 'Luxusní spa' },
  { icon: 'face', label: 'Péče o pleť' },
  { icon: 'air', label: 'Aromaterapie' },
  { icon: 'water_drop', label: 'Olejová masáž' },
];

useHead(() => ({ title: 'Prostředí — Serenity' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section
      class="relative flex min-h-[420px] items-center justify-center overflow-hidden pb-16 pt-28 text-center"
    >
      <img :src="ph.services[3]" alt="" class="absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-brand-dark/60" />
      <div class="container-x relative">
        <h1 class="text-5xl !text-white sm:text-6xl">Prostředí</h1>
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/80">
          <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-white">Domů</NuxtLink>
          <span class="material-symbols-outlined text-base">chevron_right</span>
          <span class="text-brand">Prostředí</span>
        </nav>
      </div>
    </section>

    <!-- INTRO SPLIT -->
    <section class="bg-brand-soft py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl shadow-lg">
              <img :src="ph.aboutMain" alt="" class="aspect-[5/4] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -right-4 hidden size-32 place-items-center rounded-full bg-white text-brand shadow-xl sm:grid"
            >
              <ThemeCircleText
                text="• NEZAPOMENUTELNÝ ZÁŽITEK • SERENITY "
                :duration="22"
                class="size-full p-1"
              >
                <span class="material-symbols-outlined text-3xl">spa</span>
              </ThemeCircleText>
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Prostředí studia
            </p>
            <h2 class="mt-5 text-4xl leading-tight sm:text-[44px]">
              Klidné a stylové prostředí pro dokonalé
              <span class="italic text-brand">uvolnění</span>
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Každý kout Serenity jsme navrhli s ohledem na váš klid — od tlumeného světla přes
              přírodní materiály až po aromaterapii, která vás provede celou návštěvou.
            </p>
            <p class="mt-4 leading-relaxed text-brand-muted">
              Privátní studia, sauna, parní lázeň i tichá odpočívárna vytváří prostor, kde se snadno
              nadechnete a zapomenete na okolní svět.
            </p>
            <div class="mt-10">
              <ThemeButton :to="`/demo/${slug}/kontakt`" variant="solid"
                >Rezervovat návštěvu</ThemeButton
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- GALLERY -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Nezapomenutelný zážitek"
          title="Luxusní prostory"
          text="Nahlédněte do našich studií, sauny a relaxačních zón."
          align="center"
        />

        <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <button
            v-for="(f, i) in c.facilities"
            :key="f.name"
            type="button"
            class="reveal group relative overflow-hidden rounded-3xl text-left shadow-sm"
            @click="lb.show(i)"
          >
            <img
              :src="f.image"
              :alt="f.name"
              class="aspect-[3/4] w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/10 to-transparent"
            />
            <div class="absolute inset-x-0 bottom-0 p-6">
              <h3 class="text-xl !text-white">{{ f.name }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-white/80">{{ f.text }}</p>
            </div>
            <span
              class="absolute right-5 top-5 flex size-11 items-center justify-center rounded-full bg-white/90 text-brand-ink opacity-0 shadow transition-opacity duration-300 group-hover:opacity-100"
            >
              <span class="material-symbols-outlined">zoom_in</span>
            </span>
          </button>
        </div>
      </div>
    </section>

    <!-- AMENITIES -->
    <section class="bg-brand-cream py-20">
      <div class="container-x">
        <div class="grid gap-10 sm:grid-cols-3 lg:grid-cols-6">
          <div
            v-for="a in amenities"
            :key="a.label"
            class="reveal flex flex-col items-center gap-3 text-center"
          >
            <span
              class="flex size-16 items-center justify-center rounded-2xl bg-white text-brand shadow-sm"
            >
              <span class="material-symbols-outlined text-3xl">{{ a.icon }}</span>
            </span>
            <span class="text-sm font-semibold uppercase tracking-wider text-brand-ink">{{
              a.label
            }}</span>
          </div>
        </div>
      </div>
    </section>

    <ThemeLightbox ref="lb" :images="gallery" />
  </ThemeInnerLayout>
</template>
