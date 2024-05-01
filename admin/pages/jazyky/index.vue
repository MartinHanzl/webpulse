<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import Table from "~/components/base/Table.vue";
import { ref, onMounted, watch } from 'vue';

const pageHeadingData = {
  title: 'Jazyky',
  breadcrumbLinks: [
    {
      text: 'Jazyky',
      href: '/jazyky',
    },
  ],
  actionButton: {
    text: 'Přidat jazyk',
    href: '/jazyky/pridat',
  }
};

const items = ref({data:[]});
const pending = ref(false);
const page = ref(1);

async function loadItems() {
  pending.value = true;
  await useFetch('http://localhost:8000/api/admin/language', {
    method: 'GET',
    params: {
      page: page,
      perPage: 2,
      orderBy: 'id',
      orderWay: 'desc',
    },
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  }).then((response) => {
    items.value = response.data.value;
    pending.value = false;
  })
}
function updatePage(newPage: Number) {
  page.value = newPage;
  loadItems();
}
onBeforeMount(() => {
  loadItems();
});
</script>

<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData"
    />
    <Table
      :columns="[
        { key: 'id', name: 'ID', type: 'number', width: 50, mobile: true },
        { key: 'name', name: 'Název', type: 'text', width: 50, mobile: true },
        { key: 'code', name: 'Kód', type: 'text', width: 50, mobile: false },
        { key: 'iso', name: 'Iso kód', type: 'text', width: 50, mobile: false },
        { key: 'active', name: 'Aktivní', type: 'status', width: 50, mobile: true },
        { key: 'created_at', name: 'Vytvořeno', type: 'date', width: 50, mobile: false },
      ]"
      :items="items"
      :actions="{ edit: true, view: true, delete: true }"
      :pending="pending"
      :titles="{
        singular: 'Jazyk',
        plural: 'Jazyky',
      }"
      :pagination="{ total: 5, perPage: 2, currentPage: 1}"
      @update-page="updatePage"
    />
  </div>
</template>