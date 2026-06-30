<script setup lang="ts">
import { computed } from 'vue';
import { useDemos } from '~/../app/composables/useDemos';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

const props = defineProps<{ slug: string }>();

const { getDemo, nav } = useDemos();
const demo = computed(() => getDemo(props.slug)!);
const links = computed(() => nav(props.slug));

useScrollReveal();
</script>

<template>
  <div class="demo-root min-h-screen bg-white" :data-demo="demo.palette">
    <ThemeNavbar :demo="demo" :links="links" transparent />
    <main>
      <slot :demo="demo" />
    </main>
    <ThemeFooter :demo="demo" :links="links" />
    <ThemeDemoSwitcher :active="slug" />
  </div>
</template>
