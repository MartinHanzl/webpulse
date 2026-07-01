<script setup lang="ts">
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

const facilities = [
  {
    icon: 'meeting_room',
    title: 'Privátní salónek',
    text: 'Nerušené soukromí pro oslavy, firemní večeře i degustační menu až pro 20 hostů.',
    image: ph.work[0],
  },
  {
    icon: 'set_meal',
    title: 'Raw bar',
    text: 'Čerstvé ústřice, tataráky a carpaccia připravovaná přímo před vašima očima.',
    image: ph.work[1],
  },
  {
    icon: 'restaurant',
    title: 'Catering',
    text: 'Zážitkovou gastronomii Savoria přivezeme kamkoli — na svatbu i firemní event.',
    image: ph.work[2],
  },
];

useHead(() => ({ title: 'O nás — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Luxusní restaurace"
      title="O nás"
      :image="ph.hero"
      :crumbs="[{ label: 'O nás' }]"
    />

    <!-- INTRO SPLIT -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[500px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-10 -right-4 hidden w-56 overflow-hidden rounded-2xl border-4 border-white shadow-2xl sm:block lg:w-64"
            >
              <img :src="ph.aboutSecondary" alt="" class="h-48 w-full object-cover" />
            </div>
            <div
              class="absolute -left-4 top-8 flex items-center gap-3 rounded-2xl bg-brand px-6 py-4 text-brand-dark shadow-xl"
            >
              <span class="text-4xl font-bold leading-none"><ThemeCounter :to="35" /></span>
              <span class="text-sm font-semibold uppercase leading-tight">let<br />tradice</span>
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand-accent"
            >
              <span class="h-px w-8 bg-brand-accent" />
              od roku 1988
            </p>
            <h2 class="mt-5 text-4xl uppercase leading-tight tracking-wide sm:text-5xl">
              Fine dining v srdci Prahy
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Savoria vznikla z lásky k poctivé kuchyni a k lidem, kteří ji sdílejí. Už více než tři
              desetiletí spojujeme moderní evropskou gastronomii s lokálními sezónními surovinami a
              péčí o každý detail.
            </p>
            <p class="mt-4 text-lg leading-relaxed text-brand-muted">
              Náš tým šéfkuchařů, sommelierů a číšníků má jediný cíl — proměnit vaši návštěvu v
              nezapomenutelný zážitek od prvního přípitku po poslední doušek kávy.
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
              <ThemeButton :to="`/demo/${slug}/pribeh`" variant="dark" size="md">
                Náš příběh
              </ThemeButton>
              <ThemeButton :to="`/demo/${slug}/kontakt`" variant="outline" size="md" :icon="false">
                Rezervovat stůl
              </ThemeButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BLOCKQUOTE -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <figure class="reveal mx-auto max-w-3xl text-center">
          <span
            class="material-symbols-outlined text-6xl text-brand"
            style="font-variation-settings: 'FILL' 1"
            >format_quote</span
          >
          <blockquote class="mt-4 text-3xl uppercase leading-snug tracking-wide sm:text-4xl">
            Dobré jídlo je základ opravdového štěstí. My ho servírujeme na každém talíři.
          </blockquote>
          <figcaption class="mt-8 flex flex-col items-center gap-1">
            <span class="text-lg font-bold text-brand-ink">Herman Miller</span>
            <span class="text-sm uppercase tracking-wider text-brand-accent"
              >Šéfkuchař &amp; majitel</span
            >
          </figcaption>
        </figure>
      </div>
    </section>

    <!-- MARQUEE -->
    <ThemeSectionMarquee :words="['Lahodné', 'Skvělé', 'Zážitek', 'Kuchyně', 'Čerstvé']" />

    <!-- FACILITIES -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Co nabízíme"
          title="Prostory a služby"
          text="Ať už hledáte intimní večeři ve dvou nebo velkolepou oslavu, u nás najdete ten pravý prostor."
          align="center"
        />

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <article
            v-for="f in facilities"
            :key="f.title"
            class="reveal group overflow-hidden rounded-3xl border border-brand-ink/5 bg-brand-cream shadow-sm transition-transform duration-300 hover:-translate-y-2"
          >
            <div class="relative h-56 overflow-hidden">
              <img
                :src="f.image"
                alt=""
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute -bottom-6 left-6 flex size-14 items-center justify-center rounded-full bg-brand text-brand-dark shadow-lg"
              >
                <span class="material-symbols-outlined text-3xl">{{ f.icon }}</span>
              </span>
            </div>
            <div class="p-7 pt-10">
              <h3 class="text-2xl uppercase tracking-wide">{{ f.title }}</h3>
              <p class="mt-3 text-sm leading-relaxed text-brand-muted">{{ f.text }}</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- HAPPY CUSTOMERS COUNTER -->
    <section class="bg-brand-dark py-16 text-white">
      <div class="container-x">
        <div class="reveal flex flex-col items-center justify-center gap-4 text-center">
          <div class="flex gap-1 text-brand">
            <span
              v-for="n in 5"
              :key="n"
              class="material-symbols-outlined text-3xl"
              style="font-variation-settings: 'FILL' 1"
              >star</span
            >
          </div>
          <p class="text-5xl font-bold uppercase tracking-wide text-brand sm:text-6xl">
            <ThemeCounter :to="25000" suffix="+" />
          </p>
          <p class="text-lg uppercase tracking-[0.2em] text-white/70">spokojených hostů</p>
        </div>
      </div>
    </section>

    <!-- AWARDS -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Ocenění"
          title="Naše úspěchy"
          text="Práce našeho týmu si za ta léta vysloužila řadu prestižních gastronomických ocenění."
          align="center"
        />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="a in c.awards"
            :key="a.year"
            class="reveal flex flex-col items-center gap-3 rounded-3xl border border-brand-ink/5 bg-white p-8 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">emoji_events</span>
            </span>
            <span class="text-3xl font-bold uppercase tracking-wide text-brand-accent">{{
              a.year
            }}</span>
            <h3 class="text-lg uppercase leading-tight tracking-wide">{{ a.title }}</h3>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
