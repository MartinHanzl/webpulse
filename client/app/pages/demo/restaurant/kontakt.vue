<script setup lang="ts">
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const ph = useStockImages().get('restaurant');

useHead(() => ({ title: 'Kontakt — Savoria' }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Rezervace"
      title="Kontakt"
      :image="ph.hero"
      :crumbs="[{ label: 'Kontakt' }]"
    />

    <!-- MAP PLACEHOLDER -->
    <section class="bg-white pt-24">
      <div class="container-x">
        <div
          class="reveal relative flex h-[360px] items-center justify-center overflow-hidden rounded-3xl border border-brand-ink/10 bg-brand-cream"
        >
          <div
            class="absolute inset-0 opacity-30"
            style="
              background-image: radial-gradient(circle, rgba(0, 0, 0, 0.08) 1px, transparent 1px);
              background-size: 22px 22px;
            "
          />
          <div class="relative flex flex-col items-center gap-3 text-center">
            <span
              class="flex size-16 items-center justify-center rounded-full bg-brand text-brand-dark shadow-lg"
            >
              <span class="material-symbols-outlined text-4xl">location_on</span>
            </span>
            <span class="text-2xl uppercase tracking-wide text-brand-ink">{{
              demo.brandName
            }}</span>
            <span class="text-brand-muted">{{ demo.address }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT FORM -->
    <ThemeSectionContact
      :demo="demo"
      subtitle="Rezervace"
      title="Rezervujte si stůl"
      text="Napište nám termín a počet osob — potvrdíme rezervaci co nejdříve."
    />

    <!-- CONTACT DETAILS -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid gap-8 md:grid-cols-3">
          <a
            :href="`mailto:${demo.email}`"
            class="reveal group flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-white p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-brand-dark"
            >
              <span class="material-symbols-outlined text-3xl">mail</span>
            </span>
            <h3 class="text-lg uppercase tracking-wide">Napište nám</h3>
            <span class="text-brand-muted">{{ demo.email }}</span>
          </a>

          <a
            :href="`tel:${demo.phone.replace(/\s/g, '')}`"
            class="reveal group flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-white p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-brand-dark"
            >
              <span class="material-symbols-outlined text-3xl">call</span>
            </span>
            <h3 class="text-lg uppercase tracking-wide">Zavolejte</h3>
            <span class="text-brand-muted">{{ demo.phone }}</span>
          </a>

          <div
            class="reveal flex flex-col items-center gap-4 rounded-3xl border border-brand-ink/5 bg-white p-10 text-center shadow-sm"
          >
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">schedule</span>
            </span>
            <h3 class="text-lg uppercase tracking-wide">Otevírací doba</h3>
            <span class="text-brand-muted">Po–Čt 11–23 &middot; Pá–So 11–24 &middot; Ne 11–22</span>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
