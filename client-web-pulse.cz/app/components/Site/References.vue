<script setup lang="ts">
const REFERENCES = [
  {
    quote:
      '„Nový web nám do měsíce přivedl první poptávky. Komunikace rychlá, termíny seděly na den přesně."',
    name: 'Petra Svobodová',
    role: 'majitelka, Studio Květina',
  },
  {
    quote:
      '„Převzal náš zastaralý e‑shop a během dvou měsíců zdvojnásobil rychlost načítání. Konverze šly znatelně nahoru."',
    name: 'Martin Doležal',
    role: 'jednatel, Nářadí Doležal s.r.o.',
  },
  {
    quote:
      '„Spolupracujeme na subdodávkách přes rok. Odevzdává čistý kód, drží slovo a klienti si ho chválí."',
    name: 'Lucie Hrubá',
    role: 'projektová manažerka, Agentura Pixel',
  },
];
</script>

<template>
  <section id="reference" class="section">
    <div class="container-x">
      <div class="reveal mb-14 max-w-[640px]">
        <span
          class="mb-4 inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          Reference
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)]">Co říkají klienti</h2>
      </div>
      <div class="grid gap-6 md:grid-cols-3">
        <figure
          v-for="reference in REFERENCES"
          :key="reference.name"
          class="reveal m-0 flex flex-col gap-[18px] rounded-3xl bg-brand-soft/50 p-8"
        >
          <span class="text-[22px] tracking-widest text-brand-accent">★★★★★</span>
          <blockquote class="m-0 leading-[1.65] text-brand-ink">
            {{ reference.quote }}
          </blockquote>
          <figcaption class="flex flex-col gap-0.5">
            <strong class="text-[15px]">{{ reference.name }}</strong>
            <span class="text-sm text-brand-muted">{{ reference.role }}</span>
          </figcaption>
        </figure>
      </div>
    </div>
  </section>
</template>
