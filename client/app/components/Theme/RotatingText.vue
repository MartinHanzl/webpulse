<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

// Cycles through `words`, cross-fading — replaces the theme's "fancy rotating
// text" effect without any JS library.
const props = withDefaults(defineProps<{ words: string[]; interval?: number }>(), {
  interval: 2600,
});

const i = ref(0);
let timer: ReturnType<typeof setInterval> | undefined;

onMounted(() => {
  if (props.words.length > 1) {
    timer = setInterval(() => {
      i.value = (i.value + 1) % props.words.length;
    }, props.interval);
  }
});
onBeforeUnmount(() => timer && clearInterval(timer));
</script>

<template>
  <span class="relative inline-grid">
    <transition
      enter-active-class="transition duration-500 ease-out"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-300 ease-in absolute inset-0"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
      mode="out-in"
    >
      <span :key="i" class="col-start-1 row-start-1 whitespace-nowrap">{{ words[i] }}</span>
    </transition>
  </span>
</template>
