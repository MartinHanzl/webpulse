<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoProject } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    items: DemoProject[];
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
      <ThemeSectionHeading
        subtitle="Reference"
        title="Naše realizace"
        :light="dark"
        max="max-w-2xl"
      />

      <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <NuxtLink
          v-for="(p, i) in items"
          :key="p.slug"
          :to="`/demo/${demo.slug}/reference/${p.slug}`"
          class="reveal group flex flex-col overflow-hidden rounded-2xl transition-all duration-300"
          :class="card"
          :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
        >
          <div class="relative aspect-[4/3] overflow-hidden">
            <img
              :src="p.image"
              :alt="p.title"
              class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <span
              class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-brand shadow-sm"
            >
              {{ p.category }}
            </span>
          </div>
          <div class="flex flex-1 flex-col p-6">
            <h3 class="text-lg font-bold transition-colors group-hover:text-brand" :class="heading">
              {{ p.title }}
            </h3>
            <p class="mt-2 line-clamp-2 flex-1 text-[15px] leading-relaxed" :class="body">
              {{ p.excerpt }}
            </p>
            <div class="mt-5 flex items-center gap-4 text-sm" :class="body">
              <span class="inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-brand">place</span>
                {{ p.location }}
              </span>
              <span class="inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-brand">calendar_today</span>
                {{ p.year }}
              </span>
            </div>
          </div>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
