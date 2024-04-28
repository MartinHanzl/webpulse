<template>
  <div>
    <PageHeading
      :title="title"
      :links="links"
      :action-button="actionButton"
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
</script>
<script>
export default {
  data() {
    return {
      title: "FAQ",
      links: [
        {
          text: "Faq",
          href: "/",
        }
      ],
      actionButton: [
        {
          text: "Přidat FAQ otázku",
          href: "/faq/1",
        }
      ],
    };
  },
  head () {
    return {
      title: 'FAQ - %s',
    };
  },
}
</script>
