<script setup lang="ts">
import PageHeading from "~/components/layout/PageHeading.vue";
import { ref } from 'vue';
import { Switch } from '@headlessui/vue';
import Translations from "~/components/form/Translations.vue";
import Informations from "~/components/form/Informations.vue";

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
  actionButton: {
    text: 'Uložit',
    type: 'save'
  }
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

const id = ref(0);
const pending = ref(false);
const form = ref({
  name: '',
  code: '',
  iso: '',
  active: true,
  translations: {},
});

async function saveItem() {
  pending.value = true;
  try {
    await useFetch(id.value === 0 ? '/api/admin/language' : '/api/admin/language/' + id.value, {
      method: 'POST',
      body: form.value,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
    }).then(() => {
      pending.value = false;
    }).catch((e) => {
      pending.value = false;
    });
    await useRouter().push('/jazyky');
  } catch (e) {
    pending.value = false;
    console.log(e);
  }
}

async function loadItem() {
  pending.value = true;
  await useFetch('/api/admin/language/' + id.value, {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  }).then((response) => {
    form.value.name = response.data.value.data.name;
    form.value.code = response.data.value.data.code;
    form.value.iso = response.data.value.data.iso;
    form.value.active = response.data.value.data.active;
    form.value.translations = response.data.value.data.translations;
    pending.value = false;
  })
}
onMounted(() => {
  id.value = useRoute().params.id !== 'pridat' ? useRoute().params.id : 0;
  if (id.value !== 0) {
    loadItem();
  }
  changeTab('#informace');
});
</script>
<template>
  <div>
    {{ form }}
    <PageHeading
      :page-heading-data="pageHeadingData"
      @save-item="saveItem"
    />
    <div v-if="!pending">
      <div class="sm:hidden">
        <label
          for="tabs"
          class="sr-only"
        >Select a tab</label>
        <select
          id="tabs"
          name="tabs"
          class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
        >
          <option
            v-for="tab in tabs"
            :key="tab.name"
            :selected="tab.current"
            @click="changeTab(tab.href)"
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
            :class="[tab.current ? 'bg-blue-100 text-blue-700 ring-1 ring-blue-200' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']"
            :aria-current="tab.current ? 'page' : undefined"
            @click="changeTab(tab.href)"
          >{{ tab.name }}</a>
        </nav>
        <div v-if="currentTab === '#informace' && !pending">
          <div class="space-y-12">
            <div>
              <Informations
                v-model:form="form"
                :rows="[
                  { row: [
                    { name: 'name', label: 'Název', type: 'text', span: '4', minlength: 2, maxlength: 64},
                  ] },
                  { row: [
                    { name: 'code', label: 'Kód', type: 'text', span: '4', minlength: 2, maxlength: 2 },
                    { name: 'iso', label: 'Iso kód', type: 'text', span: '4', minlength: 5, maxlength: 5},
                  ] },
                  { row: [
                    { name: 'active', label: 'Aktivní', type: 'switch', span: 'full' },
                  ] },
                ]"
              />
            </div>
          </div>
        </div>
        <div v-else-if="currentTab === '#preklady' && !pending">
          <Translations
            v-model:translations="form.translations"
            :item-translations="form.translations"
          />
        </div>
      </div>
    </div>
  </div>
</template>