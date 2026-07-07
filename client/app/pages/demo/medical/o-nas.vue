<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'medical';
const ph = useStockImages().get('medical');
const { reviews } = useMedicalContent();

useHead(() => ({ title: 'O klinice — Vitalmed' }));

const accordionOpen = ref(0);
const accordion = [
  {
    number: '01',
    title: 'Poslání naší kliniky',
    text: 'Poskytovat péči, které pacienti rozumí a mohou jí důvěřovat — od první návštěvy po úplné uzdravení.',
  },
  {
    number: '02',
    title: 'Vize naší kliniky',
    text: 'Být klinikou první volby pro rodiny z celého regionu díky moderní medicíně a lidskému přístupu.',
  },
];

const excellence = [
  {
    icon: 'stethoscope',
    title: 'Nejlepší lékaři',
    text: 'Špičková léčba a specialisté ve svém oboru.',
  },
  {
    icon: 'ambulance',
    title: 'Výjezdová služba',
    text: 'Sanitku k vám brzy vyšleme i s online sledováním.',
  },
  {
    icon: 'health_and_safety',
    title: 'Zdravotní podpora',
    text: 'Vaše zdravotní záznamy máte vždy po ruce.',
  },
  {
    icon: 'support_agent',
    title: 'Lékařské poradenství',
    text: 'Konzultace s lékaři kliniky kdykoli potřebujete.',
  },
];

const checklist = [
  {
    title: 'Ročně ošetříme přes 10 000 pacientů.',
    text: 'Kapacita ordinací roste každý rok spolu s důvěrou pacientů.',
  },
  {
    title: 'Více než dvě dekády spolehlivé péče.',
    text: 'Zkušenosti předáváme dál — vzděláváme mladé lékaře i sestry.',
  },
];

// Reviews slider: 3 / 2 / 1 per view with autoplay
const perView = ref(1);
const rIndex = ref(0);
let timer: ReturnType<typeof setInterval> | null = null;
const rMax = () => Math.max(reviews.length - perView.value, 0);
function rGo(i: number) {
  rIndex.value = Math.min(Math.max(i, 0), rMax());
}
let offs: Array<() => void> = [];
onMounted(() => {
  const queries: Array<[string, number]> = [
    ['(min-width: 1200px)', 3],
    ['(min-width: 768px) and (max-width: 1199px)', 2],
    ['(max-width: 767px)', 1],
  ];
  offs = queries.map(([q, count]) => {
    const mq = window.matchMedia(q);
    const apply = () => {
      if (mq.matches) {
        perView.value = count;
        rIndex.value = Math.min(rIndex.value, rMax());
      }
    };
    apply();
    mq.addEventListener('change', apply);
    return () => mq.removeEventListener('change', apply);
  });
  timer = setInterval(() => {
    rIndex.value = rIndex.value >= rMax() ? 0 : rIndex.value + 1;
  }, 3500);
});
onBeforeUnmount(() => {
  if (timer) clearInterval(timer);
  offs.forEach((off) => off());
});
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section class="relative overflow-hidden bg-brand-soft lg:mx-10 lg:rounded-2xl">
      <img
        :src="ph.facts"
        alt=""
        class="absolute inset-0 size-full object-cover opacity-25 mix-blend-luminosity"
      />
      <div class="container-x relative grid min-h-[420px] items-center gap-10 py-24 lg:grid-cols-2">
        <div>
          <p class="reveal flex items-center gap-3 text-lg font-semibold text-brand">
            <span class="h-0.5 w-10 bg-brand" />
            Moderní léčebné centrum
          </p>
          <h1 class="reveal mt-4 text-5xl sm:text-6xl">O klinice</h1>
        </div>
        <div class="relative hidden min-h-[220px] md:block">
          <span
            class="sonar-ring absolute left-8 top-4 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">home_health</span>
          </span>
          <div
            class="floaty absolute right-4 top-12 w-[250px] rounded-xl bg-white/70 p-6 shadow-xl backdrop-blur-md"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">location_on</span>
            </span>
            <p class="mt-3 font-bold">Klinika blízko vás</p>
            <p class="text-sm text-brand-muted">Profesionální lékaři</p>
          </div>
        </div>
      </div>
    </section>

    <!-- MISSION & VISION -->
    <section class="section bg-white">
      <div class="container-x grid items-center gap-16 lg:grid-cols-12">
        <div class="reveal-left group relative lg:col-span-6">
          <div
            class="overflow-hidden rounded-xl transition-transform duration-500 group-hover:[transform:perspective(2450px)_rotateX(2deg)_rotateY(-3deg)]"
          >
            <img :src="ph.choose" alt="" class="aspect-[6/5] w-full object-cover" />
          </div>
          <a
            href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
            target="_blank"
            rel="noopener"
            class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center gap-3 rounded-full bg-white px-8 py-4 font-bold shadow-2xl transition-transform hover:scale-105"
          >
            <span
              class="material-symbols-outlined text-2xl text-[#f45959]"
              style="font-variation-settings: 'FILL' 1"
              >play_circle</span
            >
            Virtuální prohlídka
          </a>
        </div>

        <div class="reveal-right lg:col-span-5 lg:col-start-8">
          <p class="flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">emoji_events</span>
            </span>
            Naše poslání a vize
          </p>
          <h2 class="mt-6 text-4xl sm:text-5xl">Nejnovější medicína, výjimečná péče.</h2>

          <div class="mt-8">
            <div
              v-for="(item, i) in accordion"
              :key="item.number"
              class="border-b border-brand-ink/10"
            >
              <button
                type="button"
                class="flex w-full items-center gap-6 py-6 text-left"
                @click="accordionOpen = accordionOpen === i ? -1 : i"
              >
                <span
                  class="text-4xl font-extrabold tracking-tight"
                  :class="accordionOpen === i ? 'text-brand' : 'text-brand-ink/15'"
                >
                  {{ item.number }}
                </span>
                <span class="flex-1 text-xl font-bold">{{ item.title }}</span>
                <span class="material-symbols-outlined text-brand-muted">
                  {{ accordionOpen === i ? 'remove' : 'add' }}
                </span>
              </button>
              <p
                v-show="accordionOpen === i"
                class="pb-6 pl-[72px] leading-relaxed text-brand-muted"
              >
                {{ item.text }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CLINICAL EXCELLENCE -->
    <section class="relative bg-brand-soft py-24 lg:mx-10 lg:rounded-2xl">
      <div class="container-x">
        <h2 class="reveal text-center text-4xl sm:text-5xl">Klinická excelence</h2>
        <div class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
          <article
            v-for="card in excellence"
            :key="card.title"
            class="reveal group rounded-xl bg-white p-9 shadow-xl shadow-brand-ink/5 transition-colors duration-300 hover:bg-brand"
          >
            <span
              class="flex size-24 items-center justify-center rounded-full bg-brand-soft text-brand transition-colors duration-300 group-hover:bg-white/15 group-hover:text-white"
            >
              <span class="material-symbols-outlined text-5xl">{{ card.icon }}</span>
            </span>
            <h3
              class="mt-7 text-xl font-bold transition-colors duration-300 group-hover:text-white"
            >
              {{ card.title }}
            </h3>
            <p
              class="mt-2 leading-relaxed text-brand-muted transition-colors duration-300 group-hover:text-white/80"
            >
              {{ card.text }}
            </p>
          </article>
        </div>
        <p class="reveal mt-12 text-center text-lg font-semibold">
          Zdravotní péče zítřka pro celou vaši rodinu.
          <NuxtLink
            :to="`/demo/${slug}/lekari`"
            class="text-brand underline decoration-brand/40 underline-offset-4 transition-colors hover:text-brand-dark"
          >
            Prohlédněte si naše lékaře.
          </NuxtLink>
        </p>
      </div>
    </section>

    <!-- ABOUT HOSPITAL -->
    <section class="section bg-white">
      <div class="container-x grid items-center gap-16 lg:grid-cols-12">
        <div class="reveal-left relative lg:col-span-6">
          <div class="ml-auto w-[85%] overflow-hidden rounded-xl lg:w-3/4">
            <img :src="ph.aboutMain" alt="" class="aspect-[4/5] w-full object-cover" />
          </div>
          <div
            class="floaty absolute -bottom-6 left-0 w-[230px] rounded-xl bg-brand-dark p-4 shadow-2xl"
          >
            <div class="rounded-lg bg-white p-5 text-center">
              <p class="text-4xl font-extrabold tracking-tight">4,8</p>
              <p class="mt-1 flex justify-center text-brand-accent">
                <span
                  v-for="i in 5"
                  :key="i"
                  class="material-symbols-outlined text-base"
                  style="font-variation-settings: 'FILL' 1"
                  >star</span
                >
              </p>
              <p class="mt-1 text-sm text-brand-muted">2 488 recenzí</p>
              <p
                class="mx-auto mt-3 inline-block rounded-full bg-emerald-500 px-3 py-1 text-xs font-bold text-white"
              >
                Vynikající skóre
              </p>
            </div>
            <p class="mt-3 text-center text-sm font-bold text-white">★ Ověřené recenze</p>
          </div>
        </div>

        <div class="reveal-right lg:col-span-5 lg:col-start-8">
          <p class="flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">medical_information</span>
            </span>
            O klinice Vitalmed
          </p>
          <h2 class="mt-6 text-4xl sm:text-5xl">Poskytujeme prvotřídní zdravotní péči.</h2>

          <div class="mt-8 flex flex-col gap-6">
            <div v-for="item in checklist" :key="item.title" class="flex items-start gap-5">
              <span
                class="mt-1 flex size-12 shrink-0 items-center justify-center rounded-full bg-brand/15 text-brand"
              >
                <span class="material-symbols-outlined">check</span>
              </span>
              <div>
                <p class="text-lg font-bold">{{ item.title }}</p>
                <p class="mt-1 leading-relaxed text-brand-muted">{{ item.text }}</p>
              </div>
            </div>
          </div>

          <ThemeButton :to="`/demo/${slug}/lecba`" variant="dark" size="lg" class="mt-10">
            Zobrazit léčbu
          </ThemeButton>
        </div>
      </div>
    </section>

    <!-- PATIENT REVIEWS -->
    <section class="section overflow-hidden bg-brand-cream/60">
      <div class="container-x">
        <h2 class="reveal text-center text-4xl sm:text-5xl">Recenze pacientů</h2>

        <div class="reveal mt-14 overflow-hidden">
          <div
            class="flex transition-transform duration-700 ease-out"
            :style="{ transform: `translateX(-${rIndex * (100 / perView)}%)` }"
          >
            <div
              v-for="review in reviews"
              :key="review.name"
              class="shrink-0 px-3"
              :style="{ width: `${100 / perView}%` }"
            >
              <figure
                class="flex h-full items-center gap-5 rounded-2xl bg-white p-7 shadow-xl shadow-brand-ink/5"
              >
                <img
                  :src="review.image"
                  alt=""
                  class="size-24 shrink-0 rounded-full object-cover"
                />
                <div>
                  <blockquote class="font-semibold leading-snug">„{{ review.text }}"</blockquote>
                  <p
                    class="mt-3 inline-flex items-center gap-0.5 rounded-full bg-[#ffea23] px-3 py-1 text-brand-ink"
                  >
                    <span
                      v-for="i in Math.floor(review.stars)"
                      :key="i"
                      class="material-symbols-outlined text-sm"
                      style="font-variation-settings: 'FILL' 1"
                      >star</span
                    >
                    <span
                      v-if="review.stars % 1 !== 0"
                      class="material-symbols-outlined text-sm"
                      style="font-variation-settings: 'FILL' 1"
                      >star_half</span
                    >
                  </p>
                  <figcaption class="mt-2 font-bold">{{ review.name }}</figcaption>
                </div>
              </figure>
            </div>
          </div>

          <div class="mt-8 flex justify-center gap-2">
            <button
              v-for="i in Math.max(reviews.length - perView, 0) + 1"
              :key="i"
              type="button"
              :aria-label="`Recenze ${i}`"
              class="size-2.5 rounded-full transition-all"
              :class="rIndex === i - 1 ? 'w-7 bg-brand' : 'bg-brand-ink/20 hover:bg-brand/50'"
              @click="rGo(i - 1)"
            />
          </div>
        </div>

        <p class="reveal mt-12 flex items-center justify-center gap-4 text-xl font-semibold">
          <span
            class="rounded-full bg-brand px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white"
            >Důvěra</span
          >
          Klinice Vitalmed důvěřuje přes 10 000 pacientů.
        </p>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
