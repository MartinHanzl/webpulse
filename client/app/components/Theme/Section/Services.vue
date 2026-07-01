<script setup lang="ts">
import { computed, ref, onBeforeUnmount } from 'vue';
import { useI18n } from 'vue-i18n';
import { useApi } from '~/../app/composables/useApi';
import type { Service } from '~/types/Service';

interface FallbackService {
  icon: string;
  name: string;
  perex: string;
  image: string;
}

defineProps<{
  subtitle: string;
  title: string;
  text?: string;
  fallback: FallbackService[];
  dark?: boolean;
}>();

const api = useApi();
const { locale } = useI18n();

const { data: apiServices } = useAsyncData<Service[]>(
  'demo-services',
  () => api.service.services(locale.value),
  { watch: [locale], default: () => [] },
);

const hasApi = computed(() => (apiServices.value?.length ?? 0) > 0);

// Scroll-snap carousel (multi-per-view) — replaces the original Swiper slider.
const track = ref<HTMLElement | null>(null);
let timer: ReturnType<typeof setInterval> | undefined;

function scrollByCards(dir: number) {
  const el = track.value;
  if (el) el.scrollBy({ left: dir * el.clientWidth * 0.85, behavior: 'smooth' });
}

function autoplay() {
  const el = track.value;
  if (!el) return;
  if (el.scrollLeft + el.clientWidth >= el.scrollWidth - 8) {
    el.scrollTo({ left: 0, behavior: 'smooth' });
  } else {
    scrollByCards(1);
  }
}

function resume() {
  pause();
  timer = setInterval(autoplay, 5000);
}
function pause() {
  if (timer) {
    clearInterval(timer);
    timer = undefined;
  }
}

onMounted(resume);
onBeforeUnmount(pause);
</script>

<template>
  <section id="services" class="section" :class="dark ? 'bg-neutral-950' : 'bg-brand-cream'">
    <div class="container-x">
      <ThemeSectionHeading
        :subtitle="subtitle"
        :title="title"
        :text="text"
        max="max-w-3xl"
        :light="dark"
      />

      <div class="relative mt-14" @mouseenter="pause" @mouseleave="resume">
        <!-- API services -->
        <div
          v-if="hasApi"
          ref="track"
          class="flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
          style="scrollbar-width: none"
        >
          <NuxtLink
            v-for="(s, i) in apiServices"
            :key="s.id"
            to="/sluzby"
            class="group flex w-[80%] shrink-0 snap-start flex-col overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl sm:w-[45%] lg:w-[31%]"
            :class="dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white'"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <div class="aspect-[4/3] overflow-hidden">
              <BaseImage
                :src="`/content/images/service/medium/${s.image}`"
                :alt="s.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col p-7">
              <h3
                class="text-xl font-bold transition-colors group-hover:text-brand-pop"
                :class="dark ? 'text-white' : ''"
              >
                {{ s.name }}
              </h3>
              <p
                class="mt-3 line-clamp-3 text-[15px] leading-relaxed"
                :class="dark ? 'text-white/60' : 'text-brand-muted'"
                v-html="s.perex"
              />
              <span
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop transition-all group-hover:gap-3"
              >
                Detail služby
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </span>
            </div>
          </NuxtLink>
        </div>

        <!-- Fallback static services -->
        <div
          v-else
          ref="track"
          class="flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
          style="scrollbar-width: none"
        >
          <div
            v-for="(s, i) in fallback"
            :key="s.name"
            class="group relative flex w-[80%] shrink-0 snap-start flex-col overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl sm:w-[45%] lg:w-[31%]"
            :class="dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white'"
            :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
          >
            <div class="relative aspect-[4/3] overflow-hidden">
              <img
                :src="s.image"
                :alt="s.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <span
                class="absolute left-5 top-5 flex size-12 items-center justify-center rounded-2xl bg-white/95 text-brand-pop shadow-lg"
              >
                <span class="material-symbols-outlined">{{ s.icon }}</span>
              </span>
            </div>
            <div class="flex flex-1 flex-col p-7">
              <h3
                class="text-xl font-bold transition-colors group-hover:text-brand-pop"
                :class="dark ? 'text-white' : ''"
              >
                {{ s.name }}
              </h3>
              <p
                class="mt-3 text-[15px] leading-relaxed"
                :class="dark ? 'text-white/60' : 'text-brand-muted'"
              >
                {{ s.perex }}
              </p>
              <NuxtLink
                to="#contact"
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop transition-all group-hover:gap-3"
              >
                Mám zájem
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </NuxtLink>
            </div>
          </div>
        </div>

        <!-- Arrows -->
        <div class="mt-8 flex items-center justify-center gap-4">
          <button
            type="button"
            class="flex size-11 items-center justify-center rounded-full border transition-colors"
            :class="
              dark
                ? 'border-white/20 text-white hover:bg-white hover:text-neutral-950'
                : 'border-brand/20 text-brand-ink hover:bg-brand hover:text-white'
            "
            aria-label="Předchozí"
            @click="scrollByCards(-1)"
          >
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <button
            type="button"
            class="flex size-11 items-center justify-center rounded-full border transition-colors"
            :class="
              dark
                ? 'border-white/20 text-white hover:bg-white hover:text-neutral-950'
                : 'border-brand/20 text-brand-ink hover:bg-brand hover:text-white'
            "
            aria-label="Další"
            @click="scrollByCards(1)"
          >
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
