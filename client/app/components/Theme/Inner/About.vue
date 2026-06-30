<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';
import { useStockImages } from '~/../app/composables/useStockImages';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
  }>(),
  { dark: false },
);

const c = useDemoContent(props.demo.slug);
const sph = useStockImages().get(props.demo.slug);

const sectionAlt = computed(() => (props.dark ? 'bg-neutral-900' : 'bg-brand-cream'));
const sectionBase = computed(() => (props.dark ? 'bg-neutral-950' : 'bg-white'));
const heading = computed(() => (props.dark ? 'text-white' : 'text-brand-ink'));
const body = computed(() => (props.dark ? 'text-white/60' : 'text-brand-muted'));
const card = computed(() =>
  props.dark
    ? 'bg-white/[0.04] border border-white/10'
    : 'bg-white border border-slate-100 hover:-translate-y-1.5 hover:shadow-xl',
);
</script>

<template>
  <div>
    <!-- Intro -->
    <section class="section" :class="sectionBase">
      <div class="container-x grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div class="order-2 lg:order-1">
          <ThemeSectionHeading
            :subtitle="demo.industry"
            :title="c.about.lead"
            align="left"
            :light="dark"
          />
          <div class="reveal mt-6 flex flex-col gap-4">
            <p
              v-for="(p, i) in c.about.paragraphs"
              :key="i"
              class="text-lg leading-relaxed"
              :class="body"
            >
              {{ p }}
            </p>
          </div>
        </div>

        <div class="reveal reveal-right relative order-1 lg:order-2">
          <div class="overflow-hidden rounded-3xl">
            <img :src="sph.aboutMain" alt="" class="aspect-[5/6] w-full object-cover" />
          </div>
          <div
            class="absolute -bottom-8 -left-8 hidden w-44 overflow-hidden rounded-2xl border-4 shadow-xl sm:block"
            :class="dark ? 'border-neutral-950' : 'border-white'"
          >
            <img :src="sph.aboutSecondary" alt="" class="aspect-square w-full object-cover" />
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="section" :class="sectionAlt">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naše hodnoty"
          title="Na čem nám záleží"
          :light="dark"
          max="max-w-2xl"
        />
        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="(v, i) in c.about.values"
            :key="v.title"
            class="reveal flex flex-col rounded-2xl p-8 transition-all duration-300"
            :class="card"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined text-[28px]">{{ v.icon }}</span>
            </span>
            <h3 class="mt-6 text-xl font-bold" :class="heading">{{ v.title }}</h3>
            <p class="mt-3 text-[15px] leading-relaxed" :class="body">{{ v.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Counters band -->
    <section class="bg-brand py-20">
      <div class="container-x">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="(co, i) in c.about.counters"
            :key="co.label"
            class="reveal text-center"
            :style="{ transitionDelay: `${i * 90}ms` }"
          >
            <div class="text-4xl font-bold text-white sm:text-5xl">{{ co.value }}</div>
            <div class="mt-2 text-sm font-medium uppercase tracking-wider text-white/70">
              {{ co.label }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team -->
    <section class="section" :class="sectionBase">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš tým"
          title="Lidé, kteří to dělají"
          :light="dark"
          max="max-w-2xl"
        />
        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="(m, i) in c.about.team"
            :key="m.name"
            class="reveal group overflow-hidden rounded-2xl transition-all duration-300"
            :class="card"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <div class="aspect-[4/3] overflow-hidden">
              <img
                :src="m.image"
                :alt="m.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="p-6">
              <h3 class="text-lg font-bold" :class="heading">{{ m.name }}</h3>
              <p class="mt-1 text-sm font-semibold text-brand">{{ m.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
