<script setup lang="ts">
const TECH = ['React', 'Vue & Nuxt', 'MySQL', 'AWS', 'Laravel', 'TypeScript', 'Node.js'];
</script>

<template>
  <div class="border-y border-brand-soft bg-white py-[18px]">
    <div class="marquee-mask">
      <div class="marquee-track items-center text-[15px] font-bold text-brand-muted">
        <div
          v-for="copy in 2"
          :key="copy"
          :aria-hidden="copy === 2 ? 'true' : undefined"
          class="flex items-center gap-14 pr-14"
        >
          <template v-for="tech in TECH" :key="tech">
            <span>{{ tech }}</span>
            <span class="text-brand-accent">✦</span>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
