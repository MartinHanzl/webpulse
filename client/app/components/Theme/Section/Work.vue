<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue';

interface Project {
  title: string;
  category: string;
  image: string;
}
const props = defineProps<{
  subtitle: string;
  title: string;
  text?: string;
  projects: Project[];
}>();

// Horizontal scroll-snap carousel with clickable dot indicators + arrows.
// Replaces the original Swiper portfolio slider; tiles stay NuxtLinks.
const track = ref<HTMLElement | null>(null);
const active = ref(0);
let raf = 0;
let timer: ReturnType<typeof setInterval> | undefined;

function cardStep(el: HTMLElement) {
  const first = el.children[0] as HTMLElement | undefined;
  const second = el.children[1] as HTMLElement | undefined;
  if (first && second) return second.offsetLeft - first.offsetLeft;
  if (first) return first.offsetWidth;
  return el.clientWidth;
}

function onScroll() {
  cancelAnimationFrame(raf);
  raf = requestAnimationFrame(() => {
    const el = track.value;
    if (!el) return;
    active.value = Math.round(el.scrollLeft / cardStep(el));
  });
}

function go(i: number) {
  const el = track.value;
  if (!el) return;
  const n = props.projects.length;
  const idx = ((i % n) + n) % n;
  const child = el.children[idx] as HTMLElement | undefined;
  const base = (el.children[0] as HTMLElement | undefined)?.offsetLeft ?? 0;
  if (child) el.scrollTo({ left: child.offsetLeft - base, behavior: 'smooth' });
  active.value = idx;
}

function resume() {
  pause();
  timer = setInterval(() => go(active.value + 1), 5000);
}
function pause() {
  if (timer) {
    clearInterval(timer);
    timer = undefined;
  }
}

onMounted(resume);
onBeforeUnmount(() => {
  pause();
  cancelAnimationFrame(raf);
});
</script>

<template>
  <section id="work" class="section">
    <div class="container-x">
      <div class="flex flex-col items-end justify-between gap-6 sm:flex-row">
        <ThemeSectionHeading
          :subtitle="subtitle"
          :title="title"
          :text="text"
          align="left"
          max="max-w-2xl"
        />
        <ThemeButton to="#contact" variant="outline" size="md" class="reveal shrink-0"
          >Všechny reference</ThemeButton
        >
      </div>

      <div class="relative mt-12" @mouseenter="pause" @mouseleave="resume">
        <div
          ref="track"
          class="flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
          style="scrollbar-width: none"
          @scroll="onScroll"
        >
          <NuxtLink
            v-for="(p, i) in projects"
            :key="p.title"
            to="#contact"
            class="reveal group relative block w-[80%] shrink-0 snap-start overflow-hidden rounded-3xl sm:w-[55%] lg:w-[31%]"
            :style="{ transitionDelay: `${(i % 4) * 80}ms` }"
          >
            <div class="aspect-[4/5] overflow-hidden">
              <img
                :src="p.image"
                :alt="p.title"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div
              class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 via-brand-dark/10 to-transparent opacity-80 transition-opacity group-hover:opacity-100"
            />
            <div
              class="absolute inset-x-0 bottom-0 translate-y-2 p-6 transition-transform duration-300 group-hover:translate-y-0"
            >
              <span class="text-xs font-semibold uppercase tracking-wider text-brand-accent">{{
                p.category
              }}</span>
              <h3 class="mt-1 text-xl font-bold !text-white">{{ p.title }}</h3>
            </div>
            <span
              class="absolute right-5 top-5 flex size-11 -translate-y-2 items-center justify-center rounded-full bg-white text-brand-ink opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"
            >
              <span class="material-symbols-outlined">north_east</span>
            </span>
          </NuxtLink>
        </div>

        <!-- Controls -->
        <div class="mt-8 flex items-center justify-center gap-4">
          <button
            type="button"
            class="flex size-11 items-center justify-center rounded-full border border-brand/20 text-brand-ink transition-colors hover:bg-brand hover:text-white"
            aria-label="Předchozí"
            @click="go(active - 1)"
          >
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <div class="flex gap-2">
            <button
              v-for="(p, i) in projects"
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
            @click="go(active + 1)"
          >
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
