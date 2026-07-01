<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue';

// Gallery lightbox. Usage:
//   const lb = ref();
//   <div v-for="(g,i) in items" @click="lb.show(i)">…</div>
//   <ThemeLightbox ref="lb" :images="items" />
interface LightboxItem {
  image: string;
  title?: string;
  category?: string;
}
const props = defineProps<{ images: LightboxItem[] }>();

const open = ref(false);
const idx = ref(0);

function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape') close();
  else if (e.key === 'ArrowRight') next();
  else if (e.key === 'ArrowLeft') prev();
}
function show(i: number) {
  if (!props.images.length) return;
  idx.value = ((i % props.images.length) + props.images.length) % props.images.length;
  open.value = true;
  if (typeof document !== 'undefined') document.addEventListener('keydown', onKey);
}
function close() {
  open.value = false;
  if (typeof document !== 'undefined') document.removeEventListener('keydown', onKey);
}
function next() {
  idx.value = (idx.value + 1) % props.images.length;
}
function prev() {
  idx.value = (idx.value - 1 + props.images.length) % props.images.length;
}

onBeforeUnmount(() => {
  if (typeof document !== 'undefined') document.removeEventListener('keydown', onKey);
});

defineExpose({ show, open: show, close });
</script>

<template>
  <Teleport to="body">
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="open"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
        @click.self="close"
      >
        <button
          class="absolute right-5 top-5 flex size-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
          aria-label="Zavřít"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
        <button
          v-if="images.length > 1"
          class="absolute left-4 flex size-12 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 sm:left-8"
          aria-label="Předchozí"
          @click="prev"
        >
          <span class="material-symbols-outlined text-[28px]">chevron_left</span>
        </button>

        <figure class="flex max-h-[88vh] max-w-[92vw] flex-col items-center gap-4">
          <img
            :src="images[idx].image"
            :alt="images[idx].title || ''"
            class="max-h-[80vh] max-w-[92vw] rounded-xl object-contain shadow-2xl"
          />
          <figcaption v-if="images[idx].title" class="text-center">
            <span
              v-if="images[idx].category"
              class="text-xs font-semibold uppercase tracking-wider text-brand"
              >{{ images[idx].category }}</span
            >
            <p class="text-lg font-semibold text-white">{{ images[idx].title }}</p>
          </figcaption>
        </figure>

        <button
          v-if="images.length > 1"
          class="absolute right-4 flex size-12 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 sm:right-8"
          aria-label="Další"
          @click="next"
        >
          <span class="material-symbols-outlined text-[28px]">chevron_right</span>
        </button>
      </div>
    </transition>
  </Teleport>
</template>
