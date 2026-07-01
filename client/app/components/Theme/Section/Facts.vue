<script setup lang="ts">
import { computed } from 'vue';

interface Fact {
  value: number | string;
  suffix?: string;
  label: string;
  icon: string;
}
const props = defineProps<{ items: Fact[]; image?: string }>();

interface ParsedFact extends Fact {
  numeric: number | null;
  parsedSuffix: string;
}

// Parse a numeric part + suffix out of each stat so plain numbers animate
// with ThemeCounter, while non-numeric values (e.g. "ISO", "4,9★") stay static.
const parsed = computed<ParsedFact[]>(() =>
  props.items.map((f) => {
    if (typeof f.value === 'number') {
      return { ...f, numeric: f.value, parsedSuffix: f.suffix ?? '' };
    }
    const match = String(f.value).match(/^\s*([\d.,]+)(.*)$/);
    if (match && match[1]) {
      const numeric = Number(match[1].replace(/\s/g, '').replace(',', '.'));
      if (!Number.isNaN(numeric)) {
        return { ...f, numeric, parsedSuffix: f.suffix ?? match[2].trimEnd() };
      }
    }
    return { ...f, numeric: null, parsedSuffix: f.suffix ?? '' };
  }),
);
</script>

<template>
  <section class="relative overflow-hidden bg-brand-dark py-20">
    <ThemeParallax v-if="image" :image="image" :speed="0.2" class="opacity-10" />
    <div
      class="pointer-events-none absolute -left-20 top-1/2 size-72 -translate-y-1/2 rounded-full bg-brand/20 blur-3xl"
    />
    <div class="container-x relative grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
      <div
        v-for="(f, i) in parsed"
        :key="f.label"
        class="reveal flex flex-col items-center gap-3 text-center"
        :style="{ transitionDelay: `${i * 100}ms` }"
      >
        <span
          class="flex size-16 items-center justify-center rounded-2xl bg-white/10 text-brand-accent"
        >
          <span class="material-symbols-outlined text-[32px]">{{ f.icon }}</span>
        </span>
        <span class="text-4xl font-extrabold !text-white sm:text-5xl">
          <ThemeCounter v-if="f.numeric !== null" :to="f.numeric" :suffix="f.parsedSuffix" />
          <template v-else>{{ f.value }}</template>
        </span>
        <span class="text-sm font-medium text-white/60">{{ f.label }}</span>
      </div>
    </div>
  </section>
</template>
