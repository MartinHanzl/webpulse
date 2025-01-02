<script setup lang="ts">
import { ref } from 'vue';
import type { ApexOptions } from 'apexcharts';

const props = defineProps({
	items: {
		type: Object,
		required: true,
	},
	activities: {
		type: Object,
		required: true,
	},
});

const chart = ref<{
	series: { name: string; data: number[]; color: string }[];
	options: ApexOptions;
}>({
	series: props.items.business.series ?? [],
	options: {
		chart: {
			zoom: { enabled: true },
		},
		dataLabels: { enabled: false },
		stroke: { curve: 'smooth', width: 1 },
		title: { text: 'Růst byznysu', align: 'left' },
		grid: {
			row: {
				colors: ['#f3f3f3', 'transparent'],
				opacity: 0.5,
			},
		},
		xaxis: {
			categories: props.items.business.axis ?? [],
		},
		yaxis: {
			show: true,
			stepSize: 1,
		},
	},
});
</script>

<template>
	<div id="chart">
		<apexchart
			type="area"
			height="400"
			:options="chart.options"
			:series="chart.series"
		/>
	</div>
</template>
