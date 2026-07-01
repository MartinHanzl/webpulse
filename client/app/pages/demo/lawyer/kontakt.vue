<script setup lang="ts">
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'lawyer';
const ph = useStockImages().get('lawyer');

useHead(() => ({ title: 'Kontakt — Veritas' }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Kontakt"
      title="Spojte se s námi"
      :image="ph.hero"
      :crumbs="[{ label: 'Kontakt' }]"
    />

    <!-- INFO BOXES -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="grid gap-8 md:grid-cols-3">
          <div
            class="reveal flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-brand-cream p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">location_on</span>
            </span>
            <h3 class="text-lg">Adresa</h3>
            <span class="text-brand-muted">{{ demo.address }}</span>
          </div>

          <a
            :href="`tel:${demo.phone.replace(/\s/g, '')}`"
            class="reveal group flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-brand-cream p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-white"
            >
              <span class="material-symbols-outlined text-3xl">call</span>
            </span>
            <h3 class="text-lg">Telefon</h3>
            <span class="text-brand-muted">{{ demo.phone }}</span>
          </a>

          <a
            :href="`mailto:${demo.email}`"
            class="reveal group flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-brand-cream p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-white"
            >
              <span class="material-symbols-outlined text-3xl">mail</span>
            </span>
            <h3 class="text-lg">E-mail</h3>
            <span class="text-brand-muted">{{ demo.email }}</span>
          </a>
        </div>
      </div>
    </section>

    <!-- CONTACT FORM -->
    <ThemeSectionContact
      :demo="demo"
      subtitle="Konzultace"
      title="Napište nám"
      text="Popište nám svůj případ — ozveme se vám a navrhneme další postup."
    />

    <!-- MAP + HOURS -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid gap-8 lg:grid-cols-3">
          <!-- Map placeholder -->
          <div
            class="reveal relative flex h-[360px] items-center justify-center overflow-hidden rounded-3xl border border-brand-ink/10 bg-white lg:col-span-2"
          >
            <div
              class="absolute inset-0 opacity-30"
              style="
                background-image: radial-gradient(
                  circle,
                  rgba(21, 40, 51, 0.08) 1px,
                  transparent 1px
                );
                background-size: 22px 22px;
              "
            />
            <div class="relative flex flex-col items-center gap-3 text-center">
              <span
                class="flex size-16 items-center justify-center rounded-full bg-brand text-white shadow-lg"
              >
                <span class="material-symbols-outlined text-4xl">location_on</span>
              </span>
              <span class="text-2xl text-brand-ink">{{ demo.brandName }}</span>
              <span class="text-brand-muted">{{ demo.address }}</span>
            </div>
          </div>

          <!-- Opening hours -->
          <div class="reveal flex flex-col justify-center rounded-3xl bg-brand-dark p-10">
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/20 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">schedule</span>
            </span>
            <h3 class="mt-6 text-2xl !text-white">Otevírací doba</h3>
            <ul class="mt-6 flex flex-col gap-4 text-white/80">
              <li class="flex items-center justify-between border-b border-white/10 pb-4">
                <span>Pondělí – Pátek</span>
                <span class="font-semibold text-brand">8:00 – 18:00</span>
              </li>
              <li class="flex items-center justify-between border-b border-white/10 pb-4">
                <span>Sobota</span>
                <span class="font-semibold text-brand">Dle domluvy</span>
              </li>
              <li class="flex items-center justify-between">
                <span>Neděle</span>
                <span class="font-semibold text-white/50">Zavřeno</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
