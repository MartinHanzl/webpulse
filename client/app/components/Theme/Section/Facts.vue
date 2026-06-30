<script setup lang="ts">
interface Fact {
  value: number;
  suffix?: string;
  label: string;
  icon: string;
}
defineProps<{ items: Fact[]; image?: string }>();
</script>

<template>
  <section class="relative overflow-hidden bg-brand-dark py-20">
    <div v-if="image" class="absolute inset-0 opacity-10">
      <img :src="image" alt="" class="size-full object-cover" />
    </div>
    <div
      class="pointer-events-none absolute -left-20 top-1/2 size-72 -translate-y-1/2 rounded-full bg-brand/20 blur-3xl"
    />
    <div class="container-x relative grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
      <div
        v-for="(f, i) in items"
        :key="f.label"
        class="reveal flex flex-col items-center gap-3 text-center"
        :style="{ transitionDelay: `${i * 100}ms` }"
      >
        <span
          class="flex size-16 items-center justify-center rounded-2xl bg-white/10 text-brand-accent"
        >
          <span class="material-symbols-outlined text-[32px]">{{ f.icon }}</span>
        </span>
        <span class="text-4xl font-extrabold !text-white sm:text-5xl">
          <ThemeCounter :to="f.value" :suffix="f.suffix" />
        </span>
        <span class="text-sm font-medium text-white/60">{{ f.label }}</span>
      </div>
    </div>
  </section>
</template>
