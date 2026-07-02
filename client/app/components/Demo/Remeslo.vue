<script setup lang="ts">
import { ref } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useStockImages } from '~/../app/composables/useStockImages';

defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('remeslo');

const aboutCounters = [
  { to: 3500, suffix: '+', label: 'dokončených zakázek' },
  { to: 15, suffix: '', label: 'let praxe' },
  { to: 24, suffix: '/7', label: 'havarijní servis' },
];

const services = [
  {
    icon: 'electrical_services',
    title: 'Elektroinstalace',
    text: 'Kompletní elektroinstalace novostaveb i rekonstrukce — rozvaděče, zásuvky, světla i datové rozvody.',
  },
  {
    icon: 'plumbing',
    title: 'Voda a odpady',
    text: 'Rozvody vody a odpadů, výměna baterií, umyvadel, WC i připojení myček a praček.',
  },
  {
    icon: 'mode_heat',
    title: 'Topení a kotle',
    text: 'Montáž a servis kotlů, radiátorů a podlahového vytápění. Postaráme se i o pravidelný servis.',
  },
  {
    icon: 'emergency',
    title: 'Havarijní servis 24/7',
    text: 'Prasklá voda nebo výpadek proudu? Přijedeme co nejdříve — i o víkendu a svátcích.',
  },
  {
    icon: 'solar_power',
    title: 'Chytrá domácnost & FVE',
    text: 'Chytré vypínače, termostaty a fotovoltaika na klíč. Ušetříte energii i starosti.',
  },
  {
    icon: 'fact_check',
    title: 'Revize a certifikace',
    text: 'Revize elektro i plynu s platnou zprávou pro pojišťovnu, kolaudaci i klidný spánek.',
  },
];

const chooseFeatures = [
  'Certifikovaní elektrikáři a instalatéři',
  'Revizní technik pro elektro i plyn',
  'Doprava a cenová nabídka zdarma',
  'Záruka na práci i materiál',
];

const pricing = [
  {
    name: 'Drobné opravy',
    price: 'od 490 Kč',
    desc: 'Pro rychlé opravy a jednotlivé úkony u vás doma.',
    featured: false,
    features: [
      'Výjezd technika',
      'Oprava do jedné hodiny',
      'Drobný spojovací materiál',
      'Cena známá předem',
    ],
  },
  {
    name: 'Montáž & instalace',
    price: 'od 2 900 Kč',
    desc: 'Nejčastější volba pro byty, domy i provozovny.',
    featured: true,
    features: [
      'Elektro, voda i topení',
      'Montáž zařízení a rozvodů',
      'Odzkoušení a předání',
      'Úklid pracoviště',
      'Záruka na práci i materiál',
    ],
  },
  {
    name: 'Rekonstrukce',
    price: 'od 12 900 Kč',
    desc: 'Kompletní rekonstrukce rozvodů na klíč.',
    featured: false,
    features: [
      'Vše z Montáže',
      'Nové rozvody elektro i vody',
      'Rozvaděč a jištění',
      'Revizní zpráva',
      'Následný servis',
    ],
  },
];

const blogPosts = [
  {
    title: 'Jak poznat, že potřebujete nový rozvaděč',
    category: 'Elektro',
    date: '14. ledna 2025',
    image: ph.blog[0],
  },
  {
    title: 'Kapající kohoutek: kdy stačí těsnění a kdy výměna',
    category: 'Voda',
    date: '3. února 2025',
    image: ph.blog[1],
  },
  {
    title: 'Vyplatí se fotovoltaika v roce 2025?',
    category: 'Úspory',
    date: '26. února 2025',
    image: ph.blog[2],
  },
];

// Before / After comparison slider
const pos = ref(55);
</script>

<template>
  <div>
    <!-- 1. HERO / BANNER -->
    <section class="relative overflow-hidden bg-brand-cream">
      <span
        class="pointer-events-none absolute -left-24 -top-24 size-72 rounded-full bg-brand-soft/60 blur-3xl"
      />
      <span
        class="pointer-events-none absolute -bottom-32 right-0 size-80 rounded-full bg-brand-accent/20 blur-3xl"
      />
      <div class="container-x relative grid items-center gap-12 py-24 lg:grid-cols-2 lg:py-32">
        <div class="reveal-left flex flex-col items-start gap-6">
          <span
            class="inline-flex items-center gap-2 rounded-full bg-brand-soft px-4 py-1.5 text-sm font-semibold uppercase tracking-wider text-brand"
          >
            <span class="material-symbols-outlined text-base">electrical_services</span>
            Elektro &middot; voda &middot; topení &middot; plyn
          </span>
          <h1 class="text-4xl font-bold leading-tight text-brand-ink sm:text-5xl lg:text-[56px]">
            Řemeslo, na které se spolehnete
          </h1>
          <p class="max-w-xl text-lg leading-relaxed text-brand-muted">
            Elektroinstalace, voda, topení i plyn — od drobné opravy po kompletní rekonstrukci.
            Přijedeme včas, po sobě uklidíme a práci vám vždy odzkoušíme.
          </p>
          <div class="flex flex-wrap gap-4">
            <ThemeButton to="#contact" variant="solid" size="lg">Nezávazná poptávka</ThemeButton>
            <ThemeButton to="#contact" variant="outline" size="lg">Zavolat</ThemeButton>
          </div>
        </div>

        <div class="reveal-right relative">
          <div class="overflow-hidden rounded-[2.5rem] shadow-2xl shadow-brand/20">
            <img
              :src="ph.hero"
              alt="Řemeslníci při práci"
              class="h-[26rem] w-full object-cover sm:h-[32rem]"
            />
          </div>
          <div
            class="absolute -bottom-6 -left-6 flex items-center gap-3 rounded-2xl bg-white px-6 py-4 shadow-xl"
          >
            <span
              class="flex size-12 items-center justify-center rounded-full bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined">workspace_premium</span>
            </span>
            <div>
              <p class="text-2xl font-bold leading-none text-brand-ink">
                <ThemeCounter :to="15" /> let
              </p>
              <p class="text-sm text-brand-muted">praxe v oboru</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. ABOUT -->
    <section class="bg-white py-24">
      <div class="container-x grid items-center gap-14 lg:grid-cols-2">
        <div class="reveal-left relative">
          <div class="overflow-hidden rounded-3xl shadow-xl">
            <img
              :src="ph.aboutMain"
              alt="Elektrikář při práci"
              class="h-[28rem] w-full object-cover"
            />
          </div>
          <div
            class="absolute -bottom-8 -right-4 hidden w-48 overflow-hidden rounded-2xl border-8 border-white shadow-xl sm:block"
          >
            <img
              :src="ph.aboutSecondary"
              alt="Nářadí řemeslníka"
              class="h-40 w-full object-cover"
            />
          </div>
        </div>

        <div class="reveal-right flex flex-col items-start gap-6">
          <span
            class="inline-flex items-center gap-2 rounded-full bg-brand-soft px-4 py-1.5 text-sm font-semibold uppercase tracking-wider text-brand"
          >
            <span class="size-1.5 rounded-full bg-current" />
            O nás
          </span>
          <h2 class="text-3xl font-bold leading-tight text-brand-ink sm:text-4xl">
            Jedna firma pro elektřinu i vodu
          </h2>
          <p class="text-lg leading-relaxed text-brand-muted">
            Jsme parta řemeslníků, která zvládne elektroinstalace, vodu, topení i plyn pod jednou
            střechou. Nemusíte shánět tři různé firmy — domluvíte se s námi a je hotovo. Pracujeme
            spolehlivě, čistě a za férovou cenu.
          </p>

          <div class="grid w-full grid-cols-3 gap-4">
            <div
              v-for="c in aboutCounters"
              :key="c.label"
              class="rounded-2xl bg-brand-cream px-4 py-5 text-center transition-colors duration-300 hover:bg-brand-soft"
            >
              <p class="text-2xl font-bold text-brand sm:text-3xl">
                <ThemeCounter :to="c.to" :suffix="c.suffix" />
              </p>
              <p class="mt-1 text-sm leading-snug text-brand-muted">{{ c.label }}</p>
            </div>
          </div>

          <ThemeButton to="#contact" variant="solid" size="lg">Poptat práci</ThemeButton>
        </div>
      </div>
    </section>

    <!-- 3. SERVICES -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naše služby"
          title="Vše kolem domu i firmy — pod jednou střechou"
          text="Od zásuvky po kotel. Postaráme se o elektřinu, vodu, topení i plyn — profesionálně a s revizí."
          align="center"
        />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="s in services"
            :key="s.title"
            class="reveal group flex flex-col gap-4 rounded-2xl bg-white p-8 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
          >
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand-soft text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white"
            >
              <span class="material-symbols-outlined text-2xl">{{ s.icon }}</span>
            </span>
            <h3 class="text-xl font-bold text-brand-ink">{{ s.title }}</h3>
            <p class="text-[15px] leading-relaxed text-brand-muted">{{ s.text }}</p>
            <NuxtLink
              to="#contact"
              class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-brand transition-colors hover:text-brand-dark"
            >
              Více
              <span class="material-symbols-outlined text-base">arrow_forward</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. WHY CHOOSE US -->
    <section class="bg-white py-24">
      <div class="container-x grid items-center gap-14 lg:grid-cols-2">
        <div class="reveal-left flex flex-col items-start gap-6">
          <span
            class="inline-flex items-center gap-2 rounded-full bg-brand-soft px-4 py-1.5 text-sm font-semibold uppercase tracking-wider text-brand"
          >
            <span class="size-1.5 rounded-full bg-current" />
            Proč my
          </span>
          <h2 class="text-3xl font-bold leading-tight text-brand-ink sm:text-4xl">
            Přijedeme včas a uklidíme po sobě
          </h2>
          <p class="text-lg leading-relaxed text-brand-muted">
            Spojujeme řemeslnou zručnost s pořádkem a spolehlivostí. Víte předem, kolik zaplatíte, a
            práci vždy předáme odzkoušenou, čistou a s revizí.
          </p>

          <ul class="flex w-full flex-col gap-3">
            <li
              v-for="f in chooseFeatures"
              :key="f"
              class="flex items-center gap-3 rounded-xl bg-brand-cream px-4 py-3"
            >
              <span
                class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand text-white"
              >
                <span class="material-symbols-outlined text-lg">check</span>
              </span>
              <span class="font-semibold text-brand-ink">{{ f }}</span>
            </li>
          </ul>

          <ThemeButton to="#contact" variant="solid" size="lg">Domluvit termín</ThemeButton>
        </div>

        <div class="reveal-right relative">
          <div class="overflow-hidden rounded-3xl shadow-xl">
            <img
              :src="ph.choose"
              alt="Kvalitně provedená instalace"
              class="h-[30rem] w-full object-cover"
            />
          </div>
          <div
            class="absolute -left-5 top-8 hidden items-center gap-3 rounded-2xl bg-white px-5 py-4 shadow-xl sm:flex"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined">verified</span>
            </span>
            <div>
              <p class="font-bold leading-none text-brand-ink">Záruka</p>
              <p class="text-sm text-brand-muted">na práci i materiál</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. PRICING -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Ceník"
          title="Férové ceny bez skrytých poplatků"
          text="Vyberte si rozsah podle zakázky. Přesnou cenu vždy potvrdíme po prohlídce nebo podle fotky."
          align="center"
        />

        <div class="mt-14 grid items-stretch gap-8 lg:grid-cols-3">
          <div
            v-for="p in pricing"
            :key="p.name"
            class="reveal relative flex flex-col gap-6 rounded-3xl p-8 shadow-sm transition-all duration-300"
            :class="
              p.featured
                ? 'bg-brand text-white shadow-2xl ring-2 ring-brand-accent lg:scale-105'
                : 'bg-white hover:-translate-y-2 hover:shadow-xl'
            "
          >
            <span
              v-if="p.featured"
              class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-brand-accent px-4 py-1 text-xs font-bold uppercase tracking-wider text-brand-dark"
            >
              Nejoblíbenější
            </span>

            <div>
              <h3 class="text-xl font-bold" :class="p.featured ? 'text-white' : 'text-brand-ink'">
                {{ p.name }}
              </h3>
              <p
                class="mt-2 text-sm leading-relaxed"
                :class="p.featured ? 'text-white/80' : 'text-brand-muted'"
              >
                {{ p.desc }}
              </p>
            </div>

            <div class="flex items-end gap-2">
              <span class="text-4xl font-bold" :class="p.featured ? 'text-white' : 'text-brand'">
                {{ p.price }}
              </span>
            </div>

            <ul
              class="flex flex-col gap-3 border-t pt-6"
              :class="p.featured ? 'border-white/20' : 'border-brand-soft'"
            >
              <li
                v-for="feat in p.features"
                :key="feat"
                class="flex items-start gap-3 text-[15px]"
                :class="p.featured ? 'text-white/90' : 'text-brand-muted'"
              >
                <span
                  class="material-symbols-outlined mt-0.5 text-lg"
                  :class="p.featured ? 'text-brand-accent' : 'text-brand'"
                >
                  check
                </span>
                {{ feat }}
              </li>
            </ul>

            <div class="mt-auto pt-2">
              <ThemeButton to="#contact" :variant="p.featured ? 'accent' : 'outline'" size="md">
                Poptat
              </ThemeButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. BEFORE / AFTER -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Před a po"
          title="Výsledek mluví sám za sebe"
          text="Posuňte jezdec a podívejte se na rozdíl před zásahem a po něm."
          align="center"
        />

        <div class="reveal mx-auto mt-14 max-w-4xl">
          <div class="relative aspect-[16/10] overflow-hidden rounded-3xl shadow-xl">
            <img
              :src="ph.work[0]"
              alt="Stav před zásahem"
              class="absolute inset-0 size-full object-cover"
            />
            <img
              :src="ph.work[1]"
              alt="Stav po zásahu"
              class="absolute inset-0 size-full object-cover"
              :style="{ clipPath: `inset(0 ${100 - pos}% 0 0)` }"
            />

            <span
              class="absolute left-4 top-4 rounded-full bg-brand-ink/70 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white"
            >
              Před
            </span>
            <span
              class="absolute right-4 top-4 rounded-full bg-brand/80 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white"
            >
              Po
            </span>

            <!-- divider line + handle knob -->
            <span
              class="pointer-events-none absolute inset-y-0 w-0.5 -translate-x-1/2 bg-white shadow-[0_0_8px_rgba(0,0,0,0.4)]"
              :style="{ left: `${pos}%` }"
            >
              <span
                class="absolute left-1/2 top-1/2 flex size-11 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand shadow-lg ring-1 ring-brand/10"
              >
                <span class="material-symbols-outlined text-xl">drag_indicator</span>
              </span>
            </span>
          </div>

          <div class="mt-6 flex items-center gap-4">
            <span class="material-symbols-outlined text-brand-muted">construction</span>
            <input
              v-model="pos"
              type="range"
              min="0"
              max="100"
              class="h-2 w-full cursor-pointer appearance-none rounded-full bg-brand-soft accent-brand"
              aria-label="Posuvník porovnání před a po"
            />
            <span class="material-symbols-outlined text-brand">bolt</span>
          </div>
        </div>
      </div>
    </section>

    <!-- 7. BLOG -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="reveal flex flex-wrap items-end justify-between gap-6">
          <ThemeSectionHeading
            subtitle="Blog"
            title="Rady a tipy pro váš dům"
            align="left"
            max="max-w-xl"
          />
          <NuxtLink
            to="#contact"
            class="inline-flex items-center gap-1 text-sm font-semibold text-brand transition-colors hover:text-brand-dark"
          >
            Zobrazit blog
            <span class="material-symbols-outlined text-base">arrow_forward</span>
          </NuxtLink>
        </div>

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <article
            v-for="post in blogPosts"
            :key="post.title"
            class="reveal group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
          >
            <div class="relative overflow-hidden">
              <img
                :src="post.image"
                :alt="post.title"
                class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <span
                class="absolute left-4 top-4 rounded-full bg-brand px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white"
              >
                {{ post.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col gap-3 p-7">
              <span class="flex items-center gap-2 text-sm text-brand-muted">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                {{ post.date }}
              </span>
              <h3 class="text-lg font-bold leading-snug text-brand-ink">{{ post.title }}</h3>
              <NuxtLink
                to="#contact"
                class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-brand transition-colors hover:text-brand-dark"
              >
                Číst dál
                <span class="material-symbols-outlined text-base">arrow_forward</span>
              </NuxtLink>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 8. CTA BAND -->
    <section class="relative overflow-hidden">
      <ThemeParallax :image="ph.facts" :speed="0.25">
        <div class="absolute inset-0 bg-brand-ink/80" />
      </ThemeParallax>
      <div class="container-x relative flex flex-col items-center gap-6 py-24 text-center">
        <h2 class="reveal max-w-3xl text-3xl font-bold leading-tight text-white sm:text-4xl">
          Nefunguje elektřina nebo teče voda? Ozvěte se ještě dnes
        </h2>
        <p class="reveal max-w-2xl text-lg leading-relaxed text-white/80">
          Přijedeme na prohlídku, navrhneme řešení a řekneme přesnou cenu předem — bez závazku.
        </p>
        <ThemeButton to="#contact" variant="accent" size="lg">Objednat řemeslníka</ThemeButton>
      </div>
    </section>

    <!-- 9. CONTACT -->
    <ThemeSectionContact
      :demo="demo"
      subtitle="Kontakt"
      title="Pojďme naplánovat vaši zakázku"
      text="Napište nám, co potřebujete a kde. Ozveme se s termínem a cenovou nabídkou."
    />
  </div>
</template>
