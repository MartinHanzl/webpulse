<script setup lang="ts">
definePageMeta({ layout: false });

useHead({ title: 'Kontakt' });
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }">
    <ThemeBreadcrumb title="Kontaktujte nás" subtitle="Kontakt" :crumbs="[{ label: 'Kontakt' }]" />

    <ThemeSectionContact
      :demo="demo"
      subtitle="Spojte se s námi"
      title="Rádi vám pomůžeme"
      text="Zavolejte, napište nebo vyplňte formulář. Ozveme se vám zpravidla do 24 hodin s návrhem řešení."
    />

    <!-- Map placeholder -->
    <section class="pb-24">
      <div class="container-x">
        <div class="reveal overflow-hidden rounded-3xl ring-1 ring-slate-100">
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
