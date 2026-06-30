<script setup lang="ts">
defineProps<{
  subtitle: string;
  title: string;
  text: string;
  image: string;
  imageSecondary?: string;
  bullets: string[];
  experienceYears?: string;
  signature?: string;
}>();
</script>

<template>
  <section id="about" class="section">
    <div class="container-x grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
      <!-- Images -->
      <div class="reveal reveal-left relative">
        <div class="overflow-hidden rounded-3xl">
          <img :src="image" alt="" class="aspect-[4/5] w-full object-cover" />
        </div>
        <div
          v-if="imageSecondary"
          class="absolute -bottom-8 -right-4 hidden w-48 overflow-hidden rounded-2xl border-4 border-white shadow-xl sm:block lg:w-56"
        >
          <img :src="imageSecondary" alt="" class="aspect-square w-full object-cover" />
        </div>
        <div
          v-if="experienceYears"
          class="absolute -left-4 bottom-10 flex items-center gap-3 rounded-2xl bg-brand px-5 py-4 text-white shadow-xl"
        >
          <span class="text-4xl font-extrabold !text-white">{{ experienceYears }}</span>
          <span class="text-sm font-medium leading-tight">let<br />zkušeností</span>
        </div>
      </div>

      <!-- Content -->
      <div>
        <ThemeSectionHeading :subtitle="subtitle" :title="title" align="left" />
        <p class="reveal mt-5 text-lg leading-relaxed text-brand-muted">{{ text }}</p>
        <ul class="reveal mt-7 grid gap-3 sm:grid-cols-2">
          <li v-for="b in bullets" :key="b" class="flex items-start gap-3">
            <span
              class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-brand-soft text-brand"
            >
              <span class="material-symbols-outlined text-[16px]">check</span>
            </span>
            <span class="font-medium text-brand-ink">{{ b }}</span>
          </li>
        </ul>
        <div class="reveal mt-9 flex flex-wrap items-center gap-6">
          <ThemeButton to="#contact" size="lg">Domluvit konzultaci</ThemeButton>
          <p v-if="signature" class="text-sm text-brand-muted">
            <span class="font-bold text-brand-ink">{{ signature }}</span
            ><br />
            zakladatel
          </p>
        </div>
      </div>
    </div>
  </section>
</template>
