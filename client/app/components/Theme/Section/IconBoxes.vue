<script setup lang="ts">
interface Box {
  icon: string; // material symbol name
  title: string;
  text: string;
}
defineProps<{ items: Box[] }>();
</script>

<template>
  <section class="relative z-20 -mt-20">
    <div class="container-x">
      <div class="grid gap-6 rounded-3xl bg-brand-cream p-6 shadow-xl sm:p-8 md:grid-cols-3">
        <div
          v-for="(box, i) in items"
          :key="box.title"
          class="reveal group flex flex-col items-start gap-4 rounded-2xl bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
          :style="{ transitionDelay: `${i * 90}ms` }"
        >
          <span
            class="flex size-14 items-center justify-center rounded-2xl bg-brand-soft text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white"
          >
            <span class="material-symbols-outlined text-[28px]">{{ box.icon }}</span>
          </span>
          <h3 class="text-xl font-bold">{{ box.title }}</h3>
          <p class="text-[15px] leading-relaxed text-brand-muted">{{ box.text }}</p>
          <NuxtLink
            to="#services"
            class="mt-auto inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-colors group-hover:gap-2.5"
          >
            Více informací
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </NuxtLink>
        </div>
      </div>
    </div>
  </section>
</template>
