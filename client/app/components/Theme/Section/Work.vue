<script setup lang="ts">
interface Project {
  title: string;
  category: string;
  image: string;
}
defineProps<{
  subtitle: string;
  title: string;
  text?: string;
  projects: Project[];
}>();
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

      <div class="mt-12 grid auto-rows-[260px] grid-cols-2 gap-5 lg:grid-cols-4">
        <NuxtLink
          v-for="(p, i) in projects"
          :key="p.title"
          to="#contact"
          class="reveal group relative block overflow-hidden rounded-3xl"
          :class="[
            i % 5 === 0 ? 'col-span-2 row-span-2 lg:col-span-2' : '',
            i % 5 === 3 ? 'row-span-2' : '',
          ]"
          :style="{ transitionDelay: `${(i % 4) * 80}ms` }"
        >
          <img
            :src="p.image"
            :alt="p.title"
            class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
          />
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
    </div>
  </section>
</template>
