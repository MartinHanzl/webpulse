<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData[0]"
    />
    <Table />
    {{ data.todos }}
    {{ pending }}
    {{ error }}
  </div>
</template>
<script setup>
import PageHeading from "~/components/layout/PageHeading.vue";
import Table from "~/components/base/Table.vue";

const {data, pending, error, refresh } = await useFetch('https://dummyjson.com/todos?skip=0&limit=10');
const pageHeadingData = [
  {
    title: 'FAQ',
    links: [
      {
        text: 'Faq',
        href: '/faq',
      },
    ],
    actionButton: {
      text: 'Přidat FAQ otázku',
      href: '/faq/pridat',
    },
  },
];
</script>
