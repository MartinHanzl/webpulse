<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoService } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    items: DemoService[];
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
        subtitle="Naše služby"
        title="Co pro vás zajistíme"
        :light="dark"
        max="max-w-2xl"
      />

      <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <NuxtLink
          v-for="(s, i) in items"
          :key="s.slug"
          :to="`/demo/${demo.slug}/sluzby/${s.slug}`"
          class="reveal group flex flex-col rounded-2xl p-8 transition-all duration-300"
          :class="card"
          :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
        >
          <span
            class="flex size-14 items-center justify-center rounded-2xl bg-brand-pop/10 text-brand-pop transition-colors group-hover:bg-brand-pop group-hover:text-white"
          >
            <span class="material-symbols-outlined text-[28px]">{{ s.icon }}</span>
          </span>
          <h3
            class="mt-6 text-xl font-bold transition-colors group-hover:text-brand-pop"
            :class="heading"
          >
            {{ s.name }}
          </h3>
          <p class="mt-3 flex-1 text-[15px] leading-relaxed" :class="body">{{ s.perex }}</p>
          <span
            class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop transition-all group-hover:gap-3"
          >
            Více
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </span>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>
