<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoArticle } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    article: DemoArticle;
    others: DemoArticle[];
  }>(),
  { dark: false },
);

const section = computed(() => (props.dark ? 'bg-neutral-950' : 'bg-white'));
const sectionAlt = computed(() => (props.dark ? 'bg-neutral-900' : 'bg-brand-cream'));
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
    <article class="section" :class="section">
      <div class="container-x max-w-3xl">
        <NuxtLink
          :to="`/demo/${demo.slug}/blog`"
          class="reveal inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all hover:gap-3"
        >
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          Zpět na výpis
        </NuxtLink>

        <header class="reveal mt-6">
          <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-brand">
            <span class="rounded-full bg-brand-soft px-3 py-1">{{ article.category }}</span>
            <span :class="body">{{ article.date }}</span>
            <span :class="body">·</span>
            <span :class="body">{{ article.author }}</span>
          </div>
          <h1
            class="mt-4 text-3xl font-bold leading-tight sm:text-4xl lg:text-[44px]"
            :class="heading"
          >
            {{ article.title }}
          </h1>
          <p class="mt-4 text-lg leading-relaxed" :class="body">{{ article.perex }}</p>
        </header>

        <div class="reveal mt-8 overflow-hidden rounded-3xl">
          <img
            :src="article.image"
            :alt="article.title"
            class="aspect-[16/9] w-full object-cover"
          />
        </div>

        <div class="reveal mt-8 flex flex-col gap-5">
          <p v-for="(p, i) in article.body" :key="i" class="text-lg leading-relaxed" :class="body">
            {{ p }}
          </p>
        </div>
      </div>
    </article>

    <!-- Other articles -->
    <section v-if="others.length" class="section" :class="sectionAlt">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Blog"
          title="Další články"
          :light="dark"
          align="left"
          max="max-w-2xl"
        />
        <div class="mt-12 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
          <NuxtLink
            v-for="(o, i) in others"
            :key="o.slug"
            :to="`/demo/${demo.slug}/blog/${o.slug}`"
            class="reveal group flex flex-col overflow-hidden rounded-2xl transition-all duration-300"
            :class="card"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <div class="aspect-[16/10] overflow-hidden">
              <img
                :src="o.image"
                :alt="o.title"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col p-7">
              <div class="flex items-center gap-3 text-xs font-semibold text-brand">
                <span class="rounded-full bg-brand-soft px-3 py-1">{{ o.category }}</span>
                <span :class="body">{{ o.date }}</span>
              </div>
              <h3
                class="mt-4 text-lg font-bold leading-snug transition-colors group-hover:text-brand"
                :class="heading"
              >
                {{ o.title }}
              </h3>
              <span
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all group-hover:gap-3"
              >
                Číst dál
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </span>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>
