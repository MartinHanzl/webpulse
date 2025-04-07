<script setup lang="ts">
import { ref } from 'vue';
import { Form } from 'vee-validate';

const toast = useToast();

const route = useRoute();
const router = useRouter();

const error = ref(false);
const loading = ref(false);

const tabs = ref([
  { name: 'Základní údaje', link: '#info', current: false },
  { name: 'Kontaktní údaje', link: '#adresy', current: false },
  { name: 'Soubory', link: '#soubory', current: false },
]);

const pageTitle = ref(route.params.id === 'pridat' ? 'Nový projekt' : 'Detail projektu');

const breadcrumbs = ref([
	{
		name: 'Projekty',
		link: '/projekty',
		current: false,
	},
	{
		name: 'Nový projekt',
		link: '/projekty/pridat',
		current: true,
	},
]);

const item = ref({
	id: null as number | null,
	name: '' as string,
	rate: 0 as number,
});

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		id: number | null;
		name: string;
		rate: number;
	}>('/api/admin/project/' + route.params.id, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
		breadcrumbs.value.pop();
		breadcrumbs.value.push({
			name: item.value.name,
			link: '/projekty/' + route.params.id,
			current: true,
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst projekt. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function saveItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		id: number | null;
		name: string;
		rate: number;
	}>(route.params.id === 'pridat' ? '/api/admin/project' : '/api/admin/project/' + route.params.id, {
		method: 'POST',
		body: JSON.stringify(item.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: route.params.id === 'pridat' ? 'Projekt byl úspěšně vytvořen.' : 'Projekt byl úspěšně upraven.',
			color: 'green',
		});
		router.push('/dph');
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit projekt. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

watchEffect(() => {
  const routeTabHash = route.hash;
  if (routeTabHash && routeTabHash !== '') {
    tabs.value.forEach((tab) => {
      tab.current = tab.link === routeTabHash;
    });
  }
  else {
    tabs.value[0].current = true;
    router.push(route.path + '#info');
  }
});

useHead({
	title: pageTitle.value,
});

onMounted(() => {
	if (route.params.id !== 'pridat') {
		loadItem();
	}
});
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			:title="route.params.id === 'pridat' ? 'Nový projekt' : item.name"
			:breadcrumbs="breadcrumbs"
			:actions="[{ type: 'save' }]"
			slug="projects"
			@save="saveItem"
		/>
    <div>
      <div class="block mt-5">
        <nav
            class="isolate flex divide-x divide-gray-200 shadow-sm"
            aria-label="Tabs"
        >
          <NuxtLink
              v-for="(tab, index) in tabs"
              :key="index"
              :to="tab.link"
              class="group relative min-w-0 flex-1 overflow-hidden bg-white px-2 lg:px-4 py-2.5 lg:py-4 text-center text-xs lg:text-sm font-medium text-grayCustom hover:bg-gray-50 hover:text-grayDark focus:z-10"
          >
            <span>{{ tab.name }}</span>
            <span
                aria-hidden="true"
                :class="tab.current ? 'absolute inset-x-0 bottom-0 h-0.5 bg-primaryCustom' : 'absolute inset-x-0 bottom-0 h-0.5 bg-transparent'"
            />
          </NuxtLink>
        </nav>
      </div>
    </div>
		<Form @submit="saveItem">
      <template v-if="tabs.find(tab => tab.current && tab.link === '#info')">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-8 gap-y-2 lg:gap-y-4">
          <LayoutContainer class="col-span-9 w-full">
            <LayoutTitle>Základní údaje</LayoutTitle>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">
              <BaseFormInput
                  v-model="item.name"
                  label="Název"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormTextarea
                  v-model="item.description"
                  label="Příjmení"
                  name="lastname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormTextarea
                  v-model="item.note"
                  label="E-mail"
                  name="email"
                  rules="email"
                  class="col-span-full"
              />
              <div class="col-span-full border-b border-grayLight mb-2 mt-4" />
              <BaseFormInput
                  v-model="item.company"
                  label="Očekáváná cena"
                  type="text"
                  name="company"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.company"
                  label="Celková cena"
                  type="text"
                  name="company"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.company"
                  label="Celková cena vč. DPH"
                  type="text"
                  name="company"
                  class="col-span-1"
              />
              <div class="col-span-full border-b border-grayLight mb-2 mt-4" />
              <BaseFormInput
                  v-model="item.company"
                  label="Datum začátku"
                  type="text"
                  name="company"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.company"
                  label="Datum ukončení"
                  type="text"
                  name="company"
                  class="col-span-1"
              />
            </div>
          </LayoutContainer>
          <LayoutContainer class="col-span-3 w-full">
            <LayoutTitle>Historie</LayoutTitle>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">
              <LayoutTitle>todo: historie</LayoutTitle>
            </div>
          </LayoutContainer>
        </div>
      </template>
      <template v-if="tabs.find(tab => tab.current && tab.link === '#adresy')">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-2 lg:gap-y-4">
          <LayoutContainer class="col-span-1 w-full">
            <LayoutTitle>Fakturační údaje</LayoutTitle>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">
              <BaseFormInput
                  v-model="item.name"
                  label="Jméno"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Příjmení"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="IČO"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="DIČ"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="E-mail"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Předčíslí"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Telefonní číslo"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Ulice a č. p."
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="PSČ"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Město"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Země"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
            </div>
          </LayoutContainer>
          <LayoutContainer class="col-span-1 w-full">
            <LayoutTitle>Dodací údaje</LayoutTitle>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">
              <BaseFormInput
                  v-model="item.name"
                  label="Jméno"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Příjmení"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="E-mail"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Předčíslí"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Telefonní číslo"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Ulice a č. p."
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-full"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="PSČ"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Město"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
              <BaseFormInput
                  v-model="item.name"
                  label="Země"
                  type="text"
                  name="firstname"
                  rules="required|min:3"
                  class="col-span-1"
              />
            </div>
          </LayoutContainer>
        </div>
      </template>
		</Form>
	</div>
</template>
