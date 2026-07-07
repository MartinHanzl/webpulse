<script setup lang="ts">
const LINKS = [
  { label: 'Služby', to: '#sluzby' },
  { label: 'Ceník', to: '#cenik' },
  { label: 'Kontakt', to: '#kontakt' },
];
</script>

<template>
  <footer class="border-t border-brand/25 bg-brand-dark py-8">
    <div class="container-x flex flex-wrap items-center justify-between gap-6">
      <span class="text-sm text-white/65">© 2026 Martin Hanzl · IČO 11634031 · Praha</span>
      <div class="flex gap-6 text-sm font-semibold">
        <a
          v-for="link in LINKS"
          :key="link.to"
          :href="link.to"
          class="text-white/80 transition-colors hover:text-brand-accent"
        >
          {{ link.label }}
        </a>
      </div>
    </div>
  </footer>
</template>
