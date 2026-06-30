<script setup lang="ts">
import { computed, ref } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import type { DemoFaq } from '~/../app/composables/useDemoContent';

const props = withDefaults(
  defineProps<{
    demo: DemoDefinition;
    dark?: boolean;
    items: DemoFaq[];
  }>(),
  { dark: false },
);

const open = ref(0);
const toggle = (i: number) => {
  open.value = open.value === i ? -1 : i;
};

const section = computed(() => (props.dark ? 'bg-neutral-950' : 'bg-white'));
const heading = computed(() => (props.dark ? 'text-white' : 'text-brand-ink'));
const body = computed(() => (props.dark ? 'text-white/60' : 'text-brand-muted'));
const item = computed(() =>
  props.dark ? 'bg-white/[0.04] border border-white/10' : 'bg-white border border-slate-100',
);
</script>

<template>
  <section class="section" :class="section">
    <div class="container-x max-w-3xl">
      <ThemeSectionHeading subtitle="FAQ" title="Časté dotazy" :light="dark" max="max-w-2xl" />

      <div class="mt-12 flex flex-col gap-4">
        <div
          v-for="(f, i) in items"
          :key="i"
          class="reveal overflow-hidden rounded-2xl transition-all duration-300"
          :class="item"
        >
          <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
            :aria-expanded="open === i"
            @click="toggle(i)"
          >
            <span class="text-[17px] font-bold" :class="heading">{{ f.question }}</span>
            <span
              class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-soft text-brand transition-transform duration-300"
              :class="open === i ? 'rotate-180' : ''"
            >
              <span class="material-symbols-outlined text-[22px]">expand_more</span>
            </span>
          </button>
          <div v-show="open === i" class="px-6 pb-6">
            <p class="text-[15px] leading-relaxed" :class="body">{{ f.answer }}</p>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div
        class="reveal mt-12 flex flex-col items-center gap-5 rounded-3xl bg-brand px-8 py-12 text-center"
      >
        <h3 class="text-2xl font-bold text-white sm:text-3xl">Nenašli jste odpověď?</h3>
        <p class="max-w-md text-white/80">
          Ozvěte se nám — rádi zodpovíme vaše dotazy a připravíme nezávaznou nabídku.
        </p>
        <ThemeButton :to="`/demo/${demo.slug}/kontakt`" variant="light" size="lg">
          Nezávazná poptávka
        </ThemeButton>
      </div>
    </div>
  </section>
</template>
