<script setup lang="ts">
import { ref, inject } from 'vue';
import _ from 'lodash';
import { definePageMeta } from '#imports';

const toast = useToast();
const pageTitle = ref('Měny');

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([
	{
		name: pageTitle.value,
		link: '/nastaveni/meny',
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
});

const items = ref([]);

async function loadItems() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number }>('/api/admin/currency', {
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
			description: 'Nepodařilo se načíst měny. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function deleteItem(id: number) {
	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number }>('/api/admin/currency/' + id, {
		method: 'DELETE',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se smazat položku měny.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
		loadItems();
	});
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

const debouncedLoadItems = _.debounce(loadItems, 400);
watch(searchString, () => {
	tableQuery.value.search = searchString.value;
	debouncedLoadItems();
});

useHead({
	title: pageTitle.value,
});

onMounted(() => {
	loadItems();
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
				{ type: 'add', text: 'Přidat měnu' },
			]"
			slug="currencies"
		/>
		<LayoutContainer>
			<BaseTable
				:items="items"
				:columns="[
					{ key: 'id', name: 'ID', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'name', name: 'Název', type: 'text', width: 80, hidden: false, sortable: false },
					{ key: 'code', name: 'Kód', type: 'text', width: 80, hidden: true, sortable: true },
					{ key: 'symbol_before', name: 'Symbol před', type: 'text', width: 80, hidden: true, sortable: false },
					{ key: 'symbol_after', name: 'Symbol za', type: 'text', width: 80, hidden: true, sortable: false },
					{ key: 'active', name: 'Aktivní', type: 'status', width: 80, hidden: true, sortable: true },
				]"
				:actions="[
					{ type: 'edit' },
					{ type: 'delete' },
				]"
				:loading="loading"
				:error="error"
				singular="Měnu"
				plural="Měny"
				:query="tableQuery"
				slug="currencies"
				@delete-item="deleteItem"
				@update-sort="updateSort"
				@update-page="updatePage"
			/>
		</LayoutContainer>
	</div>
</template>
