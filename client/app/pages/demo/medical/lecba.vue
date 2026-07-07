<script setup lang="ts">
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

definePageMeta({ layout: false });

const slug = 'medical';
const ph = useStockImages().get('medical');
const { treatments } = useMedicalContent();

useHead(() => ({ title: 'Léčba — Vitalmed' }));

const excellenceSlides = [
  {
    title: 'Nejmodernější infrastruktura.',
    text: 'Diagnostické přístroje poslední generace zkracují čekání na výsledky i samotnou léčbu.',
  },
  {
    title: 'Certifikované transplantační centrum.',
    text: 'Naše týmy provádějí i nejnáročnější zákroky s mezinárodní akreditací.',
  },
  {
    title: 'Mikroskopická chirurgie nádorů.',
    text: 'Šetrné operační postupy s minimální zátěží pro pacienta.',
  },
];
const {
  index: exIndex,
  next: exNext,
  prev: exPrev,
  pause: exPause,
  resume: exResume,
} = useAutoSlider(excellenceSlides.length, 4000);
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section class="relative overflow-hidden bg-brand-soft lg:mx-10 lg:rounded-2xl">
      <img
        :src="ph.facts"
        alt=""
        class="absolute inset-0 size-full object-cover opacity-25 mix-blend-luminosity"
      />
      <div class="container-x relative grid min-h-[420px] items-center gap-10 py-24 lg:grid-cols-2">
        <div>
          <p class="reveal flex items-center gap-3 text-lg font-semibold text-brand">
            <span class="h-0.5 w-10 bg-brand" />
            Péče na světové úrovni
          </p>
          <h1 class="reveal mt-4 text-5xl sm:text-6xl">Naše léčba</h1>
        </div>
        <div class="relative hidden min-h-[220px] md:block">
          <div
            class="floaty absolute left-4 top-12 w-[250px] rounded-xl bg-white/70 p-6 shadow-xl backdrop-blur-md"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">healing</span>
            </span>
            <p class="mt-3 font-bold">Nejlepší léčba</p>
            <p class="text-sm text-brand-muted">Specializovaní lékaři</p>
          </div>
          <span
            class="sonar-ring absolute right-8 top-4 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">medical_services</span>
          </span>
        </div>
      </div>
    </section>

    <!-- CLINICAL EXCELLENCE SLIDER -->
    <section class="section bg-white">
      <div class="container-x grid items-center gap-16 lg:grid-cols-12">
        <div class="reveal-left relative lg:col-span-6">
          <div class="overflow-hidden rounded-xl">
            <img :src="ph.work[1]" alt="" class="aspect-[5/4] w-full object-cover" />
          </div>
          <div
            class="absolute -bottom-8 right-6 flex w-[300px] items-center gap-4 rounded-xl bg-white p-4 shadow-2xl"
          >
            <img :src="ph.work[2]" alt="" class="h-20 w-28 shrink-0 rounded-lg object-cover" />
            <p class="text-sm leading-snug">
              Jedno z <strong>předních kardiologických</strong> center v zemi.
            </p>
          </div>
        </div>

        <div
          class="reveal-right lg:col-span-5 lg:col-start-8"
          @mouseenter="exPause"
          @mouseleave="exResume"
        >
          <p class="flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">bookmark_star</span>
            </span>
            Klinická excelence
          </p>

          <transition name="fade" mode="out-in">
            <div :key="exIndex" class="mt-6 min-h-[190px]">
              <h2 class="text-4xl leading-tight sm:text-5xl">
                {{ excellenceSlides[exIndex].title }}
              </h2>
              <p class="mt-5 text-lg leading-relaxed text-brand-muted">
                {{ excellenceSlides[exIndex].text }}
              </p>
            </div>
          </transition>

          <div class="mt-8 flex gap-3">
            <button
              type="button"
              aria-label="Předchozí"
              class="flex size-12 items-center justify-center rounded-lg border border-brand-ink/15 transition-colors hover:border-brand hover:bg-brand hover:text-white"
              @click="exPrev"
            >
              <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <button
              type="button"
              aria-label="Další"
              class="flex size-12 items-center justify-center rounded-lg border border-brand-ink/15 transition-colors hover:border-brand hover:bg-brand hover:text-white"
              @click="exNext"
            >
              <span class="material-symbols-outlined">arrow_forward</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- SPECIALIST TREATMENTS -->
    <section class="relative bg-brand-soft py-24 lg:mx-10 lg:rounded-2xl">
      <svg
        class="pointer-events-none absolute -left-10 -top-10 hidden w-72 text-brand/20 lg:block"
        viewBox="0 0 200 200"
        fill="none"
        stroke="currentColor"
      >
        <template v-for="row in 5" :key="row">
          <path
            v-for="col in 5"
            :key="col"
            :transform="`translate(${col * 36 - 26} ${row * 36 - 26}) rotate(30 14 14)`"
            d="M14 1l11.3 6.5v13L14 27 2.7 20.5v-13z"
          />
        </template>
      </svg>

      <div class="container-x">
        <h2 class="reveal text-center text-4xl sm:text-5xl">Specializovaná léčba</h2>

        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
          <article
            v-for="treatment in treatments"
            :key="treatment.slug"
            class="reveal group rounded-xl bg-white p-9 shadow-xl shadow-brand-ink/5 transition-colors duration-300 hover:bg-brand"
          >
            <span
              class="flex size-24 items-center justify-center rounded-full bg-brand-soft text-brand transition-colors duration-300 group-hover:bg-white/15 group-hover:text-white"
            >
              <span class="material-symbols-outlined text-5xl">{{ treatment.icon }}</span>
            </span>
            <h3
              class="mt-7 text-xl font-bold transition-colors duration-300 group-hover:text-white"
            >
              {{ treatment.name }}
            </h3>
            <p
              class="mt-2 leading-relaxed text-brand-muted transition-colors duration-300 group-hover:text-white/80"
            >
              {{ treatment.text }}
            </p>
          </article>
        </div>

        <p class="reveal mt-12 text-center text-lg font-semibold">
          Zdravotní péče zítřka pro celou vaši rodinu.
          <NuxtLink
            :to="`/demo/${slug}/objednani`"
            class="text-brand underline decoration-brand/40 underline-offset-4 transition-colors hover:text-brand-dark"
          >
            Objednejte se online.
          </NuxtLink>
        </p>
      </div>
    </section>
  </ThemeInnerLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
