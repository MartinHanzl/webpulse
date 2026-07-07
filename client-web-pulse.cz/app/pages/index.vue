<script setup lang="ts">
import { useHead } from '#app';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

definePageMeta({ layout: false });

const description =
  'Jsem Martin Hanzl, freelance webdeveloper. Firmám, startupům i agenturám stavím rychlé a spolehlivé weby na míru — od návrhu po dlouhodobou správu.';

useHead({
  title: 'Freelance webdeveloper',
  meta: [
    { name: 'description', content: description },
    { property: 'og:title', content: 'Martin Hanzl — Freelance webdeveloper' },
    { property: 'og:description', content: description },
    { property: 'og:type', content: 'website' },
    { property: 'og:url', content: 'https://web-pulse.cz' },
    { property: 'og:image', content: 'https://web-pulse.cz/images/og.png' },
    { name: 'twitter:card', content: 'summary_large_image' },
    { name: 'twitter:title', content: 'Martin Hanzl — Freelance webdeveloper' },
    { name: 'twitter:description', content: description },
    { name: 'twitter:image', content: 'https://web-pulse.cz/images/og.png' },
  ],
});

useScrollReveal();
</script>

<template>
  <div class="demo-root scroll-smooth" data-demo="remeslo">
    <SiteNavbar />
    <main>
      <SiteHero />
      <!--<SiteTechMarquee />-->
      <SiteServices />
      <SiteCms />
      <SiteProcess />
      <SitePortfolio />
      <!--<SiteReferences />-->
      <SitePricing />
      <SiteAbout />
      <SiteFaq />
      <SiteContact />
    </main>
    <SiteFooter />
  </div>
</template>
