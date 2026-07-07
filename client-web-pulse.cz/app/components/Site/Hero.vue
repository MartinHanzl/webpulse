<script setup lang="ts">
const STATS = [
  { value: '5+', label: 'let praxe' },
  { value: '60+', label: 'dokončených projektů' },
  { value: '48 h', label: 'reakce na poptávku' },
];
</script>

<template>
  <section
    id="uvod"
    class="section bg-[linear-gradient(180deg,rgb(var(--brand-soft)/0.55),#fff_70%)] !pb-16"
  >
    <div class="container-x grid items-center gap-14 lg:grid-cols-[1.1fr_0.9fr]">
      <div class="flex flex-col items-start gap-6">
        <span
          class="inline-flex items-center gap-2 rounded-full bg-brand-soft px-4 py-2 text-sm font-bold text-brand"
        >
          <span class="sonar-ring relative inline-block size-2 rounded-full bg-brand" />
          Přijímám nové projekty
        </span>
        <h1 class="m-0 text-[clamp(40px,4.5vw,64px)]">Weby a systémy, které pracují za vás</h1>
        <p class="m-0 max-w-[34em] text-pretty text-[19px] leading-relaxed text-brand-muted">
          Jsem Martin Hanzl, freelance webdeveloper. Firmám, startupům i agenturám stavím rychlé a
          spolehlivé weby na míru — od návrhu po dlouhodobou správu.
        </p>
        <div class="flex flex-wrap gap-3.5">
          <a
            href="#kontakt"
            class="rounded-full bg-brand px-8 py-[15px] font-bold text-white transition-colors hover:bg-brand-dark"
          >
            Poptat projekt
          </a>
          <a
            href="#portfolio"
            class="rounded-full border-2 border-brand-soft px-[30px] py-[13px] font-bold text-brand-ink transition-colors hover:border-brand hover:text-brand"
          >
            Prohlédnout práce
          </a>
        </div>
        <div class="mt-4 flex gap-10">
          <div v-for="stat in STATS" :key="stat.label" class="flex flex-col gap-0.5">
            <strong class="text-[28px] font-extrabold text-brand-ink">{{ stat.value }}</strong>
            <span class="text-sm text-brand-muted">{{ stat.label }}</span>
          </div>
        </div>
      </div>

      <div class="floaty relative">
        <div
          class="relative aspect-[4/3] w-full overflow-hidden rounded-4xl shadow-[0_32px_64px_rgb(var(--brand-dark)/0.18)]"
        >
          <img
            src="/images/hero-foto.webp"
            alt="Martin Hanzl — ukázka práce"
            class="absolute inset-0 size-full object-cover object-top"
          />
        </div>
        <div
          class="absolute -bottom-6 -left-6 rounded-[1.25rem] bg-brand-accent px-[22px] py-4 text-[15px] font-extrabold text-brand-ink shadow-[0_16px_32px_rgb(var(--brand-dark)/0.15)]"
        >
          Konzultace zdarma
        </div>
      </div>
    </div>
  </section>
</template>
