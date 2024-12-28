<script setup lang="ts">
import { ref } from 'vue';
import { definePageMeta } from '#imports';

const toast = useToast();
const pageTitle = ref('Aktivita');

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([
	{
		name: pageTitle.value,
		link: '/aktivita',
		current: true,
	},
]);

const showQuickEditDialog = ref(false);
const quickEditDialogItem = ref(null);

const items = ref([]);

async function loadItems() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{
		id: number;
	}>('/api/admin/user/activity', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		items.value = response;
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

async function deleteItem(id: number) {
	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number }>('/api/admin/contact/' + id, {
		method: 'DELETE',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
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

async function saveItem(item) {
	const client = useSanctumClient();
	loading.value = true;

	await client<{ id: number }>('/api/admin/contact/' + item.id, {
		method: 'POST',
		body: JSON.stringify(item),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Kontakt byl úspěšně uložen.',
			color: 'green',
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit kontakt. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
		showQuickEditDialog.value = false;
		loadItems();
	});
}

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
				{ type: 'add', text: 'Přidat aktivitu' },
			]"
			slug="users_has_activities"
		/>
		<LayoutContainer>
			<BaseCalendar :activities="items" />
		</LayoutContainer>
	</div>
</template>
