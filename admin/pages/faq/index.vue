<script setup lang="ts">
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

<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData[0]"
    />
    <Table
      :columns="[
        { key: 'id', name: 'ID', type: 'number', width: 50 },
        { key: 'todo', name: 'FAQ', type: 'text', width: 50 },
        { key: 'completed', name: 'Aktivní', type: 'status', width: 50 },
        { key: 'userId', name: 'User ID', type: 'number', width: 120 },
      ]"
      :items="data.todos"
      :actions="{ edit: true, view: true, quick: true, delete: true }"
    />
    {{ data.todos }}
    {{ pending }}
    {{ error }}
  </div>
</template>
