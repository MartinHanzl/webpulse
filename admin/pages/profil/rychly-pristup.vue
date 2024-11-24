<script setup lang="ts">
const breadcrumbs = [
	{
		name: 'Profil',
		link: '/profil',
		current: false,
	},
	{
		name: 'Rychlý přístup',
		link: '/rychly-pristup',
		current: true,
	},
];
</script>

<template>
	<div>
		<LayoutHeader
			title="Rychlý přístup"
			:breadcrumbs="breadcrumbs"
		/>
	</div>
</template>
