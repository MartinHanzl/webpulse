<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoArticle } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    items: DemoArticle[];
  }>(),
  { dark: false },
);

const section = computed(() => (props.dark ? 'bg-neutral-950' : 'bg-white'));
const heading = computed(() => (props.dark ? 'text-white' : 'text-brand-ink'));
const body = computed(() => (props.dark ? 'text-white/60' : 'text-brand-muted'));
const card = computed(() =>
  props.dark
    ? 'bg-white/[0.04] border border-white/10'
    : 'bg-white border border-slate-100 hover:-translate-y-1.5 hover:shadow-xl',
);
</script>

<template>
  <section class="section" :class="section">
    <div class="container-x">
      <ThemeSectionHeading subtitle="Blog" title="Novinky a rady" :light="dark" max="max-w-2xl" />

      <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <NuxtLink
          v-for="(a, i) in items"
          :key="a.slug"
          :to="`/demo/${demo.slug}/blog/${a.slug}`"
          class="reveal group flex flex-col overflow-hidden rounded-2xl transition-all duration-300"
          :class="card"
          :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
        >
          <div class="aspect-[16/10] overflow-hidden">
            <img
              :src="a.image"
              :alt="a.title"
              class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
          </div>
          <div class="flex flex-1 flex-col p-7">
            <div class="flex items-center gap-3 text-xs font-semibold text-brand-pop">
              <span class="rounded-full bg-brand-pop/10 px-3 py-1">{{ a.category }}</span>
              <span :class="body">{{ a.date }}</span>
            </div>
            <h3
              class="mt-4 text-lg font-bold leading-snug transition-colors group-hover:text-brand-pop"
              :class="heading"
            >
              {{ a.title }}
            </h3>
            <p class="mt-3 line-clamp-2 flex-1 text-[15px] leading-relaxed" :class="body">
              {{ a.perex }}
            </p>
            <span
              class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop transition-all group-hover:gap-3"
            >
              Číst dál
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </span>
          </div>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
