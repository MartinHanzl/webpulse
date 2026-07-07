<script setup lang="ts">
import { ref } from 'vue';
import { useDemos } from '~/../app/composables/useDemos';
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'medical';
const ph = useStockImages().get('medical');
const demo = useDemos().getDemo(slug)!;
const { appointmentDoctors } = useMedicalContent();

useHead(() => ({ title: 'Objednání — Vitalmed' }));

const usps = [
  { icon: 'clinical_notes', title: 'Profesionální lékaři' },
  { icon: 'support_agent', title: 'Podpora pacientů 24/7' },
  { icon: 'family_restroom', title: 'Rodinná medicína' },
  { icon: 'event_available', title: 'Objednání bez čekání' },
];

const form = ref({ name: '', email: '', date: '', time: '', doctor: '', message: '' });
const formSent = ref(false);
function submitAppointment() {
  formSent.value = true;
}
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
            Ideální čas na návštěvu
          </p>
          <h1 class="reveal mt-4 text-5xl sm:text-6xl">Objednání</h1>
        </div>
        <div class="relative hidden min-h-[220px] md:block">
          <span
            class="sonar-ring absolute left-8 top-4 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">home_health</span>
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

    <!-- USP BADGES -->
    <section class="section bg-white !pb-16">
      <div class="container-x grid gap-10 text-center sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="(usp, i) in usps"
          :key="usp.title"
          class="reveal flex flex-col items-center gap-4"
          :class="i > 0 ? 'sm:border-l sm:border-brand-ink/10' : ''"
        >
          <span
            class="flex size-24 items-center justify-center rounded-full bg-brand-soft text-brand"
          >
            <span class="material-symbols-outlined text-5xl">{{ usp.icon }}</span>
          </span>
          <p class="text-lg font-bold leading-snug">{{ usp.title }}</p>
        </div>
      </div>
    </section>

    <!-- BOOKING FORM -->
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
        <h2 class="reveal text-center text-4xl sm:text-5xl">Rezervujte si termín</h2>

        <div class="reveal mx-auto mt-14 max-w-4xl">
          <div
            v-if="formSent"
            class="flex flex-col items-center justify-center rounded-xl bg-white p-14 text-center shadow-xl shadow-brand-ink/5"
          >
            <span class="material-symbols-outlined text-6xl text-brand">event_available</span>
            <h3 class="mt-4 text-2xl">Děkujeme za objednávku!</h3>
            <p class="mt-2 text-brand-muted">Ozveme se vám co nejdříve s potvrzením termínu.</p>
          </div>

          <form v-else class="grid gap-5 md:grid-cols-2" @submit.prevent="submitAppointment">
            <div class="flex flex-col gap-5">
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Jméno a příjmení pacienta*"
                class="w-full rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
              />
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="E-mail pacienta*"
                class="w-full rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
              />
              <div class="grid grid-cols-2 gap-5">
                <input
                  v-model="form.date"
                  type="date"
                  class="w-full rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
                />
                <input
                  v-model="form.time"
                  type="time"
                  min="08:00"
                  max="20:00"
                  class="w-full rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
                />
              </div>
            </div>

            <div class="flex flex-col gap-5">
              <select
                v-model="form.doctor"
                class="w-full rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
                :class="form.doctor === '' ? 'text-brand-muted' : ''"
              >
                <option value="" disabled selected>Vyberte lékaře</option>
                <option v-for="d in appointmentDoctors" :key="d" :value="d">{{ d }}</option>
              </select>
              <textarea
                v-model="form.message"
                rows="4"
                placeholder="Vaše zpráva"
                class="w-full flex-1 resize-none rounded-lg border border-white bg-white px-5 py-4 shadow-lg shadow-brand-ink/5 outline-none transition-colors focus:border-brand"
              />
            </div>

            <p class="text-sm text-brand-muted md:self-center">
              Ochranu vašich osobních údajů bereme vážně. Bez vašeho souhlasu je nikdy nikomu
              nepředáme.
            </p>
            <div class="md:justify-self-end">
              <ThemeButton variant="solid" size="lg" :icon="false" type="submit">
                <span class="material-symbols-outlined text-xl">event_available</span>
                Objednat se
              </ThemeButton>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- PHONE CTA STRIP -->
    <section class="bg-white py-16">
      <div
        class="container-x flex flex-col items-center justify-center gap-6 text-center lg:flex-row lg:gap-10"
      >
        <img :src="ph.blog[1]" alt="" class="reveal-left h-16 w-24 rounded-lg object-cover" />
        <p class="reveal-left max-w-xl text-lg">
          S jakýmkoli zdravotním dotazem se obraťte na naši recepci nebo volejte
          <a
            :href="`tel:${demo.phone.replace(/\s/g, '')}`"
            class="font-bold underline decoration-brand/40 underline-offset-4 transition-colors hover:text-brand"
            >{{ demo.phone }}</a
          >
        </p>
        <a
          :href="`tel:${demo.phone.replace(/\s/g, '')}`"
          class="reveal-right inline-flex items-center gap-2 rounded-full bg-brand-dark px-9 py-4 font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-brand"
        >
          <span class="material-symbols-outlined text-xl">call</span>
          {{ demo.phone }}
        </a>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
