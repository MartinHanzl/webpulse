<script setup lang="ts">
import { useHead } from '#app';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

definePageMeta({ layout: false });

useHead({
  title: 'Freelance webdeveloper',
  meta: [
    {
      name: 'description',
      content:
        'Jsem Martin Hanzl, freelance webdeveloper. Firmám, startupům i agenturám stavím rychlé a spolehlivé weby na míru — od návrhu po dlouhodobou správu.',
    },
  ],
});

useScrollReveal();
</script>

<template>
  <div class="demo-root scroll-smooth" data-demo="remeslo">
    <SiteNavbar />
    <main>
      <SiteHero />
      <SiteTechMarquee />
      <SiteServices />
      <SiteCms />
      <SiteProcess />
      <SitePortfolio />
      <SiteReferences />
      <SitePricing />
      <SiteAbout />
      <SiteFaq />
      <SiteContact />
    </main>
    <SiteFooter />
  </div>
</template>
