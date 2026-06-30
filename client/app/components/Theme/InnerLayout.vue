<script setup lang="ts">
import { computed } from 'vue';
import { useDemos } from '~/../app/composables/useDemos';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

// Inner pages (blog, faq, services, contact) are shared across demos.
// They use the Lawn palette/brand as a neutral default theme shell.
const props = withDefaults(defineProps<{ slug?: string }>(), { slug: 'lawn' });

const { getDemo, nav } = useDemos();
const demo = computed(() => getDemo(props.slug)!);
const links = computed(() => nav(props.slug));

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
    <ThemeDemoSwitcher :active="slug" />
  </div>
</template>
