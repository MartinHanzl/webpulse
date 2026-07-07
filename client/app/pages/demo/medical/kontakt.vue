<script setup lang="ts">
import { ref } from 'vue';
import { useDemos } from '~/../app/composables/useDemos';
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'medical';
const ph = useStockImages().get('medical');
const demo = useDemos().getDemo(slug)!;
const { faq, openingHours } = useMedicalContent();

useHead(() => ({ title: 'Kontakt — Vitalmed' }));

const faqOpen = ref(0);

const contactBoxes = [
  {
    icon: 'location_on',
    title: 'Adresa kliniky',
    lines: [demo.address, '140 00 Praha 4'],
  },
  {
    icon: 'mail',
    title: 'E-mailová adresa',
    lines: [demo.email, 'kariera@vitalmed.cz'],
    mail: true,
  },
  {
    icon: 'call',
    title: 'Zavolejte nám',
    lines: [`Telefon: ${demo.phone}`, 'Recepce: +420 233 456 780'],
  },
  {
    icon: 'work',
    title: 'Konzultace s lékařem',
    lines: ['Osobně i formou videohovoru.', 'Po–Pá 8.00–20.00'],
  },
];
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
            Jsme tu pro vás
          </p>
          <h1 class="reveal mt-4 text-5xl sm:text-6xl">Kontakt</h1>
        </div>
        <div class="relative hidden min-h-[220px] md:block">
          <span
            class="sonar-ring absolute left-8 top-4 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">ambulance</span>
          </span>
          <div
            class="floaty absolute right-4 top-12 w-[250px] rounded-xl bg-white/70 p-6 shadow-xl backdrop-blur-md"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">location_on</span>
            </span>
            <p class="mt-3 font-bold">Klinika blízko vás</p>
            <p class="text-sm text-brand-muted">Profesionální lékaři</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT INFO BOXES -->
    <section class="section bg-white">
      <div class="container-x grid gap-12 text-center sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="box in contactBoxes"
          :key="box.title"
          class="reveal group flex flex-col items-center gap-4"
        >
          <span
            class="flex size-24 items-center justify-center rounded-full border border-brand/20 text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white"
          >
            <span class="material-symbols-outlined text-4xl">{{ box.icon }}</span>
          </span>
          <h3 class="text-lg font-bold">{{ box.title }}</h3>
          <p class="leading-relaxed text-brand-muted">
            <template v-for="(line, i) in box.lines" :key="line">
              <a
                v-if="box.mail"
                :href="`mailto:${line}`"
                class="underline decoration-brand/40 underline-offset-4 transition-colors hover:text-brand"
                >{{ line }}</a
              >
              <template v-else>{{ line }}</template>
              <br v-if="i < box.lines.length - 1" />
            </template>
          </p>
        </div>
      </div>
    </section>

    <!-- MAP -->
    <section id="location" class="overflow-hidden lg:mx-10 lg:rounded-2xl">
      <iframe
        title="Mapa — klinika Vitalmed"
        src="https://www.openstreetmap.org/export/embed.html?bbox=14.42,50.04,14.46,50.07&layer=mapnik&marker=50.055,14.44"
        class="h-[420px] w-full border-0 grayscale"
        loading="lazy"
      />
    </section>

    <!-- FAQ + OPENING HOURS -->
    <section class="section bg-white">
      <div class="container-x grid gap-16 lg:grid-cols-12">
        <div class="reveal-left lg:col-span-6">
          <p class="flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">help</span>
            </span>
            Základní informace pro pacienty
          </p>
          <h2 class="mt-6 text-4xl sm:text-5xl">Rádi vám pomůžeme.</h2>

          <div class="mt-8">
            <div v-for="(item, i) in faq" :key="item.question" class="border-b border-brand-ink/10">
              <button
                type="button"
                class="flex w-full items-center gap-4 py-6 text-left text-lg font-bold"
                @click="faqOpen = faqOpen === i ? -1 : i"
              >
                <span class="flex-1">{{ item.question }}</span>
                <span class="material-symbols-outlined text-brand-muted">
                  {{ faqOpen === i ? 'remove' : 'add' }}
                </span>
              </button>
              <p v-show="faqOpen === i" class="pb-6 leading-relaxed text-brand-muted">
                {{ item.answer }}
              </p>
            </div>
          </div>
        </div>

        <div class="reveal-right lg:col-span-5 lg:col-start-8">
          <div class="rounded-xl bg-brand-cream p-10">
            <p class="flex items-center gap-4">
              <span
                class="flex size-14 items-center justify-center rounded-full bg-brand text-white"
              >
                <span class="material-symbols-outlined">history</span>
              </span>
              <span class="text-xl font-bold">Ordinační hodiny</span>
            </p>
            <ul class="mt-6">
              <li
                v-for="(row, i) in openingHours"
                :key="row.label"
                class="flex items-center justify-between py-4"
                :class="i < openingHours.length - 1 ? 'border-b border-brand-ink/10' : ''"
              >
                <span class="font-semibold">{{ row.label }}</span>
                <span
                  :class="row.value === 'Zavřeno' ? 'font-bold text-[#f45959]' : 'text-brand-muted'"
                  >{{ row.value }}</span
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
