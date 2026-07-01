<script setup lang="ts">
import { computed } from 'vue';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();
const ph = useStockImages().get('restaurant');

const featured = computed(() => c.chefs[0]);
const rest = computed(() => c.chefs.slice(1));

useHead(() => ({ title: 'Kuchaři — Savoria' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Tým profesionálů"
      title="Naši kuchaři"
      :crumbs="[{ label: 'Kuchaři' }]"
      :image="ph.hero"
    />

    <!-- FEATURED CHEF -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="featured.image" alt="" class="h-[540px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -left-4 hidden items-center gap-3 rounded-2xl bg-brand px-6 py-4 text-brand-dark shadow-xl sm:flex"
            >
              <span class="material-symbols-outlined text-4xl">restaurant</span>
              <span class="text-sm font-semibold uppercase leading-tight"
                >Šéfkuchař<br />Savoria</span
              >
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand-accent"
            >
              <span class="h-px w-8 bg-brand-accent" />
              Tvář naší kuchyně
            </p>
            <h2 class="mt-5 text-4xl uppercase leading-tight tracking-wide sm:text-5xl">
              {{ featured.name }}
            </h2>
            <p class="mt-3 text-lg font-semibold text-brand">{{ featured.role }}</p>
            <div class="mt-6 h-1 w-20 rounded-full bg-brand" />
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              {{ featured.name.split(' ')[0] }} stojí u kamen již přes dvě desetiletí a do Savorie
              přinesl {{ featured.cuisine.toLowerCase() }} kuchyni v moderním pojetí. Jeho filozofií
              je respekt k surovině a čistá, přesná chuť.
            </p>
            <p class="mt-4 text-lg leading-relaxed text-brand-muted">
              „Vaření je pro mě řemeslo i vášeň. Každý talíř, který opustí kuchyni, musí vyprávět
              příběh," říká náš šéfkuchař.
            </p>
            <p class="mt-8 text-3xl uppercase tracking-[0.2em] text-brand-ink">
              {{ featured.name }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CHEFS GRID -->
    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš tým"
          title="Kuchaři a cukráři"
          text="Za každým pokrmem stojí sehraný tým profesionálů, kteří milují svou práci."
          align="center"
        />

        <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <article
            v-for="chef in rest"
            :key="chef.name"
            class="reveal group relative overflow-hidden rounded-3xl shadow-sm"
          >
            <img
              :src="chef.image"
              alt=""
              class="h-96 w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/30 to-transparent"
            />

            <!-- social dots -->
            <div
              class="absolute right-5 top-5 flex translate-y-3 flex-col gap-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"
            >
              <span
                v-for="icon in ['photo_camera', 'alternate_email', 'public']"
                :key="icon"
                class="flex size-9 items-center justify-center rounded-full bg-white/90 text-brand-dark transition-colors hover:bg-brand"
              >
                <span class="material-symbols-outlined text-lg">{{ icon }}</span>
              </span>
            </div>

            <div class="absolute inset-x-0 bottom-0 p-7">
              <span
                class="inline-flex items-center gap-1.5 rounded-full bg-brand px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-dark"
              >
                {{ chef.cuisine }}
              </span>
              <h3 class="mt-3 text-2xl uppercase tracking-wide text-white">{{ chef.name }}</h3>
              <p class="mt-1 text-sm text-white/70">{{ chef.role }}</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- QUOTE -->
    <section class="bg-brand py-20 text-brand-dark lg:py-28">
      <div class="container-x">
        <div class="reveal mx-auto max-w-3xl text-center">
          <span class="material-symbols-outlined text-6xl opacity-40">format_quote</span>
          <p class="mt-4 text-2xl font-medium leading-relaxed sm:text-3xl">
            V naší kuchyni nejde jen o jídlo. Jde o okamžik, který sdílíte s lidmi, na kterých vám
            záleží. A my děláme vše pro to, aby byl dokonalý.
          </p>
          <div class="mt-8 flex items-center justify-center gap-4">
            <img
              :src="featured.image"
              alt=""
              class="size-14 rounded-full object-cover ring-2 ring-brand-dark/20"
            />
            <div class="text-left">
              <p class="text-lg font-bold uppercase tracking-wide">{{ featured.name }}</p>
              <p class="text-sm opacity-70">{{ featured.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
