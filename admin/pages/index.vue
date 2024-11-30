<script setup lang="ts">
import { ref } from 'vue';
import { definePageMeta } from '#imports';

const breadcrumbs = ref([
	{
		name: 'Přehled',
		link: '/',
		current: true,
	},
]);
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			title="Přehled"
			:breadcrumbs="breadcrumbs"
		/>
	</div>
</template>
