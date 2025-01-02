<script setup lang="ts">
import { ref, inject } from 'vue';
import { debounce } from 'lodash';
import type { ApexOptions } from 'apexcharts';
import { useActivityStore } from '~/stores/activityStore';

const activityStore = useActivityStore();

const chart = ref<{
	series: { name: string; data: number[]; color: string }[];
	options: ApexOptions;
}>({
	series: [
		{
			name: 'KM1',
			data: [30, 40, 35, 50, 49, 60, 70, 91, 125],
      color: '#fcd34d',
		},
		{
			name: 'KM2',
			data: [35, 8, 16, 16, 28, 5, 37, 19, 59],
			color: '#bef264',
		},
	],
	options: {
		chart: {
			height: 250,
			type: 'area',
			zoom: { enabled: true },
		},
		dataLabels: { enabled: true },
		stroke: { curve: 'smooth' },
		title: { text: 'Počet aktivit', align: 'left' },
		grid: {
			row: {
				colors: ['#f3f3f3', 'transparent'],
				opacity: 0.5,
			},
		},
		xaxis: {
			categories: ['1.1.', '2.1.', '3.1.', '4.1.', '5.1.', '6.1.', '7.1.', '8.1.', '9.1.'],
		},
	},
});

const toast = useToast();
const pageTitle = ref('Statistiky');

const loading = ref(false);
const error = ref(false);

const breadcrumbs = ref([
	{
		name: pageTitle.value,
		link: '/aktivity',
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
  filter: 'month' as string,
});

const items = ref([]);

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
function updatePage(page: number) {
	tableQuery.value.page = page;
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
		<LayoutContainer>
			<div id="chart">
				<!-- Correct usage of apexchart component -->
				<apexchart
					type="line"
					height="350"
					:options="chart.options"
					:series="chart.series"
				/><apexchart
					type="line"
					height="350"
					:options="chart.options"
					:series="chart.series"
				/>
			</div>
		</LayoutContainer>
	</div>
</template>
