import { onMounted, onBeforeUnmount } from 'vue';

/**
 * Lightweight replacement for the theme's `sal.js`.
 * Observes every `.reveal` element and toggles `.is-visible` when it enters
 * the viewport. Re-scans on mount so dynamically rendered sections animate too.
 */
export function useScrollReveal() {
  let observer: IntersectionObserver | null = null;

  const scan = () => {
    if (!observer) return;
    document.querySelectorAll('.reveal:not(.is-observed)').forEach((el) => {
      el.classList.add('is-observed');
      observer!.observe(el);
    });
  };

  onMounted(() => {
    if (typeof IntersectionObserver === 'undefined') {
      document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
      return;
    }

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer!.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    scan();
    // Re-scan shortly after to catch async content (API lists etc.)
    setTimeout(scan, 400);
    setTimeout(scan, 1200);
  });

  onBeforeUnmount(() => {
    observer?.disconnect();
    observer = null;
  });

  return { scan };
}
