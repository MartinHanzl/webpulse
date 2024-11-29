<script setup lang="ts">
import { ref, inject } from 'vue';
import { debounce } from 'lodash';
import { definePageMeta } from '#imports';

const toast = useToast();
const pageTitle = ref('Uživatelé');

const loading = ref(false);
const error = ref(false);

const searchString = ref(inject('searchString', ''));
const tableQuery = ref({
	search: null as string | null,
	paginate: 12 as number,
	page: 1 as number,
	orderBy: 'id' as string,
	orderWay: 'desc' as string,
});

const quickAccessDialogShow = ref(false);
const quickAccessDialogForm = ref(false);

const items = ref([]);

async function loadItems() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{}>('/api/admin/user', {
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
			description: 'Nepodařilo se načíst položky rychlého přístupu. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function deleteItem(id: number) {
	loading.value = true;
	const client = useSanctumClient();

	await client<{}>('/api/admin/quick-access/' + id, {
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
			description: 'Nepodařilo se smazat položku rychlého přístupu.',
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

const debouncedLoadItems = debounce(loadItems, 400);
watch(searchString, () => {
	tableQuery.value.search = searchString.value;
	debouncedLoadItems();
});

function openQuickAccessDialog(item) {
	quickAccessDialogShow.value = true;
	quickAccessDialogForm.value = item;
}

const breadcrumbs = [
	{
		name: pageTitle.value,
		link: '/uzivatele',
		current: true,
	},
];
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
				{ type: 'add', text: 'Vytvořit uživatele' },
			]"
		/>
		<LayoutContainer>
			<BaseTable
				:items="items"
				:columns="[
					{ key: 'id', name: 'ID', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: true },
					{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: true, target: 'target' },
					{ key: 'phone', name: 'Telefon', type: 'text', width: 80, hidden: true, sortable: true, target: 'target' },
					{ key: 'email', name: 'E-mail', type: 'text', width: 80, hidden: true, sortable: true, target: 'target' },
				]"
				:actions="[
					{ type: 'edit' },
					{ type: 'delete' },
				]"
				:loading="loading"
				:error="error"
				singular="Uživatel"
				plural="Uživatelé"
				:query="tableQuery"
				@delete-item="deleteItem"
				@update-sort="updateSort"
				@update-page="updatePage"
			/>
		</LayoutContainer>
	</div>
</template>
