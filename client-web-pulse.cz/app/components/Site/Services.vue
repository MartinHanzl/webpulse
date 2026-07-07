<script setup lang="ts">
const SERVICES = [
  {
    icon: '</>',
    title: 'Weby na míru',
    text: 'Firemní weby a prezentace postavené přesně podle vašich potřeb, ne podle šablony.',
  },
  {
    icon: '🛒',
    title: 'Systémy',
    text: 'Prodejní e‑shopy na Shopify nebo na míru — včetně napojení na platby a dopravce.',
  },
  {
    icon: '⚙',
    title: 'Správa a údržba',
    text: 'Pravidelné aktualizace, zálohy a drobné úpravy. Web, o který se nemusíte starat.',
  },
  {
    icon: '⚡',
    title: 'Rychlost a SEO',
    text: 'Audit a optimalizace stávajícího webu — rychlejší načítání, lepší pozice ve vyhledávání.',
  },
];
</script>

<template>
  <section id="sluzby" class="section">
    <div class="container-x">
      <div class="reveal mb-14 max-w-[640px]">
        <span
          class="mb-4 inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          Co nabízím
        </span>
        <h2 class="mb-3 mt-0 text-[clamp(30px,3vw,44px)]">
          Od první konzultace po dlouhodobou správu
        </h2>
        <p class="m-0 text-pretty text-[17px] leading-relaxed text-brand-muted">
          Nemusíte řešit deset dodavatelů. Návrh, vývoj i provoz vašeho webu zvládnu sám — a osobně
          za něj ručím.
        </p>
      </div>
      <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="service in SERVICES"
          :key="service.title"
          class="reveal flex flex-col gap-3.5 rounded-3xl bg-brand-cream px-7 py-8"
        >
          <span
            class="grid size-12 place-items-center rounded-[14px] bg-brand text-xl font-extrabold text-white"
          >
            {{ service.icon }}
          </span>
          <h3 class="m-0 text-xl">{{ service.title }}</h3>
          <p class="m-0 text-[15px] leading-relaxed text-brand-muted">{{ service.text }}</p>
        </div>
      </div>
    </div>
  </section>
</template>
