<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoProject } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    project: DemoProject;
    others: DemoProject[];
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

const meta = computed(() => [
  { icon: 'category', label: 'Kategorie', value: props.project.category },
  { icon: 'place', label: 'Lokalita', value: props.project.location },
  { icon: 'calendar_today', label: 'Rok', value: props.project.year },
]);
</script>

<template>
  <div>
    <section class="section" :class="section">
      <div class="container-x">
        <NuxtLink
          :to="`/demo/${demo.slug}/reference`"
          class="reveal inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all hover:gap-3"
        >
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          Zpět na výpis
        </NuxtLink>

        <div class="reveal mt-6 max-w-3xl">
          <span
            class="inline-block rounded-full bg-brand-soft px-3 py-1 text-xs font-semibold text-brand"
          >
            {{ project.category }}
          </span>
          <h1
            class="mt-4 text-3xl font-bold leading-tight sm:text-4xl lg:text-[44px]"
            :class="heading"
          >
            {{ project.title }}
          </h1>
        </div>

        <div class="reveal mt-8 overflow-hidden rounded-3xl">
          <img
            :src="project.image"
            :alt="project.title"
            class="aspect-[16/9] w-full object-cover"
          />
        </div>

        <div class="reveal mt-8 grid gap-5 sm:grid-cols-3">
          <div
            v-for="m in meta"
            :key="m.label"
            class="flex items-center gap-4 rounded-2xl p-5"
            :class="card"
          >
            <span
              class="flex size-11 items-center justify-center rounded-xl bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined">{{ m.icon }}</span>
            </span>
            <div>
              <div class="text-xs font-semibold uppercase tracking-wider" :class="body">
                {{ m.label }}
              </div>
              <div class="text-[15px] font-bold" :class="heading">{{ m.value }}</div>
            </div>
          </div>
        </div>

        <p class="reveal mt-8 max-w-3xl text-lg leading-relaxed" :class="body">
          {{ project.description }}
        </p>

        <div class="reveal mt-10">
          <ThemeButton :to="`/demo/${demo.slug}/kontakt`" variant="solid" size="lg">
            Nezávazná poptávka
          </ThemeButton>
        </div>
      </div>
    </section>

    <!-- Other projects -->
    <section v-if="others.length" class="section" :class="sectionAlt">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Reference"
          title="Další realizace"
          :light="dark"
          align="left"
          max="max-w-2xl"
        />
        <div class="mt-12 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
          <NuxtLink
            v-for="(o, i) in others"
            :key="o.slug"
            :to="`/demo/${demo.slug}/reference/${o.slug}`"
            class="reveal group flex flex-col overflow-hidden rounded-2xl transition-all duration-300"
            :class="card"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <div class="relative aspect-[4/3] overflow-hidden">
              <img
                :src="o.image"
                :alt="o.title"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <span
                class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-brand shadow-sm"
              >
                {{ o.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col p-6">
              <h3
                class="text-lg font-bold transition-colors group-hover:text-brand"
                :class="heading"
              >
                {{ o.title }}
              </h3>
              <div class="mt-3 flex items-center gap-4 text-sm" :class="body">
                <span class="inline-flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[18px] text-brand">place</span>
                  {{ o.location }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[18px] text-brand"
                    >calendar_today</span
                  >
                  {{ o.year }}
                </span>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>
