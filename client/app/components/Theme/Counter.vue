<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = withDefaults(
  defineProps<{ to: number; duration?: number; suffix?: string; prefix?: string }>(),
  { duration: 1800, suffix: '', prefix: '' },
);

const el = ref<HTMLElement | null>(null);
const display = ref(0);
let started = false;
let observer: IntersectionObserver | null = null;
let raf = 0;

function run() {
  if (started) return;
  started = true;
  const start = performance.now();
  const tick = (now: number) => {
    const p = Math.min((now - start) / props.duration, 1);
    const eased = 1 - Math.pow(1 - p, 3);
    display.value = Math.round(eased * props.to);
    if (p < 1) raf = requestAnimationFrame(tick);
  };
  raf = requestAnimationFrame(tick);
}

onMounted(() => {
  if (typeof IntersectionObserver === 'undefined') {
    display.value = props.to;
    return;
  }
  observer = new IntersectionObserver(
    (entries) => {
      if (entries.some((e) => e.isIntersecting)) {
        run();
        observer?.disconnect();
      }
    },
    { threshold: 0.4 },
  );
  if (el.value) observer.observe(el.value);
});

onBeforeUnmount(() => {
  observer?.disconnect();
  cancelAnimationFrame(raf);
});
</script>

<template>
  <span ref="el">{{ prefix }}{{ display.toLocaleString('cs-CZ') }}{{ suffix }}</span>
</template>
