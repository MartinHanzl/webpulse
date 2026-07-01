<script setup lang="ts">
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useStockImages } from '~/../app/composables/useStockImages';

defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('landscaping');

const heroCards = [
  {
    icon: 'terrain',
    title: 'Terénní úpravy',
    text: 'Modelace a urovnání pozemků každého rozsahu.',
  },
  {
    icon: 'foundation',
    title: 'Zpevněné plochy',
    text: 'Dlažby, cesty a opěrné zdi s dlouhou životností.',
  },
  { icon: 'forest', title: 'Sadové úpravy', text: 'Výsadba stromů, keřů i kompletních záhonů.' },
  { icon: 'grass', title: 'Údržba zeleně', text: 'Pravidelná péče o veřejný i firemní prostor.' },
];

const aboutBullets = [
  'Vlastní těžká technika',
  'Projekce i realizace',
  'Reference u měst a obcí',
  'Certifikace ISO',
];

const aboutBoxes = [
  {
    icon: 'engineering',
    title: 'Zkušený tým',
    text: 'Strojníci, zahradníci i projektanti pod jednou střechou.',
  },
  {
    icon: 'agriculture',
    title: 'Vlastní stroje',
    text: 'Bagry, smykem řízené nakladače a sklápěče.',
  },
  {
    icon: 'design_services',
    title: 'Vlastní projekce',
    text: 'Od studie přes prováděcí dokumentaci po realizaci.',
  },
  { icon: 'verified', title: 'Garance kvality', text: 'Záruka na dílo a následná údržbová péče.' },
];

const facts = [
  { value: '320+', label: 'Dokončených zakázek', icon: 'task_alt' },
  { value: '60 ha', label: 'Upravených ploch', icon: 'landscape' },
  { value: '28 let', label: 'Na trhu', icon: 'workspace_premium' },
  { value: 'ISO', label: 'Certifikovaná firma', icon: 'verified' },
];

const services = [
  {
    icon: 'terrain',
    name: 'Terénní úpravy',
    perex: 'Srovnání, navážky a modelace pozemků před výstavbou i výsadbou.',
    image: ph.services[0],
  },
  {
    icon: 'foundation',
    name: 'Zpevněné plochy',
    perex: 'Komunikace, chodníky, parkoviště a opěrné konstrukce.',
    image: ph.services[1],
  },
  {
    icon: 'forest',
    name: 'Sadové úpravy',
    perex: 'Výsadba dřevin, trvalkových záhonů a zakládání trávníků.',
    image: ph.services[2],
  },
  {
    icon: 'landscape',
    name: 'Modelace terénu',
    perex: 'Tvarování svahů, valů a vodních prvků v krajině.',
    image: ph.services[3],
  },
  {
    icon: 'yard',
    name: 'Zakládání ploch',
    perex: 'Travnaté, parkové i biologicky cenné plochy na klíč.',
    image: ph.services[4],
  },
  {
    icon: 'grass',
    name: 'Údržba zeleně',
    perex: 'Celoroční péče, sečení, řez dřevin a kácení.',
    image: ph.services[5],
  },
];

const steps = [
  {
    no: '01',
    icon: 'design_services',
    title: 'Konzultace a projekt',
    text: 'Prohlídka pozemku, zaměření a návrh řešení včetně kalkulace.',
  },
  {
    no: '02',
    icon: 'agriculture',
    title: 'Příprava a zemní práce',
    text: 'Skrývky, modelace terénu a příprava podloží vlastní technikou.',
  },
  {
    no: '03',
    icon: 'construction',
    title: 'Realizace',
    text: 'Pokládka ploch, výsadba zeleně a osazení mobiliáře.',
  },
  {
    no: '04',
    icon: 'grass',
    title: 'Údržba',
    text: 'Následná péče, zálivka a pravidelná údržba zeleně.',
  },
];

const projects = [
  { title: 'Revitalizace náměstí', city: 'Kolín', image: ph.work[0] },
  { title: 'Park u sídliště', city: 'Hradec Králové', image: ph.work[1] },
  { title: 'Areál výrobního závodu', city: 'Pardubice', image: ph.work[2] },
];

const testimonials = [
  {
    name: 'Ing. Pavel Novotný',
    role: 'Město Kolín',
    text: 'Revitalizaci náměstí zvládli v termínu a v perfektní kvalitě. Skvělá komunikace po celou dobu.',
    rating: 5,
  },
  {
    name: 'Lucie Marešová',
    role: 'Správa zeleně',
    text: 'Spolehlivý partner pro údržbu rozsáhlých ploch. Vlastní technika je obrovská výhoda.',
    rating: 5,
  },
  {
    name: 'Jiří Beneš',
    role: 'Investor, Pardubice',
    text: 'Od projektu po realizaci vše pod jednou střechou. Areál vypadá reprezentativně.',
    rating: 5,
  },
];

const team = [
  { name: 'Tomáš Krajina', role: 'Vedoucí realizací', image: ph.work[3] },
  { name: 'Martina Lesní', role: 'Projektantka zeleně', image: ph.services[2] },
  { name: 'Petr Zemský', role: 'Vedoucí strojník', image: ph.work[4] },
  { name: 'Karel Sad', role: 'Arborista', image: ph.services[5] },
];

const teamCounters = [
  { value: '28', label: 'Let zkušeností' },
  { value: '99 %', label: 'Spokojenosti' },
  { value: '12', label: 'Ocenění' },
];
</script>

<template>
  <div class="bg-neutral-950 text-white">
    <!-- 1. HERO -->
    <section class="relative flex min-h-[760px] items-center overflow-hidden">
      <ThemeParallax :image="ph.hero" :speed="0.25">
        <div
          class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/85 to-neutral-950/40"
        />
        <div
          class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-transparent to-neutral-950/60"
        />
      </ThemeParallax>

      <div class="container-x relative z-10 pb-24 pt-40">
        <div class="max-w-3xl">
          <span
            class="reveal inline-flex items-center gap-2 rounded-full bg-white/5 px-4 py-2 text-sm font-semibold uppercase tracking-wider text-brand-accent backdrop-blur"
          >
            <span class="size-2 rounded-full bg-brand-accent" />
            Krajinářské a sadové úpravy
          </span>
          <h1
            class="reveal mt-7 text-4xl font-extrabold leading-[1.05] text-white sm:text-5xl lg:text-6xl"
          >
            Měníme krajinu i veřejný prostor k lepšímu
          </h1>
          <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-white/70">
            Komplexní realizace terénních a sadových úprav s vlastní těžkou technikou. Od projektu
            přes zemní práce až po dlouhodobou údržbu zeleně.
          </p>
          <div class="reveal mt-9 flex flex-wrap items-center gap-4">
            <ThemeButton to="#contact" variant="accent" size="lg">Poptat realizaci</ThemeButton>
            <ThemeButton to="#services" variant="light" size="lg" :icon="false"
              >Naše služby</ThemeButton
            >
          </div>
        </div>

        <div class="reveal mt-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="card in heroCards"
            :key="card.title"
            class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur transition-colors duration-300 hover:border-brand-accent/40"
          >
            <span class="material-symbols-outlined text-3xl text-brand-accent">{{
              card.icon
            }}</span>
            <h3 class="mt-4 text-lg font-semibold text-white">{{ card.title }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-white/60">{{ card.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. ABOUT -->
    <section class="bg-neutral-950 py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl">
              <img :src="ph.aboutMain" alt="" class="h-[480px] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-10 -right-4 hidden w-56 overflow-hidden rounded-2xl border-4 border-neutral-950 shadow-2xl sm:block lg:w-64"
            >
              <img :src="ph.aboutSecondary" alt="" class="h-44 w-full object-cover" />
            </div>
            <div
              class="absolute -left-4 top-8 flex items-center gap-3 rounded-2xl bg-brand-accent px-6 py-4 text-brand-dark shadow-xl"
            >
              <span class="text-3xl font-extrabold leading-none">28</span>
              <span class="text-sm font-semibold uppercase leading-tight">let<br />praxe</span>
            </div>
          </div>

          <div class="reveal-right">
            <ThemeSectionHeading
              subtitle="O nás"
              title="Krajináři s vlastní technikou a vlastní projekcí"
              text="Realizujeme veřejnou i firemní zeleň, terénní úpravy a zpevněné plochy po celé republice. Spojujeme strojní kapacitu s citem pro krajinu."
              :light="true"
              align="left"
            />
            <ul class="mt-8 grid gap-4 sm:grid-cols-2">
              <li v-for="b in aboutBullets" :key="b" class="flex items-center gap-3 text-white/80">
                <span class="material-symbols-outlined text-xl text-brand-accent"
                  >check_circle</span
                >
                <span class="font-medium">{{ b }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="mt-20 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="box in aboutBoxes"
            :key="box.title"
            class="reveal rounded-2xl border border-white/10 bg-neutral-900 p-7 transition-transform duration-300 hover:-translate-y-1"
          >
            <span
              class="flex size-12 items-center justify-center rounded-xl bg-brand-accent/15 text-brand-accent"
            >
              <span class="material-symbols-outlined">{{ box.icon }}</span>
            </span>
            <h3 class="mt-5 text-lg font-semibold text-white">{{ box.title }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-white/60">{{ box.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. ACHIEVEMENTS / FACTS -->
    <section class="bg-neutral-900 py-24">
      <div class="container-x">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="f in facts"
            :key="f.label"
            class="reveal relative overflow-hidden rounded-3xl border border-white/10 bg-neutral-950 p-8 text-center"
          >
            <span class="material-symbols-outlined mb-4 text-4xl text-brand-accent">{{
              f.icon
            }}</span>
            <p class="text-4xl font-extrabold text-white lg:text-5xl">{{ f.value }}</p>
            <p class="mt-2 text-sm font-medium uppercase tracking-wide text-white/50">
              {{ f.label }}
            </p>
            <span
              class="material-symbols-outlined pointer-events-none absolute -bottom-4 -right-4 text-8xl text-white/5"
              >{{ f.icon }}</span
            >
          </div>
        </div>
      </div>
    </section>

    <!-- 4. SERVICES -->
    <section id="services" class="bg-neutral-950 py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naše služby"
          title="Kompletní krajinářské a sadové úpravy"
          text="Od přípravy terénu po finální zeleň zajistíme celý proces vlastními silami a vlastní technikou."
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 lg:grid-cols-3">
          <!-- Large feature card -->
          <article
            class="reveal group relative overflow-hidden rounded-3xl border border-white/10 lg:col-span-2 lg:row-span-2"
          >
            <img
              :src="services[0].image"
              alt=""
              class="h-72 w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:h-full lg:min-h-[420px]"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/30 to-transparent"
            />
            <div class="absolute inset-x-0 bottom-0 p-8">
              <span
                class="inline-flex items-center gap-2 rounded-full bg-brand-accent px-4 py-1.5 text-sm font-semibold text-brand-dark"
              >
                <span class="material-symbols-outlined text-base">{{ services[0].icon }}</span>
                Hlavní obor
              </span>
              <h3 class="mt-4 text-2xl font-bold text-white lg:text-3xl">{{ services[0].name }}</h3>
              <p class="mt-2 max-w-md text-white/70">{{ services[0].perex }}</p>
              <a
                href="#contact"
                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-accent"
              >
                Zobrazit
                <span class="material-symbols-outlined text-base">arrow_forward</span>
              </a>
            </div>
          </article>

          <!-- Smaller cards -->
          <article
            v-for="s in services.slice(1)"
            :key="s.name"
            class="reveal group overflow-hidden rounded-3xl border border-white/10 bg-neutral-900 transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="relative h-40 overflow-hidden">
              <img
                :src="s.image"
                alt=""
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute left-4 top-4 flex size-10 items-center justify-center rounded-xl bg-neutral-950/80 text-brand-accent backdrop-blur"
              >
                <span class="material-symbols-outlined">{{ s.icon }}</span>
              </span>
            </div>
            <div class="p-6">
              <h3 class="text-lg font-semibold text-white">{{ s.name }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-white/60">{{ s.perex }}</p>
              <a
                href="#contact"
                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-accent"
              >
                Zobrazit
                <span class="material-symbols-outlined text-base">arrow_forward</span>
              </a>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 5. WORK PROCESS -->
    <section class="bg-neutral-900 py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Jak postupujeme"
          title="Od konzultace k hotové krajině"
          text="Přehledný proces, ve kterém máte jistotu termínu, ceny i výsledku."
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 md:grid-cols-2">
          <div
            v-for="step in steps"
            :key="step.no"
            class="reveal relative flex gap-6 rounded-3xl border border-white/10 bg-neutral-950 p-8"
          >
            <div class="relative shrink-0">
              <span
                class="flex size-20 items-center justify-center rounded-full border border-dashed border-brand-accent/50 text-brand-accent"
              >
                <span class="material-symbols-outlined text-3xl">{{ step.icon }}</span>
              </span>
              <span
                class="absolute -right-1 -top-1 flex size-8 items-center justify-center rounded-full bg-brand-accent text-sm font-bold text-brand-dark"
                >{{ step.no }}</span
              >
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">{{ step.title }}</h3>
              <p class="mt-2 text-sm leading-relaxed text-white/60">{{ step.text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. CASE STUDIES / WORK -->
    <section class="bg-neutral-950 py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Reference"
          title="Vybrané realizace"
          text="Veřejný prostor, parky i firemní areály — ukázka projektů, na které jsme hrdí."
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <article
            v-for="p in projects"
            :key="p.title"
            class="reveal group relative overflow-hidden rounded-3xl border border-white/10 transition-transform duration-300 hover:-translate-y-2"
          >
            <img
              :src="p.image"
              alt=""
              class="h-72 w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/20 to-transparent"
            />
            <div class="absolute inset-x-0 bottom-0 p-7">
              <p class="flex items-center gap-1.5 text-sm font-medium text-brand-accent">
                <span class="material-symbols-outlined text-base">location_on</span>
                {{ p.city }}
              </p>
              <h3 class="mt-2 text-xl font-bold text-white">{{ p.title }}</h3>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 7. TESTIMONIALS -->
    <section class="bg-neutral-900 py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Spokojení klienti"
          title="Co o nás říkají"
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <figure
            v-for="t in testimonials"
            :key="t.name"
            class="reveal flex flex-col rounded-3xl border border-white/10 bg-neutral-950 p-8"
          >
            <span class="material-symbols-outlined text-4xl text-brand-accent">format_quote</span>
            <div class="mt-2 flex gap-0.5 text-brand-accent">
              <span
                v-for="n in t.rating"
                :key="n"
                class="material-symbols-outlined text-lg"
                style="font-variation-settings: 'FILL' 1"
                >star</span
              >
            </div>
            <blockquote class="mt-4 flex-1 leading-relaxed text-white/75">{{ t.text }}</blockquote>
            <figcaption class="mt-6 border-t border-white/10 pt-5">
              <p class="font-semibold text-white">{{ t.name }}</p>
              <p class="text-sm text-white/50">{{ t.role }}</p>
            </figcaption>
          </figure>

          <!-- Highlighted stat box -->
          <div
            class="reveal flex flex-col items-center justify-center rounded-3xl bg-brand-accent p-10 text-center text-brand-dark"
          >
            <p class="text-6xl font-extrabold leading-none">100%</p>
            <p class="mt-4 text-lg font-semibold">Spokojených klientů</p>
            <p class="mt-2 text-sm font-medium opacity-80">
              Doporučují nás dál pro krajinářské realizace.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- 8. TEAM -->
    <section class="bg-neutral-950 py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš tým"
          title="Lidé, kteří tvoří krajinu"
          text="Zkušení odborníci od projekce přes strojní práce až po arboristiku."
          :light="true"
          align="center"
        />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <article
            v-for="m in team"
            :key="m.name"
            class="reveal group relative overflow-hidden rounded-3xl border border-white/10"
          >
            <img
              :src="m.image"
              alt=""
              class="h-72 w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/10 to-transparent"
            />
            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between p-6">
              <div>
                <h3 class="text-lg font-bold text-white">{{ m.name }}</h3>
                <p class="text-sm text-brand-accent">{{ m.role }}</p>
              </div>
              <div
                class="flex translate-y-3 gap-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"
              >
                <span
                  class="flex size-9 items-center justify-center rounded-full bg-brand-accent text-brand-dark"
                >
                  <span class="material-symbols-outlined text-lg">groups</span>
                </span>
                <span
                  class="flex size-9 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur"
                >
                  <span class="material-symbols-outlined text-lg">mail</span>
                </span>
              </div>
            </div>
          </article>
        </div>

        <div
          class="mt-16 grid gap-6 rounded-3xl border border-white/10 bg-neutral-900 p-10 sm:grid-cols-3"
        >
          <div v-for="c in teamCounters" :key="c.label" class="text-center">
            <p class="text-4xl font-extrabold text-brand-accent lg:text-5xl">{{ c.value }}</p>
            <p class="mt-2 text-sm font-medium uppercase tracking-wide text-white/50">
              {{ c.label }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- 9. CONTACT -->
    <ThemeSectionContact
      :demo="demo"
      dark
      subtitle="Kontakt"
      title="Pojďme probrat váš projekt"
      text="Pošlete nám poptávku nebo zadávací dokumentaci. Ozveme se s návrhem řešení a kalkulací."
    />
  </div>
</template>
