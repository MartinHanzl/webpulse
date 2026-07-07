<script setup lang="ts">
const PLANS = [
  {
    title: 'Jednoduchý web',
    price: 'od 15 000 Kč',
    priceSuffix: '',
    text: 'Prezentace do 5 podstránek — ideální start pro živnostníky a menší firmy.',
    features: ['Návrh i realizace', 'Responzivní design', 'Základní SEO', 'Hotovo do 2 týdnů'],
    featured: false,
  },
  {
    title: 'Web na míru',
    price: 'od 60 000 Kč',
    priceSuffix: '',
    text: 'Větší web nebo systém na míru včetně napojení na systémy, které používáte.',
    features: [
      'Vše z Jednoduchého webu',
      'Systém / rezervace / API',
      'Copywriting ve spolupráci',
      '3 měsíce podpory zdarma',
    ],
    featured: true,
    badge: 'Nejčastější volba',
  },
  {
    title: 'Správa webu',
    price: 'od 3 500 Kč',
    priceSuffix: '/ měsíc',
    text: 'Průběžná péče o váš stávající web — ať ho stavěl kdokoliv.',
    features: [
      'Aktualizace a zálohy',
      'Drobné úpravy v ceně',
      'Monitoring dostupnosti',
      'Bez závazku, měsíční výpověď',
    ],
    featured: false,
  },
];
</script>

<template>
  <section id="cenik" class="section bg-brand-cream">
    <div class="container-x">
      <div class="reveal mb-14 max-w-[640px]">
        <span
          class="mb-4 inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          Ceník
        </span>
        <h2 class="mb-3 mt-0 text-[clamp(30px,3vw,44px)]">Férové ceny bez překvapení</h2>
        <p class="m-0 text-[17px] leading-relaxed text-brand-muted">
          Každý projekt naceňuji individuálně — tohle jsou orientační startovní ceny.
        </p>
      </div>
      <div class="grid items-stretch gap-6 md:grid-cols-3">
        <div
          v-for="plan in PLANS"
          :key="plan.title"
          class="reveal flex flex-col gap-5 rounded-4xl px-8 py-10"
          :class="
            plan.featured
              ? 'relative bg-brand-dark shadow-[0_24px_48px_rgb(var(--brand-dark)/0.25)]'
              : 'bg-white'
          "
        >
          <span
            v-if="plan.badge"
            class="absolute -top-3.5 left-8 rounded-full bg-brand-accent px-3.5 py-1.5 text-[13px] font-extrabold text-brand-ink"
          >
            {{ plan.badge }}
          </span>
          <h3 class="m-0 text-xl" :class="plan.featured ? '!text-white' : ''">{{ plan.title }}</h3>
          <!--<div class="flex items-baseline gap-1.5">
            <strong
              class="text-4xl font-extrabold"
              :class="plan.featured ? 'text-white' : 'text-brand-ink'"
            >
              {{ plan.price }}
            </strong>
            <span v-if="plan.priceSuffix" class="text-[15px] text-brand-muted">
              {{ plan.priceSuffix }}
            </span>
          </div>-->
          <p
            class="m-0 text-[15px] leading-relaxed"
            :class="plan.featured ? 'text-white/65' : 'text-brand-muted'"
          >
            {{ plan.text }}
          </p>
          <ul
            class="m-0 flex list-none flex-col gap-2.5 p-0 text-[15px]"
            :class="plan.featured ? 'text-white' : ''"
          >
            <li v-for="feature in plan.features" :key="feature" class="flex gap-2.5">
              <span
                class="font-extrabold"
                :class="plan.featured ? 'text-brand-accent' : 'text-brand'"
              >
                ✓
              </span>
              {{ feature }}
            </li>
          </ul>
          <a
            href="#kontakt"
            class="mt-auto rounded-full text-center font-bold transition-colors"
            :class="
              plan.featured
                ? 'bg-brand-accent px-6 py-3.5 font-extrabold text-brand-ink hover:bg-white'
                : 'border-2 border-brand px-6 py-3 text-brand hover:bg-brand hover:text-white'
            "
          >
            Poptat
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
