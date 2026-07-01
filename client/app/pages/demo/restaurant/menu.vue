<script setup lang="ts">
import { ref } from 'vue';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

const activeCat = ref(c.menu[0].key);

useHead(() => ({ title: 'Menu — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Sezónní nabídka"
      title="Naše menu"
      :crumbs="[{ label: 'Menu' }]"
      :image="ph.hero"
    />

    <!-- INTRO -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[460px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -right-4 hidden items-center gap-3 rounded-2xl bg-brand px-6 py-4 text-brand-dark shadow-xl sm:flex"
            >
              <span class="material-symbols-outlined text-4xl">restaurant_menu</span>
              <span class="text-sm font-semibold uppercase leading-tight">Sezónní<br />lístek</span>
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand-accent"
            >
              <span class="h-px w-8 bg-brand-accent" />
              Chuť a řemeslo
            </p>
            <h2 class="mt-5 text-4xl uppercase leading-tight tracking-wide sm:text-5xl">
              Poctivé pokrmy ze sezónních surovin
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Naše menu se mění podle toho, co právě dozrálo u našich farmářů a rybářů. Každý talíř
              připravujeme s důrazem na detail, čerstvost surovin a vyváženou chuť.
            </p>
            <p class="mt-4 text-lg leading-relaxed text-brand-muted">
              Vyberte si z předkrmů, hlavních chodů, vegetariánských specialit, dezertů i výběrových
              vín — a nechte se vést naším sommeliérem.
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
              <ThemeButton :to="`/demo/${slug}/kontakt`" variant="dark" size="md">
                Rezervovat stůl
              </ThemeButton>
              <a
                href="tel:+420212345678"
                class="group inline-flex items-center gap-3 font-semibold text-brand-ink"
              >
                <span
                  class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-brand-dark"
                >
                  <span class="material-symbols-outlined">call</span>
                </span>
                +420 212 345 678
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FULL MENU WITH TABS -->
    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Kompletní lístek"
          title="Naše menu"
          text="Vyberte kategorii a prohlédněte si všechny pokrmy, které pro vás připravujeme."
          align="center"
        />

        <!-- Tabs -->
        <div class="reveal mt-12 flex flex-wrap items-center justify-center gap-3">
          <button
            v-for="cat in c.menu"
            :key="cat.key"
            type="button"
            class="inline-flex items-center gap-2 rounded-full border px-6 py-3 text-sm font-semibold uppercase tracking-wide transition-colors duration-300"
            :class="
              activeCat === cat.key
                ? 'border-brand bg-brand text-brand-dark'
                : 'border-brand-ink/15 text-brand-muted hover:border-brand/50 hover:text-brand-ink'
            "
            @click="activeCat = cat.key"
          >
            <span class="material-symbols-outlined text-lg">{{ cat.icon }}</span>
            {{ cat.label }}
          </button>
        </div>

        <!-- Items -->
        <div
          v-for="cat in c.menu"
          v-show="activeCat === cat.key"
          :key="cat.key"
          class="mt-14 grid gap-x-12 gap-y-9 md:grid-cols-2"
        >
          <div v-for="item in cat.items" :key="item.name" class="reveal flex items-center gap-5">
            <img
              :src="item.image"
              alt=""
              class="size-16 shrink-0 rounded-full object-cover ring-2 ring-brand/40"
            />
            <div class="min-w-0 flex-1">
              <div class="flex items-baseline gap-3">
                <h3 class="text-xl uppercase tracking-wide">{{ item.name }}</h3>
                <span class="min-w-0 flex-1 border-b border-dotted border-brand-ink/25" />
                <span class="shrink-0 text-lg font-semibold text-brand-accent">{{
                  item.price
                }}</span>
              </div>
              <p class="mt-1 text-sm leading-relaxed text-brand-muted">{{ item.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CHEF'S SPECIALS -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Šéfkuchař doporučuje"
          title="Doporučení šéfkuchaře"
          text="Sezónní speciality za zvýhodněnou cenu — připravené z těch nejlepších surovin."
          align="center"
        />

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <article
            v-for="s in c.specials"
            :key="s.name"
            class="reveal group overflow-hidden rounded-3xl border border-brand-ink/5 bg-white shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="relative h-56 overflow-hidden">
              <img
                :src="s.image"
                alt=""
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
            </div>
            <div class="p-7">
              <div class="flex gap-0.5 text-brand">
                <span
                  v-for="n in s.rating ?? 5"
                  :key="n"
                  class="material-symbols-outlined text-lg"
                  style="font-variation-settings: 'FILL' 1"
                  >star</span
                >
              </div>
              <h3 class="mt-3 text-2xl uppercase tracking-wide">{{ s.name }}</h3>
              <p class="mt-2 text-sm text-brand-muted">{{ s.ingredients }}</p>
              <div class="mt-5 flex items-baseline gap-3">
                <span v-if="s.oldPrice" class="text-base text-brand-muted line-through">{{
                  s.oldPrice
                }}</span>
                <span class="text-2xl font-bold text-brand-accent">{{ s.price }}</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
