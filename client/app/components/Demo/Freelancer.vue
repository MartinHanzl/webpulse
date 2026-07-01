<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useStockImages } from '~/../app/composables/useStockImages';

const props = defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('freelancer');

// Gradient used by the floating "gold sphere" orbs.
const ORB = 'radial-gradient(circle at 32% 30%, #ffe3a0, #f0a020 45%, #a85e00)';

// Scattered dark dots in the hero (positions are literal so Tailwind keeps them).
const DOTS = [
  'left-[8%] top-[16%]',
  'left-[18%] top-[68%]',
  'left-[28%] top-[30%]',
  'left-[40%] top-[20%]',
  'left-[52%] top-[58%]',
  'right-[16%] top-[14%]',
  'right-[28%] bottom-[16%]',
  'left-[6%] bottom-[26%]',
];

// --- O mně: definiční seznam ---
const ABOUT_ITEMS = [
  {
    label: 'Objevování',
    text: 'Hledám průnik mezi minimalismem a udržitelností a vytvářím vizuální řešení, která informují a přesvědčí.',
  },
  {
    label: 'Prototypování',
    text: 'Věnuji dost času pochopení cílů byznysu i potřeb klienta — sleduji trendy a odemykám příležitosti.',
  },
  {
    label: 'Tvorba',
    text: 'Vývoj je srdcem mé práce. Odevzdávám ověřená, efektivní a škálovatelná řešení do posledního detailu.',
  },
];

// --- O mně: fakta ---
const FACTS = [
  { label: 'Zaměření', value: 'Design a web' },
  { label: 'Bydliště', value: 'Brno, ČR' },
  { label: 'Narození', value: '26. prosince 1990' },
  { label: 'Vzdělání', value: 'Mgr. designu' },
];

// --- Portfolio (masonry) ---
interface Project {
  title: string;
  category: string;
  image: string;
  span: string;
  ratio: string;
}
const PROJECTS: Project[] = [
  {
    title: 'Tailoring',
    category: 'Branding',
    image: ph.work[0],
    span: 'row-span-2',
    ratio: 'aspect-[3/4]',
  },
  { title: 'Stream', category: 'UI/UX', image: ph.services[0], span: '', ratio: 'aspect-square' },
  { title: 'Jeremy', category: 'Web', image: ph.work[1], span: '', ratio: 'aspect-square' },
  {
    title: 'Truenorth',
    category: 'Identita',
    image: ph.services[1],
    span: 'row-span-2',
    ratio: 'aspect-[3/4]',
  },
  {
    title: 'Armchair',
    category: 'Brožura',
    image: ph.work[2],
    span: 'row-span-2',
    ratio: 'aspect-[3/4]',
  },
  {
    title: 'Aparthotel',
    category: 'Vývoj',
    image: ph.services[2],
    span: '',
    ratio: 'aspect-square',
  },
  { title: 'Massive', category: 'e-Commerce', image: ph.work[3], span: '', ratio: 'aspect-square' },
  { title: 'Cortifiel', category: 'Identita', image: ph.work[4], span: '', ratio: 'aspect-square' },
];

const lb = ref();

// --- Dvojité marquee ---
const MARQUEE_TOP = ['ilustrace', 'packaging', 'web', 'focení'];
const MARQUEE_BOTTOM = ['agentura', 'digital', 'branding', 'UI/UX'];

// --- Dovednosti (animované pruhy na crimson pozadí) ---
const SKILLS = [
  { name: 'Web design', value: 80 },
  { name: 'Grafický design', value: 88 },
  { name: 'Art direction', value: 45 },
];

// --- Ocenění ---
const AWARDS = [
  { count: '9×', title: 'Site of the day', org: 'Awwwards', year: '2024' },
  { count: '2×', title: 'Site of the year', org: 'CSS Design Awards', year: '2023' },
  { count: '4×', title: 'Site of the month', org: 'Awwwards', year: '2022' },
  { count: '3×', title: 'Developer award', org: 'The Portfolio', year: '2021' },
];

// --- Animace pruhů dovedností při scrollu ---
const skillsReady = ref(false);
const skillsEl = ref<HTMLElement | null>(null);
let skillsObserver: IntersectionObserver | null = null;

onMounted(() => {
  if (typeof IntersectionObserver === 'undefined') {
    skillsReady.value = true;
    return;
  }
  skillsObserver = new IntersectionObserver(
    (entries) => {
      if (entries.some((e) => e.isIntersecting)) {
        skillsReady.value = true;
        skillsObserver?.disconnect();
      }
    },
    { threshold: 0.3 },
  );
  if (skillsEl.value) skillsObserver.observe(skillsEl.value);
});
onBeforeUnmount(() => skillsObserver?.disconnect());
</script>

<template>
  <div class="bg-brand-cream text-brand-ink">
    <!-- ============================================================= -->
    <!-- 1. HERO — full crimson, outlined name, portrait, giant word    -->
    <!-- ============================================================= -->
    <section
      id="home"
      class="relative flex min-h-screen items-center overflow-hidden bg-brand text-white"
    >
      <!-- scattered dark dots -->
      <div class="pointer-events-none absolute inset-0 z-0">
        <span
          v-for="d in DOTS"
          :key="d"
          class="absolute size-2 rounded-full bg-black/20"
          :class="d"
        />
      </div>

      <!-- concentric radial rings behind the figure (center-right) -->
      <div
        class="pointer-events-none absolute inset-y-0 right-0 z-0 hidden w-1/2 items-center justify-center md:flex"
      >
        <div class="absolute size-[380px] rounded-full border border-white/10 lg:size-[680px]" />
        <div class="absolute size-[300px] rounded-full border border-white/10 lg:size-[520px]" />
        <div class="absolute size-[220px] rounded-full border border-white/10 lg:size-[380px]" />
      </div>

      <!-- giant clipped word — left-anchored, overflows only to the right -->
      <div class="pointer-events-none absolute inset-x-0 bottom-0 z-0 flex justify-start pl-[10%]">
        <span class="whitespace-nowrap text-[18vw] font-extrabold leading-[0.8] text-white"
          >freelancer</span
        >
      </div>

      <!-- main person cutout (upper body), in front of rings + giant word -->
      <div
        class="pointer-events-none absolute right-[8%] top-[9%] z-10 hidden h-[74%] w-[26%] overflow-hidden md:block lg:right-[14%] lg:w-[22%]"
      >
        <img
          src="/theme/freelancer-person.png"
          alt="Adam Kovář"
          class="w-full object-cover object-top drop-shadow-2xl"
        />
      </div>

      <!-- second portrait peeking from the left -->
      <div
        class="floaty pointer-events-none absolute -left-10 bottom-24 z-10 hidden w-40 overflow-hidden rounded-full shadow-2xl ring-4 ring-white/10 xl:block"
        style="animation-delay: -2s"
      >
        <img
          src="/theme/freelancer-person.png"
          alt=""
          class="aspect-square w-full object-cover object-top"
        />
      </div>

      <!-- gold spheres -->
      <div
        class="floaty absolute right-[40%] top-[26%] z-20 hidden size-16 rounded-full md:block"
        :style="{ background: ORB, boxShadow: '0 20px 40px rgba(0,0,0,.25)' }"
      />
      <div
        class="floaty absolute bottom-[20%] right-[10%] z-20 hidden size-24 rounded-full md:block"
        :style="{
          background: ORB,
          boxShadow: '0 20px 40px rgba(0,0,0,.25)',
          animationDelay: '-3s',
        }"
      />

      <!-- text block -->
      <div class="container-x relative z-20 py-32">
        <div class="max-w-2xl lg:ml-[8%]">
          <div class="reveal">
            <span
              class="block text-6xl font-extrabold leading-[0.85] text-transparent sm:text-8xl xl:text-[7.5rem]"
              style="-webkit-text-stroke: 2px #fff"
              >Adam</span
            >
            <span
              class="-mt-2 flex items-start gap-2 text-7xl font-extrabold leading-[0.85] text-white sm:text-9xl xl:text-[8.5rem]"
            >
              Kovář
              <span class="material-symbols-outlined text-4xl sm:text-5xl">north_east</span>
            </span>
          </div>
          <span
            class="reveal relative z-10 -mt-4 inline-block bg-black px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-white sm:-mt-7"
            >Oceněný freelancer</span
          >
          <p class="reveal mt-5 max-w-xs text-[15px] leading-relaxed text-brand-dark/80">
            Kreativní designér z Brna. Tvořím vizuální identity a
            <u class="decoration-2 underline-offset-2">digitální zážitky</u>.
          </p>
        </div>
      </div>
    </section>

    <!-- ============================================================= -->
    <!-- 2. ABOUT                                                       -->
    <!-- ============================================================= -->
    <section id="about" class="bg-white py-24">
      <div class="container-x">
        <div class="reveal mb-14 flex items-center justify-between border-t-2 border-brand pt-4">
          <span class="text-sm font-bold uppercase tracking-wider text-brand"
            >O Adamu Kovářovi</span
          >
          <span class="text-lg font-bold text-brand">01</span>
        </div>

        <div class="mb-16 grid gap-10 lg:grid-cols-2 lg:gap-16">
          <div class="reveal-left">
            <h2 class="text-4xl leading-tight sm:text-5xl">
              Digitální produktový designér zaměřený na vývoj.
            </h2>
            <p class="mt-6 max-w-md text-lg leading-relaxed text-brand-muted">
              Posledních osm let pomáhám značkám i startupům přetavit nápady do funkčních produktů —
              jako lead designér ve studiu i na volné noze.
            </p>
          </div>

          <div class="reveal-right">
            <div
              v-for="(it, i) in ABOUT_ITEMS"
              :key="it.label"
              class="grid grid-cols-3 gap-4 border-b border-brand-ink/10 py-6"
              :class="i === 0 ? 'border-t' : ''"
            >
              <div class="col-span-1 text-lg font-semibold text-brand-ink">{{ it.label }}</div>
              <p class="col-span-2 text-brand-muted">{{ it.text }}</p>
            </div>
          </div>
        </div>

        <div class="grid gap-8 border-t border-brand-ink/10 pt-10 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="f in FACTS" :key="f.label" class="reveal">
            <div class="text-sm font-semibold text-brand-muted">{{ f.label }}</div>
            <div class="mt-1 text-lg font-bold text-brand-ink">{{ f.value }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================= -->
    <!-- 3. WORK — masonry grid                                         -->
    <!-- ============================================================= -->
    <section id="work" class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="reveal mb-14 flex items-center justify-between border-t-2 border-brand pt-4">
          <span class="text-sm font-bold uppercase tracking-wider text-brand">Vybrané práce</span>
          <span class="text-lg font-bold text-brand">02</span>
        </div>

        <div class="grid auto-rows-[190px] grid-cols-2 gap-4 md:grid-cols-4">
          <button
            v-for="(p, i) in PROJECTS"
            :key="p.title"
            type="button"
            class="reveal group relative block overflow-hidden rounded-2xl text-left"
            :class="p.span"
            @click="lb.show(i)"
          >
            <img
              :src="p.image"
              :alt="p.title"
              class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-brand/85 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >
              <span class="material-symbols-outlined text-4xl text-white">north_east</span>
              <div class="text-center text-white">
                <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/80">
                  {{ p.category }}
                </div>
                <div class="text-xl font-extrabold">{{ p.title }}</div>
              </div>
            </div>
          </button>
        </div>
      </div>

      <ThemeLightbox ref="lb" :images="PROJECTS" />
    </section>

    <!-- ============================================================= -->
    <!-- 4. DOUBLE MARQUEE                                              -->
    <!-- ============================================================= -->
    <section class="overflow-hidden bg-white py-14">
      <div class="marquee-track">
        <template v-for="n in 2" :key="'top' + n">
          <span
            v-for="w in MARQUEE_TOP"
            :key="w + n"
            class="px-6 text-5xl font-extrabold tracking-tight text-brand-ink/15 sm:text-8xl"
            >{{ w }}.</span
          >
        </template>
      </div>
      <div class="marquee-track mt-4" style="animation-direction: reverse">
        <template v-for="n in 2" :key="'bot' + n">
          <span
            v-for="w in MARQUEE_BOTTOM"
            :key="w + n"
            class="px-6 text-5xl font-extrabold tracking-tight text-brand-ink sm:text-7xl"
            >{{ w }}.</span
          >
        </template>
      </div>
    </section>

    <!-- ============================================================= -->
    <!-- 5. EXPERTISE / SKILLS (crimson)                                -->
    <!-- ============================================================= -->
    <section id="expertise" class="relative overflow-hidden bg-brand py-24 text-white">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <!-- portrait with rings -->
          <div class="relative flex justify-center">
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
              <div class="absolute size-[420px] rounded-full border border-white/10" />
              <div class="absolute size-[320px] rounded-full border border-white/10" />
            </div>
            <div
              class="relative z-10 h-[440px] w-[280px] overflow-hidden sm:h-[540px] sm:w-[360px]"
            >
              <img
                src="/theme/freelancer-person.png"
                alt=""
                class="w-full object-cover object-top drop-shadow-2xl"
              />
            </div>
          </div>

          <!-- heading + skill bars -->
          <div>
            <h2 class="reveal text-4xl leading-tight text-white sm:text-5xl">
              Tvořím nezapomenutelné digitální zážitky díky jedinečnému brandingu.
            </h2>
            <p class="reveal mt-6 text-lg leading-relaxed text-brand-dark/80">
              Zlepšuji uživatelský zážitek a návrh rozhraní jako lead designér. Tvořím značky i
              digitální produkty, u kterých na detailu záleží.
            </p>

            <div ref="skillsEl" class="mt-12 flex flex-col gap-9">
              <div v-for="s in SKILLS" :key="s.name">
                <div class="mb-3 text-sm font-bold uppercase tracking-wide text-white">
                  {{ s.name }}
                </div>
                <div class="relative h-1.5 rounded-full bg-black/30">
                  <div
                    class="relative h-full rounded-full bg-black transition-[width] duration-1000 ease-out"
                    :style="{ width: skillsReady ? s.value + '%' : '0%' }"
                  >
                    <span
                      class="absolute -top-6 right-0 bg-black px-2 py-0.5 text-[11px] font-bold leading-none text-white"
                      >{{ s.value }}%</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================= -->
    <!-- 6. AWARDS                                                      -->
    <!-- ============================================================= -->
    <section id="awards" class="bg-white py-24">
      <div class="container-x">
        <div class="reveal mb-14 flex items-center justify-between border-t-2 border-brand pt-4">
          <span class="text-sm font-bold uppercase tracking-wider text-brand">Ocenění</span>
          <span class="text-lg font-bold text-brand">03</span>
        </div>

        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
          <div class="reveal-left lg:col-span-4">
            <h2 class="text-4xl leading-tight sm:text-5xl">Práce, kterých si všimli i porotci.</h2>
            <p class="mt-6 text-brand-muted">
              Průběžně se umísťuji v prestižních designérských soutěžích — je to skvělá zpětná vazba
              a motivace posouvat laťku výš.
            </p>
          </div>

          <div class="reveal-right lg:col-span-8">
            <div
              v-for="(a, i) in AWARDS"
              :key="a.title"
              class="grid grid-cols-12 items-center gap-4 border-brand-ink/10 py-5"
              :class="i === 0 ? 'border-b border-t' : 'border-b'"
            >
              <span class="col-span-2 text-lg font-bold text-brand">{{ a.count }}</span>
              <p class="col-span-7 border-l border-brand-ink/10 pl-6">
                {{ a.title }} – <span class="font-bold text-brand-ink">{{ a.org }}</span>
              </p>
              <span class="col-span-3 text-right font-bold text-brand-muted">{{ a.year }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================= -->
    <!-- 7. CONTACT                                                     -->
    <!-- ============================================================= -->
    <section id="contact" class="relative overflow-hidden bg-brand-cream py-24">
      <!-- gold spheres for flavour -->
      <div
        class="floaty absolute right-[8%] top-16 hidden size-16 rounded-full md:block"
        :style="{ background: ORB, boxShadow: '0 20px 40px rgba(0,0,0,.25)' }"
      />
      <div
        class="floaty absolute bottom-24 right-[22%] hidden size-24 rounded-full md:block"
        :style="{
          background: ORB,
          boxShadow: '0 20px 40px rgba(0,0,0,.25)',
          animationDelay: '-3s',
        }"
      />

      <div class="container-x relative">
        <h2 class="reveal text-5xl font-extrabold leading-[0.95] sm:text-6xl">
          Vytvořme něco skvělého.
        </h2>
        <a
          :href="`mailto:${props.demo.email}`"
          class="reveal mt-6 inline-flex items-center gap-3 text-brand transition-opacity hover:opacity-80"
        >
          <span class="material-symbols-outlined text-4xl sm:text-5xl">mail</span>
          <span class="text-4xl font-bold tracking-tight sm:text-5xl">{{ props.demo.email }}</span>
        </a>

        <div
          class="reveal mt-12 flex flex-wrap items-center justify-between gap-6 border-t-2 border-brand-ink pt-8"
        >
          <p class="max-w-xl text-brand-muted">
            Vývoj je srdcem mé práce. Odevzdávám ověřená, efektivní a škálovatelná řešení — od
            návrhu až po nasazení.
          </p>
          <ul class="flex gap-6 text-lg font-bold text-brand-ink">
            <li><a href="#" class="transition-colors hover:text-brand">Fb.</a></li>
            <li><a href="#" class="transition-colors hover:text-brand">Ig.</a></li>
            <li><a href="#" class="transition-colors hover:text-brand">Tw.</a></li>
            <li><a href="#" class="transition-colors hover:text-brand">Be.</a></li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>
