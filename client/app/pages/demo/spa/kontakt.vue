<script setup lang="ts">
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'spa';
const ph = useStockImages().get('spa');

useHead(() => ({ title: 'Kontakt — Serenity' }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section
      class="relative flex min-h-[420px] items-center justify-center overflow-hidden pb-16 pt-28 text-center"
    >
      <img :src="ph.services[4]" alt="" class="absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-brand-dark/60" />
      <div class="container-x relative">
        <h1 class="text-5xl !text-white sm:text-6xl">Kontakt</h1>
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/80">
          <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-white">Domů</NuxtLink>
          <span class="material-symbols-outlined text-base">chevron_right</span>
          <span class="text-brand">Kontakt</span>
        </nav>
      </div>
    </section>

    <!-- INFO BOXES -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="grid gap-8 md:grid-cols-3">
          <div
            class="reveal flex flex-col items-center gap-4 rounded-3xl bg-brand-soft p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
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
            class="reveal group flex flex-col items-center gap-4 rounded-3xl bg-brand-soft p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
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
            class="reveal group flex flex-col items-center gap-4 rounded-3xl bg-brand-soft p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
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
      subtitle="Rezervace"
      title="Napište nám"
      text="Ozvěte se nám a domluvíme si termín vaší návštěvy."
    />

    <!-- MAP + HOURS -->
    <section class="bg-brand-soft py-24">
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
                  rgba(54, 51, 46, 0.08) 1px,
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
              <span class="font-serif text-2xl text-brand-ink">{{ demo.brandName }}</span>
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
                <span class="font-semibold text-brand">9:00 – 21:00</span>
              </li>
              <li class="flex items-center justify-between">
                <span>Sobota – Neděle</span>
                <span class="font-semibold text-brand">10:00 – 20:00</span>
              </li>
            </ul>
            <p class="mt-6 text-sm text-white/60">
              Rezervaci doporučujeme sjednat předem telefonicky nebo přes formulář.
            </p>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
