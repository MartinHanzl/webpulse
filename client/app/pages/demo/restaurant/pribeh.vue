<script setup lang="ts">
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

useHead(() => ({ title: 'Náš příběh — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Cesta od roku 1988"
      title="Náš příběh"
      :image="ph.hero"
      :crumbs="[{ label: 'Příběh' }]"
    />

    <!-- HERO IMAGE WITH OVERLAY -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="reveal relative overflow-hidden rounded-3xl">
          <img :src="ph.facts" alt="" class="h-[420px] w-full object-cover lg:h-[520px]" />
          <div class="absolute inset-0 bg-brand-dark/55" />
          <div class="absolute inset-0 flex flex-col items-center justify-center gap-6 text-center">
            <button
              type="button"
              aria-label="Přehrát video"
              class="group flex size-20 items-center justify-center rounded-full bg-brand text-brand-dark shadow-xl transition-transform duration-300 hover:scale-110"
            >
              <span
                class="material-symbols-outlined text-4xl"
                style="font-variation-settings: 'FILL' 1"
                >play_arrow</span
              >
            </button>
            <h2 class="text-4xl uppercase tracking-wide text-white sm:text-6xl">Náš příběh</h2>
          </div>
        </div>
      </div>
    </section>

    <!-- INTRO LINE -->
    <section class="bg-brand-cream py-20">
      <div class="container-x">
        <div class="reveal mx-auto max-w-3xl text-center">
          <p
            class="flex items-center justify-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand-accent"
          >
            <span class="h-px w-8 bg-brand-accent" />
            od roku 1988
            <span class="h-px w-8 bg-brand-accent" />
          </p>
          <h2 class="mt-5 text-3xl uppercase leading-tight tracking-wide sm:text-4xl">
            Více než tři desetiletí vášně pro gastronomii
          </h2>
          <p class="mt-6 text-lg leading-relaxed text-brand-muted">
            Z malé rodinné trattorie se Savoria stala jedním z nejuznávanějších podniků v Praze.
            Podívejte se, jak se náš příběh psal krok za krokem.
          </p>
        </div>
      </div>
    </section>

    <!-- TIMELINE -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="relative">
          <!-- gold connecting line -->
          <div class="absolute left-4 top-0 hidden h-full w-px bg-brand/30 lg:left-1/2 lg:block" />

          <div class="flex flex-col gap-14 lg:gap-20">
            <div
              v-for="(step, i) in c.story"
              :key="step.year"
              class="reveal relative grid items-center gap-8 lg:grid-cols-2 lg:gap-16"
            >
              <!-- node -->
              <span
                class="absolute left-4 top-8 z-10 hidden size-4 -translate-x-1/2 rounded-full border-4 border-white bg-brand shadow lg:left-1/2 lg:block"
              />

              <!-- image -->
              <div
                class="overflow-hidden rounded-3xl shadow-sm"
                :class="i % 2 === 1 ? 'lg:order-2' : ''"
              >
                <img :src="step.image" alt="" class="h-72 w-full object-cover" />
              </div>

              <!-- text -->
              <div :class="[i % 2 === 1 ? 'lg:order-1 lg:pr-16 lg:text-right' : 'lg:pl-16']">
                <span class="text-5xl font-bold uppercase tracking-wide text-brand sm:text-6xl">{{
                  step.year
                }}</span>
                <h3 class="mt-3 text-2xl uppercase tracking-wide">{{ step.title }}</h3>
                <p class="mt-3 text-base leading-relaxed text-brand-muted">{{ step.text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- AWARDS + COUNTER -->
    <section class="bg-brand-dark py-24 text-white">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Ocenění"
          title="Uznání, které nás těší"
          text="Za náš příběh mluví i řada prestižních gastronomických ocenění."
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="a in c.awards"
            :key="a.year"
            class="reveal flex flex-col items-center gap-3 rounded-3xl border border-white/10 bg-white/[0.04] p-8 text-center transition-transform duration-300 hover:-translate-y-1"
          >
            <span class="material-symbols-outlined text-4xl text-brand">emoji_events</span>
            <span class="text-3xl font-bold uppercase tracking-wide text-brand">{{ a.year }}</span>
            <h3 class="text-lg uppercase leading-tight tracking-wide text-white">{{ a.title }}</h3>
          </div>
        </div>

        <div class="reveal mt-16 flex flex-col items-center gap-2 text-center">
          <p class="text-5xl font-bold uppercase tracking-wide text-brand sm:text-6xl">
            <ThemeCounter :to="25000" suffix="+" />
          </p>
          <p class="text-lg uppercase tracking-[0.2em] text-white/70">spokojených hostů</p>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
