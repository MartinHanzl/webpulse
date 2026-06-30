<script setup lang="ts">
interface Feature {
  title: string;
  text: string;
  progress: number;
}
defineProps<{
  subtitle: string;
  title: string;
  text: string;
  image: string;
  features: Feature[];
  badge?: string;
}>();
</script>

<template>
  <section class="section">
    <div class="container-x grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
      <!-- Content -->
      <div class="order-2 lg:order-1">
        <ThemeSectionHeading :subtitle="subtitle" :title="title" align="left" />
        <p class="reveal mt-5 text-lg leading-relaxed text-brand-muted">{{ text }}</p>

        <div class="reveal mt-8 flex flex-col gap-6">
          <div v-for="f in features" :key="f.title">
            <div class="mb-2 flex items-center justify-between">
              <span class="font-semibold text-brand-ink">{{ f.title }}</span>
              <span class="text-sm font-bold text-brand">{{ f.progress }}%</span>
            </div>
            <div class="h-2.5 overflow-hidden rounded-full bg-brand-soft">
              <div
                class="h-full rounded-full bg-brand transition-all duration-1000 ease-out"
                :style="{ width: f.progress + '%' }"
              />
            </div>
            <p class="mt-1.5 text-sm text-brand-muted">{{ f.text }}</p>
          </div>
        </div>
      </div>

      <!-- Image -->
      <div class="reveal reveal-right relative order-1 lg:order-2">
        <div class="overflow-hidden rounded-3xl">
          <img :src="image" alt="" class="aspect-[5/6] w-full object-cover" />
        </div>
        <div
          v-if="badge"
          class="absolute right-6 top-6 flex max-w-[180px] items-center gap-3 rounded-2xl bg-white px-5 py-4 shadow-xl"
        >
          <span class="flex size-11 items-center justify-center rounded-full bg-brand text-white">
            <span class="material-symbols-outlined">verified</span>
          </span>
          <span class="text-sm font-semibold leading-tight text-brand-ink">{{ badge }}</span>
        </div>
      </div>
    </div>
  </section>
</template>
