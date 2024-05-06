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
const currentTab = ref('#informace');
const tabs = [
  { name: 'Informace', href: '#informace', current: true },
  { name: 'Překlady', href: '#preklady', current: false },
];

function changeTab(tab: any) {
  currentTab.value = tab;
  tabs.forEach((tab) => {
    tab.current = tab.href === currentTab.value;
  });
}
</script>
<template>
  <div>
    <PageHeading :page-heading-data="pageHeadingData" />
    <div>
      <div class="sm:hidden">
        <label
          for="tabs"
          class="sr-only"
        >Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select
          id="tabs"
          name="tabs"
          class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
        >
          <option
            v-for="tab in tabs"
            :key="tab.name"
            :selected="tab.current"
          >
            {{ tab.name }}
          </option>
        </select>
      </div>
      <div class="hidden sm:block">
        <nav
          class="flex space-x-4"
          aria-label="Tabs"
        >
          <a
            v-for="tab in tabs"
            :key="tab.name"
            :href="tab.href"
            :class="[tab.current ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']"
            :aria-current="tab.current ? 'page' : undefined"
            @click="changeTab(tab.href)"
          >{{ tab.name }}</a>
        </nav>
        <div v-if="currentTab === '#informace'">informace</div>
        <div v-else-if="currentTab === '#preklady'">překlady</div>
      </div>
    </div>
  </div>
</template>