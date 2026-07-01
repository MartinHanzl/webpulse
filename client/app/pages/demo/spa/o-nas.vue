<script setup lang="ts">
import { useSpaContent } from '~/../app/composables/useSpaContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'spa';
const c = useSpaContent();
const ph = useStockImages().get('spa');

// Split a marketing stat ("9,8" / "30k" / "96 %" / "28+") into an animatable
// integer + a static tail so <ThemeCounter> can count the leading number.
const statParts = (v: string): { to: number; suffix: string } => {
  const m = v.match(/^(\d+)(.*)$/);
  return { to: m ? parseInt(m[1], 10) : 0, suffix: m ? m[2] : v };
};
const statsC = c.stats.map((s) => ({ ...s, ...statParts(s.value) }));

useHead(() => ({ title: 'O nás — Serenity' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <!-- PAGE TITLE HERO -->
    <section
      class="relative flex min-h-[420px] items-center justify-center overflow-hidden pb-16 pt-28 text-center"
    >
      <img :src="ph.hero" alt="" class="absolute inset-0 size-full object-cover" />
      <div class="absolute inset-0 bg-brand-dark/60" />
      <div class="container-x relative">
        <h1 class="text-5xl !text-white sm:text-6xl">O nás</h1>
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/80">
          <NuxtLink :to="`/demo/${slug}`" class="transition-colors hover:text-white">Domů</NuxtLink>
          <span class="material-symbols-outlined text-base">chevron_right</span>
          <span class="text-brand">O nás</span>
        </nav>
      </div>
    </section>

    <!-- INTRO SPLIT -->
    <section class="bg-brand-soft py-24">
      <div class="container-x">
        <div class="grid items-center gap-12 lg:grid-cols-2">
          <div class="reveal-left relative">
            <div class="overflow-hidden rounded-3xl shadow-lg">
              <img :src="ph.aboutMain" alt="" class="aspect-[4/5] w-full object-cover" />
            </div>
            <div
              class="absolute -bottom-8 -right-4 hidden w-44 overflow-hidden rounded-3xl border-8 border-brand-soft shadow-xl sm:block"
            >
              <img :src="ph.aboutSecondary" alt="" class="aspect-square w-full object-cover" />
            </div>
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              O studiu
            </p>
            <h2 class="mt-5 text-4xl leading-tight sm:text-[44px]">
              Relaxujte v našem luxusním <span class="italic text-brand">wellness studiu</span>
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Serenity je oázou klidu v srdci Prahy. Už 28 let se staráme o to, aby každá návštěva
              byla útěkem od každodenního shonu — masáže, pleťové rituály i saunové procedury pod
              rukama zkušených terapeutů.
            </p>
            <p class="mt-4 leading-relaxed text-brand-muted">
              Věříme, že skutečná péče začíná u detailu. Proto pracujeme s prémiovou kosmetikou,
              přírodními oleji a v atmosféře, která zklidní tělo i mysl.
            </p>

            <div class="mt-8 flex items-center gap-4">
              <div class="flex gap-1 text-brand">
                <span
                  v-for="n in 5"
                  :key="n"
                  class="material-symbols-outlined text-xl"
                  style="font-variation-settings: 'FILL' 1"
                  >star</span
                >
              </div>
              <span class="text-brand-ink"
                ><span class="font-serif text-2xl font-semibold">9,8</span> / 25 tis. recenzí</span
              >
            </div>

            <div class="mt-10">
              <ThemeButton :to="`/demo/${slug}/kontakt`" variant="solid">Rezervovat</ThemeButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATS BAND -->
    <section class="bg-white py-20">
      <div class="container-x">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="s in statsC"
            :key="s.label"
            class="reveal flex flex-col items-center gap-2 rounded-3xl bg-brand-soft p-10 text-center"
          >
            <span class="font-serif text-5xl font-semibold text-brand">
              <ThemeCounter :to="s.to" :suffix="s.suffix" />
            </span>
            <span class="text-sm font-medium uppercase tracking-wider text-brand-muted">{{
              s.label
            }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- WHY US / BENEFITS -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid items-center gap-14 lg:grid-cols-2">
          <div class="reveal-left overflow-hidden rounded-3xl shadow-lg">
            <img :src="ph.choose" alt="" class="aspect-[5/4] w-full object-cover" />
          </div>

          <div class="reveal-right">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Proč právě my
            </p>
            <h2 class="mt-5 text-4xl leading-tight">Profesionální masáže a luxusní péče</h2>
            <p class="mt-6 leading-relaxed text-brand-muted">
              Ke každému klientovi přistupujeme individuálně. Naším cílem je, abyste odcházeli
              odpočatí, uvolnění a s novou energií.
            </p>

            <ul class="mt-8 flex flex-col gap-5">
              <li v-for="b in c.benefits" :key="b" class="flex items-center gap-4">
                <span
                  class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand/15 text-brand"
                >
                  <span class="material-symbols-outlined text-xl">check</span>
                </span>
                <span class="text-lg text-brand-ink">{{ b }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- FACILITIES TEASER -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Naše prostředí"
          title="Prostor stvořený pro odpočinek"
          text="Pět privátních studií, sauna, parní lázeň a tichá relaxační zóna."
          align="center"
        />

        <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="f in c.facilities"
            :key="f.name"
            class="reveal group overflow-hidden rounded-3xl bg-brand-soft shadow-sm"
          >
            <div class="overflow-hidden">
              <img
                :src="f.image"
                :alt="f.name"
                class="aspect-square w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
            </div>
            <div class="p-6">
              <h3 class="text-xl">{{ f.name }}</h3>
              <p class="mt-3 text-sm leading-relaxed text-brand-muted">{{ f.text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIAL -->
    <section class="bg-brand-soft py-24">
      <div class="container-x">
        <div class="reveal relative mx-auto max-w-3xl text-center">
          <div
            class="absolute -top-10 right-0 hidden size-28 place-items-center rounded-full bg-brand text-white shadow-xl lg:grid"
          >
            <ThemeCircleText
              text="• SERENITY SPA • WELLNESS STUDIO "
              :duration="22"
              class="size-full p-1"
            >
              <span class="material-symbols-outlined text-3xl">spa</span>
            </ThemeCircleText>
          </div>

          <span class="material-symbols-outlined text-6xl text-brand/30">format_quote</span>
          <blockquote
            class="mt-4 font-serif text-2xl italic leading-relaxed text-brand-ink sm:text-3xl"
          >
            „{{ c.testimonials[0].text }}“
          </blockquote>
          <figcaption class="mt-8">
            <span class="block font-serif text-lg font-semibold text-brand-ink">{{
              c.testimonials[0].name
            }}</span>
            <span class="text-sm text-brand-muted">{{ c.testimonials[0].role }}</span>
          </figcaption>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
