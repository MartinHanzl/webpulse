<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';

// Background-image parallax layer. Drop in place of a `<div class="absolute
// inset-0"><img/></div>` bg block; put the overlay/gradient in the default slot.
// Pure scroll + rAF, only runs while in view, and respects reduced-motion.
const props = withDefaults(defineProps<{ image: string; speed?: number }>(), { speed: 0.22 });

const root = ref<HTMLElement | null>(null);
const layer = ref<HTMLElement | null>(null);
let raf = 0;
let visible = false;
let io: IntersectionObserver | null = null;
let reduce = false;

function update() {
  const el = root.value;
  const lay = layer.value;
  if (!el || !lay || reduce) return;
  const rect = el.getBoundingClientRect();
  const vh = window.innerHeight || 800;
  const rel = (rect.top + rect.height / 2 - vh / 2) / (vh / 2 + rect.height / 2);
  const shift = -rel * props.speed * rect.height;
  lay.style.transform = `translate3d(0, ${shift.toFixed(1)}px, 0) scale(1.18)`;
}
function onScroll() {
  if (!visible || raf) return;
  raf = requestAnimationFrame(() => {
    raf = 0;
    update();
  });
}

onMounted(() => {
  reduce =
    typeof matchMedia !== 'undefined' && matchMedia('(prefers-reduced-motion: reduce)').matches;
  update();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  if (typeof IntersectionObserver !== 'undefined' && root.value) {
    io = new IntersectionObserver(
      (es) => {
        visible = es.some((e) => e.isIntersecting);
        if (visible) update();
      },
      { threshold: 0 },
    );
    io.observe(root.value);
  } else {
    visible = true;
  }
});
onBeforeUnmount(() => {
  window.removeEventListener('scroll', onScroll);
  window.removeEventListener('resize', onScroll);
  io?.disconnect();
  if (raf) cancelAnimationFrame(raf);
});
</script>

<template>
  <div ref="root" class="absolute inset-0 overflow-hidden">
    <div ref="layer" class="absolute inset-0 will-change-transform">
      <img :src="image" alt="" class="size-full object-cover" />
    </div>
    <slot />
  </div>
</template>
