<script setup lang="ts">
const BENEFITS = [
  'Hotová administrace — nasazení v řádu dní, ne měsíců',
  'Napojím ji na jakýkoliv web, i ten stávající',
  'CMS i CRM v jednom — obsah, kontakty a poptávky pohromadě',
  'Multitenant architektura — více webů či poboček pod jedním účtem',
  'Žádné licenční poplatky třetím stranám',
];
</script>

<template>
  <section id="cms" class="section bg-brand-soft/50">
    <div class="container-x grid items-center gap-16 lg:grid-cols-[1fr_1.1fr]">
      <div class="reveal reveal-left flex flex-col items-start gap-[22px]">
        <span
          class="inline-flex items-center gap-2 rounded-full bg-brand-accent px-4 py-2 text-sm font-extrabold text-brand-ink"
        >
          ★ Moje konkurenční výhoda
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)]">
          Vlastní CMS/CRM systém s hotovou administrací
        </h2>
        <p class="m-0 text-pretty text-[17px] leading-[1.7] text-brand-muted">
          Nemusíte platit licence za krabicová řešení ani čekat, až se administrace naprogramuje.
          Mám vlastní multitenant CMS/CRM systém, který napojím na jakýkoliv web — váš obsah,
          zákazníky i poptávky spravujete z jednoho místa od prvního dne.
        </p>
        <ul class="m-0 flex list-none flex-col gap-3 p-0 font-semibold">
          <li v-for="benefit in BENEFITS" :key="benefit" class="flex items-baseline gap-3">
            <span class="font-extrabold text-brand">✓</span>
            {{ benefit }}
          </li>
        </ul>
        <a
          href="#kontakt"
          class="rounded-full bg-brand px-8 py-[15px] font-bold text-white transition-colors hover:bg-brand-dark"
        >
          Chci vidět ukázku
        </a>
      </div>

      <div class="reveal reveal-right relative">
        <div
          class="relative aspect-[16/10] w-full overflow-hidden rounded-3xl border border-brand-soft shadow-[0_32px_64px_rgb(var(--brand-dark)/0.18)]"
        >
          <SiteImagePlaceholder
            label="Screenshot administrace vašeho CMS/CRM"
            class="absolute inset-0"
          />
        </div>
        <div
          class="absolute -top-5 right-0 flex flex-col gap-0.5 rounded-[1.25rem] bg-white px-5 py-3.5 shadow-[0_16px_32px_rgb(var(--brand-dark)/0.12)] lg:-right-4"
        >
          <strong class="text-[15px]">Administrace v ceně webu</strong>
          <span class="text-[13px] text-brand-muted">bez měsíčních licencí</span>
        </div>
      </div>
    </div>
  </section>
</template>
