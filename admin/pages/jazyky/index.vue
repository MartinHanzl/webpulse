<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import Table from "~/components/base/Table.vue";
import { ref, onMounted, watch } from 'vue';
import debounce from 'lodash/debounce';

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
    type: 'link'
  }
};

const items = ref({data:[]});
const pending = ref(false);
const page = ref(1);
const searchString = ref(inject('searchString'));
const order = ref({
  orderBy: 'id',
  orderWay: 'desc',
});

async function loadItems() {
  pending.value = true;
  await useFetch('/api/admin/language', {
    method: 'GET',
    params: {
      page: page,
      perPage: 12,
      orderBy: order.value.orderBy,
      orderWay: order.value.orderWay,
      search: searchString.value,
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
function updateOrder(newOrder: Object) {
  order.value = newOrder;
  loadItems();
}
onBeforeMount(() => {
  loadItems();
});
onMounted(() => {
  searchString.value = '';
});
watch(searchString, () => {
  loadItems();
});
</script>

<template>
  <div>
    <PageHeading
      :page-heading-data="pageHeadingData"
    />
    <Table
      v-model:order="order"
      :columns="[
        { key: 'id', name: 'ID', type: 'number', width: 50, mobile: true, sortable: true },
        { key: 'name', name: 'Název', type: 'text', width: 50, mobile: true, sortable: false },
        { key: 'code', name: 'Kód', type: 'text', width: 50, mobile: false, sortable: true },
        { key: 'iso', name: 'Iso kód', type: 'text', width: 50, mobile: false, sortable: true },
        { key: 'active', name: 'Aktivní', type: 'status', width: 50, mobile: true, sortable: true },
        { key: 'created_at', name: 'Vytvořeno', type: 'date', width: 50, mobile: false, sortable: true },
      ]"
      :items="items"
      :detail-url="'/jazyky/'"
      :actions="{ edit: true, view: true, delete: true }"
      :pending="pending"
      :titles="{
        singular: 'jazyk',
        plural: 'Jazyky',
      }"
      :slideover="{
        title: 'Detail jazyka',
        api: '/api/admin/language/',
        columns: [
          { key: 'id', name: 'ID', type: 'number' },
          { key: 'name', name: 'Název', type: 'text' },
          { key: 'code', name: 'Kód', type: 'text' },
          { key: 'iso', name: 'Iso kód', type: 'text' },
          { key: 'active', name: 'Aktivní', type: 'status' },
          { key: 'created_at', name: 'Vytvořeno', type: 'date' },
          { key: 'updated_at', name: 'Poslední úprava', type: 'date' },
        ]
      }"
      :pagination="{ total: items.total, perPage: items.perPage, currentPage: items.currentPage, lastPage: items.lastPage, from: items.from }"
      @update-page="updatePage"
      @update-order="updateOrder"
      @load-items="loadItems"
    />
  </div>
</template>