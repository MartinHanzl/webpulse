<script setup lang="ts">
import { computed } from 'vue';
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

definePageMeta({ layout: false });

const slug = 'lawyer';
const c = useLawyerContent();
const ph = useStockImages().get('lawyer');

// Parse stat strings ("1 200+" / "98 %" / "25") into count-up values.
const statNum = (v: string): { to: number; suffix: string } => ({
  to: parseInt(v.replace(/[^\d]/g, ''), 10) || 0,
  suffix: v.includes('%') ? ' %' : v.includes('+') ? '+' : '',
});
const statsC = c.stats.map((s) => ({ ...s, ...statNum(s.value) }));

// Testimonials 1-per-view cross-fade carousel.
const {
  index: tIndex,
  go: tGo,
  pause: tPause,
  resume: tResume,
} = useAutoSlider(c.testimonials.length, 7000);
const activeQuote = computed(() => c.testimonials[tIndex.value]);

useHead(() => ({ title: 'O nás — Veritas' }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="O kanceláři"
      title="Kdo jsme"
      :image="ph.hero"
      :crumbs="[{ label: 'O nás' }]"
    />

    <!-- STATS -->
    <section class="bg-white py-24">
      <div class="container-x">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="s in statsC"
            :key="s.label"
            class="reveal flex flex-col items-center gap-2 rounded-2xl border border-brand-ink/10 bg-brand-cream p-10 text-center shadow-sm transition-transform duration-300 hover:-translate-y-1"
          >
            <span class="text-5xl font-semibold text-brand">
              <ThemeCounter :to="s.to" :suffix="s.suffix" />
            </span>
            <span class="text-sm font-medium uppercase tracking-wider text-brand-muted">{{
              s.label
            }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- SPLIT: image + navy panel -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <div class="grid items-stretch gap-10 lg:grid-cols-2">
          <div class="reveal-left overflow-hidden rounded-3xl shadow-lg">
            <img :src="ph.aboutMain" alt="" class="size-full min-h-[420px] object-cover" />
          </div>

          <div
            class="reveal-right flex flex-col justify-center rounded-3xl bg-brand-dark p-10 sm:p-14"
          >
            <span
              class="flex size-14 items-center justify-center rounded-2xl bg-brand/20 text-brand"
            >
              <span class="material-symbols-outlined text-3xl">balance</span>
            </span>
            <p
              class="mt-6 flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              Advokátní kancelář Veritas
            </p>
            <h2 class="mt-4 text-3xl leading-tight !text-white sm:text-4xl">
              Právo, na které se <span class="italic text-brand">spolehnete</span>
            </h2>
            <p class="mt-6 text-lg leading-relaxed text-white/70">
              Už čtvrt století poskytujeme klientům komplexní právní služby s důrazem na odbornost,
              diskrétnost a lidský přístup. Za každým případem vidíme konkrétního člověka a jeho
              příběh.
            </p>
            <ul class="mt-8 flex flex-col gap-4">
              <li class="flex items-start gap-3 text-white/80">
                <span class="material-symbols-outlined mt-0.5 text-brand">check_circle</span>
                <span>Zkušený tým advokátů napříč všemi právními odvětvími</span>
              </li>
              <li class="flex items-start gap-3 text-white/80">
                <span class="material-symbols-outlined mt-0.5 text-brand">check_circle</span>
                <span>Transparentní ceny a pravidelná komunikace o vývoji případu</span>
              </li>
            </ul>
            <div class="mt-10">
              <ThemeButton :to="`/demo/${slug}/advokati`" variant="solid" size="md">
                Náš tým
              </ThemeButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROCESS -->
    <section class="bg-white py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Jak pracujeme"
          title="Cesta k řešení"
          text="Od první konzultace až po úspěšné vyřešení vás provedeme každým krokem."
          align="center"
        />

        <div class="relative mt-16 grid gap-10 md:grid-cols-2 lg:grid-cols-4">
          <div
            class="pointer-events-none absolute left-0 right-0 top-8 hidden border-t border-dashed border-brand/30 lg:block"
          />
          <div
            v-for="step in c.process"
            :key="step.no"
            class="reveal relative flex flex-col items-center text-center"
          >
            <span
              class="relative z-10 flex size-16 items-center justify-center rounded-full bg-brand text-xl font-semibold text-white shadow-lg"
            >
              {{ step.no }}
            </span>
            <span
              class="mt-6 flex size-12 items-center justify-center rounded-2xl bg-brand-cream text-brand"
            >
              <span class="material-symbols-outlined text-2xl">{{ step.icon }}</span>
            </span>
            <h3 class="mt-5 text-xl">{{ step.title }}</h3>
            <p class="mt-3 text-sm leading-relaxed text-brand-muted">{{ step.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS CAROUSEL -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Reference"
          title="Co říkají klienti"
          text="Důvěra našich klientů je tím nejlepším měřítkem naší práce."
          align="center"
        />

        <div class="reveal mx-auto mt-14 max-w-3xl" @mouseenter="tPause()" @mouseleave="tResume()">
          <div
            class="relative overflow-hidden rounded-3xl border border-brand-ink/5 bg-white p-10 text-center shadow-sm sm:p-14"
          >
            <span class="material-symbols-outlined text-5xl text-brand/30">format_quote</span>
            <transition name="slidefade" mode="out-in">
              <figure :key="tIndex">
                <div class="flex justify-center gap-1 text-brand">
                  <span
                    v-for="n in activeQuote.rating"
                    :key="n"
                    class="material-symbols-outlined text-xl"
                    style="font-variation-settings: 'FILL' 1"
                    >star</span
                  >
                </div>
                <blockquote class="mt-5 text-xl italic leading-relaxed text-brand-ink sm:text-2xl">
                  „{{ activeQuote.text }}“
                </blockquote>
                <figcaption class="mt-6">
                  <span class="block font-serif text-lg font-semibold text-brand-ink">{{
                    activeQuote.name
                  }}</span>
                  <span class="text-sm text-brand-muted">{{ activeQuote.role }}</span>
                </figcaption>
              </figure>
            </transition>
          </div>

          <div class="mt-8 flex items-center justify-center gap-3">
            <button
              v-for="(t, i) in c.testimonials"
              :key="t.name"
              type="button"
              :aria-label="`Reference ${i + 1}`"
              class="h-2 rounded-full transition-all duration-300"
              :class="tIndex === i ? 'w-8 bg-brand' : 'w-2 bg-brand/30 hover:bg-brand/60'"
              @click="tGo(i)"
            />
          </div>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>

<style scoped>
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
