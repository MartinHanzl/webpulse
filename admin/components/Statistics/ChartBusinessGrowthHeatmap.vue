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

function generateData(count: number, yrange: { min: number; max: number }) {
	let i = 0;
	const series = [];
	while (i < count) {
		const x = (i + 1).toString();
		const y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

		series.push({
			x,
			y,
		});
		i++;
	}
	return series;
}
const chart = ref<{
	series: { name: string; data: number[] }[];
	options: ApexOptions;
}>({
	series: props.items.business.series ?? [],
	options: {
		chart: {
			height: 350,
			type: 'heatmap',
			zoom: { enabled: false },
		},
		dataLabels: {
			enabled: true,
		},
		title: {
			text: 'Růst byznysu - heatmapa',
		},
		colors: props.items.businessColors ?? ['#008FFB'],
	},
});
</script>

<template>
	<div id="chart">
		<apexchart
			type="heatmap"
			height="350"
			:options="chart.options"
			:series="chart.series"
		/>
	</div>
</template>
