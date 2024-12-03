<script setup lang="ts">
import { ref, inject } from 'vue';
import { debounce } from 'lodash';
import { definePageMeta } from '#imports';

const toast = useToast();
const pageTitle = ref('Kontakty');

const sources = ref([]);
const phases = ref([]);

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([
	{
		name: pageTitle.value,
		link: '/kontakty',
		current: true,
	},
]);

const searchString = ref(inject('searchString', ''));
const tableQuery = ref({
	search: null as string | null,
	paginate: 12 as number,
	page: 1 as number,
	orderBy: 'id' as string,
	orderWay: 'desc' as string,
	filters: [],
});

const showQuickEditDialog = ref(false);
const quickEditDialogItem = ref(null);

const items = ref([]);

async function loadItems() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{}>('/api/admin/contact', {
		method: 'GET',
		query: tableQuery.value,
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		items.value = response;
		tableQuery.value.page = response.page;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst kontakty. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function loadPhases() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/phase', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		phases.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst fáze. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function loadSources() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/source', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		sources.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst zdroje. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function deleteItem(id: number) {
	loading.value = true;
	const client = useSanctumClient();

	await client<{}>('/api/admin/contact/' + id, {
		method: 'DELETE',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se smazat položku kontaktu.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
		loadItems();
	});
}

function showEditDialog(item) {
	console.log(item);
	quickEditDialogItem.value = item;
	showQuickEditDialog.value = true;
}

function updateSort(column: string) {
	if (tableQuery.value.orderBy === column) {
		tableQuery.value.orderWay = tableQuery.value.orderWay === 'asc' ? 'desc' : 'asc';
	}
	else {
		tableQuery.value.orderBy = column;
		tableQuery.value.orderWay = 'asc';
	}
	loadItems();
}
function updatePage(page: number) {
	tableQuery.value.page = page;
	loadItems();
}

function emitUpdateFilters(data: { slug: string; value: string }) {
	const filter = tableQuery.value.filters.find(filter => filter.slug === data.slug);
	if (!filter) {
		tableQuery.value.filters.push({ slug: data.slug, values: [data.value] });
	}
	else {
		if (!filter.values.includes(data.value)) {
			filter.values.push(data.value);
		}
	}
	loadItems();
}

const debouncedLoadItems = debounce(loadItems, 400);
watch(searchString, () => {
	tableQuery.value.search = searchString.value;
	debouncedLoadItems();
});

useHead({
	title: pageTitle.value,
});

onMounted(() => {
	loadItems();
	loadPhases();
	loadSources();
});
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			:title="pageTitle"
			:breadcrumbs="breadcrumbs"
			:actions="[
				{ type: 'add', text: 'Přidat kontakt' },
			]"
			:filters="[
				{ title: 'Podle fáze', data: phases, multiple: true, type: 'badge', slug: 'phase' },
				{ title: 'Podle zdroje', data: sources, multiple: true, type: 'badge', slug: 'source' },
			]"
			:filters-query="tableQuery"
			slug="contacts"
			:links="[
				{ name: 'Kontakty', to: '/kontakty' },
				{ name: 'Fáze', to: '/faze' },
				{ name: 'Zdroje', to: '/zdroje' },
				{ name: 'Úkoly', to: '/ukoly' },
			]"
			@update-filters="emitUpdateFilters"
		/>
		<LayoutContainer>
			<BaseTable
				:items="items"
				:columns="[
					{ key: 'id', name: 'ID', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'phone', name: 'Telefon', type: 'text', width: 80, hidden: true, sortable: true },
					{ key: 'email', name: 'E-mail', type: 'text', width: 80, hidden: true, sortable: true },
					{ key: 'phase', name: 'Fáze', type: 'badge', width: 80, hidden: true, sortable: false, colorKey: 'phase_color' },
					{ key: 'source', name: 'Zdroj', type: 'badge', width: 80, hidden: true, sortable: false, colorKey: 'source_color' },
				]"
				:actions="[
					{ type: 'edit' },
					{ type: 'edit-dialog' },
					{ type: 'delete' },
				]"
				:loading="loading"
				:error="error"
				singular="Kontakt"
				plural="Kontakty"
				:query="tableQuery"
				slug="contacts"
				@delete-item="deleteItem"
				@update-sort="updateSort"
				@update-page="updatePage"
				@open-dialog="showEditDialog"
			/>
		</LayoutContainer>
		<ContactQuickEditDialog
			v-model:show="showQuickEditDialog"
			v-model:item="quickEditDialogItem"
		/>
	</div>
</template>
