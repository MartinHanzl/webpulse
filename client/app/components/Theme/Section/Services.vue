<script setup lang="ts">
import { computed } from 'vue';
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
}>();

const api = useApi();
const { locale } = useI18n();

const { data: apiServices } = useAsyncData<Service[]>(
  'demo-services',
  () => api.service.services(locale.value),
  { watch: [locale], default: () => [] },
);

const hasApi = computed(() => (apiServices.value?.length ?? 0) > 0);
</script>

<template>
  <section id="services" class="section bg-brand-cream">
    <div class="container-x">
      <ThemeSectionHeading :subtitle="subtitle" :title="title" :text="text" max="max-w-3xl" />

      <!-- API services -->
      <div v-if="hasApi" class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <NuxtLink
          v-for="(s, i) in apiServices"
          :key="s.id"
          to="/sluzby"
          class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
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
            <h3 class="text-xl font-bold transition-colors group-hover:text-brand">{{ s.name }}</h3>
            <p
              class="mt-3 line-clamp-3 text-[15px] leading-relaxed text-brand-muted"
              v-html="s.perex"
            />
            <span
              class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all group-hover:gap-3"
            >
              Detail služby
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </span>
          </div>
        </NuxtLink>
      </div>

      <!-- Fallback static services -->
      <div v-else class="mt-14 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="(s, i) in fallback"
          :key="s.name"
          class="reveal group relative flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
          :style="{ transitionDelay: `${(i % 3) * 90}ms` }"
        >
          <div class="relative aspect-[4/3] overflow-hidden">
            <img
              :src="s.image"
              :alt="s.name"
              class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <span
              class="absolute left-5 top-5 flex size-12 items-center justify-center rounded-2xl bg-white/95 text-brand shadow-lg"
            >
              <span class="material-symbols-outlined">{{ s.icon }}</span>
            </span>
          </div>
          <div class="flex flex-1 flex-col p-7">
            <h3 class="text-xl font-bold transition-colors group-hover:text-brand">{{ s.name }}</h3>
            <p class="mt-3 text-[15px] leading-relaxed text-brand-muted">{{ s.perex }}</p>
            <NuxtLink
              to="#contact"
              class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all group-hover:gap-3"
            >
              Mám zájem
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
