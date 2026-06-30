<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    to?: string;
    variant?: 'solid' | 'outline' | 'accent' | 'dark' | 'light';
    size?: 'sm' | 'md' | 'lg';
    icon?: boolean;
  }>(),
  { variant: 'solid', size: 'md', icon: true },
);

const classes = computed(() => {
  const base =
    'group inline-flex items-center justify-center gap-2 rounded-full font-semibold tracking-tight transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-brand/30';
  const sizes: Record<string, string> = {
    sm: 'px-5 py-2.5 text-sm',
    md: 'px-7 py-3.5 text-[15px]',
    lg: 'px-9 py-4 text-base',
  };
  const variants: Record<string, string> = {
    solid:
      'bg-brand text-white hover:bg-brand-dark shadow-lg shadow-brand/25 hover:-translate-y-0.5',
    outline: 'border-2 border-brand text-brand-ink hover:bg-brand hover:text-white',
    accent:
      'bg-brand-accent text-brand-dark hover:brightness-95 shadow-lg shadow-brand-accent/30 hover:-translate-y-0.5',
    dark: 'bg-brand-dark text-white hover:bg-brand hover:-translate-y-0.5',
    light: 'bg-white text-brand-ink hover:bg-brand hover:text-white shadow-lg',
  };
  return [base, sizes[props.size], variants[props.variant]].join(' ');
});
</script>

<template>
  <component :is="to ? resolveComponent('NuxtLink') : 'button'" :to="to" :class="classes">
    <slot />
    <svg
      v-if="icon"
      class="size-4 transition-transform duration-300 group-hover:translate-x-1"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="2.5"
      stroke-linecap="round"
      stroke-linejoin="round"
    >
      <path d="M5 12h14M13 6l6 6-6 6" />
    </svg>
  </component>
</template>
