<script setup lang="ts">
import { useHead } from '#app';
import { useDemos } from '~/../app/composables/useDemos';

definePageMeta({ layout: false });

const { demos } = useDemos();

// Per-demo presentation meta for the directory cards (colors mirror DemoSwitcher / theme.css).
const meta: Record<
  string,
  { colors: [string, string, string]; dark: boolean; icon: string; highlights: string[] }
> = {
  lawn: {
    colors: ['#1FA12E', '#FECF02', '#F5F5F5'],
    dark: false,
    icon: 'grass',
    highlights: ['Slider hero', 'Karusel služeb', 'Světlá šablona'],
  },
  tree: {
    colors: ['#2E7D32', '#8FB339', '#F5F1E8'],
    dark: false,
    icon: 'forest',
    highlights: ['Ceník 3 tarify', 'Před / po slider', 'Světlá šablona'],
  },
  landscaping: {
    colors: ['#3DA35C', '#FECF02', '#0A0C0A'],
    dark: true,
    icon: 'landscape',
    highlights: ['Tmavá šablona', 'Číslovaný proces', 'Masonry recenze'],
  },
  restaurant: {
    colors: ['#d39121', '#d51f0f', '#282725'],
    dark: true,
    icon: 'restaurant',
    highlights: ['Menu s cenami', 'Kuchaři & galerie', 'Bebas Neue'],
  },
  lawyer: {
    colors: ['#b98e44', '#152833', '#f6f3ef'],
    dark: true,
    icon: 'gavel',
    highlights: ['Právní služby', 'Advokáti & profily', 'Playfair Display'],
  },
};

useHead({
  title: 'WebPulse — Demo prezentace',
  meta: [
    {
      name: 'description',
      content: 'Rozcestník ukázkových webů postavených na platformě WebPulse.',
    },
  ],
});
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-white">
    <!-- Ambient background -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
      <div class="absolute -left-32 -top-32 size-96 rounded-full bg-emerald-500/20 blur-3xl" />
      <div class="absolute -bottom-40 right-0 size-[28rem] rounded-full bg-lime-400/10 blur-3xl" />
    </div>

    <div class="relative mx-auto flex max-w-6xl flex-col px-6 py-16 sm:py-24">
      <!-- Brand -->
      <div class="flex items-center gap-3">
        <span
          class="flex size-11 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/30"
        >
          <svg class="size-6" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12Zm0 17a5 5 0 0 1-5-5c0-2.5 1.2-5.3 5-8.4V19Z"
            />
          </svg>
        </span>
        <span class="text-xl font-extrabold tracking-tight">WebPulse</span>
      </div>

      <!-- Heading -->
      <div class="mt-12 max-w-2xl">
        <span
          class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-wider text-emerald-300"
        >
          <span class="size-1.5 rounded-full bg-emerald-400" />
          Demo prezentace
        </span>
        <h1 class="mt-6 text-4xl font-extrabold leading-tight sm:text-5xl">
          Ukázkové weby na platformě WebPulse
        </h1>
        <p class="mt-5 text-lg leading-relaxed text-white/60">
          Vyberte si demo a prohlédněte si, jak může vypadat váš web. Každá ukázka je postavena na
          jiné šabloně. Otevře se v novém okně.
        </p>
      </div>

      <!-- Demo grid -->
      <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <a
          v-for="d in demos"
          :key="d.slug"
          :href="`/demo/${d.slug}`"
          target="_blank"
          rel="noopener"
          class="group relative flex flex-col overflow-hidden rounded-3xl border border-white/10 bg-white/[0.03] transition-all duration-300 hover:-translate-y-1 hover:border-white/25 hover:bg-white/[0.06] hover:shadow-2xl"
        >
          <!-- Color preview -->
          <div
            class="relative flex h-32 items-center justify-center"
            :style="{
              background: `linear-gradient(135deg, ${meta[d.slug]?.colors[0]} 0%, ${
                meta[d.slug]?.dark ? '#0A0C0A' : meta[d.slug]?.colors[1]
              } 100%)`,
            }"
          >
            <span
              class="material-symbols-outlined text-5xl"
              :class="meta[d.slug]?.dark ? 'text-white' : 'text-white/90'"
              >{{ meta[d.slug]?.icon }}</span
            >
            <!-- swatches -->
            <span
              class="absolute bottom-3 left-3 flex overflow-hidden rounded-lg ring-1 ring-white/30"
            >
              <span
                v-for="c in meta[d.slug]?.colors"
                :key="c"
                class="size-5"
                :style="{ background: c }"
              />
            </span>
          </div>

          <!-- Body -->
          <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center justify-between gap-3">
              <h2 class="text-xl font-bold">{{ d.brandName }}</h2>
              <span
                class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white/70"
                >{{ d.switchLabel }}</span
              >
            </div>
            <p class="mt-1 text-sm font-medium text-emerald-300">{{ d.industry }}</p>
            <p class="mt-3 text-sm leading-relaxed text-white/55">{{ d.tagline }}</p>

            <ul class="mt-5 flex flex-wrap gap-2">
              <li
                v-for="h in meta[d.slug]?.highlights"
                :key="h"
                class="rounded-md bg-white/[0.06] px-2.5 py-1 text-xs text-white/60"
              >
                {{ h }}
              </li>
            </ul>

            <span
              class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-white transition-colors group-hover:text-emerald-300"
            >
              Otevřít demo
              <svg
                class="size-4 transition-transform group-hover:translate-x-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M5 12h14M13 6l6 6-6 6" />
              </svg>
            </span>
          </div>
        </a>
      </div>

      <p class="mt-16 text-sm text-white/40">© {{ new Date().getFullYear() }} WebPulse</p>
    </div>
  </div>
</template>
