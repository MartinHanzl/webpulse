<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';
import { useStockImages } from '~/../app/composables/useStockImages';

const props = defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('restaurant');
const { features, menu, specials, dishes } = useRestaurantContent();

const activeCat = ref(menu[0].key);

// --- Populární chody: autoplay scroll-snap carousel ---
const track = ref<HTMLElement | null>(null);
let dishTimer: ReturnType<typeof setInterval> | undefined;

function scrollByCards(dir: number) {
  const el = track.value;
  if (el) el.scrollBy({ left: dir * el.clientWidth * 0.85, behavior: 'smooth' });
}
function autoAdvance() {
  const el = track.value;
  if (!el) return;
  if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 4) {
    el.scrollTo({ left: 0, behavior: 'smooth' });
  } else {
    scrollByCards(1);
  }
}
function startDishAuto() {
  stopDishAuto();
  dishTimer = setInterval(autoAdvance, 3000);
}
function stopDishAuto() {
  if (dishTimer) {
    clearInterval(dishTimer);
    dishTimer = undefined;
  }
}

onMounted(startDishAuto);
onBeforeUnmount(stopDishAuto);
</script>

<template>
  <div class="bg-brand-cream text-brand-ink">
    <!-- 1. HERO -->
    <section class="relative flex min-h-screen items-center overflow-hidden bg-brand-dark">
      <ThemeParallax :image="ph.hero" :speed="0.25">
        <div class="absolute inset-0 bg-brand-dark/80" />
        <div
          class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/40 to-brand-dark/70"
        />
      </ThemeParallax>

      <!-- decorative gold ring -->
      <div
        class="pointer-events-none absolute -right-40 top-1/2 hidden size-[560px] -translate-y-1/2 rounded-full border border-brand/25 lg:block"
      />
      <div
        class="pointer-events-none absolute -right-24 top-1/2 hidden size-[380px] -translate-y-1/2 rounded-full border border-brand/15 lg:block"
      />

      <div class="container-x relative z-10 py-40 text-center">
        <div class="mx-auto max-w-3xl">
          <span
            class="reveal inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.35em] text-brand"
          >
            <span class="h-px w-8 bg-brand" />
            Vítá vás Savoria
            <span class="h-px w-8 bg-brand" />
          </span>
          <h1
            class="reveal mt-8 text-6xl uppercase leading-[0.95] tracking-wide text-white sm:text-7xl lg:text-8xl"
          >
            Chuť, na kterou
            <span class="mt-2 block text-brand">
              <ThemeRotatingText :words="['nezapomenete', 'se vrátíte', 'si zamilujete']" />
            </span>
          </h1>
          <p class="reveal mx-auto mt-8 max-w-xl text-lg leading-relaxed text-white/70">
            Zážitková kuchyně z lokálních sezónních surovin, výběrová vína a servis, který dělá z
            večeře nezapomenutelný okamžik.
          </p>
          <div class="reveal mt-10 flex flex-wrap items-center justify-center gap-4">
            <ThemeButton :to="`/demo/${props.demo.slug}/kontakt`" variant="solid" size="lg">
              Rezervovat stůl
            </ThemeButton>
            <ThemeButton
              :to="`/demo/${props.demo.slug}/menu`"
              variant="light"
              size="lg"
              :icon="false"
            >
              Naše menu
            </ThemeButton>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. ABOUT -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[500px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-10 -right-4 hidden w-56 overflow-hidden rounded-2xl border-4 border-brand-cream shadow-2xl sm:block lg:w-64"
            >
              <img :src="ph.aboutSecondary" alt="" class="h-48 w-full object-cover" />
            </div>
            <div
              class="absolute -left-4 top-8 flex items-center gap-3 rounded-2xl bg-brand px-6 py-4 text-brand-dark shadow-xl"
            >
              <span class="text-4xl font-bold leading-none"><ThemeCounter :to="35" /></span>
              <span class="text-sm font-semibold uppercase leading-tight">let<br />tradice</span>
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand-accent"
            >
              <span class="h-px w-8 bg-brand-accent" />
              od roku 1988
            </p>
            <h2 class="mt-5 text-4xl uppercase leading-tight tracking-wide sm:text-5xl">
              Fine dining v srdci Prahy
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Savoria spojuje poctivé řemeslo, moderní evropskou kuchyni a lásku k detailu. Vaříme
              ze sezónních surovin od lokálních farmářů a každý talíř servírujeme jako malé umělecké
              dílo.
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
              <ThemeButton :to="`/demo/${props.demo.slug}/o-nas`" variant="dark" size="md">
                O restauraci
              </ThemeButton>
              <a
                :href="`tel:${props.demo.phone.replace(/\s/g, '')}`"
                class="group inline-flex items-center gap-3 font-semibold text-brand-ink"
              >
                <span
                  class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-brand-dark"
                >
                  <span class="material-symbols-outlined">call</span>
                </span>
                {{ props.demo.phone }}
              </a>
            </div>
          </div>
        </div>

        <div class="mt-20 grid gap-6 md:grid-cols-3">
          <div
            v-for="f in features"
            :key="f.title"
            class="reveal flex items-start gap-5 rounded-3xl border border-brand-ink/5 bg-white p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-14 shrink-0 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">{{ f.icon }}</span>
            </span>
            <div>
              <h3 class="text-xl uppercase tracking-wide">{{ f.title }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ f.text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. POPULAR MENU -->
    <section class="relative overflow-hidden bg-brand-dark py-24 text-white">
      <ThemeParallax :image="ph.facts" :speed="0.2" class="opacity-10" />
      <div class="container-x relative z-10">
        <ThemeSectionHeading
          subtitle="Naše menu"
          title="Oblíbené pokrmy"
          text="Vyberte si z našich sezónních specialit — od lehkých předkrmů po dezerty a výběrová vína."
          :light="true"
          align="center"
        />

        <!-- Tabs -->
        <div class="reveal mt-12 flex flex-wrap items-center justify-center gap-3">
          <button
            v-for="cat in menu"
            :key="cat.key"
            type="button"
            class="inline-flex items-center gap-2 rounded-full border px-6 py-3 text-sm font-semibold uppercase tracking-wide transition-colors duration-300"
            :class="
              activeCat === cat.key
                ? 'border-brand bg-brand text-brand-dark'
                : 'border-white/15 text-white/70 hover:border-brand/50 hover:text-white'
            "
            @click="activeCat = cat.key"
          >
            <span class="material-symbols-outlined text-lg">{{ cat.icon }}</span>
            {{ cat.label }}
          </button>
        </div>

        <!-- Items -->
        <div
          v-for="cat in menu"
          v-show="activeCat === cat.key"
          :key="cat.key"
          class="mt-14 grid gap-x-12 gap-y-8 md:grid-cols-2"
        >
          <div v-for="item in cat.items" :key="item.name" class="reveal flex items-center gap-5">
            <img
              :src="item.image"
              alt=""
              class="size-16 shrink-0 rounded-full object-cover ring-2 ring-brand/40"
            />
            <div class="min-w-0 flex-1">
              <div class="flex items-baseline gap-3">
                <h3 class="text-xl uppercase tracking-wide text-white">{{ item.name }}</h3>
                <span class="min-w-0 flex-1 border-b border-dotted border-white/25" />
                <span class="shrink-0 text-lg font-semibold text-brand">{{ item.price }}</span>
              </div>
              <p class="mt-1 text-sm leading-relaxed text-white/50">{{ item.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. POPULAR DISHES -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-end sm:justify-between">
          <ThemeSectionHeading
            subtitle="Speciality"
            title="Populární chody"
            text="Nejžádanější pokrmy naší kuchyně, které si hosté objednávají znovu a znovu."
            align="left"
            max="max-w-xl"
          />
          <!-- Arrow navigation -->
          <div class="flex shrink-0 gap-3">
            <button
              type="button"
              aria-label="Předchozí"
              class="flex size-12 items-center justify-center rounded-full bg-brand text-brand-dark shadow-sm transition-transform duration-300 hover:scale-110"
              @click="scrollByCards(-1)"
            >
              <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button
              type="button"
              aria-label="Další"
              class="flex size-12 items-center justify-center rounded-full bg-brand text-brand-dark shadow-sm transition-transform duration-300 hover:scale-110"
              @click="scrollByCards(1)"
            >
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </div>
        </div>

        <div
          ref="track"
          class="mt-14 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-4"
          style="scrollbar-width: none"
          @mouseenter="stopDishAuto"
          @mouseleave="startDishAuto"
        >
          <article
            v-for="dish in dishes"
            :key="dish.name"
            class="reveal group w-[80%] shrink-0 snap-start overflow-hidden rounded-3xl bg-white shadow-sm transition-transform duration-300 hover:-translate-y-2 sm:w-[45%] lg:w-[30%] xl:w-[23%]"
          >
            <div class="relative">
              <img
                :src="dish.image"
                alt=""
                class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute -bottom-6 right-5 flex size-16 flex-col items-center justify-center rounded-full bg-white text-center text-sm font-bold text-brand-accent shadow-lg"
              >
                {{ dish.price }}
              </span>
            </div>
            <div class="p-6 pt-9">
              <h3 class="text-lg uppercase tracking-wide">{{ dish.name }}</h3>
              <p class="mt-2 text-sm text-brand-muted">{{ dish.ingredients }}</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 5. CHEF'S SPECIALS -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Šéfkuchař doporučuje"
          title="Doporučení šéfkuchaře"
          text="Sezónní speciality za zvýhodněnou cenu — připravené z těch nejlepších surovin."
          align="center"
        />

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <article
            v-for="s in specials"
            :key="s.name"
            class="reveal group overflow-hidden rounded-3xl border border-brand-ink/5 bg-brand-cream shadow-sm transition-transform duration-300 hover:-translate-y-1"
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

    <!-- 6. CTA / RESERVATION -->
    <ThemeSectionContact
      :demo="props.demo"
      subtitle="Rezervace"
      title="Rezervujte si stůl"
      text="Ozvěte se nám nebo vyplňte formulář — potvrdíme vám rezervaci co nejdříve."
    />
  </div>
</template>
