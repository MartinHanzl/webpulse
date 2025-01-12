<script setup lang="ts">
import { ref, inject } from 'vue';

const toast = useToast();
const pageTitle = ref('Přehled');

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([]);

const dashboard = ref([]);

async function loadDashboard() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number }>('/api/admin/dashboard', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		dashboard.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst přehled. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

useHead({
	title: pageTitle.value,
});

onMounted(() => {
	loadDashboard();
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
		/>
		<div class="flex items-start justify-between gap-x-10 gap-y-4">
			<div class="w-1/2">
				<LayoutContainer>
					<h3 class="text-lg font-semibold text-grayCustom mb-1">
						Naposledy přidané kontakty
					</h3>
					<BaseTable
						:items="dashboard.lastAddedContacts"
						:columns="[
							{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'phone', name: 'Telefon', type: 'text', width: 80, hidden: true, sortable: false },
						]"
						:actions="[
							{ type: 'edit', path: '/kontakty', hash: '#proces' },
						]"
						:loading="loading"
						:error="error"
						singular="Poseldní přidaný kontakt"
						plural="Poslední přidané kontakty"
					/>
				</LayoutContainer>
			</div>
			<div class="w-1/2">
				<LayoutContainer>
					<h3 class="text-lg font-semibold text-grayCustom mb-1">
						Dnes máš zavolat těmto kontaktům
					</h3>
					<BaseTable
						:items="dashboard.contactsToCall"
						:columns="[
							{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'phone', name: 'Telefon', type: 'text', width: 80, hidden: true, sortable: false },
						]"
						:actions="[
							{ type: 'edit', path: '/kontakty', hash: '#proces' },
						]"
						:loading="loading"
						:error="error"
						singular="Poseldní přidaný kontakt"
						plural="Poslední přidané kontakty"
					/>
				</LayoutContainer>
				<LayoutContainer>
					<h3 class="text-lg font-semibold text-grayCustom mb-1">
						Nadcházející schůzky
					</h3>
					<BaseTable
						:items="dashboard.comingEvents"
						:columns="[
							{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: false },
							{ key: 'next_meeting', name: 'Datum a čas', type: 'datetime', width: 80, hidden: true, sortable: false },
						]"
						:actions="[
							{ type: 'edit', path: '/kontakty', hash: '#proces' },
						]"
						:loading="loading"
						:error="error"
						singular="Nadcházející schůzka"
						plural="Nadházející schůzky"
					/>
				</LayoutContainer>
			</div>
		</div>
	</div>
</template>
