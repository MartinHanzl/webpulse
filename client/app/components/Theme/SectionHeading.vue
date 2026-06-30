<script setup lang="ts">
withDefaults(
  defineProps<{
    subtitle?: string;
    title?: string;
    text?: string;
    align?: 'left' | 'center';
    light?: boolean;
    max?: string;
  }>(),
  { align: 'center', light: false, max: 'max-w-2xl' },
);
</script>

<template>
  <div
    class="reveal flex flex-col gap-4"
    :class="[
      align === 'center' ? 'mx-auto items-center text-center ' + max : 'items-start text-left',
    ]"
  >
    <span
      v-if="subtitle"
      class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold uppercase tracking-wider"
      :class="light ? 'bg-white/10 text-brand-accent' : 'bg-brand-soft text-brand'"
    >
      <span class="size-1.5 rounded-full bg-current" />
      {{ subtitle }}
    </span>
    <h2
      v-if="title"
      class="text-3xl font-bold leading-tight sm:text-4xl lg:text-[44px]"
      :class="light ? '!text-white' : ''"
    >
      <slot name="title">{{ title }}</slot>
    </h2>
    <p
      v-if="text"
      class="text-base leading-relaxed sm:text-lg"
      :class="light ? 'text-white/70' : 'text-brand-muted'"
    >
      {{ text }}
    </p>
    <slot />
  </div>
</template>
