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
	series: [44, 55, 41, 17, 15],
	options: {
		colors: ['#2E93fA', '#66DA26', '#546E7A', '#E91E63', '#FF9800'],
		labels: ['Čtení knížky', 'Poslech audia', 'Práce s nástěnkou', 'Dreambuilding', 'Práce se sny'],
		chart: {
			type: 'donut',
		},
	},
});
</script>

<template>
	<div id="chart">
		<apexchart
			type="donut"
			height="400"
			:options="chart.options"
			:series="chart.series"
		/>
	</div>
</template>
