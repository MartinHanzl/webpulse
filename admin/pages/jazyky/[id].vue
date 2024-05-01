<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";

const pageHeadingData = {
  title: 'Jazyky',
  breadcrumbLinks: [
    {
      text: 'Jazyky',
      href: '/jazyky',
    },
    {
      text: 'Přidat jazyk',
      href: '/jazyky/pridat',
    },
  ],
};
</script>
<template>
  <div>
    <PageHeading :page-heading-data="pageHeadingData" />
    Přidat jazyk
  </div>
</template>