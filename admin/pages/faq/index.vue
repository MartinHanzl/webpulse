<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import Table from "~/components/base/Table.vue";

const {data, pending, error, refresh } = await useFetch('https://dummyjson.com/todos?skip=0&limit=10');
const pageHeadingData = {
  title: 'FAQ',
  breadcrumbLinks: [
    {
      text: 'FAQ',
      href: '/faq',
    },
  ],
  actionButton: {
    text: 'Přidat jazyk',
    href: '/jazyky/pridat',
  }
};

</script>

<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData"
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
      :pending="pending"
    />
    {{ data.todos }}
    {{ pending }}
    {{ error }}
  </div>
</template>
