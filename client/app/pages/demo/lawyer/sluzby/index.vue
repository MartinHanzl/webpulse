<script setup lang="ts">
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'lawyer';
const c = useLawyerContent();
const ph = useStockImages().get('lawyer');

const intro = [
  'Individuální přístup ke každému klientovi',
  'Transparentní ceny bez skrytých poplatků',
  'Zkušený tým napříč obory práva',
  'Diskrétnost a mlčenlivost samozřejmostí',
];

useHead(() => ({ title: 'Právní služby — Veritas' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Oblasti práva"
      title="Právní služby"
      :crumbs="[{ label: 'Právní služby' }]"
      :image="ph.hero"
    />

    <!-- INTRO SPLIT -->
    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[520px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -right-4 hidden items-center gap-3 rounded-2xl bg-brand-dark px-6 py-5 text-white shadow-xl sm:flex"
            >
              <span class="material-symbols-outlined text-4xl text-brand">balance</span>
              <span class="text-sm font-semibold uppercase leading-tight"
                >25 let<br />právní praxe</span
              >
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Komplexní právní servis
            </p>
            <h2 class="mt-5 text-4xl leading-tight sm:text-5xl">
              Právní jistota <span class="italic text-brand">ve všech</span> oblastech
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Advokátní kancelář Veritas poskytuje právní služby fyzickým i právnickým osobám napříč
              všemi obory práva. Ať už řešíte obchodní spor, rodinnou záležitost nebo trestní věc,
              postaráme se o vás s maximální péčí.
            </p>
            <ul class="mt-8 grid gap-4 sm:grid-cols-2">
              <li v-for="item in intro" :key="item" class="flex items-start gap-3 text-brand-ink">
                <span class="material-symbols-outlined mt-0.5 text-brand">check_circle</span>
                <span class="font-medium">{{ item }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BAND -->
    <section class="bg-brand-dark py-14">
      <div class="container-x">
        <div
          class="flex flex-col items-center gap-6 text-center md:flex-row md:justify-between md:text-left"
        >
          <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-brand">Nevíte si rady?</p>
            <h3 class="mt-2 text-2xl !text-white sm:text-3xl">
              Zavolejte nám — naši advokáti vám pomohou
            </h3>
            <a
              :href="`tel:+420234567800`"
              class="mt-3 inline-block text-2xl font-semibold text-brand transition-colors hover:text-white"
              >+420 234 567 800</a
            >
          </div>
          <ThemeButton to="/demo/lawyer/kontakt" variant="accent" size="lg"
            >Nezávazná konzultace</ThemeButton
          >
        </div>
      </div>
    </section>

    <!-- PRACTICE AREAS — FLIP CARDS -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naše specializace"
          title="Oblasti práva"
          text="Najeďte na kartu a otočte ji. Ke každému případu přistupujeme individuálně."
          align="center"
        />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <NuxtLink
            v-for="area in c.practiceAreas"
            :key="area.slug"
            :to="`/demo/lawyer/sluzby/${area.slug}`"
            class="reveal group h-[340px] [perspective:1400px]"
          >
            <div
              class="relative size-full rounded-3xl shadow-sm transition-transform duration-700 [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]"
            >
              <!-- FRONT -->
              <div
                class="absolute inset-0 flex flex-col rounded-3xl border border-brand-dark/5 bg-white p-8 [backface-visibility:hidden]"
              >
                <span
                  class="flex size-16 items-center justify-center rounded-2xl bg-brand-soft text-brand"
                >
                  <span class="material-symbols-outlined text-3xl">{{ area.icon }}</span>
                </span>
                <h3 class="mt-6 text-xl">{{ area.name }}</h3>
                <p class="mt-3 flex-1 text-sm leading-relaxed text-brand-muted">{{ area.perex }}</p>
                <span
                  class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand"
                >
                  Otočit
                  <span class="material-symbols-outlined text-[18px]">autorenew</span>
                </span>
              </div>

              <!-- BACK -->
              <div
                class="absolute inset-0 overflow-hidden rounded-3xl [backface-visibility:hidden] [transform:rotateY(180deg)]"
              >
                <img :src="area.image" alt="" class="size-full object-cover" />
                <div class="absolute inset-0 bg-brand-dark/85" />
                <div class="absolute inset-0 flex flex-col justify-center p-8 text-white">
                  <span class="material-symbols-outlined text-3xl text-brand">{{ area.icon }}</span>
                  <h3 class="mt-4 text-xl !text-white">{{ area.name }}</h3>
                  <p class="mt-3 text-sm leading-relaxed text-white/70">{{ area.perex }}</p>
                  <span
                    class="mt-5 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand"
                  >
                    Více
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                  </span>
                </div>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- MARQUEE -->
    <ThemeSectionMarquee
      :words="['Spravedlnost', 'Důvěra', 'Zkušenost', 'Výsledky', 'Diskrétnost']"
      icon="gavel"
    />
  </ThemeInnerLayout>
</template>
