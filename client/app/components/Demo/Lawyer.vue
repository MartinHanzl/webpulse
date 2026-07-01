<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

const props = defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('lawyer');
const { practiceAreas, attorneys, stats, testimonials, faqs, articles } = useLawyerContent();

// --- Stats → count-up numbers (parse "1 200+" / "98 %" / "25") ---
const statNum = (v: string): { to: number; suffix: string } => ({
  to: parseInt(v.replace(/[^\d]/g, ''), 10) || 0,
  suffix: v.includes('%') ? ' %' : v.includes('+') ? '+' : '',
});
const statsC = stats.map((s) => ({ ...s, ...statNum(s.value) }));

// --- Hero slideshow (3 slides, cross-fade autoplay) ---
const slides = [
  {
    image: ph.hero,
    subtitle: 'Advokátní kancelář Veritas',
    title: 'Hájíme vaše práva\ns rozvahou a důrazem',
    text: 'Poskytujeme komplexní právní služby fyzickým i právnickým osobám. Spolehlivost, diskrétnost a osobní přístup ke každému případu.',
  },
  {
    image: ph.facts,
    subtitle: 'Právo pro firmy',
    title: 'Zkušený tým\npro vaši firmu',
    text: 'Korporátní agenda, smlouvy i obchodní spory — postaráme se o právní jistotu vašeho podnikání.',
  },
  {
    image: ph.work[0],
    subtitle: 'Efektivní řešení',
    title: 'Právní řešení,\nkterým můžete věřit',
    text: 'Za našimi výsledky stojí roky praxe a stovky úspěšně vyřešených případů napříč obory práva.',
  },
];
const {
  index: heroIndex,
  go: heroGo,
  next: heroNext,
  prev: heroPrev,
  pause: heroPause,
  resume: heroResume,
} = useAutoSlider(slides.length, 6000);
const activeSlide = computed(() => slides[heroIndex.value]);

// --- Practice areas carousel (scroll-snap + arrows + autoplay) ---
const paTrack = ref<HTMLElement | null>(null);
function paScroll(dir: number): void {
  const el = paTrack.value;
  if (el) el.scrollBy({ left: dir * el.clientWidth * 0.85, behavior: 'smooth' });
}
let paTimer: ReturnType<typeof setInterval> | undefined;
function paAuto(): void {
  const el = paTrack.value;
  if (!el) return;
  if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 8) {
    el.scrollTo({ left: 0, behavior: 'smooth' });
  } else {
    paScroll(1);
  }
}
function paStart(): void {
  paStop();
  paTimer = setInterval(paAuto, 4500);
}
function paStop(): void {
  if (paTimer) clearInterval(paTimer);
  paTimer = undefined;
}
onMounted(paStart);
onBeforeUnmount(paStop);

// --- Testimonials carousel (1-per-view cross-fade) ---
const {
  index: tIndex,
  go: tGo,
  pause: tPause,
  resume: tResume,
} = useAutoSlider(testimonials.length, 7000);
const activeQuote = computed(() => testimonials[tIndex.value]);

// --- FAQ accordion ---
const openIndex = ref(0);
const toggleFaq = (i: number): void => {
  openIndex.value = openIndex.value === i ? -1 : i;
};
</script>

<template>
  <div class="bg-white text-brand-ink">
    <!-- 1. HERO SLIDESHOW -->
    <section
      class="relative flex min-h-[92vh] items-center overflow-hidden bg-brand-dark"
      @mouseenter="heroPause()"
      @mouseleave="heroResume()"
    >
      <!-- backgrounds cross-fade -->
      <transition name="fade">
        <div :key="heroIndex" class="absolute inset-0">
          <img :src="activeSlide.image" alt="" class="size-full object-cover" />
          <div class="absolute inset-0 bg-brand-dark/85" />
          <div
            class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand-dark/75 to-brand-dark/40"
          />
        </div>
      </transition>

      <div class="container-x relative z-10 py-32">
        <div class="max-w-3xl">
          <transition name="slidefade" mode="out-in">
            <div :key="heroIndex">
              <span
                class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.35em] text-brand"
              >
                <span class="h-px w-10 bg-brand" />
                {{ activeSlide.subtitle }}
              </span>
              <h1
                class="mt-8 whitespace-pre-line font-serif text-5xl italic leading-[1.05] text-white drop-shadow-[0_2px_18px_rgba(0,0,0,0.45)] sm:text-6xl lg:text-7xl"
              >
                {{ activeSlide.title }}
              </h1>
              <p class="mt-8 max-w-xl text-lg leading-relaxed text-white/80">
                {{ activeSlide.text }}
              </p>
            </div>
          </transition>

          <div class="mt-10 flex flex-wrap items-center gap-4">
            <ThemeButton :to="`/demo/${props.demo.slug}/kontakt`" variant="solid" size="lg">
              Nezávazná konzultace
            </ThemeButton>
            <ThemeButton
              :to="`/demo/${props.demo.slug}/sluzby`"
              variant="light"
              size="lg"
              :icon="false"
            >
              Právní služby
            </ThemeButton>
          </div>

          <div class="mt-16 grid max-w-2xl grid-cols-2 gap-8 sm:grid-cols-4">
            <div v-for="s in statsC" :key="s.label" class="border-l border-brand/40 pl-4">
              <div class="font-serif text-3xl font-semibold text-brand">
                <ThemeCounter :to="s.to" :suffix="s.suffix" />
              </div>
              <div class="mt-1 text-xs uppercase tracking-wider text-white/60">{{ s.label }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- prev / next arrows -->
      <button
        type="button"
        aria-label="Předchozí"
        class="absolute left-5 top-1/2 z-20 hidden size-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/25 text-white transition-colors hover:border-brand hover:bg-brand md:flex"
        @click="heroPrev()"
      >
        <span class="material-symbols-outlined">arrow_back</span>
      </button>
      <button
        type="button"
        aria-label="Další"
        class="absolute right-5 top-1/2 z-20 hidden size-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/25 text-white transition-colors hover:border-brand hover:bg-brand md:flex"
        @click="heroNext()"
      >
        <span class="material-symbols-outlined">arrow_forward</span>
      </button>

      <!-- dots -->
      <div class="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3">
        <button
          v-for="(s, i) in slides"
          :key="s.title"
          type="button"
          :aria-label="`Snímek ${i + 1}`"
          class="h-2 rounded-full transition-all duration-300"
          :class="heroIndex === i ? 'w-8 bg-brand' : 'w-2 bg-white/40 hover:bg-white/70'"
          @click="heroGo(i)"
        />
      </div>
    </section>

    <!-- 2. ABOUT + STATS -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img
                :src="ph.aboutMain"
                alt=""
                class="aspect-[4/5] w-full object-cover lg:aspect-auto lg:h-[520px]"
              />
            </div>
            <div
              class="absolute -bottom-8 -right-4 hidden rounded-2xl bg-brand-dark p-7 text-white shadow-2xl sm:block"
            >
              <div class="flex gap-8">
                <div v-for="s in statsC.slice(0, 2)" :key="s.label">
                  <div class="font-serif text-4xl font-semibold text-brand">
                    <ThemeCounter :to="s.to" :suffix="s.suffix" />
                  </div>
                  <div class="mt-1 text-xs uppercase tracking-wider text-white/60">
                    {{ s.label }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-10 bg-brand" />
              O kanceláři
            </p>
            <h2 class="mt-5 font-serif text-4xl italic leading-tight sm:text-5xl">
              Právo je náš svět už 25 let
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Advokátní kancelář Veritas patří mezi respektované právní kanceláře. Naši advokáti
              spojují hluboké znalosti práva s praktickou zkušeností a bojují za nejlepší možný
              výsledek pro každého klienta.
            </p>
            <ul class="mt-8 space-y-4">
              <li class="flex items-start gap-3">
                <span
                  class="mt-0.5 flex size-7 items-center justify-center rounded-full bg-brand/15 text-brand"
                >
                  <span class="material-symbols-outlined text-lg">check</span>
                </span>
                <span class="text-brand-ink"
                  >Osobní přístup a transparentní ceny bez skrytých poplatků.</span
                >
              </li>
              <li class="flex items-start gap-3">
                <span
                  class="mt-0.5 flex size-7 items-center justify-center rounded-full bg-brand/15 text-brand"
                >
                  <span class="material-symbols-outlined text-lg">check</span>
                </span>
                <span class="text-brand-ink"
                  >Diskrétnost a odbornost napříč všemi obory práva.</span
                >
              </li>
            </ul>
            <div class="mt-9 flex flex-wrap items-center gap-6">
              <ThemeButton :to="`/demo/${props.demo.slug}/o-nas`" variant="dark" size="md">
                O kanceláři
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
        </div>
      </div>
    </section>

    <!-- 3. PRACTICE AREAS CAROUSEL -->
    <section class="bg-brand-dark py-24 text-white">
      <div class="container-x">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <ThemeSectionHeading
            subtitle="Právní služby"
            title="Oblasti naší specializace"
            text="Poskytujeme právní poradenství a zastoupení napříč hlavními obory práva."
            :light="true"
            align="left"
          />
          <div class="flex shrink-0 items-center gap-3">
            <button
              type="button"
              aria-label="Předchozí"
              class="flex size-12 items-center justify-center rounded-full border border-white/20 text-white transition-colors hover:border-brand hover:bg-brand"
              @click="paScroll(-1)"
            >
              <span class="material-symbols-outlined">arrow_back</span>
            </button>
            <button
              type="button"
              aria-label="Další"
              class="flex size-12 items-center justify-center rounded-full border border-white/20 text-white transition-colors hover:border-brand hover:bg-brand"
              @click="paScroll(1)"
            >
              <span class="material-symbols-outlined">arrow_forward</span>
            </button>
          </div>
        </div>

        <div
          ref="paTrack"
          class="mt-14 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
          style="scrollbar-width: none"
          @mouseenter="paStop()"
          @mouseleave="paStart()"
        >
          <NuxtLink
            v-for="area in practiceAreas"
            :key="area.slug"
            :to="`/demo/${props.demo.slug}/sluzby/${area.slug}`"
            class="group flex w-[80%] shrink-0 snap-start flex-col rounded-3xl border border-white/10 bg-white/5 p-8 transition-colors duration-300 hover:border-brand/40 hover:bg-white/[0.08] sm:w-[45%] lg:w-[31%]"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/15 text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white"
            >
              <span class="material-symbols-outlined text-3xl">{{ area.icon }}</span>
            </span>
            <h3 class="mt-6 font-serif text-2xl text-white">{{ area.name }}</h3>
            <p class="mt-3 flex-1 text-sm leading-relaxed text-white/60">{{ area.perex }}</p>
            <span
              class="mt-6 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              Více
              <span
                class="material-symbols-outlined text-lg transition-transform duration-300 group-hover:translate-x-1"
                >arrow_forward</span
              >
            </span>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- 4. WHY US / FAQ -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid items-start gap-14 lg:grid-cols-2">
          <div class="reveal-left">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-10 bg-brand" />
              Proč Veritas
            </p>
            <h2 class="mt-5 font-serif text-4xl italic leading-tight sm:text-5xl">
              Zkušenosti, kterým můžete věřit
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Za našimi výsledky stojí roky praxe, stovky vyřešených případů a tým advokátů, kteří
              znají právo i lidský rozměr každého sporu.
            </p>
            <div class="mt-10 grid grid-cols-2 gap-6">
              <div
                v-for="s in statsC"
                :key="s.label"
                class="rounded-2xl border border-brand-ink/5 bg-white p-7 shadow-sm"
              >
                <div class="font-serif text-4xl font-semibold text-brand">
                  <ThemeCounter :to="s.to" :suffix="s.suffix" />
                </div>
                <div class="mt-2 text-sm uppercase tracking-wider text-brand-muted">
                  {{ s.label }}
                </div>
              </div>
            </div>
          </div>

          <div class="reveal-right">
            <div class="space-y-4">
              <div
                v-for="(f, i) in faqs"
                :key="f.question"
                class="overflow-hidden rounded-2xl border border-brand-ink/10 bg-white"
              >
                <button
                  type="button"
                  class="flex w-full items-center justify-between gap-4 px-7 py-5 text-left"
                  @click="toggleFaq(i)"
                >
                  <span class="font-serif text-lg text-brand-ink">{{ f.question }}</span>
                  <span
                    class="material-symbols-outlined shrink-0 text-brand transition-transform duration-300"
                    :class="openIndex === i ? 'rotate-45' : ''"
                    >add</span
                  >
                </button>
                <div v-show="openIndex === i" class="px-7 pb-6 text-brand-muted">
                  {{ f.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. TEAM PREVIEW -->
    <section class="bg-brand-cream py-24 pt-0">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš tým"
          title="Advokáti, kteří vás zastoupí"
          text="Seznamte se s advokáty, kteří stojí za úspěchy naší kanceláře."
          align="center"
        />

        <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <NuxtLink
            v-for="a in attorneys.slice(0, 4)"
            :key="a.slug"
            :to="`/demo/${props.demo.slug}/advokati/${a.slug}`"
            class="reveal group overflow-hidden rounded-3xl bg-white shadow-sm transition-transform duration-300 hover:-translate-y-2"
          >
            <div class="relative overflow-hidden">
              <img
                :src="a.image"
                alt=""
                class="aspect-[4/5] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <div
                class="absolute inset-x-0 bottom-0 flex items-center justify-center gap-3 bg-gradient-to-t from-brand-dark/80 to-transparent pb-5 pt-12 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
              >
                <span
                  v-for="ic in ['public', 'mail', 'call']"
                  :key="ic"
                  class="flex size-9 items-center justify-center rounded-full bg-white/90 text-brand-dark"
                >
                  <span class="material-symbols-outlined text-lg">{{ ic }}</span>
                </span>
              </div>
            </div>
            <div class="p-6 text-center">
              <h3 class="font-serif text-xl text-brand-ink">{{ a.name }}</h3>
              <p class="mt-1 text-sm font-semibold uppercase tracking-wider text-brand">
                {{ a.role }}
              </p>
              <p class="mt-1 text-sm text-brand-muted">{{ a.specialization }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- 6. TESTIMONIALS CAROUSEL -->
    <section
      class="relative overflow-hidden bg-brand-dark py-28 text-white"
      @mouseenter="tPause()"
      @mouseleave="tResume()"
    >
      <ThemeParallax :image="ph.facts" :speed="0.25">
        <div class="absolute inset-0 bg-brand-dark/90" />
      </ThemeParallax>
      <div class="container-x relative z-10 text-center">
        <span class="material-symbols-outlined text-6xl text-brand">format_quote</span>
        <transition name="slidefade" mode="out-in">
          <div :key="tIndex">
            <blockquote
              class="mx-auto mt-6 max-w-4xl font-serif text-2xl italic leading-snug text-white sm:text-4xl"
            >
              „{{ activeQuote.text }}"
            </blockquote>
            <div class="mt-8">
              <div class="font-serif text-xl text-brand">{{ activeQuote.name }}</div>
              <div class="mt-1 text-sm uppercase tracking-wider text-white/60">
                {{ activeQuote.role }}
              </div>
            </div>
          </div>
        </transition>

        <div class="mt-10 flex items-center justify-center gap-3">
          <button
            v-for="(t, i) in testimonials"
            :key="t.name"
            type="button"
            :aria-label="`Reference ${i + 1}`"
            class="h-2 rounded-full transition-all duration-300"
            :class="tIndex === i ? 'w-8 bg-brand' : 'w-2 bg-white/40 hover:bg-white/70'"
            @click="tGo(i)"
          />
        </div>
      </div>
    </section>

    <!-- 7. BLOG PREVIEW -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Právní blog"
          title="Aktuálně z právního světa"
          text="Sledujte novinky v legislativě a praktické právní rady od našich advokátů."
          align="center"
        />

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <NuxtLink
            v-for="a in articles"
            :key="a.slug"
            :to="`/demo/${props.demo.slug}/blog/${a.slug}`"
            class="reveal group flex flex-col overflow-hidden rounded-3xl border border-brand-ink/5 bg-white shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="relative overflow-hidden">
              <img
                :src="a.image"
                alt=""
                class="aspect-[16/10] w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute left-4 top-4 rounded-full bg-brand px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white"
              >
                {{ a.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col p-7">
              <div
                class="flex items-center gap-4 text-xs uppercase tracking-wider text-brand-muted"
              >
                <span class="inline-flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">calendar_today</span>
                  {{ a.date }}
                </span>
                <span class="inline-flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">person</span>
                  {{ a.author }}
                </span>
              </div>
              <h3
                class="mt-4 flex-1 font-serif text-xl leading-snug text-brand-ink transition-colors group-hover:text-brand"
              >
                {{ a.title }}
              </h3>
              <span
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand"
              >
                Číst více
                <span
                  class="material-symbols-outlined text-lg transition-transform duration-300 group-hover:translate-x-1"
                  >arrow_forward</span
                >
              </span>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- 8. CONTACT -->
    <ThemeSectionContact
      :demo="props.demo"
      subtitle="Konzultace"
      title="Domluvte si nezávaznou konzultaci"
      text="Popište nám svůj případ — ozveme se vám a navrhneme další postup."
    />
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.9s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.slidefade-enter-active,
.slidefade-leave-active {
  transition:
    opacity 0.5s ease,
    transform 0.5s ease;
}
.slidefade-enter-from {
  opacity: 0;
  transform: translateY(18px);
}
.slidefade-leave-to {
  opacity: 0;
  transform: translateY(-18px);
}
</style>
