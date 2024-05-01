<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import Table from "~/components/base/Table.vue";

const pageHeadingData = [
  {
    title: 'Jazyky',
    links: [
      {
        text: 'Jazyky',
        href: '/jazyky',
      },
    ],
    actionButton: {
      text: 'Přidat jazyk',
      href: '/jazyky/pridat',
    },
  },
];
</script>

<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData[0]"
    />
  </div>
</template>