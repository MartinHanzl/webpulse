<script setup lang="ts">
import { ref } from 'vue';

const LINKS = [
  { label: 'Služby', to: '#sluzby' },
  { label: 'Vlastní CMS', to: '#cms' },
  { label: 'Portfolio', to: '#portfolio' },
  { label: 'Ceník', to: '#cenik' },
  { label: 'FAQ', to: '#faq' },
];

const mobileOpen = ref(false);
</script>

<template>
  <header class="sticky top-0 z-50 border-b border-brand-soft bg-white/90 backdrop-blur-xl">
    <div class="container-x flex h-[72px] items-center justify-between">
      <a href="#uvod" class="flex items-center gap-2.5 text-lg font-extrabold text-brand-ink">
        <span
          class="grid size-9 place-items-center rounded-[10px] bg-brand text-[15px] font-extrabold text-white"
        >
          MH
        </span>
        Martin Hanzl
      </a>

      <nav class="hidden items-center gap-7 text-[15px] font-semibold lg:flex">
        <a
          v-for="link in LINKS"
          :key="link.to"
          :href="link.to"
          class="text-brand-ink transition-colors hover:text-brand"
        >
          {{ link.label }}
        </a>
        <a
          href="#kontakt"
          class="rounded-full bg-brand px-[22px] py-2.5 font-bold text-white transition-colors hover:bg-brand-dark"
        >
          Nezávazná poptávka
        </a>
      </nav>

      <button
        type="button"
        class="grid size-10 place-items-center rounded-xl text-brand-ink lg:hidden"
        aria-label="Otevřít menu"
        @click="mobileOpen = !mobileOpen"
      >
        <span class="material-symbols-outlined">{{ mobileOpen ? 'close' : 'menu' }}</span>
      </button>
    </div>

    <nav
      v-if="mobileOpen"
      class="flex flex-col gap-1 border-t border-brand-soft bg-white px-5 py-4 text-[15px] font-semibold lg:hidden"
    >
      <a
        v-for="link in LINKS"
        :key="link.to"
        :href="link.to"
        class="rounded-xl px-3 py-2.5 text-brand-ink transition-colors hover:bg-brand-soft"
        @click="mobileOpen = false"
      >
        {{ link.label }}
      </a>
      <a
        href="#kontakt"
        class="mt-2 rounded-full bg-brand px-[22px] py-2.5 text-center font-bold text-white"
        @click="mobileOpen = false"
      >
        Nezávazná poptávka
      </a>
    </nav>
  </header>
</template>
