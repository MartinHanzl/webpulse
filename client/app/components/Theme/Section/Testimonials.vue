<script setup lang="ts">
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

interface Testimonial {
  name: string;
  role: string;
  text: string;
  avatar?: string;
  rating?: number;
}
const props = defineProps<{
  subtitle: string;
  title: string;
  items: Testimonial[];
}>();

// 1-per-view auto-advancing carousel (pause on hover).
const {
  index: active,
  go,
  next,
  prev,
  pause,
  resume,
} = useAutoSlider(() => props.items.length, 6000);
</script>

<template>
  <section
    class="section relative overflow-hidden bg-brand-soft"
    @mouseenter="pause"
    @mouseleave="resume"
  >
    <div class="container-x">
      <ThemeSectionHeading :subtitle="subtitle" :title="title" />

      <div class="relative mx-auto mt-14 max-w-3xl">
        <span
          class="material-symbols-outlined absolute -top-6 left-0 text-[120px] leading-none text-brand/15"
          >format_quote</span
        >

        <div class="relative overflow-hidden">
          <div
            class="flex transition-transform duration-500 ease-out"
            :style="{ transform: `translateX(-${active * 100}%)` }"
          >
            <div v-for="t in items" :key="t.name" class="w-full shrink-0 px-1">
              <div class="rounded-3xl bg-white p-8 text-center shadow-sm sm:p-12">
                <div class="flex justify-center gap-1 text-brand-accent">
                  <span
                    v-for="n in 5"
                    :key="n"
                    class="material-symbols-outlined text-[22px]"
                    :class="n <= (t.rating ?? 5) ? '' : 'opacity-30'"
                    style="font-variation-settings: 'FILL' 1"
                    >star</span
                  >
                </div>
                <p class="mt-6 text-lg leading-relaxed text-brand-ink sm:text-xl">"{{ t.text }}"</p>
                <div class="mt-8 flex items-center justify-center gap-4">
                  <img
                    v-if="t.avatar"
                    :src="t.avatar"
                    :alt="t.name"
                    class="size-14 rounded-full object-cover"
                  />
                  <div
                    v-else
                    class="flex size-14 items-center justify-center rounded-full bg-brand text-lg font-bold text-white"
                  >
                    {{ t.name.charAt(0) }}
                  </div>
                  <div class="text-left">
                    <p class="font-bold text-brand-ink">{{ t.name }}</p>
                    <p class="text-sm text-brand-muted">{{ t.role }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Controls -->
        <div class="mt-8 flex items-center justify-center gap-4">
          <button
            type="button"
            class="flex size-11 items-center justify-center rounded-full border border-brand/20 text-brand-ink transition-colors hover:bg-brand hover:text-white"
            aria-label="Předchozí"
            @click="prev"
          >
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <div class="flex gap-2">
            <button
              v-for="(t, i) in items"
              :key="i"
              type="button"
              class="h-2.5 rounded-full transition-all"
              :class="i === active ? 'w-7 bg-brand' : 'w-2.5 bg-brand/30'"
              :aria-label="`Reference ${i + 1}`"
              @click="go(i)"
            />
          </div>
          <button
            type="button"
            class="flex size-11 items-center justify-center rounded-full border border-brand/20 text-brand-ink transition-colors hover:bg-brand hover:text-white"
            aria-label="Další"
            @click="next"
          >
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
