<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'lawyer';
const c = useLawyerContent();
const ph = useStockImages().get('lawyer');

const bars = [
  { label: 'Úspěšnost případů', value: '98 %', width: '98%' },
  { label: 'Zkušenosti týmu', value: '25 let', width: '92%' },
  { label: 'Spokojenost klientů', value: '96 %', width: '96%' },
];

// Animate the bar widths from 0 → target when the block scrolls into view.
const barsEl = ref<HTMLElement | null>(null);
const barsShown = ref(false);
let observer: IntersectionObserver | null = null;
onMounted(() => {
  if (typeof IntersectionObserver === 'undefined') {
    barsShown.value = true;
    return;
  }
  observer = new IntersectionObserver(
    (entries) => {
      if (entries.some((e) => e.isIntersecting)) {
        barsShown.value = true;
        observer?.disconnect();
      }
    },
    { threshold: 0.35 },
  );
  if (barsEl.value) observer.observe(barsEl.value);
});
onBeforeUnmount(() => observer?.disconnect());

const socials = ['public', 'alternate_email', 'call'];

useHead(() => ({ title: 'Naši advokáti — Veritas' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Tým, kterému záleží na výsledku"
      title="Naši advokáti"
      :crumbs="[{ label: 'Advokáti' }]"
      :image="ph.hero"
    />

    <!-- INTRO -->
    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[520px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -left-4 hidden items-center gap-3 rounded-2xl bg-brand px-6 py-5 text-white shadow-xl sm:flex"
            >
              <span class="material-symbols-outlined text-4xl">groups</span>
              <span class="text-sm font-semibold uppercase leading-tight"
                >12 advokátů<br />v týmu</span
              >
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Zkušenost a odbornost
            </p>
            <h2 class="mt-5 text-4xl leading-tight sm:text-5xl">
              Tým, kterému <span class="italic text-brand">záleží</span> na výsledku
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Za kanceláří Veritas stojí sehraný tým advokátů, koncipientů a odborných poradců.
              Každý z nás se specializuje na konkrétní obor práva, díky čemuž jsme schopni pokrýt i
              ty nejsložitější případy.
            </p>

            <div ref="barsEl" class="mt-8 space-y-6">
              <div v-for="bar in bars" :key="bar.label">
                <div class="flex items-center justify-between text-sm font-semibold text-brand-ink">
                  <span>{{ bar.label }}</span>
                  <span class="text-brand">{{ bar.value }}</span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-brand-soft">
                  <div
                    class="h-full rounded-full bg-brand transition-[width] duration-1000 ease-out"
                    :style="{ width: barsShown ? bar.width : '0%' }"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ATTORNEYS GRID -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naši advokáti"
          title="Poznejte náš tým"
          text="Kdokoli z nás je připraven postarat se o váš případ s maximální péčí a diskrétností."
          align="center"
        />

        <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <NuxtLink
            v-for="person in c.attorneys"
            :key="person.slug"
            :to="`/demo/lawyer/advokati/${person.slug}`"
            class="reveal group overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
          >
            <div class="relative overflow-hidden">
              <img
                :src="person.image"
                alt=""
                class="h-80 w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <div
                class="absolute inset-x-0 bottom-0 flex translate-y-4 justify-center gap-2 pb-5 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"
              >
                <span
                  v-for="icon in socials"
                  :key="icon"
                  class="flex size-9 items-center justify-center rounded-full bg-white/90 text-brand-dark transition-colors hover:bg-brand hover:text-white"
                >
                  <span class="material-symbols-outlined text-lg">{{ icon }}</span>
                </span>
              </div>
            </div>
            <div class="p-6 text-center">
              <h3 class="text-lg">{{ person.name }}</h3>
              <p class="mt-1 text-sm font-semibold text-brand">{{ person.specialization }}</p>
              <p class="mt-1 text-xs uppercase tracking-wider text-brand-muted">
                {{ person.role }}
              </p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
