<script setup lang="ts">
import { useStockImages } from '~/../app/composables/useStockImages';
import { useSiteTheme } from '~/../app/composables/useSiteTheme';

definePageMeta({ layout: false });

const { slug, dark } = useSiteTheme();

const ph = useStockImages().get(slug.value);

const fallback = [
  {
    icon: 'grass',
    name: 'Sečení a údržba trávníku',
    perex: 'Pravidelná i jednorázová údržba travnatých ploch včetně sběru.',
    image: ph.services[0],
  },
  {
    icon: 'water_drop',
    name: 'Automatické závlahy',
    perex: 'Návrh, instalace a servis chytrých závlahových systémů.',
    image: ph.services[1],
  },
  {
    icon: 'compost',
    name: 'Hnojení a regenerace',
    perex: 'Programy výživy pro hustý a sytě zelený trávník.',
    image: ph.services[2],
  },
  {
    icon: 'park',
    name: 'Výsadba zeleně',
    perex: 'Stromy, keře a trvalkové záhony s následnou péčí.',
    image: ph.services[3],
  },
  {
    icon: 'design_services',
    name: 'Návrh zahrady',
    perex: 'Studie a 3D vizualizace zahrady na míru.',
    image: ph.services[4],
  },
  {
    icon: 'deck',
    name: 'Terasy a zpevněné plochy',
    perex: 'Dřevěné terasy, dlažby a okrasné prvky.',
    image: ph.services[5],
  },
];

useHead({ title: 'Naše služby' });
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      title="Naše služby"
      subtitle="Co umíme"
      :crumbs="[{ label: 'Služby' }]"
    />

    <ThemeSectionServices
      :dark="dark"
      subtitle="Kompletní péče"
      title="Služby pro vaši zahradu i trávník"
      text="Od pravidelné údržby po kompletní realizaci. Postaráme se o každý detail."
      :fallback="fallback"
    />

    <ThemeSectionContact
      :demo="demo"
      :dark="dark"
      subtitle="Kontakt"
      title="Nevíte si rady s výběrem?"
      text="Napište nám a my doporučíme řešení na míru vašemu pozemku i rozpočtu."
    />
  </ThemeInnerLayout>
</template>
