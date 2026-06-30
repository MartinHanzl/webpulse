<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoService } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    service: DemoService;
    others: DemoService[];
  }>(),
  { dark: false },
);

const section = computed(() => (props.dark ? 'bg-neutral-950' : 'bg-white'));
const heading = computed(() => (props.dark ? 'text-white' : 'text-brand-ink'));
const body = computed(() => (props.dark ? 'text-white/60' : 'text-brand-muted'));
const card = computed(() =>
  props.dark ? 'bg-white/[0.04] border border-white/10' : 'bg-white border border-slate-100',
);
</script>

<template>
  <section class="section" :class="section">
    <div class="container-x">
      <NuxtLink
        :to="`/demo/${demo.slug}/sluzby`"
        class="reveal inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all hover:gap-3"
      >
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Zpět na výpis
      </NuxtLink>

      <div class="mt-8 grid items-start gap-12 lg:grid-cols-3 lg:gap-14">
        <!-- Main -->
        <div class="lg:col-span-2">
          <div class="reveal overflow-hidden rounded-3xl">
            <img
              :src="service.image"
              :alt="service.name"
              class="aspect-[16/9] w-full object-cover"
            />
          </div>

          <div class="reveal mt-8 flex items-center gap-4">
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined text-[28px]">{{ service.icon }}</span>
            </span>
            <h1 class="text-3xl font-bold sm:text-4xl" :class="heading">{{ service.name }}</h1>
          </div>

          <p class="reveal mt-6 text-lg leading-relaxed" :class="body">{{ service.description }}</p>

          <ul class="reveal mt-8 grid gap-4 sm:grid-cols-2">
            <li
              v-for="f in service.features"
              :key="f"
              class="flex items-start gap-3 rounded-2xl p-5"
              :class="card"
            >
              <span
                class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-brand text-white"
              >
                <span class="material-symbols-outlined text-[18px]">check</span>
              </span>
              <span class="text-[15px] font-medium" :class="heading">{{ f }}</span>
            </li>
          </ul>

          <div class="reveal mt-10">
            <ThemeButton :to="`/demo/${demo.slug}/kontakt`" variant="solid" size="lg">
              Nezávazná poptávka
            </ThemeButton>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="reveal reveal-right rounded-2xl p-7" :class="card">
          <h2 class="text-lg font-bold" :class="heading">Další služby</h2>
          <ul class="mt-5 flex flex-col gap-2">
            <li v-for="o in others" :key="o.slug">
              <NuxtLink
                :to="`/demo/${demo.slug}/sluzby/${o.slug}`"
                class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-colors"
                :class="dark ? 'hover:bg-white/[0.06]' : 'hover:bg-brand-soft'"
              >
                <span class="material-symbols-outlined text-[22px] text-brand">{{ o.icon }}</span>
                <span
                  class="flex-1 text-[15px] font-semibold transition-colors group-hover:text-brand"
                  :class="heading"
                >
                  {{ o.name }}
                </span>
                <span class="material-symbols-outlined text-[18px] text-brand">chevron_right</span>
              </NuxtLink>
            </li>
          </ul>
        </aside>
      </div>
    </div>
  </section>
</template>
