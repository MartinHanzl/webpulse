<script setup lang="ts">
const STEPS = [
  {
    number: '01',
    tag: 'DO 48 HODIN',
    title: 'Konzultace zdarma',
    text: 'Probereme vaše cíle a rozpočet. Do 48 hodin víte, jestli si sedneme.',
  },
  {
    number: '02',
    tag: 'PEVNÁ CENA',
    title: 'Návrh a nacenění',
    text: 'Dostanete konkrétní návrh řešení, pevnou cenu a termín. Žádná překvapení.',
  },
  {
    number: '03',
    tag: 'PRŮBĚŽNÉ NÁHLEDY',
    title: 'Vývoj',
    text: 'Průběžně vidíte živou verzi webu a můžete cokoliv připomínkovat.',
  },
  {
    number: '04',
    tag: 'DLOUHODOBĚ',
    title: 'Spuštění a podpora',
    text: 'Web spustím, zaškolím vás a zůstávám k dispozici pro další rozvoj.',
  },
];
</script>

<template>
  <section id="proces" class="section bg-brand-dark">
    <div class="container-x">
      <div class="reveal mb-14 max-w-[640px]">
        <span
          class="mb-4 inline-block rounded-full bg-brand/25 px-3.5 py-1.5 text-sm font-bold text-brand-accent"
        >
          Jak spolupráce probíhá
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)] !text-white">Čtyři kroky k novému webu</h2>
      </div>

      <div class="relative mx-auto max-w-[920px]">
        <!-- svislá linka (jen desktop) -->
        <div
          class="absolute bottom-2 left-1/2 top-2 z-0 hidden w-0.5 -translate-x-1/2 bg-[linear-gradient(180deg,rgb(var(--brand)/0.6),rgb(var(--brand)/0.25))] md:block"
        />

        <div class="flex flex-col gap-8 md:gap-12">
          <div
            v-for="(step, index) in STEPS"
            :key="step.number"
            class="flex items-start gap-4 md:grid md:grid-cols-[1fr_64px_1fr] md:items-center md:gap-0"
          >
            <div class="grid shrink-0 place-items-center md:order-2">
              <span
                class="relative z-[2] grid size-11 place-items-center rounded-full bg-brand-accent text-[15px] font-extrabold text-brand-ink shadow-[0_0_0_6px_rgb(var(--brand-dark)),0_0_0_8px_rgb(var(--brand)/0.4)]"
              >
                {{ step.number }}
              </span>
            </div>
            <div
              class="reveal flex flex-col gap-2.5 rounded-3xl border border-brand/35 bg-brand/10 p-7"
              :class="
                index % 2 === 0
                  ? 'reveal-left md:order-1 md:items-end md:text-right'
                  : 'reveal-right md:order-3'
              "
            >
              <span class="text-[13px] font-extrabold tracking-wider text-brand-accent">
                {{ step.tag }}
              </span>
              <h3 class="m-0 text-xl !text-white">{{ step.title }}</h3>
              <p class="m-0 text-[15px] leading-relaxed text-white/65">{{ step.text }}</p>
            </div>
            <div v-if="index % 2 === 0" class="hidden md:order-3 md:block" />
            <div v-else class="hidden md:order-1 md:block" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
