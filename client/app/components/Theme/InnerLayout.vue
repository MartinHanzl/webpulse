<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from '#app';
import { useDemos } from '~/../app/composables/useDemos';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

// Shared theme shell for inner pages. Used both by the per-demo showcase pages
// (/demo/<slug>/…) and by the real site (blog, faq, contact…). On /demo routes
// it shows the demo navigation + the demo switcher; on the real site it uses
// the real CMS navigation and hides the switcher.
const props = withDefaults(defineProps<{ slug?: string }>(), { slug: 'lawn' });

const route = useRoute();
const isDemo = computed(() => route.path.startsWith('/demo'));

const { getDemo, nav, siteNav } = useDemos();
const demo = computed(() => getDemo(props.slug)!);
const links = computed(() => (isDemo.value ? nav(props.slug) : siteNav()));

useScrollReveal();
</script>

<template>
  <div
    class="demo-root min-h-screen"
    :class="demo.dark ? 'bg-neutral-950 text-white' : 'bg-white'"
    :data-demo="demo.palette"
  >
    <ThemeNavbar :demo="demo" :links="links" />
    <main>
      <slot :demo="demo" />
    </main>
    <ThemeFooter :demo="demo" :links="links" />
    <ThemeDemoSwitcher v-if="isDemo" :active="slug" />
  </div>
</template>
