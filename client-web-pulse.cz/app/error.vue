<script setup lang="ts">
import type { NuxtError } from '#app';

defineProps({
  error: Object as () => NuxtError,
});
</script>

<template>
  <div class="demo-root flex min-h-screen flex-col" data-demo="remeslo">
    <div class="container-x flex w-full flex-1 flex-col items-center justify-center py-24">
      <NuxtImg src="/static/img/error404.svg" alt="Chyba" class="h-72 w-auto" />
      <div class="mt-8 flex flex-col items-center gap-6 text-center">
        <h1 class="m-0 text-3xl">Stránka nenalezena</h1>
        <p class="m-0 text-brand-muted">Stránka, kterou hledáte, neexistuje nebo byla přesunuta.</p>
        <button
          type="button"
          class="cursor-pointer rounded-full border-none bg-brand px-8 py-3.5 font-bold text-white transition-colors hover:bg-brand-dark"
          @click="clearError({ redirect: '/' })"
        >
          Zpět na úvod
        </button>
      </div>
    </div>
  </div>
</template>
