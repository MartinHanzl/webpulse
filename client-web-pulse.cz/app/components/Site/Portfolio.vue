<script setup lang="ts">
const PROJECTS = [
  {
    title: 'Web CHPP s. r. o.',
    tag: 'Firemní web · na míru',
    placeholder: 'Screenshot projektu 1',
  },
  { title: 'Název projektu', tag: 'E‑shop · Shopify', placeholder: 'Screenshot projektu 2' },
  { title: 'Název projektu', tag: 'Webová aplikace · React', placeholder: 'Screenshot projektu 3' },
];
</script>

<template>
  <section id="portfolio" class="section bg-brand-cream">
    <div class="container-x">
      <div class="reveal mb-14 max-w-[640px]">
        <span
          class="mb-4 inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          Portfolio
        </span>
        <h2 class="mb-3 mt-0 text-[clamp(30px,3vw,44px)]">Ukázky mé práce</h2>
        <p class="m-0 text-[17px] leading-relaxed text-brand-muted">
          Výběr projektů z poslední doby.
        </p>
      </div>
      <div class="grid gap-6 md:grid-cols-3">
        <div
          v-for="project in PROJECTS"
          :key="project.placeholder"
          class="reveal overflow-hidden rounded-3xl bg-white shadow-[0_8px_24px_rgb(var(--brand-dark)/0.06)]"
        >
          <div class="relative aspect-[16/11]">
            <SiteImagePlaceholder :label="project.placeholder" class="absolute inset-0" />
          </div>
          <div class="flex flex-col gap-1.5 px-6 py-[22px]">
            <h3 class="m-0 text-lg">{{ project.title }}</h3>
            <span class="text-sm text-brand-muted">{{ project.tag }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
