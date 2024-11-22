<script setup lang="ts">
import { definePageMeta } from '#imports';

const breadcrumbs = [
	{
		name: 'Přehled',
		link: '/',
		current: true,
	},
];
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
