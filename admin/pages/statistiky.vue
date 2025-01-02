<script setup lang="ts">
import { ref, inject } from 'vue';
import { useActivityStore } from '~/stores/activityStore';

const activityStore = useActivityStore();

const toast = useToast();
const pageTitle = ref('Statistiky');

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([
	{
		name: pageTitle.value,
		link: '/statistiky',
		current: true,
	},
]);

const searchString = ref(inject('searchString', ''));
const tableQuery = ref({
	filter: 'month' as string,
});

const items = ref(null);

async function loadItems() {
	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number }>('/api/admin/statistics', {
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
			description: 'Nepodařilo se načíst aktivity. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
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
			slug="activities"
			:actions="[
				{ type: 'add', text: 'Přidat aktivitu' },
			]"
		/>
		<LayoutContainer v-if="items">
			<StatisticsChartBusinessGrowth
				:items="items"
				:activities="activityStore.activities"
			/>
		</LayoutContainer>
	</div>
</template>
