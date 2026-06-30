/**
 * Active theme for the real (non-/demo) site. Driven by the `siteTheme`
 * runtime config (env `SITE_THEME`, default 'lawn'). The whole public site —
 * blog, FAQ, contact, services, reviews — renders in this demo's theme, so
 * after copying /client for a client you only flip SITE_THEME to the demo they
 * picked and everything re-skins (palette + navbar/footer variant + light/dark).
 */
import { computed } from 'vue';
import { useRuntimeConfig } from '#app';
import { useDemos, type DemoDefinition } from '~/../app/composables/useDemos';

export function useSiteTheme() {
  const { getDemo } = useDemos();
  const slug = computed<string>(() => String(useRuntimeConfig().public.siteTheme ?? 'lawn'));
  const demo = computed<DemoDefinition>(() => getDemo(slug.value) ?? getDemo('lawn')!);
  const dark = computed<boolean>(() => !!demo.value.dark);
  return { slug, demo, dark };
}
