<script setup lang="ts">
interface Stat {
  value: string;
  label: string;
}
withDefaults(
  defineProps<{
    subtitle: string;
    title: string;
    text: string;
    image: string;
    ctaLabel?: string;
    ctaTo?: string;
    stats?: Stat[];
  }>(),
  { ctaLabel: 'Nezávazná poptávka', ctaTo: '#contact', stats: () => [] },
);
</script>

<template>
  <section class="relative flex min-h-[720px] items-center overflow-hidden lg:min-h-[820px]">
    <!-- Background -->
    <ThemeParallax :image="image" :speed="0.25">
      <div
        class="absolute inset-0 bg-gradient-to-r from-brand-dark/85 via-brand-dark/60 to-brand-dark/20"
      />
    </ThemeParallax>

    <!-- Decorative leaf blob -->
    <div
      class="floaty pointer-events-none absolute right-[8%] top-1/3 hidden size-72 rounded-full bg-brand-accent/20 blur-3xl lg:block"
    />

    <div class="container-x relative z-10 pb-28 pt-36">
      <div class="max-w-2xl">
        <span
          class="reveal inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold uppercase tracking-wider text-brand-accent backdrop-blur"
        >
          <span class="size-2 rounded-full bg-brand-accent" />
          {{ subtitle }}
        </span>
        <h1
          class="reveal mt-6 text-4xl font-extrabold leading-[1.08] !text-white sm:text-5xl lg:text-6xl"
        >
          {{ title }}
        </h1>
        <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/80">{{ text }}</p>
        <div class="reveal mt-9 flex flex-wrap items-center gap-4">
          <ThemeButton :to="ctaTo" variant="accent" size="lg">{{ ctaLabel }}</ThemeButton>
          <ThemeButton to="#services" variant="light" size="lg" :icon="false"
            >Naše služby</ThemeButton
          >
        </div>

        <div
          v-if="stats.length"
          class="reveal mt-14 grid max-w-xl grid-cols-2 gap-6 border-t border-white/15 pt-8 sm:grid-cols-3"
        >
          <div v-for="s in stats" :key="s.label">
            <p class="text-3xl font-extrabold !text-white sm:text-4xl">{{ s.value }}</p>
            <p class="mt-1 text-sm text-white/60">{{ s.label }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- bottom curve -->
    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-white/0 to-transparent" />
  </section>
</template>
