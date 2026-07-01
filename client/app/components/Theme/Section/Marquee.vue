<script setup lang="ts">
withDefaults(defineProps<{ words: string[]; dark?: boolean; icon?: string }>(), { icon: 'eco' });
</script>

<template>
  <div class="marquee-mask py-6" :class="dark ? 'bg-brand-dark' : 'bg-brand'">
    <div class="marquee-track">
      <template v-for="n in 2" :key="n">
        <span v-for="w in words" :key="w + n" class="flex items-center">
          <span class="px-8 text-2xl font-bold uppercase tracking-tight text-white sm:text-3xl">{{
            w
          }}</span>
          <span
            class="material-symbols-outlined text-brand-accent"
            style="font-variation-settings: 'FILL' 1"
            >{{ icon }}</span
          >
        </span>
      </template>
    </div>
  </div>
</template>
