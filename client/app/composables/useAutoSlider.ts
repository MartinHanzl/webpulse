/**
 * Tiny autoplay slider state for 1-per-view sliders (hero slideshows,
 * testimonial carousels). No external deps. Auto-advances, pauses on hover via
 * pause()/resume(), and cleans up on unmount.
 */
import { ref, onMounted, onBeforeUnmount } from 'vue';

export function useAutoSlider(length: number | (() => number), interval = 6000, auto = true) {
  const index = ref(0);
  let timer: ReturnType<typeof setInterval> | undefined;
  const len = () => (typeof length === 'function' ? length() : length);

  function go(i: number) {
    const n = len();
    if (n <= 0) return;
    index.value = ((i % n) + n) % n;
  }
  function next() {
    go(index.value + 1);
  }
  function prev() {
    go(index.value - 1);
  }
  function resume() {
    if (!auto) return;
    pause();
    if (len() > 1) timer = setInterval(next, interval);
  }
  function pause() {
    if (timer) {
      clearInterval(timer);
      timer = undefined;
    }
  }

  onMounted(resume);
  onBeforeUnmount(pause);

  return { index, go, next, prev, pause, resume };
}
