<script setup lang="ts">
import { ref } from 'vue';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

// --- Timeline: horizontal scroll-snap carousel ---
const track = ref<HTMLElement | null>(null);
function scrollByCards(dir: number) {
  const el = track.value;
  if (el) el.scrollBy({ left: dir * el.clientWidth * 0.85, behavior: 'smooth' });
}

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

    <!-- TIMELINE CAROUSEL -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-end sm:justify-between">
          <ThemeSectionHeading
            subtitle="Milníky"
            title="Krok za krokem"
            text="Projděte si nejdůležitější okamžiky, které utvářely naši restauraci."
            align="left"
            max="max-w-xl"
          />
          <div class="flex shrink-0 gap-3">
            <button
              type="button"
              aria-label="Předchozí"
              class="flex size-12 items-center justify-center rounded-full bg-brand text-brand-dark shadow-sm transition-transform duration-300 hover:scale-110"
              @click="scrollByCards(-1)"
            >
              <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button
              type="button"
              aria-label="Další"
              class="flex size-12 items-center justify-center rounded-full bg-brand text-brand-dark shadow-sm transition-transform duration-300 hover:scale-110"
              @click="scrollByCards(1)"
            >
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </div>
        </div>

        <div
          ref="track"
          class="mt-14 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-4"
          style="scrollbar-width: none"
        >
          <article
            v-for="step in c.story"
            :key="step.year"
            class="reveal group w-[85%] shrink-0 snap-start sm:w-[55%] lg:w-[38%] xl:w-[30%]"
          >
            <div class="overflow-hidden rounded-3xl shadow-sm">
              <img
                :src="step.image"
                alt=""
                class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
            </div>
            <!-- gold connecting line + node -->
            <div class="relative mt-8 flex items-center">
              <span
                class="size-4 shrink-0 rounded-full border-4 border-white bg-brand shadow ring-1 ring-brand/40"
              />
              <span class="h-px flex-1 bg-brand/30" />
            </div>
            <div class="mt-5">
              <span class="text-4xl font-bold uppercase tracking-wide text-brand sm:text-5xl">{{
                step.year
              }}</span>
              <h3 class="mt-3 text-2xl uppercase tracking-wide">{{ step.title }}</h3>
              <p class="mt-3 text-base leading-relaxed text-brand-muted">{{ step.text }}</p>
            </div>
          </article>
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
