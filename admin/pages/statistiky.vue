<script setup lang="ts">
import { ref, inject } from 'vue';
import { useActivityStore } from '~/stores/activityStore';

const activityStore = useActivityStore();

const route = useRoute();
const router = useRouter();

const toast = useToast();
const pageTitle = ref('Statistiky');

const loading = ref(false);
const error = ref(false);

const filterDialogIsOpen = ref(false);

const tabs = ref([
	{ name: 'Růst byznysu', link: '#byznys', current: false },
	{ name: 'Osobní růst', link: '#osobni', current: false },
]);

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
	month: new Date().getMonth() + 1,
	year: new Date().getFullYear(),
});

const items = ref(null);

async function loadItems() {
	loading.value = true;
  error.value = false;
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

watchEffect(() => {
	const routeTabHash = route.hash;
	if (routeTabHash && routeTabHash !== '') {
		tabs.value.forEach((tab) => {
			tab.current = tab.link === routeTabHash;
		});
	}
	else {
		tabs.value[0].current = true;
		router.push(route.path + '#byznys');
	}
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
			:actions="[{
				type: 'filter-dialog',
				text: 'Filtrovat',
			}]"
			@filter-dialog="filterDialogIsOpen = true"
		/>
		<div>
			<div class="grid grid-cols-1 sm:hidden mt-5">
				<!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
				<select
					aria-label="Select a tab"
					class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
				>
					<option>My Account</option>
					<option>Company</option>
					<option selected>
						Team Members
					</option>
					<option>Billing</option>
				</select>
				<svg
					class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500"
					viewBox="0 0 16 16"
					fill="currentColor"
					aria-hidden="true"
					data-slot="icon"
				>
					<path
						fill-rule="evenodd"
						d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
						clip-rule="evenodd"
					/>
				</svg>
			</div>
			<div class="hidden sm:block mt-5">
				<nav
					class="isolate flex divide-x divide-gray-200 shadow-sm rounded-lg"
					aria-label="Tabs"
				>
					<!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
					<NuxtLink
						v-for="(tab, index) in tabs"
						:key="index"
						:to="tab.link"
						class="group relative min-w-0 flex-1 overflow-hidden bg-white px-4 py-4 text-center text-sm font-medium text-grayCustom hover:bg-gray-50 hover:text-grayDark focus:z-10"
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
		<template v-if="tabs.find(tab => tab.current && tab.link === '#byznys')">
			<!--      <StatisticsStatsBusinessGrowth /> -->
			<LayoutContainer v-if="items && !error && !loading">
				<StatisticsChartBusinessGrowth
					:items="items"
					:activities="activityStore.activities"
				/>
				<StatisticsChartBusinessGrowthHeatmap
					:items="items"
					:activities="activityStore.activities"
				/>
			</LayoutContainer>
		</template>
		<template v-if="tabs.find(tab => tab.current && tab.link === '#osobni')">
			<LayoutContainer v-if="items && !error && !loading">
				<StatisticsChartPersonalGrowth
					:items="items"
					:activities="activityStore.activities"
				/>
			</LayoutContainer>
		</template>
		<StatisticsDialogFilter
			v-model:show="filterDialogIsOpen"
			v-model:filter="tableQuery.filter"
			v-model:year="tableQuery.year"
			v-model:month="tableQuery.month"
			@submit="loadItems"
		/>
	</div>
</template>
