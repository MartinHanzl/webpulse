<script setup lang="ts">
import { computed } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useSpaContent } from '~/../app/composables/useSpaContent';
import { useStockImages } from '~/../app/composables/useStockImages';
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

const props = defineProps<{ demo: DemoDefinition }>();

const ph = useStockImages().get('spa');
const { categories, priceList, stats, benefits, testimonials } = useSpaContent();

// --- Hero slider -----------------------------------------------------------
const slides = [
  { image: ph.hero, subtitle: 'Ayurvédské procedury', title: 'Uvolněte tělo\ni mysl' },
  { image: ph.facts, subtitle: 'Lázně a wellness', title: 'Chvíle klidu\njen pro vás' },
  { image: ph.work[0], subtitle: 'Masáže a terapie', title: 'Odpočiňte si\nv luxusu' },
];
const {
  index: heroIndex,
  go: heroGo,
  next: heroNext,
  prev: heroPrev,
  pause: heroPause,
  resume: heroResume,
} = useAutoSlider(slides.length, 6000);

// --- Testimonial carousel --------------------------------------------------
const {
  index: tIndex,
  next: tNext,
  prev: tPrev,
  pause: tPause,
  resume: tResume,
} = useAutoSlider(testimonials.length, 6000);

// --- Price list split into two columns ------------------------------------
const half = Math.ceil(priceList.length / 2);
const priceCols = [priceList.slice(0, half), priceList.slice(half)];

// --- Stat parsing: leading integer counts up, the rest becomes a suffix ---
function statParts(value: string): { to?: number; suffix?: string; text?: string } {
  const m = value.match(/^(\d+)(.*)$/);
  if (!m) return { text: value };
  return { to: Number(m[1]), suffix: m[2] };
}
const parsedStats = computed(() => stats.map((s) => ({ ...s, ...statParts(s.value) })));

// --- Partner logos (faint monochrome placeholder marks) --------------------
const logos = [
  { icon: 'coffee', name: 'Cyber Coffee' },
  { icon: 'menu_book', name: 'Book Chat' },
  { icon: 'raven', name: 'Severe Owl' },
  { icon: 'nightlife', name: 'Three Spirits' },
  { icon: 'lighthouse', name: 'Lighthouse' },
  { icon: 'local_fire_department', name: 'Fireboy' },
];
</script>

<template>
  <div class="bg-brand-cream text-brand-ink">
    <!-- 1. HERO SLIDER -->
    <section
      class="relative min-h-screen overflow-hidden bg-brand-dark"
      @mouseenter="heroPause"
      @mouseleave="heroResume"
    >
      <div
        v-for="(slide, i) in slides"
        :key="i"
        class="absolute inset-0 transition-opacity duration-1000 ease-out"
        :class="heroIndex === i ? 'z-10 opacity-100' : 'z-0 opacity-0'"
      >
        <img :src="slide.image" alt="" class="size-full object-cover" />
        <div class="absolute inset-0 bg-brand-dark/55" />
      </div>

      <!-- Slide content -->
      <div class="container-x relative z-20 flex min-h-screen items-center justify-center py-32">
        <div class="mx-auto max-w-3xl text-center">
          <transition name="fade" mode="out-in">
            <div :key="heroIndex">
              <span
                class="reveal inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.35em] text-brand"
              >
                <span class="h-px w-8 bg-brand" />
                {{ slides[heroIndex].subtitle }}
                <span class="h-px w-8 bg-brand" />
              </span>
              <h1
                class="reveal mt-8 whitespace-pre-line text-5xl leading-[1.05] text-white sm:text-6xl lg:text-7xl"
              >
                {{ slides[heroIndex].title }}
              </h1>
              <div class="reveal mt-10">
                <ThemeButton :to="`/demo/${props.demo.slug}/kontakt`" variant="outline" size="lg">
                  Rezervovat termín
                </ThemeButton>
              </div>
            </div>
          </transition>
        </div>
      </div>

      <!-- Arrows -->
      <button
        type="button"
        aria-label="Předchozí"
        class="absolute left-5 top-1/2 z-30 hidden size-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 text-white transition-colors hover:border-brand hover:bg-brand hover:text-white sm:flex lg:left-10"
        @click="heroPrev"
      >
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      <button
        type="button"
        aria-label="Další"
        class="absolute right-5 top-1/2 z-30 hidden size-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 text-white transition-colors hover:border-brand hover:bg-brand hover:text-white sm:flex lg:right-10"
        @click="heroNext"
      >
        <span class="material-symbols-outlined">chevron_right</span>
      </button>

      <!-- Pagination -->
      <div class="absolute inset-x-0 bottom-10 z-30 flex items-center justify-center gap-4">
        <template v-for="(slide, i) in slides" :key="i">
          <span v-if="i === 1" class="h-px w-8 bg-white/40" />
          <button
            type="button"
            class="text-sm font-semibold tracking-[0.2em] transition-colors"
            :class="heroIndex === i ? 'text-brand' : 'text-white/50 hover:text-white'"
            @click="heroGo(i)"
          >
            {{ String(i + 1).padStart(2, '0') }}
          </button>
        </template>
      </div>
    </section>

    <!-- 2. ABOUT STUDIO -->
    <section class="relative overflow-hidden bg-brand-soft py-28">
      <ThemeLeaf
        flip
        class="floaty pointer-events-none absolute -right-8 bottom-16 hidden w-36 text-brand/10 lg:block"
      />
      <div class="container-x">
        <div class="grid items-center gap-16 lg:grid-cols-2">
          <div class="reveal-left">
            <span class="text-sm font-semibold uppercase tracking-[0.25em] text-brand">
              O studiu
            </span>
            <h2 class="mt-5 text-4xl leading-tight sm:text-5xl">
              Odpočiňte si v luxusním lázeňském studiu.
            </h2>
            <p class="mt-6 max-w-lg text-lg leading-relaxed text-brand-muted">
              Náš tým profesionálů propojuje promyšlené postupy, přírodní produkty a péči o každý
              detail. Vytváříme prostor, kde se tělo i mysl zbaví napětí a načerpají novou energii.
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-6">
              <ThemeButton :to="`/demo/${props.demo.slug}/o-nas`" variant="outline" size="md">
                Více o nás
              </ThemeButton>
              <a
                :href="`tel:${props.demo.phone.replace(/\s/g, '')}`"
                class="group inline-flex items-center gap-3 font-semibold text-brand-ink"
              >
                <span
                  class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand transition-colors group-hover:bg-brand group-hover:text-white"
                >
                  <span class="material-symbols-outlined">call</span>
                </span>
                {{ props.demo.phone }}
              </a>
            </div>
          </div>

          <div class="reveal-right relative">
            <span
              class="absolute -left-2 top-6 z-10 flex flex-col leading-none text-brand-ink lg:top-10"
            >
              <span class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-muted">
                Od roku
              </span>
              <span class="text-6xl font-semibold lg:text-7xl">1995</span>
            </span>
            <div class="ml-auto w-4/5 overflow-hidden rounded-xl">
              <img :src="ph.aboutMain" alt="" class="h-[520px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 left-0 w-3/5 overflow-hidden rounded-xl border-4 border-brand-soft shadow-2xl sm:-left-6"
            >
              <img :src="ph.aboutSecondary" alt="" class="h-56 w-full object-cover" />
            </div>
          </div>
        </div>

        <!-- Stats -->
        <div class="mt-24 grid gap-10 text-center sm:grid-cols-2 sm:text-left lg:grid-cols-4">
          <div
            v-for="s in parsedStats"
            :key="s.label"
            class="reveal border-t border-brand-ink/10 pt-6"
          >
            <div class="flex items-start justify-center gap-1 sm:justify-start">
              <span class="text-5xl font-semibold leading-none">
                <ThemeCounter v-if="s.to !== undefined" :to="s.to" :suffix="s.suffix" />
                <template v-else>{{ s.text }}</template>
              </span>
              <span class="material-symbols-outlined text-2xl text-brand">arrow_upward</span>
            </div>
            <p class="mt-3 text-sm text-brand-muted">{{ s.label }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. CATEGORIES -->
    <section class="relative overflow-hidden bg-brand-cream py-28">
      <div class="container-x">
        <div class="reveal mx-auto max-w-xl text-center">
          <span class="text-sm font-semibold uppercase tracking-[0.25em] text-brand">
            Luxusní služby
          </span>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <article
            v-for="(cat, i) in categories"
            :key="cat.slug"
            class="reveal group grid grid-cols-2 overflow-hidden rounded-lg bg-brand-soft shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="flex flex-col justify-between gap-12 p-7 xl:p-8">
              <span class="material-symbols-outlined text-5xl text-brand">{{ cat.icon }}</span>
              <div>
                <h3 class="text-2xl">{{ cat.name }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-brand-muted">{{ cat.text }}</p>
              </div>
            </div>
            <div class="relative min-h-[280px] overflow-hidden">
              <img
                :src="cat.image"
                alt=""
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="pointer-events-none absolute -left-3 bottom-4 text-8xl font-semibold leading-none text-white/50 mix-blend-overlay"
              >
                {{ String(i + 1).padStart(2, '0') }}
              </span>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 4. PRICE LIST -->
    <section class="relative overflow-hidden bg-brand-soft py-28">
      <ThemeLeaf
        class="floaty pointer-events-none absolute -left-8 bottom-24 hidden w-32 text-brand/10 lg:block"
      />
      <div class="container-x">
        <div class="reveal mx-auto max-w-2xl text-center">
          <h2 class="text-3xl leading-snug sm:text-4xl">
            Dopřejte si chvíli odpočinku —
            <NuxtLink
              :to="`/demo/${props.demo.slug}/procedury`"
              class="underline decoration-1 underline-offset-[6px] transition-colors hover:text-brand"
            >
              prohlédnout ceník
            </NuxtLink>
          </h2>
        </div>

        <div class="mx-auto mt-16 grid max-w-5xl gap-x-16 gap-y-2 lg:grid-cols-2">
          <ul v-for="(col, c) in priceCols" :key="c" class="flex flex-col">
            <li
              v-for="item in col"
              :key="item.name"
              class="reveal flex items-center gap-5 border-b border-brand-ink/10 py-6"
            >
              <img :src="item.image" alt="" class="size-20 shrink-0 rounded-full object-cover" />
              <div class="min-w-0 flex-1">
                <div class="flex items-baseline gap-3">
                  <h3 class="text-xl">{{ item.name }}</h3>
                  <span class="min-w-0 flex-1 border-b border-dotted border-brand-ink/20" />
                  <span class="shrink-0 text-xl font-semibold text-brand">{{ item.price }}</span>
                </div>
                <p class="mt-1 text-sm text-brand-muted">{{ item.desc }}</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </section>

    <!-- 5. BENEFITS -->
    <section class="relative overflow-hidden border-t border-brand-ink/10 bg-brand-cream py-28">
      <ThemeLeaf
        flip
        class="floaty pointer-events-none absolute -right-8 top-20 hidden w-36 text-brand/10 lg:block"
      />
      <div class="container-x">
        <div class="grid items-center gap-16 lg:grid-cols-2">
          <div class="reveal-left relative">
            <span
              class="absolute left-0 top-1/2 hidden -translate-y-1/2 rotate-180 whitespace-nowrap text-sm font-semibold uppercase tracking-[0.25em] text-brand-muted [writing-mode:vertical-rl] lg:block"
            >
              Lázně pro tělo i duši
            </span>
            <div class="overflow-hidden rounded-xl lg:ml-16">
              <img :src="ph.choose" alt="" class="h-[560px] w-full object-cover" />
            </div>
          </div>

          <div class="reveal-right">
            <span class="text-sm font-semibold uppercase tracking-[0.25em] text-brand">
              Výhody lázní
            </span>
            <h2 class="mt-5 text-4xl leading-tight sm:text-5xl">
              100% přírodní a organické produkty.
            </h2>
            <p class="mt-6 max-w-lg text-lg leading-relaxed text-brand-muted">
              Každý z nás hledá místo, kde načerpá klid a novou energii. Probuďte své smysly a
              osvěžte mysl v prostředí, které dýchá pohodou.
            </p>
            <ul class="mt-8 max-w-lg">
              <li
                v-for="b in benefits"
                :key="b"
                class="flex items-center gap-4 border-b border-brand-ink/10 py-4 text-xl"
              >
                <span class="material-symbols-outlined text-brand">check_circle</span>
                {{ b }}
              </li>
            </ul>
            <div class="mt-9">
              <ThemeButton :to="`/demo/${props.demo.slug}/procedury`" variant="outline" size="md">
                Zobrazit procedury
              </ThemeButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. TESTIMONIAL -->
    <section
      class="relative overflow-hidden bg-brand-soft py-28"
      @mouseenter="tPause"
      @mouseleave="tResume"
    >
      <ThemeLeaf
        class="floaty pointer-events-none absolute -left-8 top-1/3 hidden w-28 text-brand/10 lg:block"
      />
      <ThemeLeaf
        flip
        class="floaty pointer-events-none absolute -right-8 top-1/3 hidden w-28 text-brand/10 lg:block"
      />
      <div class="container-x">
        <div class="mx-auto mb-14 size-28 text-brand">
          <ThemeCircleText text="• Užijte si přírodní lázně • luxusní péče a klid " :duration="20">
            <span
              class="flex size-16 items-center justify-center rounded-full bg-[#6f7d5a] text-white"
            >
              <span class="material-symbols-outlined text-3xl">format_quote</span>
            </span>
          </ThemeCircleText>
        </div>

        <div class="relative mx-auto max-w-4xl">
          <button
            type="button"
            aria-label="Předchozí"
            class="absolute -left-2 top-1/2 -translate-y-1/2 text-sm font-semibold uppercase tracking-[0.2em] text-brand-muted transition-colors hover:text-brand lg:-left-8"
            @click="tPrev"
          >
            Prev
          </button>
          <button
            type="button"
            aria-label="Další"
            class="absolute -right-2 top-1/2 -translate-y-1/2 text-sm font-semibold uppercase tracking-[0.2em] text-brand-muted transition-colors hover:text-brand lg:-right-8"
            @click="tNext"
          >
            Next
          </button>

          <transition name="fade" mode="out-in">
            <figure :key="tIndex" class="px-8 text-center sm:px-14">
              <blockquote class="text-2xl leading-relaxed text-brand-ink sm:text-3xl">
                <h3 class="font-normal">„{{ testimonials[tIndex].text }}"</h3>
              </blockquote>
              <figcaption class="mt-8">
                <span class="block text-sm font-semibold uppercase tracking-[0.2em] text-brand">
                  {{ testimonials[tIndex].name }}
                </span>
                <span
                  class="mt-1 block text-xs font-semibold uppercase tracking-[0.15em] text-brand-muted"
                >
                  {{ testimonials[tIndex].role }}
                </span>
              </figcaption>
            </figure>
          </transition>
        </div>
      </div>
    </section>

    <!-- 7. LOGOS -->
    <section class="border-y border-brand-ink/10 bg-brand-cream py-16">
      <div class="container-x">
        <p
          class="mb-10 text-center text-sm font-semibold uppercase tracking-[0.25em] text-brand-muted"
        >
          Spolupracujeme s
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-14 gap-y-8">
          <div
            v-for="logo in logos"
            :key="logo.name"
            class="flex items-center gap-2 text-brand-ink/40 transition-colors hover:text-brand-ink/70"
          >
            <span class="material-symbols-outlined text-3xl">{{ logo.icon }}</span>
            <span class="text-sm font-semibold uppercase tracking-[0.15em]">{{ logo.name }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- 8. CONTACT / RESERVATION -->
    <ThemeSectionContact
      :demo="props.demo"
      subtitle="Rezervace"
      title="Rezervujte si termín"
      text="Napište nám a my se ozveme s volnými termíny."
    />
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
