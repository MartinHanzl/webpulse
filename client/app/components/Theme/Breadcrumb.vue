<script setup lang="ts">
interface Crumb {
  label: string;
  to?: string;
}
withDefaults(
  defineProps<{
    title: string;
    subtitle?: string;
    crumbs?: Crumb[];
    image?: string;
    slug?: string;
  }>(),
  {
    slug: 'lawn',
    image:
      'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&q=80&w=1920',
    crumbs: () => [],
  },
);
</script>

<template>
  <section class="relative flex min-h-[340px] items-center overflow-hidden pt-20 lg:min-h-[420px]">
    <ThemeParallax :image="image" :speed="0.28">
      <div
        class="absolute inset-0 bg-gradient-to-r from-brand-dark/90 via-brand-dark/70 to-brand-dark/40"
      />
    </ThemeParallax>
    <div
      class="floaty pointer-events-none absolute right-[10%] top-1/3 hidden size-56 rounded-full bg-brand-accent/20 blur-3xl lg:block"
    />
    <div class="container-x relative z-10">
      <span
        v-if="subtitle"
        class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-wider text-brand-accent backdrop-blur"
      >
        <span class="size-1.5 rounded-full bg-brand-accent" />
        {{ subtitle }}
      </span>
      <h1 class="mt-5 text-4xl font-extrabold !text-white sm:text-5xl lg:text-6xl">{{ title }}</h1>
      <nav class="mt-5 flex items-center gap-2 text-sm font-medium text-white/70">
        <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-brand-accent"
          >Domů</NuxtLink
        >
        <template v-for="c in crumbs" :key="c.label">
          <span class="material-symbols-outlined text-[16px] text-white/40">chevron_right</span>
          <NuxtLink v-if="c.to" :to="c.to" class="transition-colors hover:text-brand-accent">{{
            c.label
          }}</NuxtLink>
          <span v-else class="text-brand-accent">{{ c.label }}</span>
        </template>
      </nav>
    </div>
  </section>
</template>
