<script setup lang="ts">
import { ref } from 'vue';
import { useDemos } from '~/../app/composables/useDemos';

defineProps<{ active: string }>();

const { demos } = useDemos();
const open = ref(false);

const swatches: Record<string, string[]> = {
  lawn: ['#1FA12E', '#FECF02', '#F5F5F5'],
  tree: ['#2E7D32', '#8FB339', '#F5F1E8'],
  landscaping: ['#3DA35C', '#FECF02', '#0A0C0A'],
  restaurant: ['#d39121', '#d51f0f', '#282725'],
  lawyer: ['#b98e44', '#152833', '#f6f3ef'],
  freelancer: ['#c2001c', '#232323', '#f7f7f7'],
};
</script>

<template>
  <div class="fixed bottom-5 right-5 z-[60] flex flex-col items-end gap-3">
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 translate-y-3 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-3 scale-95"
    >
      <div
        v-if="open"
        class="w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
      >
        <div class="border-b border-slate-100 px-5 py-4">
          <p class="text-sm font-bold text-slate-900">Demo prezentace</p>
          <p class="text-xs text-slate-500">Vyber ukázkový web pro klienta</p>
        </div>
        <div class="flex flex-col p-2">
          <NuxtLink
            v-for="d in demos"
            :key="d.slug"
            :to="`/demo/${d.slug}`"
            class="flex items-center gap-3 rounded-xl px-3 py-3 transition-colors hover:bg-slate-50"
            :class="active === d.slug ? 'bg-slate-50 ring-1 ring-slate-200' : ''"
            @click="open = false"
          >
            <span class="flex shrink-0 overflow-hidden rounded-lg border border-slate-200">
              <span
                v-for="c in swatches[d.slug]"
                :key="c"
                class="size-5"
                :style="{ background: c }"
              />
            </span>
            <span class="flex flex-1 flex-col">
              <span class="text-sm font-semibold text-slate-900">{{ d.brandName }}</span>
              <span class="text-xs text-slate-500">{{ d.switchLabel }}</span>
            </span>
            <span
              v-if="active === d.slug"
              class="text-[11px] font-bold uppercase tracking-wide text-emerald-600"
              >aktivní</span
            >
          </NuxtLink>
        </div>
      </div>
    </transition>

    <button
      class="flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3.5 text-sm font-semibold text-white shadow-2xl transition-transform hover:scale-105"
      @click="open = !open"
    >
      <svg
        class="size-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
      >
        <path d="M12 3v18M3 7.5h18M3 16.5h18" />
      </svg>
      Demo
    </button>
  </div>
</template>
