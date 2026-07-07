<script setup lang="ts">
const HIGHLIGHTS = [
  'Pevné ceny a termíny',
  'Komunikace do 48 h',
  'Web vlastníte vy',
  'Podpora i po předání',
];
</script>

<template>
  <section id="o-mne" class="section">
    <div class="container-x grid items-center gap-16 lg:grid-cols-[0.8fr_1.2fr]">
      <div class="reveal reveal-left flex justify-center">
        <div class="relative">
          <img
            src="/images/portret.webp"
            alt="Martin Hanzl — portrét"
            class="h-[460px] w-[380px] max-w-full rounded-[32px] object-cover"
          />
          <div
            class="absolute bottom-7 right-0 flex flex-col gap-0.5 rounded-[1.25rem] bg-white px-5 py-3.5 shadow-[0_16px_32px_rgb(var(--brand-dark)/0.12)] lg:-right-5"
          >
            <strong class="text-[15px]">Martin Hanzl</strong>
            <span class="text-[13px] text-brand-muted">webdeveloper na volné noze</span>
          </div>
        </div>
      </div>

      <div class="reveal reveal-right flex flex-col items-start gap-5">
        <span
          class="inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          O mně
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)]">Jeden člověk, plná odpovědnost</h2>
        <p class="m-0 text-pretty text-[17px] leading-[1.7] text-brand-muted">
          Webům se věnuji přes 5 let — začínal jsem v agentuře a v současné chvíli pracuji na volné
          noze. U mě mluvíte přímo s člověkem, který váš web skutečně staví: žádný account manager,
          žádná tichá pošta.
        </p>
        <p class="m-0 text-pretty text-[17px] leading-[1.7] text-brand-muted">
          Beru si jen tolik projektů, kolika se můžu opravdu věnovat. Proto dodávám včas a moji
          klienti se vracejí.
        </p>
        <div class="grid w-full gap-3 sm:grid-cols-2">
          <div
            v-for="highlight in HIGHLIGHTS"
            :key="highlight"
            class="flex items-center gap-2.5 rounded-2xl bg-brand-cream px-[18px] py-3.5 text-[15px] font-semibold"
          >
            <span class="text-brand">✓</span>
            {{ highlight }}
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
