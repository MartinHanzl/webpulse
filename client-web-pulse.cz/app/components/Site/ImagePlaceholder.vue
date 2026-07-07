<script setup lang="ts">
defineProps<{
  /** short label shown in the middle of the placeholder */
  label: string;
}>();
</script>

<template>
  <div
    class="flex size-full flex-col items-center justify-center gap-3 bg-gradient-to-br from-brand-soft to-brand-cream p-6 text-center"
  >
    <span class="grid size-12 place-items-center rounded-2xl bg-brand/10 text-brand">
      <span class="material-symbols-outlined">imagesmode</span>
    </span>
    <span class="max-w-[24em] text-sm font-semibold text-brand-muted">{{ label }}</span>
  </div>
</template>
