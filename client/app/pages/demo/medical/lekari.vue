<script setup lang="ts">
import { ref } from 'vue';
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'medical';
const ph = useStockImages().get('medical');
const { doctors, featuredDoctor, steps, faq } = useMedicalContent();

useHead(() => ({ title: 'Lékaři — Vitalmed' }));

const faqOpen = ref(0);
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
            Tým specialistů
          </p>
          <h1 class="reveal mt-4 text-5xl sm:text-6xl">Naši lékaři</h1>
        </div>
        <div class="relative hidden min-h-[220px] md:block">
          <span
            class="sonar-ring absolute left-8 top-4 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">stethoscope</span>
          </span>
          <div
            class="floaty absolute right-4 top-12 w-[250px] rounded-xl bg-white/70 p-6 shadow-xl backdrop-blur-md"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">healing</span>
            </span>
            <p class="mt-3 font-bold">Nejlepší léčba</p>
            <p class="text-sm text-brand-muted">Specializovaní lékaři</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED DOCTOR -->
    <section class="section bg-white">
      <div class="container-x grid items-center gap-16 lg:grid-cols-12">
        <div class="reveal-left relative lg:col-span-5">
          <div class="overflow-hidden rounded-xl">
            <img
              :src="featuredDoctor.image"
              :alt="featuredDoctor.name"
              class="aspect-[4/5] w-full object-cover"
            />
          </div>
          <div
            class="absolute -bottom-8 -left-4 flex items-center gap-5 rounded-xl bg-brand-cream p-6 shadow-2xl sm:left-6"
          >
            <span class="text-5xl font-extrabold tracking-tight">
              <ThemeCounter :to="featuredDoctor.operations" />
            </span>
            <span class="h-12 w-px bg-brand-ink/15" />
            <p class="font-semibold leading-snug">Úspěšných<br />operací.</p>
          </div>
        </div>

        <div class="reveal-right lg:col-span-6 lg:col-start-7">
          <h2 class="text-4xl sm:text-5xl">{{ featuredDoctor.name }}</h2>
          <p class="mt-3 text-lg font-semibold text-brand">{{ featuredDoctor.role }}</p>
          <p class="mt-6 text-lg leading-relaxed text-brand-muted">{{ featuredDoctor.text }}</p>

          <ul class="mt-8 max-w-md">
            <li class="flex justify-between border-b border-brand-ink/10 py-4">
              <span class="font-semibold">Telefon:</span>
              <a
                :href="`tel:${featuredDoctor.phone.replace(/\s/g, '')}`"
                class="text-brand-muted transition-colors hover:text-brand"
                >{{ featuredDoctor.phone }}</a
              >
            </li>
            <li class="flex justify-between border-b border-brand-ink/10 py-4">
              <span class="font-semibold">Ordinace:</span>
              <a
                :href="`tel:${featuredDoctor.office.replace(/\s/g, '')}`"
                class="text-brand-muted transition-colors hover:text-brand"
                >{{ featuredDoctor.office }}</a
              >
            </li>
            <li class="flex justify-between py-4">
              <span class="font-semibold">E-mail:</span>
              <a
                :href="`mailto:${featuredDoctor.email}`"
                class="font-semibold text-brand underline decoration-brand/40 underline-offset-4"
                >{{ featuredDoctor.email }}</a
              >
            </li>
          </ul>

          <p
            class="mt-8 flex flex-wrap items-center justify-center gap-3 rounded-lg bg-brand px-6 py-4 text-center font-semibold text-white"
          >
            <span
              class="flex items-center gap-1 rounded-full bg-[#ffea23] px-3 py-1 text-sm font-bold text-brand-ink"
            >
              <span
                class="material-symbols-outlined text-sm"
                style="font-variation-settings: 'FILL' 1"
                >star</span
              >
              {{ featuredDoctor.rating }}
            </span>
            Hodnocení na základě {{ featuredDoctor.reviews }} recenzí pacientů.
          </p>
        </div>
      </div>
    </section>

    <!-- DOCTORS GRID -->
    <section class="relative bg-brand-soft py-24 pb-40 lg:mx-10 lg:rounded-2xl">
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
        <h2 class="reveal text-center text-4xl sm:text-5xl">Seznamte se s našimi lékaři</h2>

        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          <article
            v-for="doc in doctors"
            :key="doc.slug"
            class="reveal rounded-xl bg-white p-8 text-center shadow-xl shadow-brand-ink/5 transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="relative mx-auto size-36">
              <img :src="doc.image" :alt="doc.name" class="size-full rounded-full object-cover" />
              <span
                class="absolute -right-2 top-0 flex items-center gap-1 rounded-full bg-[#ffea23] px-3 py-1 text-sm font-bold text-brand-ink shadow"
              >
                <span
                  class="material-symbols-outlined text-sm"
                  style="font-variation-settings: 'FILL' 1"
                  >star</span
                >
                {{ doc.rating }}
              </span>
            </div>
            <h3 class="mt-6 text-lg font-bold">{{ doc.name }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-brand-muted">
              Specializace na
              <span class="font-semibold text-brand-ink underline decoration-brand/40">{{
                doc.specialty
              }}</span>
              na klinice Vitalmed.
            </p>
            <div
              class="mt-6 flex items-center justify-center gap-5 border-t border-brand-ink/10 pt-5 text-brand-muted"
            >
              <a
                v-for="social in ['public', 'photo_camera', 'alternate_email', 'thumb_up']"
                :key="social"
                href="#"
                class="transition-colors hover:text-brand"
                @click.prevent
              >
                <span class="material-symbols-outlined text-xl">{{ social }}</span>
              </a>
            </div>
          </article>
        </div>

        <p class="reveal mt-14 flex items-center justify-center gap-4 text-xl font-semibold">
          <span
            class="rounded-full bg-brand px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white"
            >Důvěra</span
          >
          Klinice Vitalmed důvěřuje přes 10 000 pacientů.
        </p>
      </div>
    </section>

    <!-- STEPS BAR + FAQ -->
    <section class="section bg-white !pt-0">
      <div class="container-x">
        <div
          class="reveal relative -top-16 grid gap-10 rounded-xl bg-brand p-10 text-white shadow-xl shadow-brand/25 lg:grid-cols-12"
        >
          <h3 class="text-2xl !text-white lg:col-span-3">
            Najděte
            <span class="relative inline-block">
              lékaře
              <svg
                class="absolute -bottom-1 left-0 w-full"
                viewBox="0 0 100 8"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
              >
                <path d="M2 6C20 2 50 1 98 4" />
              </svg>
            </span>
            přesně pro vás.
          </h3>
          <div class="grid gap-8 sm:grid-cols-3 lg:col-span-9">
            <div v-for="step in steps" :key="step.number" class="flex items-center gap-5">
              <span
                class="flex size-16 shrink-0 items-center justify-center rounded-full border border-white/40 text-xl font-bold shadow-lg shadow-brand-ink/10"
              >
                {{ step.number }}
              </span>
              <div>
                <p class="font-bold">{{ step.title }}</p>
                <p class="text-sm text-white/70">{{ step.text }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mx-auto max-w-3xl">
          <p class="reveal flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">help</span>
            </span>
            Základní informace pro pacienty
          </p>
          <h2 class="reveal mt-6 text-3xl sm:text-4xl">Často kladené otázky</h2>

          <div class="reveal mt-8">
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
      </div>
    </section>
  </ThemeInnerLayout>
</template>
