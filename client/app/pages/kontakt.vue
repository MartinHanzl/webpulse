<script setup lang="ts">
import { useSiteTheme } from '~/../app/composables/useSiteTheme';

definePageMeta({ layout: false });

const { slug, dark } = useSiteTheme();

useHead({ title: 'Kontakt' });
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      title="Kontaktujte nás"
      subtitle="Kontakt"
      :crumbs="[{ label: 'Kontakt' }]"
    />

    <ThemeSectionContact
      :demo="demo"
      :dark="dark"
      subtitle="Spojte se s námi"
      title="Rádi vám pomůžeme"
      text="Zavolejte, napište nebo vyplňte formulář. Ozveme se vám zpravidla do 24 hodin s návrhem řešení."
    />

    <!-- Map placeholder -->
    <section class="pb-24" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <div
          class="reveal overflow-hidden rounded-3xl ring-1"
          :class="dark ? 'ring-white/10' : 'ring-slate-100'"
        >
          <iframe
            title="Mapa"
            class="h-[420px] w-full grayscale"
            loading="lazy"
            src="https://www.openstreetmap.org/export/embed.html?bbox=14.36%2C50.06%2C14.52%2C50.12&amp;layer=mapnik"
          />
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
