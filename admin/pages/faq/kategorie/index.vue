<template>
  <div>
    <PageHeading :page-heading-data="pageHeadingData[0]" />
  </div>
</template>
<script setup>
import PageHeading from "~/components/layout/PageHeading.vue";
const pageHeadingData = [
  {
    title: 'FAQ ─ Kategorie',
    links: [
      {
        text: 'Faq',
        href: '/faq',
      },
      {
        text: 'Kategorie',
        href: '/faq/kategorie',
      },
    ],
  },
];
</script>